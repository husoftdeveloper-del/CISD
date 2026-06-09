<?php
require_once '../config.php';

// Get statistics
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

// Get recent applications count
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admissions WHERE status = 'pending'");
    $pending_apps = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    $pending_apps = 0;
}

// Get total courses
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM courses WHERE status = 'active'");
    $total_courses = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    $total_courses = 0;
}

// Get total gallery images
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM gallery");
    $total_gallery = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    $total_gallery = 0;
}

// Handle Add New Story submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_story'])) {
    $name = $_POST['name'] ?? '';
    $father_name = $_POST['father_name'] ?? '';
    $course = $_POST['course'] ?? '';
    $quote = $_POST['quote'] ?? '';
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/stories/';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
        $tmpName = $_FILES['image']['tmp_name'];
        $originalName = basename($_FILES['image']['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $newName = uniqid('story_') . '.' . $ext;
        $destPath = $uploadDir . $newName;
        if (move_uploaded_file($tmpName, $destPath)) { $imagePath = $newName; }
    }
    $stmt = $pdo->prepare("INSERT INTO success_stories (image, name, father_name, course, quote) VALUES (:image, :name, :father_name, :course, :quote)");
    $stmt->execute([':image' => $imagePath, ':name' => $name, ':father_name' => $father_name, ':course' => $course, ':quote' => $quote]);
    $pdo->exec("UPDATE statistics SET stat_value = stat_value + 1 WHERE stat_key = 'success_stories'");
    header('Location: dashboard.php');
    exit();
}

// Handle Delete Story
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT image FROM success_stories WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['image']) { $file = __DIR__ . '/../uploads/stories/' . $row['image']; if (file_exists($file)) unlink($file); }
    $pdo->prepare("DELETE FROM success_stories WHERE id = :id")->execute([':id' => $id]);
    $pdo->exec("UPDATE statistics SET stat_value = GREATEST(stat_value - 1, 0) WHERE stat_key = 'success_stories'");
    header('Location: dashboard.php');
    exit();
}

// Handle Edit Story
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_story'])) {
    $id = (int)$_POST['story_id'];
    $name = $_POST['name'] ?? '';
    $father_name = $_POST['father_name'] ?? '';
    $course = $_POST['course'] ?? '';
    $quote = $_POST['quote'] ?? '';
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/stories/';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
        $tmpName = $_FILES['image']['tmp_name'];
        $originalName = basename($_FILES['image']['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $newName = uniqid('story_') . '.' . $ext;
        $destPath = $uploadDir . $newName;
        if (move_uploaded_file($tmpName, $destPath)) {
            $imagePath = $newName;
            $stmtOld = $pdo->prepare("SELECT image FROM success_stories WHERE id = :id");
            $stmtOld->execute([':id' => $id]);
            $old = $stmtOld->fetch(PDO::FETCH_ASSOC);
            if ($old && $old['image']) { $oldFile = __DIR__ . '/../uploads/stories/' . $old['image']; if (file_exists($oldFile)) unlink($oldFile); }
        }
    }
    $sql = "UPDATE success_stories SET name = :name, father_name = :father_name, course = :course, quote = :quote" . ($imagePath !== null ? ", image = :image" : "") . " WHERE id = :id";
    $params = [':name' => $name, ':father_name' => $father_name, ':course' => $course, ':quote' => $quote, ':id' => $id];
    if ($imagePath !== null) $params[':image'] = $imagePath;
    $pdo->prepare($sql)->execute($params);
    header('Location: dashboard.php');
    exit();
}

