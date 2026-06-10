<?php if (!isset($adminPageTitle)) $adminPageTitle = 'Admin'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminPageTitle) ?> — CISD Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>CISD Admin</h2>
            <p>Site Management Panel</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="<?= $adminPage === 'dashboard' ? 'active' : '' ?>">
                <span>📊</span><span>Dashboard</span>
            </a>
            <a href="settings.php" class="<?= $adminPage === 'settings' ? 'active' : '' ?>">
                <span>⚙️</span><span>Site Settings</span>
            </a>
            <a href="features.php" class="<?= $adminPage === 'features' ? 'active' : '' ?>">
                <span>✨</span><span>Features & Values</span>
            </a>
            <a href="team.php" class="<?= $adminPage === 'team' ? 'active' : '' ?>">
                <span>👥</span><span>Team</span>
            </a>
            <a href="stories.php" class="<?= $adminPage === 'stories' ? 'active' : '' ?>">
                <span>💬</span><span>Success Stories</span>
            </a>
            <a href="courses.php" class="<?= $adminPage === 'courses' ? 'active' : '' ?>">
                <span>📚</span><span>Courses</span>
            </a>
            <a href="gallery.php" class="<?= $adminPage === 'gallery' ? 'active' : '' ?>">
                <span>🖼️</span><span>Gallery</span>
            </a>
            <a href="students.php" class="<?= $adminPage === 'students' ? 'active' : '' ?>">
                <span>🎓</span><span>Students Showcase</span>
            </a>
            <a href="statistics.php" class="<?= $adminPage === 'statistics' ? 'active' : '' ?>">
                <span>📈</span><span>Statistics</span>
            </a>
            <a href="applications.php" class="<?= $adminPage === 'applications' ? 'active' : '' ?>">
                <span>📝</span><span>Applications</span>
                <?php if ($pending_apps > 0): ?>
                    <span class="nav-badge"><?= $pending_apps ?></span>
                <?php endif; ?>
            </a>
            <a href="messages.php" class="<?= $adminPage === 'messages' ? 'active' : '' ?>">
                <span>✉️</span><span>Messages</span>
                <?php if ($unread_messages > 0): ?>
                    <span class="nav-badge"><?= $unread_messages ?></span>
                <?php endif; ?>
            </a>
        </nav>
        <div class="sidebar-footer">
            <p class="admin-user">Logged in as <?= e($_SESSION['admin_username'] ?? 'Admin') ?></p>
            <form method="POST" action="logout.php">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </aside>
    <main class="main-content">
