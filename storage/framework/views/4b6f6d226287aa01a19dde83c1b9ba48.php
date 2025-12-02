<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionnaire d'albums - Accueil</title>
    <style>
    :root{
        --accent:#ff6b6b;
        --accent-dark:#ee5a52;
        --muted:#a0aec0;
        --card-bg:#1a202c;
        --page-bg:#0f1419;
        --border:#2d3748;
        --text-primary:#e2e8f0;
        --text-secondary:#cbd5e0;
    }
    html,body{height:100%;}
    

    body{
        font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
        background:var(--page-bg);
        color:var(--text-primary);
        margin:0;
        padding:0;
    }

    header{
        padding:24px 16px;
        border-bottom:1px solid var(--border);
        background:linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        box-shadow:0 4px 12px rgba(0,0,0,0.3);
    }
    header h1{
        margin:0;
        font-size:28px;
        font-weight:700;
        background:linear-gradient(135deg, #ff6b6b, #ff8787);
        -webkit-background-clip:text;
        -webkit-text-fill-color:transparent;
        background-clip:text;
    }

    main{padding:32px 12px}

    .controls{
        max-width:1200px;
        margin:0 auto 2rem;
        padding:24px;
        display:flex;
        gap:1.5rem;
        align-items:flex-start;
        flex-wrap:wrap;
        background:var(--card-bg);
        border:1px solid var(--border);
        border-radius:12px;
        box-shadow:0 8px 24px rgba(0,0,0,0.4);
    }

    .controls label{
        display:block;
        font-size:13px;
        margin-bottom:8px;
        color:var(--muted);
        font-weight:600;
        text-transform:uppercase;
        letter-spacing:0.5px;
    }

    .controls input[type='url'],
    .controls input[type='text'],
    .controls select{
        padding:10px 12px;
        border:1px solid var(--border);
        border-radius:8px;
        background:#0f1419;
        color:var(--text-primary);
        font-size:14px;
        transition:all 0.2s ease;
    }

    .controls input[type='url']:focus,
    .controls input[type='text']:focus,
    .controls select:focus{
        outline:none;
        border-color:var(--accent);
        box-shadow:0 0 0 3px rgba(255,107,107,0.1);
    }

    .controls .btn{
        padding:10px 24px;
        background:linear-gradient(135deg, var(--accent), var(--accent-dark));
        color:white;
        border-radius:8px;
        border:none;
        cursor:pointer;
        font-weight:600;
        transition:all 0.3s ease;
        box-shadow:0 4px 12px rgba(255,107,107,0.3);
    }

    .controls .btn:hover{
        transform:translateY(-2px);
        box-shadow:0 6px 16px rgba(255,107,107,0.4);
    }

    .controls .btn:active{
        transform:translateY(0);
    }

    .gallery--columns{
        max-width:1200px;
        margin:0 auto;
        padding:1rem;
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:20px;
    }

    @media(max-width:1024px){
        .gallery--columns{ grid-template-columns:repeat(2, 1fr); }
    }

    @media(max-width:640px){
        .gallery--columns{ grid-template-columns:1fr; }
    }

    .card{
        position:relative;
        display:inline-block;
        width:100%;
        break-inside:avoid;
        -webkit-column-break-inside:avoid;
        margin:0;
        border-radius:12px;
        overflow:hidden;
        background:var(--card-bg);
        border:1px solid var(--border);
        box-shadow:0 8px 24px rgba(0,0,0,0.4);
        transition:all 0.3s ease;
        cursor:pointer;
    }

    .card:hover{
        transform:translateY(-4px);
        box-shadow:0 12px 32px rgba(255,107,107,0.2);
        border-color:var(--accent);
    }

    .card img{
        width:100%;
        height:auto;
        display:block;
        background:linear-gradient(135deg, #2d3748, #1a202c);
    }

    .card figcaption{
        padding:12px;
        font-size:14px;
        color:var(--text-secondary);
        font-weight:500;
        background:rgba(0,0,0,0.2);
    }

    .remove-btn{
        position:absolute;
        right:8px;
        top:8px;
        background:rgba(255,107,107,0.9);
        color:#fff;
        border:none;
        padding:6px 10px;
        border-radius:6px;
        cursor:pointer;
        font-size:12px;
        font-weight:600;
        transition:all 0.2s ease;
    }

    .remove-btn:hover{
        background:var(--accent);
        box-shadow:0 4px 12px rgba(255,107,107,0.4);
    }

    .note{
        max-width:1200px;
        margin:0 auto;
        padding:0 1rem 2rem;
        color:var(--muted);
        font-size:14px;
    }

    footer{
        padding:24px;
        color:var(--muted);
        text-align:center;
        border-top:1px solid var(--border);
        margin-top:48px;
    }

    .control-col{flex:1;min-width:240px}
    .control-row{display:flex;gap:.5rem;align-items:center}
    .control-label{width:120px;display:inline-block;font-weight:600}

    .album-btn{
        background:var(--card-bg);
        border:2px solid var(--border);
        padding:10px 16px;
        border-radius:8px;
        cursor:pointer;
        font-weight:600;
        color:var(--text-secondary);
        transition:all 0.2s ease;
    }

    .album-btn:hover{
        border-color:var(--accent);
        color:var(--text-primary);
    }

    .album-btn.active{
        background:linear-gradient(135deg, var(--accent), var(--accent-dark));
        color:#fff;
        border-color:var(--accent);
        box-shadow:0 4px 12px rgba(255,107,107,0.3);
    }

    .tag-btn{
        background:var(--card-bg);
        border:1px solid var(--border);
        padding:6px 12px;
        border-radius:20px;
        cursor:pointer;
        font-size:12px;
        color:var(--text-secondary);
        font-weight:500;
        transition:all 0.2s ease;
    }

    .tag-btn:hover{
        border-color:var(--accent);
        color:var(--accent);
    }

    .tag-btn.active{
        background:#1f2937;
        color:var(--accent);
        border-color:var(--accent);
        box-shadow:0 0 8px rgba(255,107,107,0.2);
    }

    #siteNav{
        max-width:1200px;
        margin:1.5rem auto;
        padding:0 1rem;
        display:flex;
        justify-content:center;
    }

    #albumNavWrapper{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        align-items:center;
    }

    #albumPanels{
        max-width:1200px;
        margin:0 auto;
        padding:1rem;
    }

    .album-panel{
        margin-bottom:3rem;
        padding:24px;
        background:var(--card-bg);
        border:1px solid var(--border);
        border-radius:12px;
        box-shadow:0 8px 24px rgba(0,0,0,0.4);
    }

    .album-panel h2{
        margin:0 0 4px 0;
        font-size:24px;
        color:var(--text-primary);
    }

    .album-tagNav{
        display:flex;
        gap:8px;
        align-items:center;
        flex-wrap:wrap;
    }

    #photoModal{
        background:rgba(0,0,0,0.8);
    }

    #photoModal > div{
        background:var(--card-bg);
        border:1px solid var(--border);
    }

    #photoModal .modal-title{
        color:var(--text-primary);
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar{
        width:8px;
        height:8px;
    }

    ::-webkit-scrollbar-track{
        background:var(--page-bg);
    }

    ::-webkit-scrollbar-thumb{
        background:var(--border);
        border-radius:4px;
    }

    ::-webkit-scrollbar-thumb:hover{
        background:var(--accent);
    }

    </style>
