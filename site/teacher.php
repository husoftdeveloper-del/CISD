<?php
$PAGE = 'about';
$PAGE_TITLE = 'Team Member — CISD INSTITUTE';
require_once 'config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ? AND status = 'active'");
$stmt->execute([$id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    include 'includes/header.php';
    echo '<section class="section"><div class="container"><p>Team member not found.</p><a href="about.php" class="btn btn-navy">Back to Team</a></div></section>';
    include 'includes/footer.php';
    exit;
}

$PAGE_TITLE = $teacher['name'] . ' — CISD INSTITUTE';
include 'includes/header.php';
?>

<section class="section">
  <div class="container">
    <div class="card" style="max-width:600px;margin:auto;">
      <img src="<?= e($teacher['image_path']) ?>" alt="<?= e($teacher['name']) ?>" style="width:100%;object-fit:cover;" />
      <div class="card-body">
        <h3><?= e($teacher['name']) ?></h3>
        <p style="color:var(--gold);font-weight:600;margin:0;"><?= e($teacher['role']) ?></p>
        <p><?= e($teacher['specialty']) ?></p>
        <?php if ($teacher['bio']): ?><p><?= e($teacher['bio']) ?></p><?php endif; ?>
        <ul style="list-style:none;padding:0;margin-top:10px;">
          <?php if ($teacher['location']): ?><li><strong>Location:</strong> <?= e($teacher['location']) ?></li><?php endif; ?>
          <?php if ($teacher['contact']): ?><li><strong>Contact:</strong> <?= e($teacher['contact']) ?></li><?php endif; ?>
          <?php if ($teacher['email']): ?><li><strong>Email:</strong> <a href="mailto:<?= e($teacher['email']) ?>"><?= e($teacher['email']) ?></a></li><?php endif; ?>
          <?php if ($teacher['education']): ?><li><strong>Education:</strong> <?= e($teacher['education']) ?></li><?php endif; ?>
        </ul>
        <a href="about.php" class="view-more-btn" style="margin-top:15px;display:inline-block;">Back to Team</a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
