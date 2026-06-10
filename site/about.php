<?php
$PAGE='about';
$PAGE_TITLE='About — CISD INSTITUTE';
$PAGE_DESC='Learn about our mission, vision and the team behind CISD INSTITUTE.';
include 'includes/header.php';

$aboutValues = cisd_get_features($pdo, 'about_values');
$team = cisd_get_team($pdo);
?>

<section class="page-header">
  <div class="container">
    <span class="eyebrow"><?= e(site_setting('about_header_eyebrow', 'About Us')) ?></span>
    <h1><?= e(site_setting('about_header_title')) ?></h1>
    <p><?= e(site_setting('about_header_subtitle')) ?></p>
  </div>
</section>

<section class="section">
  <div class="container about-grid">
    <div class="reveal">
      <span class="eyebrow">Our Story</span>
      <h2><?= e(site_setting('about_story_title')) ?></h2>
      <p style="color:var(--muted);margin-top:14px"><?= e(site_setting('about_story_p1')) ?></p>
      <p style="color:var(--muted);margin-top:10px"><?= e(site_setting('about_story_p2')) ?></p>
    </div>
    <div class="reveal">
      <img src="<?= e(site_setting('about_story_image', 'images/hero-classroom.jpg')) ?>" alt="Classroom" style="border-radius:20px;box-shadow:var(--shadow)" />
    </div>
  </div>
</section>

<section class="section" style="background:#fff">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(site_setting('about_values_eyebrow', 'Our Values')) ?></span>
      <h2><?= e(site_setting('about_values_title', 'Mission, Vision & Promise')) ?></h2>
    </div>
    <div class="mission-grid">
      <?php foreach ($aboutValues as $value): ?>
      <div class="feature reveal">
        <div class="icon"><?= e($value['icon']) ?></div>
        <h3><?= e($value['title']) ?></h3>
        <p><?= e($value['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(site_setting('about_team_eyebrow', 'Meet the Team')) ?></span>
      <h2><?= e(site_setting('about_team_title', 'Mentors who care about your success')) ?></h2>
    </div>
    <div class="grid cards-3" style="justify-content:center;">
      <?php foreach($team as $t): ?>
      <div class="card reveal">
        <img src="<?= e($t['image_path']) ?>" alt="<?= e($t['name']) ?>" loading="lazy" onerror="this.onerror=null; this.src='images/hero-classroom.jpg';" />
        <div class="card-body">
          <h3><?= e($t['name']) ?></h3>
          <p style="color:var(--gold);font-weight:600;margin:0"><?= e($t['role']) ?></p>
          <p><?= e($t['specialty']) ?></p>
          <a href="teacher.php?id=<?= (int) $t['id'] ?>" class="view-more-btn">View More</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
