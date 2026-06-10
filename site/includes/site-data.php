<?php

function cisd_load_site_settings(PDO $pdo): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    } catch (PDOException $e) {
        // Table may not exist yet during first connection.
    }

    return $cache;
}

function site_setting(string $key, string $default = ''): string
{
    global $SITE_SETTINGS;
    return $SITE_SETTINGS[$key] ?? $default;
}

function cisd_apply_institute_from_settings(): void
{
    global $INSTITUTE, $SITE_SETTINGS;

    if (empty($SITE_SETTINGS)) {
        return;
    }

    $INSTITUTE = [
        'name'     => site_setting('institute_name', $INSTITUTE['name'] ?? 'CISD INSTITUTE'),
        'tagline'  => site_setting('institute_tagline', $INSTITUTE['tagline'] ?? ''),
        'phone'    => site_setting('institute_phone', $INSTITUTE['phone'] ?? ''),
        'whatsapp' => site_setting('institute_whatsapp', $INSTITUTE['whatsapp'] ?? ''),
        'email'    => site_setting('institute_email', $INSTITUTE['email'] ?? ''),
        'address'  => site_setting('institute_address', $INSTITUTE['address'] ?? ''),
        'maps'     => site_setting('institute_maps', $INSTITUTE['maps'] ?? ''),
    ];
}

function cisd_get_features(?PDO $pdo, string $section): array
{
    if (!$pdo) return [];
    try {
        $stmt = $pdo->prepare("SELECT * FROM features WHERE section = ? ORDER BY display_order ASC, id ASC");
        $stmt->execute([$section]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function cisd_get_team(?PDO $pdo, bool $activeOnly = true): array
{
    if (!$pdo) return [];
    try {
        $sql = "SELECT * FROM team_members";
        if ($activeOnly) {
            $sql .= " WHERE status = 'active'";
        }
        $sql .= " ORDER BY display_order ASC, id ASC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function cisd_get_gallery(?PDO $pdo): array
{
    if (!$pdo) return [];
    try {
        return $pdo->query("SELECT * FROM gallery ORDER BY display_order ASC, id DESC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function cisd_get_success_stories(?PDO $pdo, ?int $limit = null): array
{
    if (!$pdo) return [];
    try {
        $sql = "SELECT * FROM success_stories ORDER BY created_at DESC";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit;
        }
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function cisd_admin_pending_count(PDO $pdo): int
{
    try {
        return (int) $pdo->query("SELECT COUNT(*) FROM admissions WHERE status = 'pending'")->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

function cisd_admin_unread_messages(PDO $pdo): int
{
    try {
        return (int) $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

function cisd_save_settings(PDO $pdo, array $settings): void
{
    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    foreach ($settings as $key => $value) {
        $stmt->execute([$key, $value]);
    }
}
