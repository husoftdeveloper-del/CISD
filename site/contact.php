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
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if (!$name || !$email || !$message) {
    $status='error'; $msg='Please fill all required fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $status='error'; $msg='Please enter a valid email address.';
  } else {
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?,?,?,?,?)");
    if ($stmt->execute([$name, $email, $phone, $subject, $message])) {
      $status='success'; $msg='Message sent! We will get back to you soon.'; $_POST=[];
    } else {
      $status='error'; $msg='Could not send message. Please try again.';
    }
  }
}
include 'includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <span class="eyebrow"><?= e(site_setting('contact_eyebrow', 'Contact Us')) ?></span>
    <h1><?= e(site_setting('contact_title')) ?></h1>
    <p><?= e(site_setting('contact_subtitle')) ?></p>
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
      <div class="map-frame"><iframe src="<?= e($INSTITUTE['maps']) ?>" loading="lazy"></iframe></div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
