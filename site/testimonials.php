<?php
$PAGE = 'testimonials';
$PAGE_TITLE = 'What our graduates say - CISD Institute';
$PAGE_DESC = 'Read stories and testimonials from our successful graduates.';
include 'includes/header.php';
?>

<section class="section testimonials">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Student Stories</span>
      <h2>What our graduates say</h2>
    
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
        
      </div>
      <?php endforeach; ?>
        </div>

  </div>
</section>




<?php include 'includes/footer.php'; ?>
