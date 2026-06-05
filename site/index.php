<?php 
$PAGE='home'; 
$PAGE_TITLE='CISD INSTITUTE — Professional IT & Digital Skills Training'; 
$PAGE_DESC='Master in-demand IT and digital skills with hands-on training, expert mentors and real projects.'; 
include 'includes/header.php';

// Fetch statistics from database
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
    } catch (PDOException $e) {
        // Use default values if database fails
    }
}

// Fetch courses from database
$courses = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM courses WHERE status = 'active' ORDER BY display_order ASC, id DESC LIMIT 6");
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Fallback to hardcoded data if database fails
    }
}

// If no courses from database, use fallback
if (empty($courses)) {
    include 'data/courses.php';
    $courses = array_slice($COURSES, 0, 6);
}
?>

<section class="hero">
  <div class="container hero-inner">
    <div>
      <span class="eyebrow">Premium IT Training</span>
      <h1>Learn the skills that <span>shape the future</span></h1>
      <p>Industry-grade courses in Web Development, Graphic Design, Digital Marketing, App Development and more — taught by expert mentors with real projects.</p>
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
<div class="stat reveal"><div class="v" data-target="<?= $stats['success_stories'] ?? 100 ?>"><span class="counter">0</span><span>+</span></div><div class="l">Success Stories</div><a class="btn btn-navy stat-btn" href="testimonials.php" target="_blank">View All Success Stories</a></div>
  </div>
</section>


<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Why Choose Us</span>
      <h2>Built for serious learners</h2>
      <p>Practical curriculum, modern tools, mentor support and project-based learning that prepares you for jobs and freelance work.</p>
    </div>
    <div class="features">
      <div class="feature reveal"><div class="icon">★</div><h3>Expert Instructors</h3><p>Learn from industry professionals with 5+ years of hands-on experience.</p></div>
      <div class="feature reveal"><div class="icon">⚡</div><h3>Project-Based</h3><p>Build real portfolios with live client-style projects from day one.</p></div>
      <div class="feature reveal"><div class="icon">✓</div><h3>Career Support</h3><p>CV reviews, freelancing mentorship and interview preparation included.</p></div>
      <div class="feature reveal"><div class="icon">⏱</div><h3>Flexible Timings</h3><p>Morning, evening and weekend batches for students and professionals.</p></div>
      <div class="feature reveal"><div class="icon">🏆</div><h3>Verified Certificate</h3><p>Earn an industry-recognized certificate upon successful completion.</p></div>
      <div class="feature reveal"><div class="icon">💬</div><h3>1:1 Mentorship</h3><p>Personal guidance through WhatsApp groups and live doubt sessions.</p></div>
    </div>
  </div>
</section>

<section class="section" style="background:#fff">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Featured Courses</span>
      <h2>Programs designed for the modern career</h2>
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


  
    <div class="section-head reveal">
      <span class="eyebrow">Student Stories</span>
      <h2>What our graduates say</h2>
    </div>
    <div class="grid cards-3">
      <?php
      $reviews=[
        ['Hafiz Ullah','Web Development','Hands-on projects helped me land my first freelance client in 3 months.','Hafiz.png','Sardheri Charsadda ','+92 3265611593','hu.softdeveloper@gmail.com','FSC Computer Science'],
        ['Maaz Khan','Graphic Designing','From zero to running my own Instagram design page. Practical and modern.','Maaz.jpeg','Saedheri Charsadda','+92 3705171087','maazkhanmalik6@gmail.com','BS Computer Science'],
        ['Daniyal Khan','Digital Marketing','Best decision. I now manage ads for two local brands and earn in dollars.','Daniyal.jpeg','Sardheri Charsadda ','+92 3478763428','danyalkhan034787@gmail.com','MBA Marketing'],
      ];
      foreach($reviews as $r): ?>
      <div class="t-card reveal">
        <img src="images/<?= e($r[3]) ?>" alt="<?= e($r[0]) ?>" class="t-profile-img" />
        <div class="t-stars">✦✦✦✦✦</div>
        <p class="t-quote">“<?= e($r[2]) ?>”</p>
        <div class="t-author">
          <div><strong><?= e($r[0]) ?></strong><small><?= e($r[1]) ?></small></div>
        </div>
        <button class="view-more-btn" onclick="toggleDetails(this)">View More</button>
        <div class="t-details">
          <p><strong>📍 Location:</strong> <?= e($r[4]) ?></p>
          <p><strong>📞 Contact:</strong> <?= e($r[5]) ?></p>
          <p><strong>✉️ Email:</strong> <?= e($r[6]) ?></p>
          <p><strong>🎓 Education:</strong> <?= e($r[7]) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="cta-band reveal">
      <div>
        <h2>Ready to start your journey?</h2>
        <p>Limited seats per batch — secure your admission today.</p>
      </div>
      <a class="btn btn-gold" href="admissions.php">Apply for Admission →</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
