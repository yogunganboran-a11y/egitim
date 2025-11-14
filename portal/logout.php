<?php
session_start();

// Oturumu temizle
$_SESSION = array();

// Oturum çerezini sil
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Oturumu yok et
session_destroy();

// Giriş sayfasına yönlendir
header('Location: giris.php');
exit;
?>
