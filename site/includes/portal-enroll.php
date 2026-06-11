<?php
require_once __DIR__ . '/portal-migrate.php';

/**
 * Generate next CISD registration number for portal admissions.
 */
function cisd_portal_next_registration_no(mysqli $conn): string
{
    $year = date('Y');
    $pattern = "CISD-$year-%";
    $stmt = $conn->prepare("SELECT registration_no FROM admissions WHERE registration_no LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param('s', $pattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $newNum = 1;
    if ($row = $result->fetch_assoc()) {
        $newNum = (int) substr($row['registration_no'], -3) + 1;
    }
    $stmt->close();
    return 'CISD-' . $year . '-' . str_pad((string) $newNum, 3, '0', STR_PAD_LEFT);
}

/**
 * Enroll a website online application into the institute portal admissions table.
 *
 * @return array{success:bool,message:string,portal_admission_id?:int,registration_no?:string}
 */
function cisd_enroll_online_application(PDO $pdo, mysqli $portalConn, int $applicationId): array
{
    cisd_portal_run_migrations($portalConn);

    $stmt = $pdo->prepare('SELECT * FROM online_applications WHERE id = ?');
    $stmt->execute([$applicationId]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        return ['success' => false, 'message' => 'Application not found.'];
    }

    if (!empty($app['portal_admission_id'])) {
        return [
            'success' => true,
            'message' => 'Already enrolled in institute portal.',
            'portal_admission_id' => (int) $app['portal_admission_id'],
        ];
    }

    $check = $portalConn->prepare('SELECT id FROM admissions WHERE online_application_id = ? LIMIT 1');
    $check->bind_param('i', $applicationId);
    $check->execute();
    $existing = $check->get_result()->fetch_assoc();
    $check->close();

    if ($existing) {
        $portalId = (int) $existing['id'];
        $pdo->prepare('UPDATE online_applications SET portal_admission_id = ?, enrolled_at = NOW(), status = ? WHERE id = ?')
            ->execute([$portalId, 'approved', $applicationId]);
        return [
            'success' => true,
            'message' => 'Linked to existing institute admission.',
            'portal_admission_id' => $portalId,
        ];
    }

    $registrationNo = cisd_portal_next_registration_no($portalConn);
    $name = $app['full_name'];
    $email = $app['email'] ?? '';
    $phone = $app['phone'] ?? '';
    $course = $app['course'] ?? '';
    $domicile = $app['city'] ?? '';
    $education = $app['education'] ?? '';
    $message = $app['message'] ?? '';
    $address = $domicile;
    $notes = trim($education !== '' ? "Education: $education\n" : '') . ($message !== '' ? $message : '');
    $gender = '';
    $fatherName = '';
    $cnic = '';
    $dob = '0000-00-00';

    $insert = $portalConn->prepare(
        'INSERT INTO admissions
        (registration_no, name, father_name, cnic, dob, email, domicile, address, gender, course, message, phone, online_application_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $insert->bind_param(
        'ssssssssssssi',
        $registrationNo,
        $name,
        $fatherName,
        $cnic,
        $dob,
        $email,
        $domicile,
        $address,
        $gender,
        $course,
        $notes,
        $phone,
        $applicationId
    );

    if (!$insert->execute()) {
        return ['success' => false, 'message' => 'Portal insert failed: ' . $portalConn->error];
    }

    $portalId = (int) $portalConn->insert_id;
    $insert->close();

    $pdo->prepare('UPDATE online_applications SET portal_admission_id = ?, enrolled_at = NOW(), status = ? WHERE id = ?')
        ->execute([$portalId, 'approved', $applicationId]);

    return [
        'success' => true,
        'message' => "Enrolled as $registrationNo. Complete father name, CNIC, and fees in the institute portal.",
        'portal_admission_id' => $portalId,
        'registration_no' => $registrationNo,
    ];
}

function cisd_portal_connection(): ?mysqli
{
    global $host, $user, $pass, $portal_db, $db;

    $portalDbName = $portal_db ?? $db;
    $portalConn = new mysqli($host, $user, $pass, $portalDbName);
    if ($portalConn->connect_error) {
        return null;
    }
    $portalConn->set_charset('utf8mb4');
    return $portalConn;
}
