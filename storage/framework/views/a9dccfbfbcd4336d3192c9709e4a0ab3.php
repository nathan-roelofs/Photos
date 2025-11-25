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

    /* album nav buttons */
    .album-btn{background:#fff;border:1px solid #eee;padding:.6rem 1rem;border-radius:8px;cursor:pointer}
    .album-btn.active{background:var(--accent);color:#fff;border-color:var(--accent)}

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
        </nav>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const buttons = Array.from(document.querySelectorAll('.album-btn'));
                const panels = Array.from(document.querySelectorAll('.album-panel'));
                if(!buttons.length) return;

                function showAll(){
                    buttons.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed','false'); });
                    const allBtn = buttons.find(b => b.dataset.index === 'all');
                    if(allBtn) { allBtn.classList.add('active'); allBtn.setAttribute('aria-pressed','true'); }
                    panels.forEach(p => p.style.display = '');
                }

                function setActiveIndex(index){
                    buttons.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed','false'); });
                    panels.forEach(p => p.style.display = 'none');

                    const btn = buttons.find(b => b.dataset.index == index);
                    if(btn){ btn.classList.add('active'); btn.setAttribute('aria-pressed','true'); }

                    const panel = panels.find(p => p.dataset.index == index);
                    if(panel){ panel.style.display = ''; panel.scrollIntoView({behavior:'smooth', block:'start'}); }
                }

                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const idx = btn.dataset.index;
                        if(idx === 'all') return showAll();
                        setActiveIndex(idx);
                    });
                    btn.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); btn.click(); } });
                });

                // default selection: first button (unless there's an explicit 'all')
                const firstBtn = buttons[0];
                if(firstBtn && firstBtn.dataset.index === 'all') showAll(); else if(firstBtn) setActiveIndex(firstBtn.dataset.index);
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
                <div class="album-panel" data-index="<?php echo e($index); ?>" style="display:none;margin-bottom:2rem;padding:0 1rem;">
                    <header style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                        <div>
                            <h2 style="margin:0;font-size:1.1rem"><?php echo e($album->titre); ?></h2>
                            <div style="font-size:.9rem;color:var(--muted)">Créé: <?php echo e($album->creation); ?> — <?php echo e($album->photos->count()); ?> photos</div>
                        </div>
                    </header>

                    <?php if($album->photos->isEmpty()): ?>
                        <div class="note">Aucune photo dans cet album.</div>
                    <?php else: ?>
                        <div class="gallery--columns">
                            <?php $__currentLoopData = $album->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <figure class="card" data-id="<?php echo e($photo->id); ?>">
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
    <footer>
        <!-- Pied de page -->
    </footer>
</body>
</html><?php /**PATH /Users/clemtitine/Desktop/Cours/MMI2/S3_P2/Photos/resources/views/index.blade.php ENDPATH**/ ?>