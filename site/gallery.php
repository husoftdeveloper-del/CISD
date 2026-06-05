<?php $PAGE='gallery'; $PAGE_TITLE='Gallery — CISD INSTITUTE'; $PAGE_DESC='Glimpses of our campus, classrooms and student life.'; include 'includes/header.php'; ?>

<section class="page-header">
  <div class="container">
    <span class="eyebrow">Campus Life</span>
    <h1>A look inside our learning space</h1>
    <p>Modern classrooms, collaborative learning and a vibrant student community.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="gallery-grid">
      <?php
      $images = ['gallery-1.jpg','gallery-2.jpg','gallery-3.jpg','hero-classroom.jpg','course-webdev.jpg','course-graphic.jpg','course-marketing.jpg','course-uiux.jpg','course-video.jpg'];
      foreach($images as $img): ?>
        <div class="g-item reveal"><img src="images/<?= e($img) ?>" alt="Gallery image" loading="lazy" /></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<div class="lightbox" id="lightbox">
  <button class="lightbox-close">×</button>
  <img id="lightbox-img" src="" alt="Preview" />
</div>

<?php include 'includes/footer.php'; ?>
