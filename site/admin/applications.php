<?php
$adminPage = 'applications';
$adminPageTitle = 'Applications Management';
require_once 'includes/init.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/portal-enroll.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = $adminMessage ?? '';
$messageType = $adminMessageType ?? '';

// Handle enroll into institute portal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'enroll') {
    $id = intval($_POST['id']);
    $portalConn = cisd_portal_connection();
    if (!$portalConn) {
        $message = 'Institute portal database is not connected. Open Portal Setup to configure it.';
        $messageType = 'error';
    } else {
        $result = cisd_enroll_online_application($pdo, $portalConn, $id);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
        $portalConn->close();
    }
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = intval($_POST['id']);
    $status = $_POST['status'] ?? 'pending';
    
    try {
        $stmt = $pdo->prepare("UPDATE online_applications SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $message = 'Application status updated successfully!';
        $messageType = 'success';

        if ($status === 'approved') {
            $portalConn = cisd_portal_connection();
            if ($portalConn) {
                $enroll = cisd_enroll_online_application($pdo, $portalConn, $id);
                if ($enroll['success'] && !empty($enroll['registration_no'])) {
                    $message .= ' Enrolled as ' . $enroll['registration_no'] . '.';
                }
                $portalConn->close();
            }
        }

        // Fetch user email to send notification
        $stmt = $pdo->prepare("SELECT email, full_name FROM online_applications WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $to = $user['email'];
            $subject = "Application Status Update - CISD Institute";
            // Determine email body based on new status
            if ($status === 'approved') {
                $body = "Hello " . $user['full_name'] . ",\n\nCongratulations! Your application has been approved by CISD Institute.\n\nBest regards,\nCISD Institute Team";
            } elseif ($status === 'rejected') {
                $body = "Hello " . $user['full_name'] . ",\n\nWe are sorry. Your application was not approved by CISD Institute.\n\nBest regards,\nCISD Institute Team";
            } else {
                // For other status changes (e.g., pending), optionally send a generic update
                $body = "Hello " . $user['full_name'] . ",\n\nYour application status has been updated to: " . ucfirst($status) . ".\n\nBest regards,\nCISD Institute Team";
            }
            // Send email
            // Send email using PHPMailer


$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->SMTPDebug = 0; // Enable SMTP debug output
    $mail->isSMTP();
    $mail->Host = $SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = $SMTP_USER;
    $mail->Password = $SMTP_PASS;
    $mail->SMTPSecure = $SMTP_SECURE; // tls or ssl
    $mail->Port = $SMTP_PORT;

    // Recipients
    $mail->setFrom($SMTP_USER, 'CISD Institute');
    $mail->addAddress($to, $user['full_name']);

    // Content
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $body;
    // Debug log before sending
    error_log("Attempting to send email from $SMTP_USER to $to for application ID $id");

    $mail->send();
    // Log email transmission details
    error_log("Email sent from $SMTP_USER to $to for application ID $id");
} catch (Exception $e) {
    // Log error or set message
    error_log('Mail could not be sent. PHPMailer Error: ' . $e->getMessage());
}
        }
    } catch (PDOException $e) {
        $message = 'Database error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM online_applications WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Application deleted successfully!';
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = 'Database error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Get filter and search parameters
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$query = "SELECT * FROM online_applications WHERE 1=1";
$params = [];

if ($status_filter) {
    $query .= " AND status = ?";
    $params[] = $status_filter;
}

if ($search) {
    $query .= " AND (full_name LIKE ? OR email LIKE ? OR phone LIKE ? OR course LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

$query .= " ORDER BY created_at DESC";

// Get applications
try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $applications = [];
}

// Get counts
try {
    $pending_count = $pdo->query("SELECT COUNT(*) FROM online_applications WHERE status = 'pending'")->fetchColumn();
    $approved_count = $pdo->query("SELECT COUNT(*) FROM online_applications WHERE status = 'approved'")->fetchColumn();
    $rejected_count = $pdo->query("SELECT COUNT(*) FROM online_applications WHERE status = 'rejected'")->fetchColumn();
} catch (PDOException $e) {
    $pending_count = 0;
    $approved_count = 0;
    $rejected_count = 0;
}
require 'includes/header.php';
?>
            <div class="header">
                <h1>Student Applications</h1>
                <div class="header-actions">
                    <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="dashboard-cards" style="margin-bottom: 32px;">
                <div class="card">
                    <h3>Pending</h3>
                    <div class="number"><?= $pending_count ?></div>
                </div>
                <div class="card">
                    <h3>Approved</h3>
                    <div class="number"><?= $approved_count ?></div>
                </div>
                <div class="card">
                    <h3>Rejected</h3>
                    <div class="number"><?= $rejected_count ?></div>
                </div>
                <div class="card">
                    <h3>Total</h3>
                    <div class="number"><?= count($applications) ?></div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="table-container">
                <div class="table-header">
                    <h2>All Applications (<?= count($applications) ?>)</h2>
                </div>
                
                <form method="GET" class="search-filter">
                    <input type="text" name="search" placeholder="Search by name, email, phone, or course..." value="<?= htmlspecialchars($search) ?>">
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= $status_filter === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= $status_filter === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="applications.php" class="btn btn-warning">Clear</a>
                </form>

                <!-- Applications Table -->
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Education</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Enrolled</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td>#<?= $app['id'] ?></td>
                                    <td><?= htmlspecialchars($app['full_name']) ?></td>
                                    <td><?= htmlspecialchars($app['email']) ?></td>
                                    <td><?= htmlspecialchars($app['phone']) ?></td>
                                    <td><?= htmlspecialchars($app['course']) ?></td>
                                    <td><?= htmlspecialchars($app['education'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($app['city'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge badge-<?= $app['status'] ?>">
                                            <?= ucfirst($app['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($app['portal_admission_id'])): ?>
                                            <a href="../smart_portal/edit_admission.php?id=<?= (int) $app['portal_admission_id'] ?>" class="btn btn-sm btn-success" target="_blank">Portal #<?= (int) $app['portal_admission_id'] ?></a>
                                        <?php else: ?>
                                            <span class="badge badge-pending">Not yet</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($app['created_at'])) ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                            <select name="status" onchange="this.form.submit()" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0;">
                                                <option value="pending" <?= $app['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="approved" <?= $app['status'] === 'approved' ? 'selected' : '' ?>>Approve</option>
                                                <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Reject</option>
                                            </select>
                                        </form>
                                        <button onclick="showDetails(<?= htmlspecialchars(json_encode($app)) ?>)" class="btn btn-sm btn-primary" style="margin-left: 4px;">View</button>
                                        <?php if (empty($app['portal_admission_id'])): ?>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Enroll this applicant in the institute portal? You can complete CNIC, father name, and fees there.');">
                                            <input type="hidden" name="action" value="enroll">
                                            <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-success" style="margin-left: 4px;">Enroll</button>
                                        </form>
                                        <?php endif; ?>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this application?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" style="margin-left: 4px;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($applications)): ?>
                                <tr>
                                    <td colspan="11" style="text-align: center; color: #64748b; padding: 40px;">No applications found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    <!-- Details Modal -->
    <div id="detailsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
        <div style="background: #fff; border-radius: 12px; padding: 32px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <h2 style="margin-bottom: 20px; color: #0f172a;">Application Details</h2>
            <div id="modalContent"></div>
            <button onclick="document.getElementById('detailsModal').style.display='none'" class="btn btn-primary" style="margin-top: 20px; width: 100%;">Close</button>
        </div>
    </div>

    <script>
        function showDetails(app) {
            const content = `
                <p><strong>Full Name:</strong> ${app.full_name}</p>
                <p><strong>Email:</strong> ${app.email}</p>
                <p><strong>Phone:</strong> ${app.phone}</p>
                <p><strong>Course:</strong> ${app.course}</p>
                <p><strong>Education:</strong> ${app.education || 'N/A'}</p>
                <p><strong>City:</strong> ${app.city || 'N/A'}</p>
                <p><strong>Message:</strong></p>
                <p style="background: #f1f5f9; padding: 12px; border-radius: 8px;">${app.message || 'No message'}</p>
                <p><strong>Status:</strong> <span class="badge badge-${app.status}">${app.status.charAt(0).toUpperCase() + app.status.slice(1)}</span></p>
                <p><strong>Applied On:</strong> ${new Date(app.created_at).toLocaleString()}</p>
            `;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('detailsModal').style.display = 'flex';
        }

        // Close modal when clicking outside
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    </script>
<?php require 'includes/footer.php'; ?>
