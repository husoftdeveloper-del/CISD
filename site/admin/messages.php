<?php
$adminPage = 'messages';
$adminPageTitle = 'Contact Messages';
require_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'mark_read') {
        $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$id]);
        $adminMessage = 'Marked as read.';
        $adminMessageType = 'success';
    } elseif ($action === 'delete') {
        $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$id]);
        $adminMessage = 'Message deleted.';
        $adminMessageType = 'success';
    }
    $unread_messages = cisd_admin_unread_messages($pdo);
}

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
require 'includes/header.php';
?>

<div class="header"><h1>Contact Messages</h1></div>

<?php if ($adminMessage): ?>
    <div class="alert alert-<?= e($adminMessageType) ?>"><?= e($adminMessage) ?></div>
<?php endif; ?>

<div class="table-container">
    <div class="table-header"><h2>All Messages (<?= count($messages) ?>)</h2></div>
    <?php if (empty($messages)): ?>
        <p style="padding:24px;color:#64748b">No messages yet.</p>
    <?php else: ?>
    <table class="data-table">
        <thead><tr><th>Status</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($messages as $msg): ?>
            <tr style="<?= !$msg['is_read'] ? 'font-weight:600;background:#f8fafc' : '' ?>">
                <td><?= $msg['is_read'] ? 'Read' : 'New' ?></td>
                <td><?= e($msg['name']) ?><br><small><?= e($msg['phone'] ?? '') ?></small></td>
                <td><a href="mailto:<?= e($msg['email']) ?>"><?= e($msg['email']) ?></a></td>
                <td><?= e($msg['subject'] ?? '—') ?></td>
                <td><?= e(substr($msg['message'], 0, 100)) ?><?= strlen($msg['message']) > 100 ? '...' : '' ?></td>
                <td><?= e($msg['created_at']) ?></td>
                <td>
                    <?php if (!$msg['is_read']): ?>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="action" value="mark_read">
                        <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Mark Read</button>
                    </form>
                    <?php endif; ?>
                    <form method="POST" style="display:inline" onsubmit="return confirm('Delete?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>
