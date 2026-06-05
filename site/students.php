<?php
$PAGE='students';
include 'includes/header.php';
// Fetch students from DB
$students = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM students ORDER BY display_order ASC, id DESC");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // handle error silently
    }
}
?>
<section class="section" style="background:#fff">
  <div class="container">
    <div class="section-head reveal">
      <h2>Our Graduates</h2>
      <p>Explore the talented students we have trained.</p>
    </div>
    <div class="student-grid">
      <?php foreach ($students as $student): ?>
        <div class="student-card reveal">
          <div class="student-card-img-wrapper">
            <img src="<?php echo e($student['image_path']); ?>" alt="<?php echo e($student['name']); ?>" class="student-card-img" loading="lazy" />
          </div>
          <div class="student-card-body">
            <span class="student-badge"><?php echo e($student['course']); ?></span>
            <h3 class="student-name"><?php echo e($student['name']); ?></h3>
            <div class="student-father">F/Name: <?php echo e($student['father_name']); ?></div>
            <p class="student-desc"><?php echo e($student['description']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($students)): ?>
        <p style="grid-column:1/-1;text-align:center;color:var(--muted);padding:40px 0;">No students to display.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
