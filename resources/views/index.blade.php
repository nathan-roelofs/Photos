<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionnaire d'albums - Accueil</title>
    <style>
    :root{
        --accent:#ef4444;
        --muted:#6b7280;
        --card-bg:#fff;
        --page-bg:#f9fafb;
    }
    html,body{height:100%;}
    

    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial;background:var(--page-bg);color:#111;margin:0;padding:0}

    header{padding:20px 16px;border-bottom:1px solid #eee;background:#fff}
    header h1{margin:0;font-size:20px;font-weight:600}

    main{padding:24px 12px}

    .controls{max-width:900px;margin:1rem auto;padding:1rem;display:flex;gap:1rem;align-items:center;justify-content:space-between;flex-wrap:wrap}
    .controls label{display:block;font-size:14px;margin-bottom:6px}
    .controls input[type='file']{display:block}
    .controls input[type='url']{flex:1;padding:.5rem;border:1px solid #ddd;border-radius:6px}
    .controls .btn{padding:.5rem .75rem;background:var(--accent);color:white;border-radius:6px;border:none;cursor:pointer}

    .gallery--columns{max-width:1100px;margin:0 auto;padding:1rem;column-gap:16px}
    .gallery--columns{column-count:3}

    /* masonry flow: cards must be inline-block and avoid column breaks */
    .card{position:relative;display:inline-block;width:100%;break-inside:avoid;-webkit-column-break-inside:avoid;margin:0 0 16px;border-radius:8px;overflow:hidden;background:var(--card-bg);box-shadow:0 6px 18px rgba(16,24,40,0.06)}
    .card img{width:100%;height:auto;display:block}
    .card figcaption{padding:.5rem;font-size:.95rem;color:var(--accent)}
    .remove-btn{position:absolute;right:8px;top:8px;background:rgba(0,0,0,0.55);color:#fff;border:none;padding:.25rem .4rem;border-radius:6px;cursor:pointer}

    .note{max-width:1100px;margin:0 auto;padding:0 1rem 2rem;color:var(--muted);font-size:.9rem}

    footer{padding:16px;color:var(--muted);text-align:center}

    .control-col{flex:1;min-width:240px}
    .control-row{display:flex;gap:.5rem;align-items:center}

    .control-label{width:120px;display:inline-block}

    .card figcaption{color:#111}

    @media (max-width:1100px){
        .gallery--columns{column-count:2}
    }

    @media (max-width:680px){
        .gallery--columns{column-count:1}
    }

    </style>
</head>
<body>
    <header>
        <h1>Albums photos</h1>
    </header>

    <main>
        <!-- Controls: add images from files or by URL (server-side) -->
        <section class="controls">
            <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data" class="control-col">
                @csrf
                <label for="fileInput">Ajouter des images (depuis votre ordinateur)</label>
                <input id="fileInput" name="file[]" type="file" accept="image/*" multiple>
                <div style="margin-top:.5rem;display:flex;gap:.5rem;align-items:center">
                    <select name="album_id" aria-label="Choisir album" required style="padding:.4rem;border:1px solid #ddd;border-radius:6px">
                        <option value="">— Aucun album —</option>
                        @if(isset($albums))
                            @foreach($albums as $albumOption)
                                <option value="{{ $albumOption->id }}">{{ $albumOption->titre }} ({{ $albumOption->photos->count() }})</option>
                            @endforeach
                        @endif
                    </select>
                    <button type="submit" class="btn">Téléverser</button>
                </div>
            </form>

            <form action="{{ route('photos.store') }}" method="POST" class="control-col control-row">
                @csrf
                <label for="urlInput" class="control-label">Ajouter par URL</label>
                <input id="urlInput" name="url" type="url" placeholder="https://example.com/photo.jpg">
                <select name="album_id" aria-label="Choisir album (URL)" required style="padding:.4rem;border:1px solid #ddd;border-radius:6px">
                    <option value="">— Aucun album —</option>
                    @if(isset($albums))
                        @foreach($albums as $albumOption)
                            <option value="{{ $albumOption->id }}">{{ $albumOption->titre }} ({{ $albumOption->photos->count() }})</option>
                        @endforeach
                    @endif
                </select>
                <button type="submit" class="btn">Ajouter</button>
            </form>
        </section>

        @if(session('success'))
            <div style="max-width:1100px;margin:0 auto;padding:0 1rem 1rem;color:green;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div style="max-width:1100px;margin:0 auto;padding:0 1rem 1rem;color:#b91c1c;">{{ $errors->first() }}</div>
        @endif

        <!-- Gallery grid -->
        <section id="gallery" aria-label="Galerie">
            <!-- Albums + their photos (DB) -->
                @if(isset($albums) && $albums->count())
                    @foreach($albums as $album)
                        <section style="max-width:1100px;margin:0 auto 2rem;padding:0 1rem;">
                            <header style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                                <div>
                                    <h2 style="margin:0;font-size:1.1rem">{{ $album->titre }}</h2>
                                    <div style="font-size:.9rem;color:var(--muted)">Créé: {{ $album->creation }} — {{ $album->photos->count() }} photos</div>
                                </div>
                                <div>
                                    <!-- optional album actions could go here -->
                                </div>
                            </header>

                            @if($album->photos->isEmpty())
                                <div class="note">Aucune photo dans cet album.</div>
                            @else
                                <div class="gallery--columns">
                                    @foreach($album->photos as $photo)
                                        <figure class="card" data-id="{{ $photo->id }}">
                                            <img src="{{ $photo->data ? route('photos.image', $photo) : $photo->url }}" alt="{{ $photo->titre }}">
                                            <figcaption>{{ $photo->titre }}</figcaption>

                                            <form style="position:absolute;right:8px;top:8px;" method="POST" action="{{ route('photos.destroy', $photo) }}" onsubmit="return confirm('Supprimer cette photo ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="remove-btn" type="submit">Suppr</button>
                                            </form>
                                        </figure>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    @endforeach
                @else
                    <div class="note">Aucun album trouvé dans la base.</div>
                @endif
        </section>

        {{-- server-side upload handled by forms; no client-only add/remove JS here --}}

    </main>
    <footer>
        <!-- Pied de page -->
    </footer>
</body>
</html>