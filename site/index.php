<?php 
$PAGE='home'; 
$PAGE_TITLE='CISD INSTITUTE — Professional IT & Digital Skills Training'; 
$PAGE_DESC='Master in-demand IT and digital skills with hands-on training, expert mentors and real projects.'; 
include 'includes/header.php';

$stats = [
    'students_trained' => 500,
    'modern_courses' => 20,
    'years_experience' => 2,
    'success_stories' => 100
];

if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT stat_key, stat_value FROM statistics");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats[$row['stat_key']] = $row['stat_value'];
        }
    } catch (PDOException $e) {}
}

$courses = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM courses WHERE status = 'active' ORDER BY display_order ASC, id DESC LIMIT 6");
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}

if (empty($courses)) {
    include 'data/courses.php';
    $courses = array_slice($COURSES, 0, 6);
}

$homeFeatures = cisd_get_features($pdo, 'home');
$reviews = cisd_get_success_stories($pdo, 3);
?>

<section class="hero">
  <div class="container hero-inner">
    <div>
      <span class="eyebrow"><?= e(site_setting('hero_eyebrow', 'Premium IT Training')) ?></span>
      <h1><?= site_setting('hero_title', 'Learn the skills that <span>shape the future</span>') ?></h1>
      <p><?= e(site_setting('hero_subtitle')) ?></p>
      <div class="hero-cta">
        <a class="btn btn-gold" href="admissions.php">Apply for Admission →</a>
        <a class="btn btn-outline" href="courses.php">Explore Courses</a>
      </div>
    </div>
    <div class="hero-art">
      <div class="blob b1">&lt;/&gt;</div>
      <div class="blob b2">UI</div>
      <div class="blob b3">★</div>
    </div>
  </div>
</section>

<section class="stats">
  <div class="container stats-grid">
    <div class="stat reveal clickable" id="students_trained_box">
      <div class="v" data-target="<?php echo $stats['students_trained'] ?? 0; ?>">
        <span class="counter">0</span><span>+</span>
      </div>
      <div class="l">Students Trained</div>
      <a class="btn btn-navy stat-btn" href="students.php" target="_blank">View All Students</a>
    </div>
    <div class="stat reveal">
      <div class="v" data-target="<?php echo $stats['modern_courses'] ?? 20; ?>">
        <span class="counter">0</span><span>+</span>
      </div>
      <div class="l">Modern Courses</div>
      <a class="btn btn-navy stat-btn" href="courses.php" target="_blank">View All Courses</a>
    </div>
    <div class="stat reveal">
      <div class="v" data-target="<?php echo $stats['years_experience'] ?? 2; ?>">
        <span class="counter">0</span><span>+</span>
      </div>
      <div class="l">Years Experience</div>
    </div>
    <div class="stat reveal">
      <div class="v" data-target="<?= $stats['success_stories'] ?? 100 ?>"><span class="counter">0</span><span>+</span></div>
      <div class="l">Success Stories</div>
      <a class="btn btn-navy stat-btn" href="testimonials.php" target="_blank">View All Success Stories</a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(site_setting('features_eyebrow', 'Why Choose Us')) ?></span>
      <h2><?= e(site_setting('features_title', 'Built for serious learners')) ?></h2>
      <p><?= e(site_setting('features_subtitle')) ?></p>
    </div>
    <div class="features">
      <?php foreach ($homeFeatures as $feature): ?>
      <div class="feature reveal">
        <div class="icon"><?= e($feature['icon']) ?></div>
        <h3><?= e($feature['title']) ?></h3>
        <p><?= e($feature['description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" style="background:#fff">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(site_setting('courses_eyebrow', 'Featured Courses')) ?></span>
      <h2><?= e(site_setting('courses_title', 'Programs designed for the modern career')) ?></h2>
    </div>
    <div class="grid cards-3">
      <?php foreach($courses as $c): ?>
        <div class="card reveal">
          <img src="<?= !empty($c['image_path']) ? e($c['image_path']) : 'images/' . e($c['image'] ?? 'course-placeholder.jpg') ?>" alt="<?= e($c['title']) ?>" loading="lazy" />
          <div class="card-body">
            <span class="badge"><?= e($c['duration'] ?? 'Flexible') ?></span>
            <h3><?= e($c['title']) ?></h3>
            <p><?= e(substr($c['description'] ?? $c['desc'] ?? '', 0, 100)) ?></p>
            <div class="card-meta"><span>Apply Now →</span></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:36px">
      <a class="btn btn-navy" href="courses.php">View All Courses</a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(site_setting('testimonials_eyebrow', 'Student Stories')) ?></span>
      <h2><?= e(site_setting('testimonials_title', 'What our graduates say')) ?></h2>
    </div>
    <div class="grid cards-3">
      <?php foreach($reviews as $r): ?>
      <div class="t-card reveal">
        <img src="<?= e($r['image'] ? 'uploads/stories/'.$r['image'] : 'images/course-placeholder.jpg') ?>" alt="<?= e($r['name']) ?>" class="t-profile-img" />
        <div class="t-stars">✦✦✦✦✦</div>
        <p class="t-quote">“<?= e($r['quote']) ?>”</p>
        <div class="t-author">
          <div><strong><?= e($r['name']) ?></strong><small><?= e($r['course']) ?></small></div>
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

<section class="section">
  <div class="container">
    <div class="cta-band reveal">
      <div>
        <h2><?= e(site_setting('cta_title', 'Ready to start your journey?')) ?></h2>
        <p><?= e(site_setting('cta_subtitle')) ?></p>
      </div>
      <a class="btn btn-gold" href="admissions.php">Apply for Admission →</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
