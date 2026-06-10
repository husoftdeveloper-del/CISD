<?php
$adminPage = 'stories';
$adminPageTitle = 'Success Stories';
require_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_story']) || isset($_POST['edit_story'])) {
        $name = trim($_POST['name'] ?? '');
        $father_name = trim($_POST['father_name'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $quote = trim($_POST['quote'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $education = trim($_POST['education'] ?? '');
        $imagePath = $_POST['existing_image'] ?? '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/stories/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $newName = uniqid('story_') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName)) {
                if ($imagePath && file_exists($uploadDir . $imagePath)) unlink($uploadDir . $imagePath);
                $imagePath = $newName;
            }
        }

        if (isset($_POST['edit_story'])) {
            $id = (int) $_POST['story_id'];
            $stmt = $pdo->prepare("UPDATE success_stories SET image=?, name=?, father_name=?, course=?, quote=?, location=?, contact=?, email=?, education=? WHERE id=?");
            $stmt->execute([$imagePath, $name, $father_name, $course, $quote, $location, $contact, $email, $education, $id]);
            $adminMessage = 'Story updated.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO success_stories (image, name, father_name, course, quote, location, contact, email, education) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$imagePath, $name, $father_name, $course, $quote, $location, $contact, $email, $education]);
            $pdo->exec("UPDATE statistics SET stat_value = stat_value + 1 WHERE stat_key = 'success_stories'");
            $adminMessage = 'Story added.';
        }
        $adminMessageType = 'success';
    }
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("SELECT image FROM success_stories WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['image']) {
        $file = __DIR__ . '/../uploads/stories/' . $row['image'];
        if (file_exists($file)) unlink($file);
    }
    $pdo->prepare("DELETE FROM success_stories WHERE id = ?")->execute([$id]);
    $pdo->exec("UPDATE statistics SET stat_value = GREATEST(stat_value - 1, 0) WHERE stat_key = 'success_stories'");
    header('Location: stories.php');
    exit();
}

$editStory = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM success_stories WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editStory = $stmt->fetch(PDO::FETCH_ASSOC);
}

$stories = cisd_get_success_stories($pdo);
require 'includes/header.php';
?>

<div class="header"><h1>Success Stories</h1></div>

<?php if ($adminMessage): ?>
    <div class="alert alert-<?= e($adminMessageType) ?>"><?= e($adminMessage) ?></div>
<?php endif; ?>

<div class="table-container" style="margin-bottom:32px">
    <div class="table-header"><h2><?= $editStory ? 'Edit Story' : 'Add Story' ?></h2></div>
    <form method="POST" enctype="multipart/form-data" class="form-container">
        <?php if ($editStory): ?>
            <input type="hidden" name="edit_story" value="1">
            <input type="hidden" name="story_id" value="<?= $editStory['id'] ?>">
            <input type="hidden" name="existing_image" value="<?= e($editStory['image']) ?>">
        <?php else: ?>
            <input type="hidden" name="add_story" value="1">
        <?php endif; ?>
        <div class="settings-grid">
            <div class="form-group"><label>Student Name *</label><input name="name" value="<?= e($editStory['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>Father Name</label><input name="father_name" value="<?= e($editStory['father_name'] ?? '') ?>"></div>
            <div class="form-group"><label>Course *</label><input name="course" value="<?= e($editStory['course'] ?? '') ?>" required></div>
            <div class="form-group"><label>Location</label><input name="location" value="<?= e($editStory['location'] ?? '') ?>"></div>
            <div class="form-group"><label>Contact</label><input name="contact" value="<?= e($editStory['contact'] ?? '') ?>"></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= e($editStory['email'] ?? '') ?>"></div>
            <div class="form-group"><label>Education</label><input name="education" value="<?= e($editStory['education'] ?? '') ?>"></div>
            <div class="form-group full"><label>Quote / Testimonial *</label><textarea name="quote" required><?= e($editStory['quote'] ?? '') ?></textarea></div>
            <div class="form-group full"><label>Photo <?= $editStory ? '(leave empty to keep)' : '*' ?></label><input type="file" name="image" accept="image/*" <?= $editStory ? '' : 'required' ?>></div>
        </div>
        <button type="submit" class="btn btn-primary"><?= $editStory ? 'Update Story' : 'Add Story' ?></button>
        <?php if ($editStory): ?><a href="stories.php" class="btn btn-warning" style="margin-left:10px">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="table-container">
    <div class="table-header"><h2>All Stories (<?= count($stories) ?>)</h2></div>
    <table class="data-table">
        <thead><tr><th>Photo</th><th>Name</th><th>Course</th><th>Quote</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($stories as $story): ?>
            <tr>
                <td><img src="../uploads/stories/<?= e($story['image']) ?>" alt="" style="height:50px;border-radius:6px"></td>
                <td><?= e($story['name']) ?></td>
                <td><?= e($story['course']) ?></td>
                <td><?= e(substr($story['quote'], 0, 60)) ?>...</td>
                <td>
                    <a href="stories.php?edit=<?= $story['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="stories.php?delete=<?= $story['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/footer.php'; ?>
