<?php
$adminPage = 'dashboard';
$adminPageTitle = 'Dashboard';
require_once 'includes/init.php';

try {
    $stmt = $pdo->query("SELECT stat_key, stat_value FROM statistics");
    $stats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stats[$row['stat_key']] = $row['stat_value'];
    }
} catch (PDOException $e) {
    $stats = [];
}

$total_courses = (int) $pdo->query("SELECT COUNT(*) FROM courses WHERE status = 'active'")->fetchColumn();
$total_gallery = (int) $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
$total_stories = (int) $pdo->query("SELECT COUNT(*) FROM success_stories")->fetchColumn();
$total_team = (int) $pdo->query("SELECT COUNT(*) FROM team_members WHERE status = 'active'")->fetchColumn();

require 'includes/header.php';
?>

<div class="header">
    <h1>Dashboard Overview</h1>
    <div class="header-actions">
        <a href="../index.php" target="_blank" class="btn btn-primary">View Website</a>
    </div>
</div>

<div class="dashboard-cards">
    <div class="card"><h3>Students Trained</h3><div class="number"><?= $stats['students_trained'] ?? 0 ?></div></div>
    <div class="card"><h3>Modern Courses</h3><div class="number"><?= $stats['modern_courses'] ?? 0 ?></div></div>
    <div class="card"><h3>Years Experience</h3><div class="number"><?= $stats['years_experience'] ?? 0 ?></div></div>
    <div class="card"><h3>Success Stories</h3><div class="number"><?= $stats['success_stories'] ?? 0 ?></div></div>
</div>

<div class="dashboard-cards">
    <div class="card"><h3>Pending Applications</h3><div class="number"><?= $pending_apps ?></div></div>
    <div class="card"><h3>Active Courses</h3><div class="number"><?= $total_courses ?></div></div>
    <div class="card"><h3>Gallery Images</h3><div class="number"><?= $total_gallery ?></div></div>
    <div class="card"><h3>Team Members</h3><div class="number"><?= $total_team ?></div></div>
    <div class="card"><h3>Testimonials</h3><div class="number"><?= $total_stories ?></div></div>
    <div class="card"><h3>Unread Messages</h3><div class="number"><?= $unread_messages ?></div></div>
</div>

<div class="table-container">
    <div class="table-header"><h2>Quick Actions</h2></div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;padding:20px">
        <a href="settings.php" class="btn btn-primary">Site Settings</a>
        <a href="courses.php" class="btn btn-success">Manage Courses</a>
        <a href="stories.php" class="btn btn-success">Success Stories</a>
        <a href="team.php" class="btn btn-primary">Manage Team</a>
        <a href="gallery.php" class="btn btn-warning">Gallery</a>
        <a href="applications.php" class="btn btn-primary">Applications</a>
        <a href="messages.php" class="btn btn-warning">Messages</a>
        <a href="statistics.php" class="btn btn-primary">Statistics</a>
        <a href="../smart_portal/dashboard.php" class="btn btn-success">Institute Portal →</a>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
