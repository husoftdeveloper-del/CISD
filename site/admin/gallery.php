<?php
$adminPage = 'gallery';
$adminPageTitle = 'Gallery Management';
require_once 'includes/init.php';

$message = $adminMessage ?? '';
$messageType = $adminMessageType ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 0);
        
        // Handle image upload
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/gallery/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = 'images/gallery/' . $file_name;
            } else {
                $message = 'Failed to upload image.';
                $messageType = 'error';
            }
        } elseif ($action === 'edit') {
            // Keep existing image if no new image uploaded
            $image_path = $_POST['existing_image'] ?? '';
        }
        
        if ($image_path) {
            try {
                if ($action === 'add') {
                    $stmt = $pdo->prepare("INSERT INTO gallery (image_path, title, description, display_order) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$image_path, $title, $description, $display_order]);
                    $message = 'Image added successfully!';
                    $messageType = 'success';
                } elseif ($action === 'edit') {
                    $id = intval($_POST['id']);
                    $stmt = $pdo->prepare("UPDATE gallery SET image_path = ?, title = ?, description = ?, display_order = ? WHERE id = ?");
                    $stmt->execute([$image_path, $title, $description, $display_order, $id]);
                    $message = 'Image updated successfully!';
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
            $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            $image = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($image) {
                // Delete file from server
                $file_path = '../' . $image['image_path'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Delete from database
                $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Image deleted successfully!';
                $messageType = 'success';
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get all gallery images
try {
    $stmt = $pdo->query("SELECT * FROM gallery ORDER BY display_order ASC, id DESC");
    $gallery_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $gallery_items = [];
}

// Get item for editing
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_item = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $edit_item = null;
    }
}
require 'includes/header.php';
?>
            <div class="header">
                <h1>Gallery Management</h1>
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
                    <h2><?= $edit_item ? 'Edit Image' : 'Add New Image' ?></h2>
                </div>
                <form method="POST" enctype="multipart/form-data" class="form-container" style="max-width: 100%;">
                    <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'add' ?>">
                    <?php if ($edit_item): ?>
                        <input type="hidden" name="id" value="<?= $edit_item['id'] ?>">
                        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_item['image_path']) ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" accept="image/*" <?= $edit_item ? '' : 'required' ?>>
                        <?php if ($edit_item): ?>
                            <small>Leave empty to keep existing image</small>
                            <div style="margin-top: 10px;">
                                <img src="../<?= htmlspecialchars($edit_item['image_path']) ?>" alt="Current image" class="image-preview">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" value="<?= $edit_item ? htmlspecialchars($edit_item['title']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?= $edit_item ? htmlspecialchars($edit_item['description']) : '' ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" value="<?= $edit_item ? $edit_item['display_order'] : '0' ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?= $edit_item ? 'Update Image' : 'Add Image' ?></button>
                    <?php if ($edit_item): ?>
                        <a href="gallery.php" class="btn btn-warning" style="margin-left: 10px;">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Gallery Grid -->
            <div class="table-container">
                <div class="table-header">
                    <h2>Gallery Images (<?= count($gallery_items) ?>)</h2>
                </div>
                <div class="gallery-grid">
                    <?php foreach ($gallery_items as $item): ?>
                        <div class="gallery-item">
                            <img src="../<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            <div class="actions">
                                <a href="gallery.php?edit=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($gallery_items)): ?>
                        <p style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 40px;">No images in gallery yet. Add your first image above.</p>
                    <?php endif; ?>
                </div>
            </div>
<?php require 'includes/footer.php'; ?>
