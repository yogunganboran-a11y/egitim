<?php
/**
 * Veritabanı Kullanım Örnekleri
 *
 * Bu dosya includes/db.php kullanımına dair örnekler içerir.
 * Projenize entegre ederken bu örnekleri referans alabilirsiniz.
 */

// Veritabanı bağlantısını dahil et
require_once '../includes/db.php';

// ================================
// ÖRNEK 1: KULLANICI GİRİŞİ
// ================================
function loginUser($tckn, $password) {
    // Güvenli sorgu (SQL Injection korumalı)
    $query = "SELECT * FROM users WHERE tckn = ? AND is_active = 1 LIMIT 1";
    $user = db_fetch_one($query, [$tckn], 's');

    if ($user && password_verify($password, $user['password'])) {
        // Giriş başarılı
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_logged_in'] = true;
        return true;
    }
    return false;
}

// Kullanım:
// if (loginUser('12345678901', '123456')) {
//     header('Location: index.php');
// }

// ================================
// ÖRNEK 2: YENİ KULLANICI KAYDI
// ================================
function registerUser($data) {
    $query = "INSERT INTO users (tckn, name, surname, phone, birth_date, password)
              VALUES (?, ?, ?, ?, ?, ?)";

    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    $params = [
        $data['tckn'],
        $data['name'],
        $data['surname'],
        $data['phone'],
        $data['birth_date'],
        $hashedPassword
    ];

    $userId = db_insert($query, $params, 'ssssss');

    if ($userId) {
        return ['success' => true, 'user_id' => $userId];
    }
    return ['success' => false, 'error' => 'Kayıt başarısız'];
}

// Kullanım:
// $result = registerUser([
//     'tckn' => '11111111111',
//     'name' => 'Test',
//     'surname' => 'Kullanıcı',
//     'phone' => '0555 555 5555',
//     'birth_date' => '1990-01-01',
//     'password' => 'sifre123'
// ]);

// ================================
// ÖRNEK 3: KULLANICI LİSTELE (SAYFALAMA)
// ================================
function getUsers($page = 1, $perPage = 20, $search = '') {
    $offset = ($page - 1) * $perPage;

    if (!empty($search)) {
        $query = "SELECT u.*, c.name as company_name
                  FROM users u
                  LEFT JOIN companies c ON u.company_id = c.id
                  WHERE (u.name LIKE ? OR u.surname LIKE ? OR u.tckn LIKE ? OR u.phone LIKE ?)
                  ORDER BY u.created_at DESC
                  LIMIT ? OFFSET ?";

        $searchParam = "%$search%";
        $params = [$searchParam, $searchParam, $searchParam, $searchParam, $perPage, $offset];
        return db_fetch_all($query, $params, 'ssssii');
    } else {
        $query = "SELECT u.*, c.name as company_name
                  FROM users u
                  LEFT JOIN companies c ON u.company_id = c.id
                  ORDER BY u.created_at DESC
                  LIMIT ? OFFSET ?";

        return db_fetch_all($query, [$perPage, $offset], 'ii');
    }
}

// Kullanım:
// $users = getUsers(1, 20, 'Ahmet'); // 1. sayfa, sayfa başına 20, "Ahmet" arası

// ================================
// ÖRNEK 4: EĞİTİME KAYIT
// ================================
function enrollUserToCourse($userId, $courseId) {
    // Önce kayıt var mı kontrol et
    $checkQuery = "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?";
    $existing = db_fetch_one($checkQuery, [$userId, $courseId], 'ii');

    if ($existing) {
        return ['success' => false, 'error' => 'Zaten kayıtlı'];
    }

    // Yeni kayıt oluştur
    $query = "INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'enrolled')";
    $enrollmentId = db_insert($query, [$userId, $courseId], 'ii');

    if ($enrollmentId) {
        // Bildirim gönder
        sendNotification($userId, 'Eğitime Kayıt', 'Eğitime başarıyla kaydoldunuz!', 'success');
        return ['success' => true, 'enrollment_id' => $enrollmentId];
    }

    return ['success' => false, 'error' => 'Kayıt başarısız'];
}

