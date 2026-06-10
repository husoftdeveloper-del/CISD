<?php
$PAGE = 'testimonials';
$PAGE_TITLE = 'What our graduates say - CISD Institute';
$PAGE_DESC = 'Read stories and testimonials from our successful graduates.';
include 'includes/header.php';
?>

<section class="section testimonials">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(site_setting('testimonials_eyebrow', 'Student Stories')) ?></span>
      <h2><?= e(site_setting('testimonials_title', 'What our graduates say')) ?></h2>
    
    </div>
    <div class="grid cards-3">
      <?php
      $stmt = $pdo->query("SELECT id, image, name, father_name, course, quote, location, contact, email, education, created_at FROM success_stories ORDER BY created_at DESC");
      $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($reviews as $r): ?>
      <div class="t-card reveal">
        <img src="<?php echo e($r['image'] ? 'uploads/stories/'.$r['image'] : 'uploads/stories/placeholder.png'); ?>" alt="<?php echo e($r['name']); ?>" class="t-profile-img" />
        <div class="t-stars">✦✦✦✦✦</div>
        <p class="t-quote">“<?php echo e($r['quote']); ?>”</p>
        <div class="t-author">
          <div><strong><?php echo e($r['name']); ?></strong><small><?php echo e($r['course']); ?></small></div>
        </div>
        <?php if ($r['location'] || $r['contact'] || $r['email'] || $r['education']): ?>
        <button class="view-more-btn" onclick="toggleDetails(this)">View More</button>
        <div class="t-details">
          <?php if ($r['location']): ?><p><strong>📍 Location:</strong> <?= e($r['location']) ?></p><?php endif; ?>
          <?php if ($r['contact']): ?><p><strong>📞 Contact:</strong> <?= e($r['contact']) ?></p><?php endif; ?>
          <?php if ($r['email']): ?><p><strong>✉️ Email:</strong> <?= e($r['email']) ?></p><?php endif; ?>
          <?php if ($r['education']): ?><p><strong>🎓 Education:</strong> <?= e($r['education']) ?></p><?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
        </div>

  </div>
</section>




<?php include 'includes/footer.php'; ?>
