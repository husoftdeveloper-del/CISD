<?php
$adminPage = 'portal-setup';
$adminPageTitle = 'Institute Portal Setup';
require_once 'includes/init.php';
require_once __DIR__ . '/../includes/portal-migrate.php';
require_once __DIR__ . '/../includes/portal-enroll.php';

$message = '';
$messageType = '';
$portalStatus = [];
$portalDbName = $portal_db ?? $db;

$portalConn = cisd_portal_connection();
if ($portalConn) {
    $portalStatus['connected'] = true;
    $res = $portalConn->query('SELECT COUNT(*) AS c FROM admissions');
    $portalStatus['admissions'] = $res ? (int) $res->fetch_assoc()['c'] : 0;
    $res = $portalConn->query('SELECT COUNT(*) AS c FROM teachers');
    $portalStatus['teachers'] = $res ? (int) $res->fetch_assoc()['c'] : 0;
    $res = $portalConn->query('SELECT COUNT(*) AS c FROM fee_receipts_v2');
    $portalStatus['receipts'] = $res ? (int) $res->fetch_assoc()['c'] : 0;
} else {
    $portalStatus['connected'] = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'run_migrations' && $portalConn) {
        cisd_portal_run_migrations($portalConn);
        $message = 'Portal database tables are ready.';
        $messageType = 'success';
    }

    if ($action === 'import_source' && $portalConn) {
        $sourceDb = trim($_POST['source_db'] ?? ($portal_import_from ?? ''));
        if ($sourceDb === '') {
            $message = 'Enter the source database name (e.g. CISD on local XAMPP).';
            $messageType = 'error';
        } else {
            $result = cisd_portal_import_from_database($portalConn, $sourceDb);
            if ($result['errors']) {
                $message = 'Import finished with errors: ' . implode('; ', $result['errors']);
                $messageType = 'error';
            } else {
                $message = 'Imported tables: ' . (empty($result['imported']) ? 'none found in source DB' : implode(', ', $result['imported']));
                $messageType = empty($result['imported']) ? 'error' : 'success';
            }
        }
    }

    if ($action === 'import_sql' && $portalConn && !empty($_FILES['sql_file']['tmp_name'])) {
        $sql = file_get_contents($_FILES['sql_file']['tmp_name']);
        if ($sql === false) {
            $message = 'Could not read uploaded file.';
            $messageType = 'error';
        } else {
            $portalConn->query('SET FOREIGN_KEY_CHECKS=0');
            if ($portalConn->multi_query($sql)) {
                do {
                    if ($result = $portalConn->store_result()) {
                        $result->free();
                    }
                } while ($portalConn->more_results() && $portalConn->next_result());
            }
            $portalConn->query('SET FOREIGN_KEY_CHECKS=1');
            if ($portalConn->error) {
                $message = 'SQL import error: ' . $portalConn->error;
                $messageType = 'error';
            } else {
                cisd_portal_run_migrations($portalConn);
                $message = 'SQL file imported successfully.';
                $messageType = 'success';
            }
        }
    }
}

if ($portalConn) {
    $res = $portalConn->query('SELECT COUNT(*) AS c FROM admissions');
    $portalStatus['admissions'] = $res ? (int) $res->fetch_assoc()['c'] : 0;
}

require 'includes/header.php';
?>

<div class="header">
    <h1>Institute Portal Setup</h1>
    <div class="header-actions">
        <a href="../smart_portal/dashboard.php" class="btn btn-success">Open Institute Portal</a>
        <a href="dashboard.php" class="btn btn-primary">Back</a>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?= e($messageType) ?>"><?= e($message) ?></div>
<?php endif; ?>

<div class="dashboard-cards">
    <div class="card">
        <h3>Portal Database</h3>
        <div class="number" style="font-size:18px"><?= e($portalDbName) ?></div>
    </div>
    <div class="card">
        <h3>Connection</h3>
        <div class="number" style="font-size:18px"><?= $portalStatus['connected'] ? '✅ OK' : '❌ Failed' ?></div>
    </div>
    <div class="card">
        <h3>Students</h3>
        <div class="number"><?= $portalStatus['admissions'] ?? 0 ?></div>
    </div>
    <div class="card">
        <h3>Fee Receipts</h3>
        <div class="number"><?= $portalStatus['receipts'] ?? 0 ?></div>
    </div>
</div>

<div class="table-container" style="margin-top:24px">
    <div class="table-header"><h2>Step 1 — Create portal database on Hostinger</h2></div>
    <div style="padding:20px;line-height:1.7">
        <p>In hPanel → <strong>Databases</strong>, create a new MySQL database (example: <code>u328011253_cisd_portal</code>).</p>
        <p>Set it in <code>config.php</code> as <code>$portal_db</code> (separate from the website database).</p>
    </div>
</div>

<div class="table-container" style="margin-top:24px">
    <div class="table-header"><h2>Step 2 — Create portal tables</h2></div>
    <div style="padding:20px">
        <form method="POST">
            <input type="hidden" name="action" value="run_migrations">
            <button type="submit" class="btn btn-primary" <?= !$portalStatus['connected'] ? 'disabled' : '' ?>>Run Portal Migration</button>
        </form>
        <p style="margin-top:12px;color:#64748b">Creates admissions, fees, teachers, expenditures, and related tables.</p>
    </div>
</div>

<div class="table-container" style="margin-top:24px">
    <div class="table-header"><h2>Step 3 — Import your local CISD data</h2></div>
    <div style="padding:20px;display:grid;gap:24px">
        <div>
            <h3 style="margin-bottom:12px">Option A — Copy from another database on this server</h3>
            <p style="margin-bottom:12px">If your old portal DB (e.g. <code>CISD</code>) is on the same MySQL server, enter its name:</p>
            <form method="POST" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
                <input type="hidden" name="action" value="import_source">
                <input type="text" name="source_db" placeholder="CISD" value="<?= e($portal_import_from ?? 'CISD') ?>" style="padding:10px;border:1px solid #e2e8f0;border-radius:8px;min-width:220px">
                <button type="submit" class="btn btn-success" <?= !$portalStatus['connected'] ? 'disabled' : '' ?> onclick="return confirm('This replaces portal tables with data from the source database. Continue?')">Import from Database</button>
            </form>
        </div>
        <div>
            <h3 style="margin-bottom:12px">Option B — Upload SQL export (.sql)</h3>
            <p style="margin-bottom:12px">Export from phpMyAdmin locally: select CISD database → Export → SQL. Upload here:</p>
            <form method="POST" enctype="multipart/form-data" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
                <input type="hidden" name="action" value="import_sql">
                <input type="file" name="sql_file" accept=".sql" required>
                <button type="submit" class="btn btn-warning" <?= !$portalStatus['connected'] ? 'disabled' : '' ?>>Upload &amp; Import SQL</button>
            </form>
        </div>
    </div>
</div>

<div class="table-container" style="margin-top:24px">
    <div class="table-header"><h2>Online applications → Institute admissions</h2></div>
    <div style="padding:20px;line-height:1.7">
        <p>Website applications are stored in <code>online_applications</code>. When you <strong>Approve</strong> or click <strong>Enroll</strong> in Applications, the student is added to the institute portal with a registration number (e.g. CISD-2026-001).</p>
        <a href="applications.php" class="btn btn-primary" style="margin-top:12px">Manage Applications</a>
    </div>
</div>

<?php
if ($portalConn) {
    $portalConn->close();
}
require 'includes/footer.php';