</head>
<body>
    <header>
        <h1>Albums photos</h1>
    </header>

    <main>

        <div class="controls" style="max-width:1200px;margin:0 auto 2rem;padding:24px;display:flex;gap:1.5rem;align-items:center;justify-content:space-between;flex-wrap:wrap;background:var(--card-bg);border:1px solid var(--border);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.4);">
            <button id="openAddPhotoBtn" type="button" class="btn" style="padding:10px 24px;margin:0">➕ Ajouter une image</button>

            <!-- Search + sort -->
            <div style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap">
                <div style="display:flex;flex-direction:column;">
                    <label style="font-size:.85rem;color:var(--muted);">Rechercher</label>
                    <input id="titleSearch" type="search" placeholder="Rechercher par titre..." style="padding:.5rem;border:1px solid #ddd;border-radius:6px;width:220px">
                </div>

                <div style="display:flex;flex-direction:column;">
                    <label style="font-size:.85rem;color:var(--muted);">Trier photos</label>
                    <select id="photoSort" style="padding:.45rem;border:1px solid #ddd;border-radius:6px">
                        <option value="note_desc">Note (décroissant)</option>
                        <option value="note_asc">Note (croissant)</option>
                        <option value="title_asc">Titre (A → Z)</option>
                        <option value="title_desc">Titre (Z → A)</option>
                    </select>
                </div>

                <div style="display:none;flex-direction:column;margin-left:8px" id="albumSortContainer">
                    <label style="font-size:.85rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Trier albums</label>
                    <select id="albumSort" style="padding:10px 12px;border:1px solid var(--border);border-radius:8px;background:#0f1419;color:var(--text-primary)">
                        <option value="creation_desc">Date création (récent → ancien)</option>
                        <option value="creation_asc">Date création (ancien → récent)</option>
                        <option value="title_asc">Titre (A → Z)</option>
                        <option value="title_desc">Titre (Z → A)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lightbox pour ajouter une image -->
        <div id="addPhotoModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.8);align-items:center;justify-content:center;z-index:1300;padding:20px">
            <div style="max-width:600px;width:100%;background:var(--card-bg);border-radius:12px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.6);border:1px solid var(--border)">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:24px;border-bottom:1px solid var(--border)">
                    <h2 style="margin:0;font-size:22px;color:var(--text-primary)">Ajouter une image</h2>
                    <button type="button" id="closeAddPhotoBtn" style="border:none;background:transparent;font-size:24px;padding:0;cursor:pointer;color:var(--text-secondary)">✕</button>
                </div>
                <div style="padding:24px;overflow-y:auto;max-height:calc(90vh - 100px)">
                    <form id="addPhotoForm" method="POST" action="<?php echo e(route('photos.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div style="margin-bottom:20px">
                            <label style="display:block;margin-bottom:8px;color:var(--text-primary);font-weight:600">Album *</label>
                            <select name="album_id" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;background:#0f1419;color:var(--text-primary)">
                                <?php $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($alb->id); ?>"><?php echo e($alb->titre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div style="margin-bottom:20px">
                            <label style="display:block;margin-bottom:8px;color:var(--text-primary);font-weight:600">Titre (optionnel)</label>
                            <input type="text" name="titre" placeholder="Titre pour la photo" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;background:#0f1419;color:var(--text-primary);box-sizing:border-box">
                        </div>

                        <div style="margin-bottom:20px">
                            <label style="display:block;margin-bottom:8px;color:var(--text-primary);font-weight:600">URL de l'image *</label>
                            <input type="url" name="url" placeholder="https://example.com/image.jpg" required style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;background:#0f1419;color:var(--text-primary);box-sizing:border-box">
                        </div>

                        <div style="display:flex;gap:10px;justify-content:flex-end">
                            <button type="button" id="cancelAddPhotoBtn" style="padding:10px 24px;background:var(--border);color:var(--text-primary);border:none;border-radius:8px;cursor:pointer;font-weight:600;transition:all 0.2s">Annuler</button>
                            <button type="submit" style="padding:10px 24px;background:linear-gradient(135deg, var(--accent), var(--accent-dark));color:white;border:none;border-radius:8px;cursor:pointer;font-weight:600;transition:all 0.2s">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Header / Navigation menu: choose an album -->
        <nav id="siteNav" style="max-width:1100px;margin:1rem auto;padding:0 1rem;display:flex;justify-content:center">
            <div id="albumNavWrapper" style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center">
                <?php if(isset($albums) && $albums->count()): ?>
                    <?php $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" class="album-btn" data-index="<?php echo e($index); ?>" data-album-id="<?php echo e($album->id); ?>" aria-pressed="false"><?php echo e($album->titre); ?></button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="album-btn" data-index="all" style="margin-left:8px">Tous</button>
                <?php else: ?>
                    <div class="note">Aucun album trouvé dans la base.</div>
                <?php endif; ?>
            </div>

                <!-- tag nav removed from top-level — each album shows its own tags next to the title -->
        </nav>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                // --- Lightbox for adding photos ---
                const addPhotoModal = document.getElementById('addPhotoModal');
                const openAddPhotoBtn = document.getElementById('openAddPhotoBtn');
                const closeAddPhotoBtn = document.getElementById('closeAddPhotoBtn');
                const cancelAddPhotoBtn = document.getElementById('cancelAddPhotoBtn');

                function openAddPhotoModal(){
                    if(addPhotoModal) addPhotoModal.style.display = 'flex';
                }

                function closeAddPhotoModal(){
                    if(addPhotoModal) addPhotoModal.style.display = 'none';
                }

                openAddPhotoBtn?.addEventListener('click', openAddPhotoModal);
                closeAddPhotoBtn?.addEventListener('click', closeAddPhotoModal);
                cancelAddPhotoBtn?.addEventListener('click', closeAddPhotoModal);

                // close modal on clickout
                addPhotoModal?.addEventListener('click', (e) => {
                    if(e.target === addPhotoModal) closeAddPhotoModal();
                });

                // close modal on Escape key
                document.addEventListener('keydown', (e) => {
                    if(e.key === 'Escape' && addPhotoModal?.style.display !== 'none') closeAddPhotoModal();
                });

                // --- Gallery functionality ---
                function getAlbumButtons(){ return Array.from(document.querySelectorAll('.album-btn')); }
                const panels = Array.from(document.querySelectorAll('.album-panel'));
                if(!getAlbumButtons().length) return;

                function showAll(){
                    const btns = getAlbumButtons();
                    btns.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed','false'); });
                    const allBtn = btns.find(b => b.dataset.index === 'all');
                    if(allBtn) { allBtn.classList.add('active'); allBtn.setAttribute('aria-pressed','true'); }

                    // show album sort dropdown
                    const albumSortContainer = document.getElementById('albumSortContainer');
                    if(albumSortContainer) albumSortContainer.style.display = 'flex';

                    // clear active tags for every panel and show all
                    panels.forEach(p => {
                        p.style.display = '';
                        Array.from(p.querySelectorAll('.tag-btn')).forEach(tb => { tb.classList.remove('active'); tb.setAttribute('aria-pressed','false'); });
                        applySearchAndSort(p);
                    });
                }

                function setActiveIndex(index){
                    const btns = getAlbumButtons();
                    btns.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed','false'); });
                    panels.forEach(p => p.style.display = 'none');

                    // hide album sort dropdown
                    const albumSortContainer = document.getElementById('albumSortContainer');
                    if(albumSortContainer) albumSortContainer.style.display = 'none';

                    const btn = getAlbumButtons().find(b => b.dataset.index == index);
                    if(btn){ btn.classList.add('active'); btn.setAttribute('aria-pressed','true'); }

                    const panel = panels.find(p => p.dataset.index == index);
                    if(panel){ panel.style.display = ''; panel.scrollIntoView({behavior:'smooth', block:'start'}); applySearchAndSort(panel); }
                }

                // apply tag filter for a single panel only
                function applyFilters(panel){
                    if(!panel) return;
                    // combine tag + title search filters
                    const tagButtons = Array.from(panel.querySelectorAll('.tag-btn'));
                    const activeTag = tagButtons.find(t => t.classList.contains('active'));
                    const tagId = activeTag ? activeTag.dataset.tagId : null;

                    const search = (document.getElementById('titleSearch')?.value || '').trim().toLowerCase();
                    const cards = Array.from(panel.querySelectorAll('.card'));

                    cards.forEach(card => {
                        // tag check
                        let tagMatch = true;
                        if(tagId && tagId !== 'all'){
                            const tags = (card.dataset.tags || '').split(',').filter(Boolean);
                            tagMatch = tags.includes(tagId);
                        }

                        // title check
                        let titleMatch = true;
                        if(search){
                            const title = (card.dataset.title || '').toLowerCase();
                            titleMatch = title.includes(search);
                        }

                        card.style.display = (tagMatch && titleMatch) ? '' : 'none';
                    });
                }

                // use event delegation for album nav buttons
                const albumNavWrapperEl = document.getElementById('albumNavWrapper');
                albumNavWrapperEl?.addEventListener('click', (e) => {
                    const btn = e.target.closest('.album-btn');
                    if(!btn) return;
                    const idx = btn.dataset.index;
                    if(idx === 'all') return showAll();
                    setActiveIndex(idx);
                });
                albumNavWrapperEl?.addEventListener('keydown', (e)=>{
                    const btn = e.target.closest('.album-btn');
                    if(!btn) return;
                    if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); btn.click(); }
                });

                // search + sort hooks
                const titleSearch = document.getElementById('titleSearch');
                const photoSort = document.getElementById('photoSort');
                const albumSort = document.getElementById('albumSort');

                function sortCards(panel, method){
                    if(!panel) return;
                    const container = panel.querySelector('.gallery--columns');
                    if(!container) return;

                    const cards = Array.from(container.querySelectorAll('.card'));
                    let sorted = cards.slice();

                    if(method === 'note_desc') sorted.sort((a,b) => (parseInt(b.dataset.note||0) - parseInt(a.dataset.note||0)));
                    else if(method === 'note_asc') sorted.sort((a,b) => (parseInt(a.dataset.note||0) - parseInt(b.dataset.note||0)));
                    else if(method === 'title_asc') sorted.sort((a,b) => a.dataset.title.localeCompare(b.dataset.title));
                    else if(method === 'title_desc') sorted.sort((a,b) => b.dataset.title.localeCompare(a.dataset.title));

                    // re-append in sorted order
                    sorted.forEach(c => container.appendChild(c));
                }

                function applySearchAndSort(panel){
                    applyFilters(panel);
                    sortCards(panel, photoSort?.value || 'note_desc');
                }
                const firstBtn = getAlbumButtons()[0];
                if(firstBtn && firstBtn.dataset.index === 'all') showAll(); else if(firstBtn) setActiveIndex(firstBtn.dataset.index);
                // Tag button interactions are scoped per-panel: find tag buttons inside each visible panel
                panels.forEach(panel => {
                    const tagButtons = Array.from(panel.querySelectorAll('.tag-btn'));
                    if(!tagButtons.length) return;

                    tagButtons.forEach(tb=> tb.addEventListener('click', () => {
                        // toggle behavior: if clicked and active -> clear filters
                            if(tb.classList.contains('active')){
                            tb.classList.remove('active');
                            tb.setAttribute('aria-pressed','false');
                            applySearchAndSort(panel);
                            return;
                        }

                        // single-tag selection inside this panel
                        tagButtons.forEach(t=> { t.classList.remove('active'); t.setAttribute('aria-pressed','false'); });
                        tb.classList.add('active');
                        tb.setAttribute('aria-pressed','true');
                        applySearchAndSort(panel);
                    }));
                });

                // --- image viewer modal ---
                function showPhotoModal(src, title){
                    let modal = document.getElementById('photoModal');
                    if(!modal) return;
                    modal.querySelector('img').src = src;
                    modal.querySelector('.modal-title').textContent = title || '';
                    modal.style.display = 'flex';
                }

                function closePhotoModal(){
                    const modal = document.getElementById('photoModal');
                    if(modal) modal.style.display = 'none';
                }

                // open modal on image click
                panels.forEach(panel => {
                    panel.querySelectorAll('.card img').forEach(img=> img.addEventListener('click', (e)=>{
                        const card = img.closest('.card');
                        const src = img.src;
                        const title = card?.dataset.title || '';
                        showPhotoModal(src, title);
                    }));
                });

                // close modal on clickout + esc
                document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape') closePhotoModal(); });
                const modalEl = document.getElementById('photoModal');
                modalEl?.addEventListener('click', (e)=>{
                    // if user clicked the overlay (not the modal content) close
                    if(e.target === modalEl) closePhotoModal();
                });

                // wire up search and sort
                if(titleSearch){
                    titleSearch.addEventListener('input', ()=>{
                        panels.forEach(p => { if(p.style.display !== 'none') applySearchAndSort(p); });
                    });
                }

                if(photoSort){
                    photoSort.addEventListener('change', ()=>{ panels.forEach(p => { if(p.style.display !== 'none') sortCards(p, photoSort.value); }); });
                }

                if(albumSort){
                    albumSort.addEventListener('change', ()=>{
                        // reorder panels and album buttons
                        const wrapper = document.getElementById('albumPanels');
                        const navWrapper = document.getElementById('albumNavWrapper');
                        const panelsArr = Array.from(wrapper.querySelectorAll('.album-panel'));

                        const mapPanel = panelsArr.map(p => {
                            const id = p.dataset.albumId;
                            const title = (p.querySelector('h2')?.textContent || '').trim().toLowerCase();
                            const dateStr = p.dataset.creation || '2000-01-01';
                            const date = new Date(dateStr);
                            return { panel: p, id, title, date };
                        });

                        if(albumSort.value === 'creation_desc') mapPanel.sort((a,b) => b.date - a.date);
                        else if(albumSort.value === 'creation_asc') mapPanel.sort((a,b) => a.date - b.date);
                        else if(albumSort.value === 'title_asc') mapPanel.sort((a,b) => a.title.localeCompare(b.title));
                        else if(albumSort.value === 'title_desc') mapPanel.sort((a,b) => b.title.localeCompare(a.title));

                        // re-insert panels in new order and update data-index on both panels and buttons
                        mapPanel.forEach((m, idx) => {
                            wrapper.appendChild(m.panel);
                            m.panel.dataset.index = idx;
                        });

                        // reorder nav buttons (excluding 'all')
                        const allBtn = Array.from(navWrapper.querySelectorAll('.album-btn')).find(b => b.dataset.index === 'all');
                        const albumBtns = Array.from(navWrapper.querySelectorAll('.album-btn')).filter(b => b.dataset.index !== 'all');
                        // clear non-all buttons
                        albumBtns.forEach(b => navWrapper.removeChild(b));
                        // append in new order
                        mapPanel.forEach((m, idx) => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'album-btn';
                            btn.dataset.index = idx;
                            btn.dataset.albumId = m.id;
                            btn.textContent = m.panel.querySelector('h2')?.textContent || 'Album';
                            btn.setAttribute('aria-pressed','false');
                            // wire up click
                            btn.addEventListener('click', ()=> setActiveIndex(btn.dataset.index));
                            btn.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); btn.click(); } });
                            navWrapper.insertBefore(btn, allBtn);
                        });
                    });
                }
            });
        </script>

        <?php if(session('success')): ?>
            <div style="max-width:1100px;margin:0 auto;padding:0 1rem 1rem;color:green;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div style="max-width:1100px;margin:0 auto;padding:0 1rem 1rem;color:#b91c1c;"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <!-- Album panels (initially hidden; shown when a button is selected) -->
        <section id="albumPanels" style="max-width:1100px;margin:0 auto;padding:1rem;">
            <?php $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="album-panel" data-index="<?php echo e($index); ?>" data-album-id="<?php echo e($album->id); ?>" data-creation="<?php echo e($album->creation); ?>" style="display:none;margin-bottom:2rem;padding:0 1rem;">
                    <header style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                        <div style="display:flex;flex-direction:column;gap:6px">
                            <div style="display:flex;align-items:center;gap:.6rem">
                                <h2 style="margin:0;font-size:1.1rem"><?php echo e($album->titre); ?></h2>

                                <?php
                                    // collect tags used by this album's photos
                                    $albumTags = $album->photos->flatMap->tags->unique('id');
                                ?>

                                <?php if($albumTags->count()): ?>
                                    <div class="album-tagNav" style="display:flex;gap:.35rem;align-items:center;">
                                        <button type="button" class="tag-btn" data-tag-id="all" aria-pressed="false">Tous</button>
                                        <?php $__currentLoopData = $albumTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button" class="tag-btn" data-tag-id="<?php echo e($tag->id); ?>" aria-pressed="false"><?php echo e($tag->nom); ?></button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div style="font-size:.9rem;color:var(--muted)">Créé: <?php echo e($album->creation); ?> — <?php echo e($album->photos->count()); ?> photos</div>
                        </div>
                    </header>

                    <?php if($album->photos->isEmpty()): ?>
                        <div class="note">Aucune photo dans cet album.</div>
                    <?php else: ?>
                        <div class="gallery--columns">
                            <?php $__currentLoopData = $album->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <figure class="card" data-id="<?php echo e($photo->id); ?>" data-tags="<?php echo e($photo->tags->pluck('id')->join(',')); ?>" data-title="<?php echo e(htmlspecialchars($photo->titre, ENT_QUOTES)); ?>" data-note="<?php echo e($photo->note ?? 0); ?>">
                                    <img src="<?php echo e($photo->data ? route('photos.image', $photo) : $photo->url); ?>" alt="<?php echo e($photo->titre); ?>">
                                    <figcaption><?php echo e($photo->titre); ?></figcaption>
                                    <form style="position:absolute;right:8px;top:8px;" method="POST" action="<?php echo e(route('photos.destroy', $photo)); ?>" onsubmit="return confirm('Supprimer cette photo ?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="remove-btn" type="submit">Suppr</button>
                                    </form>
                                </figure>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>

    </main>
    <!-- photo modal -->
    <div id="photoModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);align-items:center;justify-content:center;z-index:1200;padding:20px">
        <div style="max-width:1100px;width:100%;max-height:88vh;background:#fff;border-radius:8px;overflow:hidden;display:flex;flex-direction:column">
            <div style="display:flex;align-items:center;padding:.6rem;border-bottom:1px solid #eee">
                <div class="modal-title" style="font-weight:600"></div>
                <button style="margin-left:auto;border:none;background:transparent;font-size:18px;padding:.4rem;cursor:pointer" onclick="(function(){document.getElementById('photoModal').style.display='none'})()">✕</button>
            </div>
            <div style="padding:12px;display:flex;align-items:center;justify-content:center;overflow:auto;flex:1">
                <img style="max-width:100%;max-height:80vh;display:block;border-radius:6px" src="" alt="" />
            </div>
        </div>
    </div>
    <footer>
        <!-- Pied de page -->
    </footer>
</body>
</html><?php /**PATH /Users/Manon/Photos/resources/views/index.blade.php ENDPATH**/ ?>