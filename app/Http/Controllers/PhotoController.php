<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
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

                // read binary content and save directly to DB
                $content = file_get_contents($file->getRealPath());
                $mime = $file->getClientMimeType();

                $photo = new Photo();
                $photo->titre = $titre;
                $photo->url = null; // primary source will be DB data
                $photo->data = $content;
                $photo->mime = $mime;
                $photo->album_id = $albumId;
                $photo->save();
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

                    $photo = new Photo();
                    $photo->titre = $titre;
                    $photo->url = $prixUrl; // keep original URL too
                    $photo->data = $content;
                    $photo->mime = $mime;
                    $photo->album_id = $albumId;
                    $photo->save();

                    return redirect()->back()->with('success', 'Photo ajoutée et sauvegardée en base.');
                }
            } catch (\Exception $e) {
                // fall through and save url only below
            }

            // if fetching failed store URL only (data = null)
            $photo = new Photo();
            $photo->titre = $titre;
            $photo->url = $prixUrl;
            $photo->data = null;
            $photo->mime = null;
            $photo->album_id = $albumId;
            $photo->save();

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

    /** Serve raw image bytes from DB when available */
    public function image(Photo $photo)
    {
        if ($photo->data) {
            $mime = $photo->mime ?: 'image/jpeg';
            return response($photo->data, 200)->header('Content-Type', $mime);
        }

        // fallback to redirect to URL if no data stored
        if ($photo->url) {
            return redirect($photo->url);
        }

        abort(404);
    }
}
