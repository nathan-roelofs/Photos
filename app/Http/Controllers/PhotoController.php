<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'sometimes',
            'file.*' => 'file|image|max:5120',
            'url' => 'sometimes|nullable|url',
            'titre' => 'sometimes|nullable|string|max:255',
            'album_id' => 'required|integer|exists:albums,id',
        ]);

        // check whether DB has blob columns (migration might not have been run)
        $hasDataColumn = Schema::hasColumn('photos', 'data');
        $hasMimeColumn = Schema::hasColumn('photos', 'mime');

        // file upload has priority
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            // if single file, normalize to array
            if (!is_array($files)) {
                $files = [$files];
            }

                foreach ($files as $file) {
                $titre = $request->input('titre') ?: $file->getClientOriginalName();
                $albumId = $request->input('album_id') ?: null;

                // if the DB supports blob columns, store bytes in DB; otherwise store file in public disk and save URL
                    if ($hasDataColumn && $hasMimeColumn) {
                    $content = file_get_contents($file->getRealPath());
                    $mime = $file->getClientMimeType();

                    $photo = new Photo();
                    $photo->titre = $titre;
                    $photo->url = null; // primary source will be DB data
                    $photo->data = $content;
                    $photo->mime = $mime;
                    $photo->album_id = $albumId;
                    try {
                        $photo->save();
                    } catch (QueryException $e) {
                        // If the DB doesn't actually support the 'data' column (race or mismatch), fallback to disk store
                        if (str_contains($e->getMessage(), 'Unknown column') || str_contains($e->getMessage(), 'data')) {
                            $path = $file->store('photos', 'public');
                            $url = Storage::url($path);
                            // Attempt a safe Eloquent save without the binary/mime fields
                            $photo->data = null;
                            $photo->mime = null;
                            $photo->url = $url;
                            try {
                                $photo->save();
                            } catch (QueryException $e2) {
                                // final fallback: do a direct insert only for columns we know exist
                                if (str_contains($e2->getMessage(), 'Unknown column') || str_contains($e2->getMessage(), 'data')) {
                                    DB::table('photos')->insert([
                                        'titre' => $photo->titre,
                                        'url' => $photo->url,
                                        'album_id' => $photo->album_id,
                                    ]);
                                } else {
                                    throw $e2;
                                }
                            }
                        } else {
                            throw $e;
                        }
                    }
                    } else {
                    // fallback: persist the file in storage/public and store its URL
                    $path = $file->store('photos', 'public');
                    // Storage::url() returns /storage/..., but we need the full relative path for serving
                    $url = 'storage/app/public/' . $path;

                    $photo = new Photo();
                    $photo->titre = $titre;
                    $photo->url = $url;
                    $photo->data = null;
                    $photo->mime = null;
                    $photo->album_id = $albumId;
                    try {
                        $photo->save();
                    } catch (QueryException $e) {
                        // Even though we checked columns don't exist, Eloquent model might still try to set them
                        // Fallback to direct insert
                        if (str_contains($e->getMessage(), 'Unknown column') || str_contains($e->getMessage(), 'data')) {
                            DB::table('photos')->insert([
                                'titre' => $photo->titre,
                                'url' => $photo->url,
                                'album_id' => $photo->album_id,
                            ]);
                        } else {
                            throw $e;
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Photos enregistrées.');
        }

        // url based add
        if ($request->filled('url')) {
            $prixUrl = $request->input('url');
            $titre = $request->input('titre') ?: basename(parse_url($prixUrl, PHP_URL_PATH));
            $albumId = $request->input('album_id') ?: null;

            // try to fetch the image bytes from the URL so it's stored in DB
                try {
                    $resp = Http::withOptions(['verify' => false])->get($prixUrl);
                    if ($resp->ok()) {
                    $content = $resp->body();
                    $mime = $resp->header('Content-Type') ?: 'image/jpeg';

                    // if we have DB blob support, save content; otherwise only keep the URL
                    if ($hasDataColumn && $hasMimeColumn) {
                        $photo = new Photo();
                        $photo->titre = $titre;
                        $photo->url = $prixUrl; // keep original URL too
                        $photo->data = $content;
                        $photo->mime = $mime;
                        $photo->album_id = $albumId;
                        try {
                            $photo->save();
                        } catch (QueryException $e) {
                            // fallback to saving URL only if insert fails due to missing column
                            if (str_contains($e->getMessage(), 'Unknown column') || str_contains($e->getMessage(), 'data')) {
                                $photo->data = null;
                                $photo->mime = null;
                                try {
                                    $photo->save();
                                } catch (QueryException $e2) {
                                    if (str_contains($e2->getMessage(), 'Unknown column') || str_contains($e2->getMessage(), 'data')) {
                                        DB::table('photos')->insert([
                                            'titre' => $photo->titre,
                                            'url' => $photo->url,
                                            'album_id' => $photo->album_id,
                                        ]);
                                    } else {
                                        throw $e2;
                                    }
                                }
                            } else {
                                throw $e;
                            }
                        }
                    } else {
                        // no blob support — store URL only
                        $photo = new Photo();
                        $photo->titre = $titre;
                        $photo->url = $prixUrl;
                        $photo->data = null;
                        $photo->mime = null;
                        $photo->album_id = $albumId;
                        $photo->save();
                    }

                    return redirect()->back()->with('success', 'Photo ajoutée et sauvegardée en base.');
                }
            } catch (\Exception $e) {
                // fall through and save url only below
            }

            // if fetching failed, fallback: if blob support absent we still keep URL only
            $photo = new Photo();
            $photo->titre = $titre;
            $photo->url = $prixUrl;
            $photo->data = null;
            $photo->mime = null;
            $photo->album_id = $albumId;
                    try {
                $photo->save();
            } catch (QueryException $e) {
                // if DB refuses because of missing columns, attempt a very small insert of only allowed columns
                if (str_contains($e->getMessage(), 'Unknown column') || str_contains($e->getMessage(), 'data')) {
                    // insert only titre and url and album_id
                    $pdo = DB::connection()->getPdo();
                    $stmt = $pdo->prepare('INSERT INTO photos (titre, url, album_id) VALUES (:titre, :url, :album_id)');
                    $stmt->execute([':titre' => $titre, ':url' => $prixUrl, ':album_id' => $albumId]);
                } else {
                    throw $e;
                }
            }

            return redirect()->back()->with('success', 'Photo ajoutée (URL sauvegardée).');

            return redirect()->back()->with('success', 'Photo ajoutée depuis URL.');
        }

        return redirect()->back()->withErrors('Aucun fichier ou URL fourni.');
    }

    public function destroy(Photo $photo)
    {
        // If file on storage/public, try to remove it
        $url = $photo->url;
        // If URL points to /storage/* then remove the underlying storage path
        if (str_starts_with($url, '/storage/')) {
            $path = substr($url, strlen('/storage/'));
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $photo->delete();
        return redirect()->back()->with('success', 'Photo supprimée.');
    }

    /** Serve raw image bytes from DB when available, or from storage */
    public function image(Photo $photo)
    {
        if ($photo->data) {
            $mime = $photo->mime ?: 'image/jpeg';
            return response($photo->data, 200)->header('Content-Type', $mime);
        }

        // If URL is a storage path, serve from disk
        $url = $photo->url;
        $path = null;

        // Handle /storage/photos/... format
        if (str_starts_with($url, '/storage/')) {
            $path = substr($url, strlen('/storage/'));
        }
        // Handle storage/app/public/photos/... format (no leading slash)
        elseif (str_starts_with($url, 'storage/app/public/')) {
            $path = substr($url, strlen('storage/app/public/'));
        }

        if ($path && Storage::disk('public')->exists($path)) {
            $file = Storage::disk('public')->get($path);
            // guess mime type from file extension
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $mimeTypes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp'];
            $mime = $mimeTypes[strtolower($ext)] ?? 'image/jpeg';
            return response($file, 200)->header('Content-Type', $mime);
        }

        // fallback to redirect to external URL if no local data stored
        if ($photo->url) {
            return redirect($photo->url);
        }

        abort(404);
    }
}
