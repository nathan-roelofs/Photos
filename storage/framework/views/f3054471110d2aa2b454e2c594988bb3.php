<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionnaire d'albums - Accueil</title>
    <link rel="stylesheet" href="/resources/css/app.css">
</head>
<body>
    <header>
        <h1>Albums photos</h1>
    </header>

    <main>
        <!-- Controls: add images from files or by URL -->
        <section class="controls">
            <div style="flex:1;min-width:240px;">
                <label for="fileInput">Ajouter des images (depuis votre ordinateur)</label>
                <input id="fileInput" type="file" accept="image/*" multiple>
            </div>

            <div style="flex:1;min-width:240px;display:flex;gap:.5rem;align-items:center;">
                <label for="urlInput" style="width:120px;">Ajouter par URL</label>
                <input id="urlInput" type="url" placeholder="https://example.com/photo.jpg">
                <button id="addUrlBtn" class="btn">Ajouter</button>
            </div>
        </section>

        <!-- Gallery grid -->
        <section id="gallery" aria-label="Galerie">
            <!-- Preloaded images (if present). These will be removable by the user. -->
            <figure class="card" data-id="1">
                <img src="/images/accueil.jpg" alt="accueil">
                <figcaption>Image d'accueil</figcaption>
                <button class="remove-btn" aria-label="Supprimer image">Suppr</button>
            </figure>

            <figure class="card" data-id="2">
                <img src="/images/accueil2.jpg" alt="accueil2">
                <figcaption>Image d'accueil 2</figcaption>
                <button class="remove-btn" aria-label="Supprimer image">Suppr</button>
            </figure>
        </section>

        <!-- Small note: this is purely client-side; images won't be saved server-side in this prototype -->
        <div class="note">Note: ici l'ajout et la suppression sont gérés côté client (prévisualisation). Pour conserver les images, il faut implémenter un upload serveur.</div>

        <script>
            // Simple client-side gallery: add images from file input or URL and remove them from DOM.
            (function(){
                const fileInput = document.getElementById('fileInput');
                const urlInput = document.getElementById('urlInput');
                const addUrlBtn = document.getElementById('addUrlBtn');
                const gallery = document.getElementById('gallery');
                let nextId = 3;

                function addImageElement(src, alt){
                    const id = nextId++;
                    const fig = document.createElement('figure');
                    fig.className = 'card';
                    fig.setAttribute('data-id', id);
                    fig.style.position = 'relative';
                    fig.style.borderRadius = '8px';
                    fig.style.overflow = 'hidden';
                    fig.style.background = '#fff';

                    const img = document.createElement('img');
                    img.src = src;
                    img.alt = alt || 'image';
                    img.style.width = '100%';
                    img.style.height = '160px';
                    img.style.objectFit = 'cover';

                    const caption = document.createElement('figcaption');
                    caption.style.padding = '.5rem';
                    caption.style.fontSize = '.95rem';
                    caption.style.color = '#111';
                    caption.textContent = alt || 'Nouvelle image';

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-btn';
                    removeBtn.setAttribute('aria-label','Supprimer image');
                    removeBtn.style.position = 'absolute';
                    removeBtn.style.right = '8px';
                    removeBtn.style.top = '8px';
                    removeBtn.style.background = 'rgba(0,0,0,0.55)';
                    removeBtn.style.color = '#fff';
                    removeBtn.style.border = 'none';
                    removeBtn.style.padding = '.25rem .4rem';
                    removeBtn.style.borderRadius = '6px';
                    removeBtn.style.cursor = 'pointer';
                    removeBtn.textContent = 'Suppr';

                    removeBtn.addEventListener('click', function(){
                        fig.remove();
                    });

                    fig.appendChild(img);
                    fig.appendChild(caption);
                    fig.appendChild(removeBtn);
                    gallery.appendChild(fig);
                    // scroll to new image
                    fig.scrollIntoView({behavior:'smooth', block:'center'});
                }

                fileInput.addEventListener('change', function(ev){
                    const files = Array.from(ev.target.files || []);
                    files.forEach(file => {
                        if(!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = e => addImageElement(e.target.result, file.name);
                        reader.readAsDataURL(file);
                    });
                    // clear input to allow re-upload same file if needed
                    fileInput.value = '';
                });

                addUrlBtn.addEventListener('click', function(){
                    const url = urlInput.value.trim();
                    if(!url) return;
                    // basic validation
                    try{ new URL(url); } catch(e){ alert('URL invalide'); return; }
                    addImageElement(url, url.split('/').pop());
                    urlInput.value = '';
                });

                // delegate remove buttons for initial elements
                gallery.addEventListener('click', function(e){
                    if(e.target && e.target.classList.contains('remove-btn')){
                        const fig = e.target.closest('figure');
                        if(fig) fig.remove();
                    }
                });
            })();
        </script>

    </main>
    <footer>
        <!-- Pied de page -->
    </footer>
</body>
</html><?php /**PATH C:\Users\Nathan\Documents\Cours\BUT_2\Photos\resources\views/index.blade.php ENDPATH**/ ?>