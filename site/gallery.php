<?php
$PAGE='gallery';
$PAGE_TITLE='Gallery — CISD INSTITUTE';
$PAGE_DESC='Glimpses of our campus, classrooms and student life.';
include 'includes/header.php';

$galleryItems = cisd_get_gallery($pdo);
if (empty($galleryItems)) {
    $fallback = ['gallery-1.jpg','gallery-2.jpg','gallery-3.jpg','hero-classroom.jpg','course-webdev.jpg','course-graphic.jpg','course-marketing.jpg','course-uiux.jpg','course-video.jpg'];
    foreach ($fallback as $img) {
        $galleryItems[] = ['image_path' => 'images/' . $img, 'title' => 'Gallery image'];
    }
}
?>

<section class="page-header">
  <div class="container">
    <span class="eyebrow"><?= e(site_setting('gallery_eyebrow', 'Campus Life')) ?></span>
    <h1><?= e(site_setting('gallery_title')) ?></h1>
    <p><?= e(site_setting('gallery_subtitle')) ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="gallery-grid">
      <?php foreach($galleryItems as $item): ?>
        <div class="g-item reveal">
          <img src="<?= e($item['image_path']) ?>" alt="<?= e($item['title'] ?? 'Gallery image') ?>" loading="lazy" />
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<div class="lightbox" id="lightbox">
  <button class="lightbox-close">×</button>
  <img id="lightbox-img" src="" alt="Preview" />
</div>

<?php include 'includes/footer.php'; ?>
