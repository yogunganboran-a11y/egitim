<?php
session_start();

// Giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: giris.php');
    exit;
}

// Aktif sayfa tespiti
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $page_title ?? 'Admin Panel'; ?> - Eğitim Platformu</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar Backdrop -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-container" id="logoContainer">
                    <img src="assets/img/logo.png" alt="Logo" class="sidebar-logo" onerror="this.style.display='none'">
                </div>
            </div>

            <div class="sidebar-divider"></div>

            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Satışlar</span>
                </a>
                <a href="kullanicilar.php" class="nav-item <?php echo $current_page == 'kullanicilar.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Kullanıcılar</span>
                </a>
                <a href="destek.php" class="nav-item <?php echo $current_page == 'destek.php' ? 'active' : ''; ?>">
                    <i class="fas fa-headset"></i>
                    <span>Destek Talepleri</span>
                    <span class="notification-badge" style="display: none;">0</span>
                </a>
                <a href="faturalar.php" class="nav-item <?php echo $current_page == 'faturalar.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice"></i>
                    <span>Faturalar</span>
                </a>
                <a href="ip-takip.php" class="nav-item <?php echo $current_page == 'ip-takip.php' ? 'active' : ''; ?>">
                    <i class="fas fa-network-wired"></i>
                    <span>IP Takip</span>
                </a>
                <a href="whatsapp.php" class="nav-item <?php echo $current_page == 'whatsapp.php' ? 'active' : ''; ?>">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>
                <a href="telefon.php" class="nav-item <?php echo $current_page == 'telefon.php' ? 'active' : ''; ?>">
                    <i class="fas fa-phone"></i>
                    <span>Telefon</span>
                </a>
                <a href="egitimler.php" class="nav-item <?php echo $current_page == 'egitimler.php' ? 'active' : ''; ?>">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Eğitimler</span>
                </a>
                <a href="ayarlar.php" class="nav-item <?php echo $current_page == 'ayarlar.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Ayarlar</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Çıkış Yap</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>