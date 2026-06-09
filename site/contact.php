<?php
$PAGE='contact';
$PAGE_TITLE='Contact — CISD INSTITUTE';
$PAGE_DESC='Get in touch with CISD INSTITUTE.';
require_once 'config.php';

$status=''; $msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name    = trim($_POST['name'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $phone   = trim($_POST['phone'] ?? '');
  $education = trim($_POST['education'] ?? '');
  $course   = trim($_POST['course'] ?? '');
  $address  = trim($_POST['address'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if (!$name || !$email || !$message) {
    $status='error'; $msg='Please fill all required fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $status='error'; $msg='Please enter a valid email address.';
  } else {
    // Prepare insertion into admissions table with new fields
        $stmt = $conn->prepare("INSERT INTO admissions (full_name, email, phone, education, course, message) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('ssssss', $name, $email, $phone, $education, $course, $message);
    if ($stmt->execute()) { $status='success'; $msg='Application submitted! We will contact you soon.'; $_POST=[]; }
    else { $status='error'; $msg='Could not submit application. Please try again.'; }
    $stmt->close();
  }
}
include 'includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <span class="eyebrow">Contact Us</span>
    <h1>We'd love to hear from you</h1>
    <p>Questions about courses, fees or admissions — drop us a message.</p>
  </div>
</section>

<section class="section">
  <div class="container contact-grid">
    <div class="form-card reveal">
      <?php if($status==='success'): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
      <?php if($status==='error'):   ?><div class="alert alert-error"><?= e($msg) ?></div><?php endif; ?>
      <form method="POST" class="form-grid">
        <div class="field"><label>Name *</label><input name="name" required value="<?= e($_POST['name'] ?? '') ?>" /></div>
        <div class="field"><label>Email *</label><input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" /></div>
        <div class="field"><label>Phone</label><input name="phone" value="<?= e($_POST['phone'] ?? '') ?>" /></div>
        <div class="field"><label>Subject</label><input name="subject" value="<?= e($_POST['subject'] ?? '') ?>" /></div>
        <div class="field full"><label>Message *</label><textarea name="message" required><?= e($_POST['message'] ?? '') ?></textarea></div>
        <div class="field full"><button type="submit" class="btn btn-gold">Send Message →</button></div>
      </form>
    </div>
    <div class="reveal">
      <div class="info-card"><h4>📞 Phone</h4><p><?= e($INSTITUTE['phone']) ?></p></div>
      <div class="info-card"><h4>✉️ Email</h4><p><?= e($INSTITUTE['email']) ?></p></div>
      <div class="info-card"><h4>📍 Address</h4><p><?= e($INSTITUTE['address']) ?></p></div>
      <div class="map-frame"><iframe src="https://www.google.com/maps?q=<?= e($INSTITUTE['address']) ?>&output=embed" loading="lazy"></iframe></div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
