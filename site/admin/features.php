<?php
$adminPage = 'features';
$adminPageTitle = 'Features & Values';
require_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $section = $_POST['section'] === 'about_values' ? 'about_values' : 'home';
        $icon = trim($_POST['icon'] ?? '★');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);

        if ($title && $description) {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO features (section, icon, title, description, display_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$section, $icon, $title, $description, $display_order]);
                $adminMessage = 'Feature added.';
            } else {
                $id = (int) $_POST['id'];
                $stmt = $pdo->prepare("UPDATE features SET section=?, icon=?, title=?, description=?, display_order=? WHERE id=?");
                $stmt->execute([$section, $icon, $title, $description, $display_order, $id]);
                $adminMessage = 'Feature updated.';
            }
            $adminMessageType = 'success';
        }
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM features WHERE id = ?");
        $stmt->execute([(int) $_POST['id']]);
        $adminMessage = 'Feature deleted.';
        $adminMessageType = 'success';
    }
}

$editFeature = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM features WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editFeature = $stmt->fetch(PDO::FETCH_ASSOC);
}

$features = $pdo->query("SELECT * FROM features ORDER BY section ASC, display_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);

require 'includes/header.php';
?>

<div class="header">
    <h1>Features & Values</h1>
    <p style="color:#64748b;margin-top:4px">Manage homepage "Why Choose Us" cards and About page values.</p>
</div>

<?php if ($adminMessage): ?>
    <div class="alert alert-<?= e($adminMessageType) ?>"><?= e($adminMessage) ?></div>
<?php endif; ?>

<div class="table-container" style="margin-bottom:32px">
    <div class="table-header"><h2><?= $editFeature ? 'Edit Item' : 'Add Item' ?></h2></div>
    <form method="POST" class="form-container">
        <input type="hidden" name="action" value="<?= $editFeature ? 'edit' : 'add' ?>">
        <?php if ($editFeature): ?><input type="hidden" name="id" value="<?= $editFeature['id'] ?>"><?php endif; ?>
        <div class="settings-grid">
            <div class="form-group">
                <label>Section</label>
                <select name="section">
                    <option value="home" <?= ($editFeature['section'] ?? '') === 'home' ? 'selected' : '' ?>>Homepage Features</option>
                    <option value="about_values" <?= ($editFeature['section'] ?? '') === 'about_values' ? 'selected' : '' ?>>About Values</option>
                </select>
            </div>
            <div class="form-group"><label>Icon (emoji or letter)</label><input name="icon" value="<?= e($editFeature['icon'] ?? '★') ?>" maxlength="20"></div>
            <div class="form-group"><label>Title</label><input name="title" value="<?= e($editFeature['title'] ?? '') ?>" required></div>
            <div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= e($editFeature['display_order'] ?? '0') ?>"></div>
            <div class="form-group full"><label>Description</label><textarea name="description" required><?= e($editFeature['description'] ?? '') ?></textarea></div>
        </div>
        <button type="submit" class="btn btn-primary"><?= $editFeature ? 'Update' : 'Add' ?></button>
        <?php if ($editFeature): ?><a href="features.php" class="btn btn-warning" style="margin-left:10px">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="table-container">
    <div class="table-header"><h2>All Items (<?= count($features) ?>)</h2></div>
    <table class="data-table">
        <thead><tr><th>Section</th><th>Icon</th><th>Title</th><th>Description</th><th>Order</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($features as $f): ?>
            <tr>
                <td><?= $f['section'] === 'home' ? 'Homepage' : 'About Values' ?></td>
                <td><?= e($f['icon']) ?></td>
                <td><?= e($f['title']) ?></td>
                <td><?= e(substr($f['description'], 0, 80)) ?>...</td>
                <td><?= (int) $f['display_order'] ?></td>
                <td>
                    <a href="features.php?edit=<?= $f['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <form method="POST" style="display:inline" onsubmit="return confirm('Delete this item?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $f['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/footer.php'; ?>
