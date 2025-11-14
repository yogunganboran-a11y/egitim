<?php
// Oturum başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site ayarları
define('SITE_NAME', 'Eğitim Portalı');
define('SITE_URL', '/portal');

// Varsayılan timezone
date_default_timezone_set('Europe/Istanbul');

// Hata raporlama (geliştirme aşamasında)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Demo kullanıcı verileri (backend hazır olana kadar)
$demo_users = [
    '12345678901' => [
        'tckn' => '12345678901',
        'ad' => 'Ahmet',
        'soyad' => 'Yılmaz',
        'telefon' => '0532 123 4567',
        'dogum_tarihi' => '15.03.1990',
        'avatar' => null
    ],
    '98765432109' => [
        'tckn' => '98765432109',
        'ad' => 'Ayşe',
        'soyad' => 'Kaya',
        'telefon' => '0533 234 5678',
        'dogum_tarihi' => '22.07.1985',
        'avatar' => null
    ]
];

// Oturum kontrolü fonksiyonu
function checkAuth() {
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        header('Location: giris.php');
        exit;
    }
}

// Kullanıcı bilgilerini al
function getUserInfo() {
    if (isset($_SESSION['user_data'])) {
        return $_SESSION['user_data'];
    }
    return null;
}

// Sayfa başlığı ayarla
function setPageTitle($title) {
    return $title . ' - ' . SITE_NAME;
}
?>
