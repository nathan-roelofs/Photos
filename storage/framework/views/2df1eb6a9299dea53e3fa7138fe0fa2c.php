<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo e($album->titre); ?></title>
  <style>
    body{font-family:system-ui,Arial;margin:0;background:#f9fafb;color:#111}
    .container{max-width:1100px;margin:1.5rem auto;padding:0 1rem}
    header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
    .gallery{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px}
    .card{background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.06)}
    .card img{width:100%;height:160px;object-fit:cover;display:block}
    .btn{padding:.5rem .75rem;background:#ef4444;color:#fff;border-radius:6px;text-decoration:none}
  </style>
</head>
<body>
  <div class="container">
    <header>
      <div>
        <h1 style="margin:0"><?php echo e($album->titre); ?></h1>
        <div style="color:#6b7280;font-size:.9rem">Créé: <?php echo e($album->creation); ?> — <?php echo e($album->photos->count()); ?> photos</div>
      </div>
      <div style="display:flex;gap:.5rem;align-items:center">
        <a href="<?php echo e(url('/')); ?>" class="btn" style="background:#6b7280">Retour</a>
      </div>
    </header>

    <?php if($album->photos->isEmpty()): ?>
      <div>Aucune photo dans cet album.</div>
    <?php else: ?>
      <div class="gallery">
        <?php $__currentLoopData = $album->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <figure class="card">
            <img src="<?php echo e($photo->data ? route('photos.image', $photo) : $photo->url); ?>" alt="<?php echo e($photo->titre); ?>">
            <figcaption style="padding:.5rem"><?php echo e($photo->titre); ?></figcaption>
          </figure>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
<?php /**PATH C:\Users\Nathan\Documents\Cours\BUT_2\Photos\resources\views/albums/show.blade.php ENDPATH**/ ?>