// ================================
// ÖRNEK 5: BİLDİRİM GÖNDER
// ================================
function sendNotification($userId, $title, $message, $type = 'info', $link = null) {
    $query = "INSERT INTO notifications (user_id, title, message, type, link, icon)
              VALUES (?, ?, ?, ?, ?, ?)";

    $icons = [
        'success' => 'fa-check-circle',
        'info' => 'fa-info-circle',
        'warning' => 'fa-exclamation-triangle',
        'danger' => 'fa-times-circle'
    ];

    $icon = $icons[$type] ?? 'fa-bell';

    $params = [$userId, $title, $message, $type, $link, $icon];
    return db_insert($query, $params, 'isssss');
}

// ================================
// ÖRNEK 6: VİDEO İZLEME KAYDI GÜNCELLE
// ================================
function updateVideoProgress($enrollmentId, $watchedSeconds, $totalDuration) {
    $percentage = ($watchedSeconds / $totalDuration) * 100;
    $videoWatched = ($percentage >= 90) ? 1 : 0; // %90 izlerse tamamlanmış sayılır

    $query = "UPDATE enrollments
              SET video_watch_percentage = ?,
                  video_max_watched_time = ?,
                  video_watched = ?,
                  updated_at = CURRENT_TIMESTAMP
              WHERE id = ?";

    return db_execute($query, [$percentage, $watchedSeconds, $videoWatched, $enrollmentId], 'diii');
}

// ================================
// ÖRNEK 7: TEST SONUCU KAYDET
// ================================
function saveTestResult($enrollmentId, $score, $passScore = 70) {
    $passed = ($score >= $passScore) ? 1 : 0;
    $status = $passed ? 'completed' : 'failed';

    $query = "UPDATE enrollments
              SET test_completed = 1,
                  test_score = ?,
                  test_passed = ?,
                  status = ?,
                  completed_at = IF(? = 1, CURRENT_TIMESTAMP, NULL)
              WHERE id = ?";

    $affected = db_execute($query, [$score, $passed, $status, $passed, $enrollmentId], 'disii');

    if ($affected && $passed) {
        // Test başarılı, sertifika oluştur
        generateCertificate($enrollmentId);
    }

    return $affected;
}

// ================================
// ÖRNEK 8: SERTİFİKA OLUŞTUR
// ================================
function generateCertificate($enrollmentId) {
    // Enrollment bilgilerini al
    $query = "SELECT e.user_id, e.course_id FROM enrollments e WHERE e.id = ?";
    $enrollment = db_fetch_one($query, [$enrollmentId], 'i');

    if (!$enrollment) return false;

    // Sertifika numarası oluştur
    $certificateNumber = 'CERT-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

    // Sertifika kaydet
    $insertQuery = "INSERT INTO certificates (user_id, course_id, enrollment_id, certificate_number, issue_date, expiry_date)
                    VALUES (?, ?, ?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 365 DAY))";

    return db_insert($insertQuery, [
        $enrollment['user_id'],
        $enrollment['course_id'],
        $enrollmentId,
        $certificateNumber
    ], 'iiis');
}

// ================================
// ÖRNEK 9: DESTEK TALEBİ OLUŞTUR
// ================================
function createSupportTicket($userId, $category, $subject, $message) {
    // Ticket numarası oluştur
    $ticketNumber = 'TKT-' . date('Ymd') . '-' . rand(1000, 9999);

    $query = "INSERT INTO support_tickets (user_id, ticket_number, category, subject, message, status, priority)
              VALUES (?, ?, ?, ?, ?, 'open', 'medium')";

    $ticketId = db_insert($query, [$userId, $ticketNumber, $category, $subject, $message], 'issss');

    if ($ticketId) {
        // İlk mesajı kaydet
        $msgQuery = "INSERT INTO support_messages (ticket_id, sender_type, sender_id, message)
                     VALUES (?, 'user', ?, ?)";
        db_insert($msgQuery, [$ticketId, $userId, $message], 'iis');

        return ['success' => true, 'ticket_id' => $ticketId, 'ticket_number' => $ticketNumber];
    }

    return ['success' => false];
}

// ================================
// ÖRNEK 10: GERİ ARAMA EKLE
// ================================
function addCallback($phone, $name, $source, $scheduledDate, $ruleName = null) {
    $query = "INSERT INTO callbacks (phone, name, source, scheduled_date, rule_name, status, priority)
              VALUES (?, ?, ?, ?, ?, 'planned', 'medium')";

    return db_insert($query, [$phone, $name, $source, $scheduledDate, $ruleName], 'sssss');
}

