<?php
/**
 * Veritabanı Bağlantı Dosyası
 *
 * Bu dosya tüm veritabanı işlemleri için kullanılır.
 * Her türlü hosting ve sunucuda çalışacak şekilde optimize edilmiştir.
 */

// Hata raporlama (Production'da kapatın)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Production'da 0 olmalı

// Veritabanı bağlantı bilgileri
// ÖNEMLİ: Bu bilgileri hosting'inizeuygun şekilde güncelleyin!
define('DB_HOST', 'localhost');          // Genelde localhost
define('DB_USER', 'root');                // Veritabanı kullanıcı adı
define('DB_PASS', '');                    // Veritabanı şifresi
define('DB_NAME', 'egitim');              // Veritabanı adı
define('DB_CHARSET', 'utf8mb4');          // Karakter seti

// Bağlantı oluştur
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Bağlantı kontrolü
    if ($conn->connect_error) {
        throw new Exception("Veritabanı bağlantı hatası: " . $conn->connect_error);
    }

    // Karakter seti ayarla (Türkçe karakter desteği)
    if (!$conn->set_charset(DB_CHARSET)) {
        throw new Exception("Karakter seti hatası: " . $conn->error);
    }

    // Timezone ayarla
    $conn->query("SET time_zone = '+03:00'");

} catch (Exception $e) {
    // Production'da kullanıcıya detay göstermeyin
    die("Bir hata oluştu. Lütfen daha sonra tekrar deneyin.");
    // Development'da: die($e->getMessage());
}

/**
 * Prepared Statement ile Güvenli Sorgu
 *
 * @param string $query SQL sorgusu (? placeholder ile)
 * @param array $params Parametreler
 * @param string $types Parametre tipleri (s=string, i=integer, d=double, b=blob)
 * @return mysqli_result|bool
 */
function db_query($query, $params = [], $types = '') {
    global $conn;

    if (empty($params)) {
        return $conn->query($query);
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("SQL Prepare Error: " . $conn->error);
        return false;
    }

    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Tek Satır Getir
 *
 * @param string $query SQL sorgusu
 * @param array $params Parametreler
 * @param string $types Parametre tipleri
 * @return array|null
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
 *
 * @param string $query SQL sorgusu
 * @param array $params Parametreler
 * @param string $types Parametre tipleri
 * @return array
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
 * Insert Sorgusu (Son eklenen ID'yi döndürür)
 *
 * @param string $query INSERT sorgusu
 * @param array $params Parametreler
 * @param string $types Parametre tipleri
 * @return int|false
 */
function db_insert($query, $params = [], $types = '') {
    global $conn;
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("SQL Insert Error: " . $conn->error);
        return false;
    }

    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    return false;
}

/**
 * Update/Delete Sorgusu (Etkilenen satır sayısını döndürür)
 *
 * @param string $query UPDATE/DELETE sorgusu
 * @param array $params Parametreler
 * @param string $types Parametre tipleri
 * @return int|false
 */
function db_execute($query, $params = [], $types = '') {
    global $conn;
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("SQL Execute Error: " . $conn->error);
        return false;
    }

    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    if ($stmt->execute()) {
        return $stmt->affected_rows;
    }
    return false;
}

/**
 * SQL Injection Koruması (Ekstra güvenlik)
 *
 * @param string $string Temizlenecek string
 * @return string
 */
function db_escape($string) {
    global $conn;
    return $conn->real_escape_string($string);
}

/**
 * Transaction Başlat
 */
function db_begin_transaction() {
    global $conn;
    $conn->begin_transaction();
}

/**
 * Transaction Commit
 */
function db_commit() {
    global $conn;
    $conn->commit();
}

/**
 * Transaction Rollback
 */
function db_rollback() {
    global $conn;
    $conn->rollback();
}

/**
 * Bağlantıyı Kapat
 */
function db_close() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
}

// Script sonunda bağlantıyı otomatik kapat
register_shutdown_function('db_close');
?>
