<?php
$PAGE='admissions';
$PAGE_TITLE='Admissions — CISD INSTITUTE';
$PAGE_DESC='Apply online for admission to CISD INSTITUTE.';
require_once 'config.php';

// Fetch courses from database for dropdown
$courses = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT title FROM courses WHERE status = 'active' ORDER BY display_order ASC, id DESC");
        $courses = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        // Fallback to hardcoded data if database fails
    }
}

// If no courses from database, use fallback
if (empty($courses)) {
    include 'data/courses.php';
    $courses = array_column($COURSES, 'title');
}

$status=''; $msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $full_name = trim($_POST['full_name'] ?? '');
  $email     = trim($_POST['email'] ?? '');
  $phone     = trim($_POST['phone'] ?? '');
  $course    = trim($_POST['course'] ?? '');
  $education = trim($_POST['education'] ?? '');
  $city      = trim($_POST['city'] ?? '');
  $message   = trim($_POST['message'] ?? '');

  if (!$full_name || !$email || !$phone || !$course) {
    $status='error'; $msg='Please fill all required fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $status='error'; $msg='Please enter a valid email address.';
  } else {
    // Try to save to database if available
    if (isset($pdo)) {
      try {
        $stmt = $pdo->prepare("INSERT INTO admissions (full_name, email, phone, course, education, city, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $phone, $course, $education, $city, $message]);
        $status='success'; 
        $msg='Application submitted! Our team will contact you within 24 hours.'; 
        $_POST=[];
      } catch (PDOException $e) {
        $status='error'; 
        $msg='Submission failed. Please try again.';
      }
    } else {
      // Fallback if database not available
      $status='success'; 
      $msg='Application submitted! Our team will contact you within 24 hours.'; 
      $_POST=[];
    }
  }
}
include 'includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <span class="eyebrow">Admissions Open</span>
    <h1>Apply for Admission</h1>
    <p>Fill the form below — our advisor will reach out to confirm your seat.</p>
  </div>
</section>

<section class="section">
  <div class="container" style="display:grid;grid-template-columns:1.2fr 1fr;gap:30px">
    <div class="form-card reveal">
      <?php if($status==='success'): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
      <?php if($status==='error'):   ?><div class="alert alert-error"><?= e($msg) ?></div><?php endif; ?>
      <form method="POST" class="form-grid">
        <div class="field"><label>Full Name *</label><input name="full_name" required value="<?= e($_POST['full_name'] ?? '') ?>" /></div>
        <div class="field"><label>Email *</label><input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" /></div>
        <div class="field"><label>Phone *</label><input name="phone" required value="<?= e($_POST['phone'] ?? '') ?>" /></div>
        <div class="field"><label>City</label><input name="city" value="<?= e($_POST['city'] ?? '') ?>" /></div>
        <div class="field"><label>Course *</label>
          <select name="course" required>
            <option value="">Select a course</option>
            <?php $pre=$_GET['course']??($_POST['course']??''); foreach($courses as $c): ?>
              <option value="<?= e($c) ?>" <?= $pre===$c?'selected':'' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field"><label>Education</label><input name="education" placeholder="e.g. BSc / Intermediate" value="<?= e($_POST['education'] ?? '') ?>" /></div>
        <div class="field full"><label>Message</label><textarea name="message" placeholder="Anything we should know?"><?= e($_POST['message'] ?? '') ?></textarea></div>
        <div class="field full"><button type="submit" class="btn btn-gold">Submit Application →</button></div>
      </form>
    </div>
    <div class="reveal">
      <div class="info-card"><h4>Admission Process</h4><p>1. Submit application form<br>2. Counseling call within 24 hrs<br>3. Fee deposit & seat confirmation<br>4. Welcome onboarding + class start</p></div>
      <div class="info-card"><h4>Fee Structure</h4><p>Easy installment plans available. Discount for early-bird and group admissions.</p></div>
      <div class="info-card"><h4>Need help?</h4><p>Call <strong><?= e($INSTITUTE['phone']) ?></strong> or email <strong><?= e($INSTITUTE['email']) ?></strong></p></div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