// ================================
// ÖRNEK 11: TRANSACTION KULLANIMI
// ================================
function transferCertificate($fromUserId, $toUserId, $certificateId) {
    db_begin_transaction();

    try {
        // Sertifikayı eski kullanıcıdan kaldır
        $query1 = "UPDATE certificates SET is_valid = 0 WHERE id = ? AND user_id = ?";
        db_execute($query1, [$certificateId, $fromUserId], 'ii');

        // Yeni sertifika oluştur
        $query2 = "INSERT INTO certificates (user_id, course_id, certificate_number, issue_date)
                   SELECT ?, course_id, CONCAT('CERT-TRANS-', id), CURDATE()
                   FROM certificates WHERE id = ?";
        db_insert($query2, [$toUserId, $certificateId], 'ii');

        // Her şey başarılı, commit
        db_commit();
        return true;

    } catch (Exception $e) {
        // Hata olursa rollback
        db_rollback();
        error_log("Transfer error: " . $e->getMessage());
        return false;
    }
}

// ================================
// ÖRNEK 12: TOPLU SMS GÖNDER
// ================================
function sendBulkSMS($userIds, $message) {
    // Kullanıcıların telefon numaralarını al
    $placeholders = implode(',', array_fill(0, count($userIds), '?'));
    $query = "SELECT id, phone FROM users WHERE id IN ($placeholders) AND is_active = 1";

    $types = str_repeat('i', count($userIds));
    $users = db_fetch_all($query, $userIds, $types);

    $successCount = 0;
    foreach ($users as $user) {
        // SMS gönderimi burada yapılır (API entegrasyonu)
        // Şimdilik sadece kayıt edelim

        $insertQuery = "INSERT INTO sms_history (user_id, phone, message, status)
                        VALUES (?, ?, ?, 'pending')";

        if (db_insert($insertQuery, [$user['id'], $user['phone'], $message], 'iss')) {
            $successCount++;
        }
    }

    return ['success' => true, 'sent' => $successCount, 'total' => count($users)];
}

// ================================
// ÖRNEK 13: DASHBOARD İSTATİSTİKLERİ
// ================================
function getDashboardStats() {
    $stats = [];

    // Toplam kullanıcı
    $stats['total_users'] = db_fetch_one("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'];

    // Aktif kayıtlar
    $stats['active_enrollments'] = db_fetch_one("SELECT COUNT(*) as count FROM enrollments WHERE status IN ('enrolled', 'in_progress')")['count'];

    // Bu ay verilen sertifikalar
    $stats['monthly_certificates'] = db_fetch_one("SELECT COUNT(*) as count FROM certificates WHERE MONTH(issue_date) = MONTH(CURDATE())")['count'];

    // Bekleyen destek talepleri
    $stats['pending_tickets'] = db_fetch_one("SELECT COUNT(*) as count FROM support_tickets WHERE status = 'open'")['count'];

    // Bugünkü geri aramalar
    $stats['today_callbacks'] = db_fetch_one("SELECT COUNT(*) as count FROM callbacks WHERE DATE(scheduled_date) = CURDATE()")['count'];

    return $stats;
}

// ================================
// ÖRNEK 14: KULLANICI DETAYLARI (JOIN)
// ================================
function getUserProfile($userId) {
    $query = "SELECT
                u.*,
                c.name as company_name,
                COUNT(DISTINCT e.id) as total_courses,
                COUNT(DISTINCT cert.id) as total_certificates,
                COUNT(DISTINCT st.id) as total_tickets
              FROM users u
              LEFT JOIN companies c ON u.company_id = c.id
              LEFT JOIN enrollments e ON u.id = e.user_id
              LEFT JOIN certificates cert ON u.id = cert.user_id AND cert.is_valid = 1
              LEFT JOIN support_tickets st ON u.id = st.user_id
              WHERE u.id = ?
              GROUP BY u.id";

    return db_fetch_one($query, [$userId], 'i');
}

// ================================
// KULLANIM ÖRNEKLERİ
// ================================

/*
// Dashboard göster
$stats = getDashboardStats();
echo "Toplam Kullanıcı: " . $stats['total_users'];

// Kullanıcı profili
$profile = getUserProfile(1);
echo $profile['name'] . ' ' . $profile['surname'];
echo "Aldığı Eğitim: " . $profile['total_courses'];

// Arama yap
$searchResults = getUsers(1, 20, 'Ahmet');
foreach ($searchResults as $user) {
    echo $user['name'] . ' ' . $user['surname'] . '<br>';
}
*/

?>
