<?php
require_once '../config.php';

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration = trim($_POST['duration'] ?? '');
        $fees = floatval($_POST['fees'] ?? 0);
        $display_order = intval($_POST['display_order'] ?? 0);
        $status = $_POST['status'] ?? 'active';
        
        // Handle image upload
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/courses/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = 'images/courses/' . $file_name;
            } else {
                $message = 'Failed to upload image.';
                $messageType = 'error';
            }
        } elseif ($action === 'edit') {
            // Keep existing image if no new image uploaded
            $image_path = $_POST['existing_image'] ?? '';
        }
        
        if ($image_path || $action === 'edit') {
            try {
                if ($action === 'add') {
                    $stmt = $pdo->prepare("INSERT INTO courses (title, description, duration, fees, image_path, display_order, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $description, $duration, $fees, $image_path, $display_order, $status]);
                    $message = 'Course added successfully!';
                    $messageType = 'success';
                } elseif ($action === 'edit') {
                    $id = intval($_POST['id']);
                    $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, duration = ?, fees = ?, image_path = ?, display_order = ?, status = ? WHERE id = ?");
                    $stmt->execute([$title, $description, $duration, $fees, $image_path, $display_order, $status, $id]);
                    $message = 'Course updated successfully!';
                    $messageType = 'success';
                }
            } catch (PDOException $e) {
                $message = 'Database error: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        try {
            // Get image path before deleting
            $stmt = $pdo->prepare("SELECT image_path FROM courses WHERE id = ?");
            $stmt->execute([$id]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($course) {
                // Delete file from server
                $file_path = '../' . $course['image_path'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Delete from database
                $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Course deleted successfully!';
                $messageType = 'success';
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get all courses
try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY display_order ASC, id DESC");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $courses = [];
}

// Get course for editing
$edit_course = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_course = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $edit_course = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Management - CISD Institute Admin</title>
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
                <a href="courses.php" class="active">
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
                <h1>Courses Management</h1>
                <div class="header-actions">
                    <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <!-- Add/Edit Form -->
            <div class="table-container" style="margin-bottom: 32px;">
                <div class="table-header">
                    <h2><?= $edit_course ? 'Edit Course' : 'Add New Course' ?></h2>
                </div>
                <form method="POST" enctype="multipart/form-data" class="form-container" style="max-width: 100%;">
                    <input type="hidden" name="action" value="<?= $edit_course ? 'edit' : 'add' ?>">
                    <?php if ($edit_course): ?>
                        <input type="hidden" name="id" value="<?= $edit_course['id'] ?>">
                        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_course['image_path']) ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="title">Course Title *</label>
                        <input type="text" id="title" name="title" value="<?= $edit_course ? htmlspecialchars($edit_course['title']) : '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?= $edit_course ? htmlspecialchars($edit_course['description']) : '' ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="text" id="duration" name="duration" value="<?= $edit_course ? htmlspecialchars($edit_course['duration']) : '' ?>" placeholder="e.g., 3 months, 6 weeks">
                    </div>
                    
                    <div class="form-group">
                        <label for="fees">Fees (PKR)</label>
                        <input type="number" id="fees" name="fees" value="<?= $edit_course ? $edit_course['fees'] : '' ?>" step="0.01" placeholder="e.g., 15000">
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Course Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if ($edit_course): ?>
                            <small>Leave empty to keep existing image</small>
                            <div style="margin-top: 10px;">
                                <img src="../<?= htmlspecialchars($edit_course['image_path']) ?>" alt="Current image" class="image-preview">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" value="<?= $edit_course ? $edit_course['display_order'] : '0' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active" <?= $edit_course && $edit_course['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $edit_course && $edit_course['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?= $edit_course ? 'Update Course' : 'Add Course' ?></button>
                    <?php if ($edit_course): ?>
                        <a href="courses.php" class="btn btn-warning" style="margin-left: 10px;">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Courses Grid -->
            <div class="table-container">
                <div class="table-header">
                    <h2>All Courses (<?= count($courses) ?>)</h2>
                </div>
                <div class="course-grid">
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                            <?php if ($course['image_path']): ?>
                                <img src="../<?= htmlspecialchars($course['image_path']) ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                            <?php else: ?>
                                <div style="width: 100%; height: 180px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b;">No Image</div>
                            <?php endif; ?>
                            <div class="content">
                                <h3><?= htmlspecialchars($course['title']) ?></h3>
                                <p><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</p>
                                <div class="meta">
                                    <span>⏱️ <?= htmlspecialchars($course['duration']) ?></span>
                                    <span>💰 PKR <?= number_format($course['fees']) ?></span>
                                </div>
                                <span class="badge badge-<?= $course['status'] === 'active' ? 'active' : 'inactive' ?>"><?= ucfirst($course['status']) ?></span>
                                <div class="actions" style="margin-top: 16px;">
                                    <a href="courses.php?edit=<?= $course['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $course['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($courses)): ?>
                        <p style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 40px;">No courses yet. Add your first course above.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">☰</button>
</body>
</html>