// Fetch edit data
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM success_stories WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editStory = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all stories for display
$stories = $pdo->query("SELECT * FROM success_stories ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CISD Institute Admin</title>
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
                <a href="dashboard.php" class="active">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="applications.php">
                    <span>📝</span>
                    <span>Applications</span>
                    <?php if ($pending_apps > 0): ?>
                        <span style="background: #dc2626; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 12px; margin-left: auto;"><?= $pending_apps ?></span>
                    <?php endif; ?>
                </a>
                <a href="courses.php">
                    <span>📚</span>
                    <span>Courses</span>
                </a>
                <a href="gallery.php">
                    <span>🖼️</span>
                    <span>Gallery</span>
                </a>
                <a href="statistics.php">
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
                <h1>Dashboard Overview</h1>
                <div class="header-actions">
                    <a href="../index.php" target="_blank" class="btn btn-primary">View Website</a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="dashboard-cards">
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

            <!-- Quick Stats -->
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Pending Applications</h3>
                    <div class="number"><?= $pending_apps ?></div>
                </div>
                <div class="card">
                    <h3>Total Courses</h3>
                    <div class="number"><?= $total_courses ?></div>
                </div>
                <div class="card">
                    <h3>Gallery Images</h3>
                    <div class="number"><?= $total_gallery ?></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="table-container">
                <div class="table-header">
                    <h2>Quick Actions</h2>
                </div>
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <a href="applications.php" class="btn btn-primary">View Applications</a>
                    <a href="courses.php" class="btn btn-success">Add Course</a>
                    <a href="gallery.php" class="btn btn-warning">Add Image</a>
                    <a href="statistics.php" class="btn btn-primary">Update Statistics</a>
                    <a href="students.php" class="btn btn-success">Manage Showcase</a>
                </div>
            </div>
        
        <section class="success-stories">
            <h2>🎓 Success Stories</h2>
            <form method="POST" enctype="multipart/form-data">
                <?php if (isset($editStory)): ?>
                    <input type="hidden" name="edit_story" value="1">
                    <input type="hidden" name="story_id" value="<?= $editStory['id'] ?>">
                <?php else: ?>
                    <input type="hidden" name="add_story" value="1">
                <?php endif; ?>
                <div><label>Student Image: <input type="file" name="image" accept="image/*" <?= isset($editStory) ? '' : 'required' ?>></label></div>
                <div><label>Student Name: <input type="text" name="name" required value="<?= $editStory['name'] ?? '' ?>"></label></div>
                <div><label>Father Name: <input type="text" name="father_name" required value="<?= $editStory['father_name'] ?? '' ?>"></label></div>
                <div><label>Course Name: <input type="text" name="course" required value="<?= $editStory['course'] ?? '' ?>"></label></div>
                <div><label>Story (2 lines): <textarea name="quote" rows="2" required><?= $editStory['quote'] ?? '' ?></textarea></label></div>
                
                <button type="submit" name="add_story" class="btn btn-primary"><?= isset($editStory) ? 'Update Story' : '+ Add New Story' ?></button>
                <button type="submit" name="add_another" class="btn btn-secondary" style="margin-left:10px;">Add Another Story</button>
            </form>
            <h3>All Stories</h3>
            <table class="stories-table" style="width:100%; border-collapse:collapse;">
                <thead><tr><th>Image</th><th>Name</th><th>Father</th><th>Course</th><th>Description</th><th>Date</th><th>Edit</th><th>Delete</th></tr></thead>
                <tbody>
                <?php foreach ($stories as $story): ?>
                    <tr>
                        <td><img src="../uploads/stories/<?= e($story['image']) ?>" alt="Student" style="height:60px;"></td>
                        <td><?= e($story['name']) ?></td>
                        <td><?= e($story['father_name']) ?></td>
                        <td><?= e($story['course']) ?></td>
                        <td><?= e($story['quote']) ?></td>
                        <td><?= e($story['created_at']) ?></td>
                        <td><a href="dashboard.php?edit=<?= $story['id'] ?>" class="btn btn-sm btn-success">Edit</a></td>
                        <td><a href="dashboard.php?delete=<?= $story['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this story?');">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        </main>
    </div>

    <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">☰</button>

    <script>
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.menu-toggle');
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
