<?php
$adminPage = 'team';
$adminPageTitle = 'Team Management';
require_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $specialty = trim($_POST['specialty'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $education = trim($_POST['education'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);
        $status = $_POST['status'] === 'inactive' ? 'inactive' : 'active';
        $image_path = $_POST['existing_image'] ?? '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/team/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name)) {
                $image_path = 'images/team/' . $file_name;
            }
        }

        if ($name && $role && $image_path) {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO team_members (image_path, name, role, specialty, location, contact, email, education, bio, display_order, status) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->execute([$image_path, $name, $role, $specialty, $location, $contact, $email, $education, $bio, $display_order, $status]);
                $adminMessage = 'Team member added.';
            } else {
                $id = (int) $_POST['id'];
                $stmt = $pdo->prepare("UPDATE team_members SET image_path=?, name=?, role=?, specialty=?, location=?, contact=?, email=?, education=?, bio=?, display_order=?, status=? WHERE id=?");
                $stmt->execute([$image_path, $name, $role, $specialty, $location, $contact, $email, $education, $bio, $display_order, $status, $id]);
                $adminMessage = 'Team member updated.';
            }
            $adminMessageType = 'success';
        } else {
            $adminMessage = 'Name, role, and image are required.';
            $adminMessageType = 'error';
        }
    } elseif ($action === 'delete') {
        $id = (int) $_POST['id'];
        $stmt = $pdo->prepare("SELECT image_path FROM team_members WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($member && strpos($member['image_path'], 'images/team/') === 0 && file_exists('../' . $member['image_path'])) {
            unlink('../' . $member['image_path']);
        }
        $pdo->prepare("DELETE FROM team_members WHERE id = ?")->execute([$id]);
        $adminMessage = 'Team member deleted.';
        $adminMessageType = 'success';
    }
}

$editMember = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editMember = $stmt->fetch(PDO::FETCH_ASSOC);
}

$team = cisd_get_team($pdo, false);
require 'includes/header.php';
?>

<div class="header"><h1>Team Management</h1></div>

<?php if ($adminMessage): ?>
    <div class="alert alert-<?= e($adminMessageType) ?>"><?= e($adminMessage) ?></div>
<?php endif; ?>

<div class="table-container" style="margin-bottom:32px">
    <div class="table-header"><h2><?= $editMember ? 'Edit Member' : 'Add Team Member' ?></h2></div>
    <form method="POST" enctype="multipart/form-data" class="form-container">
        <input type="hidden" name="action" value="<?= $editMember ? 'edit' : 'add' ?>">
        <?php if ($editMember): ?>
            <input type="hidden" name="id" value="<?= $editMember['id'] ?>">
            <input type="hidden" name="existing_image" value="<?= e($editMember['image_path']) ?>">
        <?php endif; ?>
        <div class="settings-grid">
            <div class="form-group"><label>Name *</label><input name="name" value="<?= e($editMember['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>Role *</label><input name="role" value="<?= e($editMember['role'] ?? '') ?>" required></div>
            <div class="form-group"><label>Specialty</label><input name="specialty" value="<?= e($editMember['specialty'] ?? '') ?>"></div>
            <div class="form-group"><label>Location</label><input name="location" value="<?= e($editMember['location'] ?? '') ?>"></div>
            <div class="form-group"><label>Contact</label><input name="contact" value="<?= e($editMember['contact'] ?? '') ?>"></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= e($editMember['email'] ?? '') ?>"></div>
            <div class="form-group"><label>Education</label><input name="education" value="<?= e($editMember['education'] ?? '') ?>"></div>
            <div class="form-group"><label>Display Order</label><input type="number" name="display_order" value="<?= e($editMember['display_order'] ?? '0') ?>"></div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= ($editMember['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($editMember['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="form-group full"><label>Photo <?= $editMember ? '' : '*' ?></label><input type="file" name="image" accept="image/*" <?= $editMember ? '' : 'required' ?>></div>
            <?php if ($editMember && $editMember['image_path']): ?>
                <div class="form-group"><img src="../<?= e($editMember['image_path']) ?>" alt="" class="image-preview"></div>
            <?php endif; ?>
            <div class="form-group full"><label>Bio (optional)</label><textarea name="bio"><?= e($editMember['bio'] ?? '') ?></textarea></div>
        </div>
        <button type="submit" class="btn btn-primary"><?= $editMember ? 'Update' : 'Add' ?></button>
        <?php if ($editMember): ?><a href="team.php" class="btn btn-warning" style="margin-left:10px">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="table-container">
    <div class="table-header"><h2>Team Members (<?= count($team) ?>)</h2></div>
    <div class="course-grid">
        <?php foreach ($team as $m): ?>
            <div class="course-card">
                <img src="../<?= e($m['image_path']) ?>" alt="<?= e($m['name']) ?>">
                <div class="content">
                    <h3><?= e($m['name']) ?></h3>
                    <p style="color:#b8860b;font-weight:600"><?= e($m['role']) ?></p>
                    <p><?= e($m['specialty']) ?></p>
                    <span class="badge badge-<?= $m['status'] === 'active' ? 'active' : 'inactive' ?>"><?= ucfirst($m['status']) ?></span>
                    <div class="actions" style="margin-top:12px">
                        <a href="team.php?edit=<?= $m['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <form method="POST" style="display:inline" onsubmit="return confirm('Delete this member?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
