<?php
require_once '../config.php';

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $students_trained = intval($_POST['students_trained'] ?? 0);
    $modern_courses = intval($_POST['modern_courses'] ?? 0);
    $years_experience = intval($_POST['years_experience'] ?? 0);
    $success_stories = intval($_POST['success_stories'] ?? 0);
    
    try {
        $stmt = $pdo->prepare("UPDATE statistics SET stat_value = ? WHERE stat_key = 'students_trained'");
        $stmt->execute([$students_trained]);
        
        $stmt = $pdo->prepare("UPDATE statistics SET stat_value = ? WHERE stat_key = 'modern_courses'");
        $stmt->execute([$modern_courses]);
        
        $stmt = $pdo->prepare("UPDATE statistics SET stat_value = ? WHERE stat_key = 'years_experience'");
        $stmt->execute([$years_experience]);
        
        $stmt = $pdo->prepare("UPDATE statistics SET stat_value = ? WHERE stat_key = 'success_stories'");
        $stmt->execute([$success_stories]);
        
        $message = 'Statistics updated successfully!';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = 'Database error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Get current statistics
try {
    $stmt = $pdo->query("SELECT stat_key, stat_value FROM statistics");
    $stats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stats[$row['stat_key']] = $row['stat_value'];
    }
} catch (PDOException $e) {
    $stats = [
        'students_trained' => 0,
        'modern_courses' => 0,
        'years_experience' => 0,
        'success_stories' => 0
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Management - CISD Institute Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>CISD Admin</h2>
                <p>Dashboard Control Panel</p>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="applications.php">
                    <span>📝</span>
                    <span>Applications</span>
                </a>
                <a href="courses.php">
                    <span>📚</span>
                    <span>Courses</span>
                </a>
                <a href="gallery.php">
                    <span>🖼️</span>
                    <span>Gallery</span>
                </a>
                <a href="statistics.php" class="active">
                    <span>📈</span>
                    <span>Statistics</span>
                </a>
                <a href="students.php">
                    <span>🎓</span>
                    <span>Students Showcase</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="logout.php">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1>Statistics Management</h1>
                <div class="header-actions">
                    <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <!-- Statistics Form -->
            <div class="table-container">
                <div class="table-header">
                    <h2>Update Website Statistics</h2>
                </div>
                <form method="POST" class="form-container" style="max-width: 600px;">
                    <div class="form-group">
                        <label for="students_trained">Students Trained</label>
                        <input type="number" id="students_trained" name="students_trained" value="<?= $stats['students_trained'] ?? 0 ?>" required min="0">
                        <small>This number will appear on the homepage statistics section.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="modern_courses">Modern Courses</label>
                        <input type="number" id="modern_courses" name="modern_courses" value="<?= $stats['modern_courses'] ?? 0 ?>" required min="0">
                        <small>Total number of courses offered by the institute.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="years_experience">Years of Experience</label>
                        <input type="number" id="years_experience" name="years_experience" value="<?= $stats['years_experience'] ?? 0 ?>" required min="0">
                        <small>Years of experience in the education sector.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="success_stories">Success Stories</label>
                        <input type="number" id="success_stories" name="success_stories" value="<?= $stats['success_stories'] ?? 0 ?>" required min="0">
                        <small>Number of successful graduates/placements.</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Statistics</button>
                </form>
            </div>

            <!-- Current Statistics Preview -->
            <div class="dashboard-cards" style="margin-top: 32px;">
                <div class="card">
                    <h3>Students Trained</h3>
                    <div class="number"><?= $stats['students_trained'] ?? 0 ?></div>
                </div>
                <div class="card">
                    <h3>Modern Courses</h3>
                    <div class="number"><?= $stats['modern_courses'] ?? 0 ?></div>
                </div>
                <div class="card">
                    <h3>Years Experience</h3>
                    <div class="number"><?= $stats['years_experience'] ?? 0 ?></div>
                </div>
                <div class="card">
                    <h3>Success Stories</h3>
                    <div class="number"><?= $stats['success_stories'] ?? 0 ?></div>
                </div>
            </div>
        </main>
    </div>

    <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">☰</button>
</body>
</html>
