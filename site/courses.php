<?php 
$PAGE='courses'; 
$PAGE_TITLE='Courses — CISD INSTITUTE'; 
$PAGE_DESC='Browse our complete catalog of IT and digital skills courses.'; 
include 'includes/header.php';

// Fetch courses from database
$courses = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM courses WHERE status = 'active' ORDER BY display_order ASC, id DESC");
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Fallback to hardcoded data if database fails
    }
}

// If no courses from database, use fallback
if (empty($courses)) {
    include 'data/courses.php';
    $courses = $COURSES;
}
?>

<section class="page-header">
  <div class="container">
    <span class="eyebrow">Our Courses</span>
    <h1>Choose your path. Build your future.</h1>
    <p>10+ practical, project-based programs designed around what the market actually pays for.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="grid cards-3">
      <?php foreach($courses as $c): ?>
      <div class="card reveal">
        <img src="<?= !empty($c['image_path']) ? e($c['image_path']) : 'images/' . e($c['image'] ?? 'course-placeholder.jpg') ?>" alt="<?= e($c['title']) ?>" loading="lazy" />
        <div class="card-body">
          <span class="badge"><?= e($c['duration'] ?? 'Flexible') ?></span>
          <h3><?= e($c['title']) ?></h3>
          <p><?= e(substr($c['description'] ?? $c['desc'] ?? '', 0, 120)) ?></p>
          <div class="card-meta">
            <span>Fee: PKR <?= number_format($c['fees'] ?? $c['fee'] ?? 0) ?></span>
            <a href="admissions.php?course=<?= urlencode($c['title']) ?>">Apply →</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
