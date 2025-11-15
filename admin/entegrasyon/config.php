<?php
/**
 * Admin Panel - Veritabanı Bağlantı Dosyası
 *
 * Bu dosya admin panel için veritabanı bağlantısını sağlar.
 * Güvenlik: Bu dosya web'den erişilemez olmalıdır.
 */

// Yetkisiz erişimi engelle
if (!defined('ADMIN_ACCESS')) {
    die('Yetkisiz erişim!');
}

// Hata raporlama (Production'da kapatın!)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Production'da mutlaka 0

// ===================================
// VERİTABANI BAĞLANTI BİLGİLERİ
// ===================================
// ÖNEMLİ: Hosting'inize göre bu bilgileri güncelleyin!

define('DB_HOST', 'localhost');          // Veritabanı sunucusu
define('DB_USER', 'root');                // Veritabanı kullanıcı adı
define('DB_PASS', '');                    // Veritabanı şifresi
define('DB_NAME', 'egitim');              // Veritabanı adı
define('DB_CHARSET', 'utf8mb4');          // Karakter seti

// ===================================
// DOSYA YOLLARI
// ===================================
define('UPLOAD_DIR', __DIR__ . '/dosyalar/');
define('CERTIFICATE_DIR', UPLOAD_DIR . 'sertifikalar/');
define('DOCUMENT_DIR', UPLOAD_DIR . 'kayit/');
define('INVOICE_DIR', UPLOAD_DIR . 'faturalar/');

// Klasörlerin var olduğundan emin ol
if (!file_exists(CERTIFICATE_DIR)) mkdir(CERTIFICATE_DIR, 0755, true);
if (!file_exists(DOCUMENT_DIR)) mkdir(DOCUMENT_DIR, 0755, true);
if (!file_exists(INVOICE_DIR)) mkdir(INVOICE_DIR, 0755, true);

// ===================================
// VERİTABANI BAĞLANTISI
// ===================================
try {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Bağlantı kontrolü
    if ($db->connect_error) {
        throw new Exception("Veritabanı bağlantı hatası: " . $db->connect_error);
    }

    // Karakter seti ayarla (Türkçe karakter desteği)
    if (!$db->set_charset(DB_CHARSET)) {
        throw new Exception("Karakter seti hatası: " . $db->error);
    }

    // Timezone ayarla (Türkiye)
    $db->query("SET time_zone = '+03:00'");

} catch (Exception $e) {
    // Güvenlik: Production'da detay gösterme
    error_log("DB Error: " . $e->getMessage());
    die("Veritabanı bağlantı hatası. Lütfen sistem yöneticisine başvurun.");
}

// ===================================
// YARDIMCI FONKSİYONLAR
// ===================================

/**
 * Prepared Statement ile Güvenli Sorgu
 *
 * @param string $query SQL sorgusu
 * @param array $params Parametreler
 * @param string $types Parametre tipleri (s=string, i=integer, d=double)
 * @return mysqli_result|bool
 */
function db_query($query, $params = [], $types = '') {
    global $db;

    if (empty($params)) {
        $result = $db->query($query);
        if (!$result) {
            error_log("SQL Error: " . $db->error . " | Query: " . $query);
        }
        return $result;
    }

    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("SQL Prepare Error: " . $db->error . " | Query: " . $query);
        return false;
    }

    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        error_log("SQL Execute Error: " . $stmt->error . " | Query: " . $query);
        return false;
    }

    return $stmt->get_result();
}

/**
 * Tek Satır Getir
 */
function db_fetch_one($query, $params = [], $types = '') {
    $result = db_query($query, $params, $types);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Tüm Satırları Getir
 */
function db_fetch_all($query, $params = [], $types = '') {
    $result = db_query($query, $params, $types);
    $rows = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

/**
 * Insert İşlemi (Son ID döndürür)
 */
function db_insert($query, $params = [], $types = '') {
    global $db;
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("SQL Insert Error: " . $db->error);
        return false;
    }

    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    if ($stmt->execute()) {
        return $db->insert_id;
    }

    error_log("Insert Execute Error: " . $stmt->error);
    return false;
}

/**
 * Update/Delete İşlemi (Etkilenen satır sayısı döndürür)
 */
function db_execute($query, $params = [], $types = '') {
    global $db;
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("SQL Execute Error: " . $db->error);
        return false;
    }

    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    if ($stmt->execute()) {
        return $stmt->affected_rows;
    }

    error_log("Execute Error: " . $stmt->error);
    return false;
}

/**
 * SQL Escape (Ekstra güvenlik)
 */
function db_escape($string) {
    global $db;
    return $db->real_escape_string($string);
}

/**
 * JSON Response Gönder
 */
function json_response($data, $success = true, $message = '') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Dosya Yükleme Kontrolü
 */
function validate_upload($file, $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'], $max_size = 5242880) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Geçersiz dosya'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Yükleme hatası'];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'Dosya çok büyük (Max: ' . ($max_size / 1024 / 1024) . 'MB)'];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_types)) {
        return ['success' => false, 'error' => 'Geçersiz dosya türü'];
    }

    return ['success' => true];
}

/**
 * Güvenli Dosya Adı Oluştur
 */
function safe_filename($filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);

    // Türkçe karakterleri değiştir
    $turkish = ['ş','Ş','ı','İ','ğ','Ğ','ü','Ü','ö','Ö','ç','Ç'];
    $english = ['s','S','i','I','g','G','u','U','o','O','c','C'];
    $name = str_replace($turkish, $english, $name);

    // Sadece harf, rakam, tire ve alt çizgi bırak
    $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);

    // Benzersiz yap
    $unique = date('Ymd_His') . '_' . substr(md5(uniqid()), 0, 6);

    return $unique . '_' . $name . '.' . $ext;
}

/**
 * Transaction Başlat
 */
function db_begin_transaction() {
    global $db;
    $db->begin_transaction();
}

/**
 * Transaction Commit
 */
function db_commit() {
    global $db;
    $db->commit();
}

/**
 * Transaction Rollback
 */
function db_rollback() {
    global $db;
    $db->rollback();
}

/**
 * Bağlantıyı Kapat
 */
function db_close() {
    global $db;
    if ($db) {
        $db->close();
    }
}

// Script sonunda bağlantıyı otomatik kapat
register_shutdown_function('db_close');

// ===================================
// GÜVENLİK KONTROL
// ===================================

// CSRF Token oluştur
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * CSRF Token Kontrolü
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Admin Yetkisi Kontrolü
 */
function require_admin_auth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: giris.php');
        exit;
    }
}

?>
