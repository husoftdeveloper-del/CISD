<?php
$adminPage = 'students';
$adminPageTitle = 'Students Showcase';
require_once 'includes/init.php';

$message = $adminMessage ?? '';
$messageType = $adminMessageType ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name'] ?? '');
        $father_name = trim($_POST['father_name'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 0);
        
        // Handle image upload
        $image_path = '';
        $upload_error = false;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/students/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = 'images/students/' . $file_name;
            } else {
                $message = 'Failed to upload image.';
                $messageType = 'error';
                $upload_error = true;
            }
        } elseif ($action === 'edit') {
            // Keep existing image if no new image uploaded
            $image_path = $_POST['existing_image'] ?? '';
        } else {
            // Image is required for adding new student
            $message = 'Please upload a student image.';
            $messageType = 'error';
            $upload_error = true;
        }
        
        if (!$upload_error && ($image_path || $action === 'edit')) {
            try {
                if ($action === 'add') {
                    $stmt = $pdo->prepare("INSERT INTO students (name, father_name, course, description, image_path, display_order) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $father_name, $course, $description, $image_path, $display_order]);
                    $message = 'Student showcase card added successfully!';
                    $messageType = 'success';
                } elseif ($action === 'edit') {
                    $id = intval($_POST['id']);
                    $stmt = $pdo->prepare("UPDATE students SET name = ?, father_name = ?, course = ?, description = ?, image_path = ?, display_order = ? WHERE id = ?");
                    $stmt->execute([$name, $father_name, $course, $description, $image_path, $display_order, $id]);
                    $message = 'Student showcase card updated successfully!';
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
            $stmt = $pdo->prepare("SELECT image_path FROM students WHERE id = ?");
            $stmt->execute([$id]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($student) {
                // Delete file from server
                $file_path = '../' . $student['image_path'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Delete from database
                $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Student showcase card deleted successfully!';
                $messageType = 'success';
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get all students
try {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY display_order ASC, id DESC");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $students = [];
}

// Get student for editing
$edit_student = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_student = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $edit_student = null;
    }
}
require 'includes/header.php';
?>
    <style>
        .student-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        .student-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
        }
        .student-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .student-card .content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .student-card h3 {
            font-size: 18px;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .student-card .father-name {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .student-card .course-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            align-self: flex-start;
        }
        .student-card p {
            color: #475569;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }
        .student-card .actions {
            display: flex;
            gap: 8px;
            border-top: 1px solid #f1f5f9;
            padding-top: 16px;
        }
    </style>
            <div class="header">
                <h1>Students Showcase Management</h1>
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
                    <h2><?= $edit_student ? 'Edit Student Showcase Card' : 'Add New Student Card' ?></h2>
                </div>
                <form method="POST" enctype="multipart/form-data" class="form-container" style="max-width: 100%;">
                    <input type="hidden" name="action" value="<?= $edit_student ? 'edit' : 'add' ?>">
                    <?php if ($edit_student): ?>
                        <input type="hidden" name="id" value="<?= $edit_student['id'] ?>">
                        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_student['image_path']) ?>">
                    <?php endif; ?>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                        <div class="form-group">
                            <label for="name">Student Name *</label>
                            <input type="text" id="name" name="name" value="<?= $edit_student ? htmlspecialchars($edit_student['name']) : '' ?>" required placeholder="e.g. Muhammad Ali">
                        </div>
                        
                        <div class="form-group">
                            <label for="father_name">Father's Name (F/Name) *</label>
                            <input type="text" id="father_name" name="father_name" value="<?= $edit_student ? htmlspecialchars($edit_student['father_name']) : '' ?>" required placeholder="e.g. Arshad Ali">
                        </div>
                        
                        <div class="form-group">
                            <label for="course">Course Name *</label>
                            <input type="text" id="course" name="course" value="<?= $edit_student ? htmlspecialchars($edit_student['course']) : '' ?>" required placeholder="e.g. Web Development">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Short Paragraph / Description * (Maximum 2 lines on showcase)</label>
                        <textarea id="description" name="description" required placeholder="Describe the student's success story or achievement (e.g., Landed web developer job at software house or started successful freelancing career on Upwork and Fiverr)." rows="3"><?= $edit_student ? htmlspecialchars($edit_student['description']) : '' ?></textarea>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; align-items: center;">
                        <div class="form-group">
                            <label for="image">Student Photo * <?= $edit_student ? '(Leave empty to keep current)' : '' ?></label>
                            <input type="file" id="image" name="image" accept="image/*" <?= $edit_student ? '' : 'required' ?>>
                            <?php if ($edit_student): ?>
                                <div style="margin-top: 10px;">
                                    <img src="../<?= htmlspecialchars($edit_student['image_path']) ?>" alt="Current student image" class="image-preview">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" id="display_order" name="display_order" value="<?= $edit_student ? $edit_student['display_order'] : '0' ?>" min="0">
                        </div>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary"><?= $edit_student ? 'Update Card' : 'Add Showcase Card' ?></button>
                        <?php if ($edit_student): ?>
                            <a href="students.php" class="btn btn-warning" style="margin-left: 10px;">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Students Grid -->
            <div class="table-container">
                <div class="table-header">
                    <h2>Active Showcase Cards (<?= count($students) ?>)</h2>
                </div>
                <div class="student-grid">
                    <?php foreach ($students as $student): ?>
                        <div class="student-card">
                            <img src="../<?= htmlspecialchars($student['image_path']) ?>" alt="<?= htmlspecialchars($student['name']) ?>">
                            <div class="content">
                                <span class="course-badge"><?= htmlspecialchars($student['course']) ?></span>
                                <h3><?= htmlspecialchars($student['name']) ?></h3>
                                <div class="father-name">F/Name: <?= htmlspecialchars($student['father_name']) ?></div>
                                <p><?= htmlspecialchars($student['description']) ?></p>
                                <div class="actions">
                                    <a href="students.php?edit=<?= $student['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student showcase card?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($students)): ?>
                        <p style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 40px;">No student showcase cards yet. Add your first student card above.</p>
                    <?php endif; ?>
                </div>
            </div>
<?php require 'includes/footer.php'; ?>
