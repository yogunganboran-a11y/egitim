<?php
require_once 'config.php';

// Oturum kontrolü yap
checkAuth();

// Kullanıcı bilgilerini al
$user = getUserInfo();

// Eğer kullanıcı bilgisi yoksa çıkış yap
if (!$user) {
    session_destroy();
    header('Location: giris.php');
    exit;
}
?>
