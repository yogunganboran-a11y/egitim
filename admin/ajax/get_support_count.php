<?php
session_start();

// Giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['count' => 0]);
    exit;
}

// Gerçek uygulamada veritabanından çekilecek
// Şimdilik sabit değer dönüyoruz
$newRequestsCount = 4; // Demo için

header('Content-Type: application/json');
echo json_encode(['count' => $newRequestsCount]);
?>