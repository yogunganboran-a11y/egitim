<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo isset($page_title) ? setPageTitle($page_title) : SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/layout.css">
    <link rel="stylesheet" href="<?php echo isset($page_css) ? $page_css : 'assets/css/dashboard.css'; ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">
</head>
<body>
    <!-- Mobil Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <!-- Logo -->
                <div class="header-logo">
                    <img src="assets/img/logo.png" alt="Logo" onerror="this.style.display='none'">
                    <span>Eğitim Portalı</span>
                </div>
            </div>
            
            <div class="top-bar-right">
                <!-- Bildirimler -->
                <div class="notification-icon" id="notificationIcon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                
                <!-- Hamburger Menu (Mobil) -->
                <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <!-- Bildirim Dropdown -->
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-header">
                <h3>Bildirimler</h3>
                <span class="notification-count">3 Yeni</span>
            </div>
            <div class="notification-list">
                <a href="#" class="notification-item unread">
                    <div class="notification-icon-circle success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-content">
                        <p>Testiniz başarıyla tamamlandı</p>
                        <span class="notification-time">2 saat önce</span>
                    </div>
                </a>
                <a href="#" class="notification-item unread">
                    <div class="notification-icon-circle info">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="notification-content">
                        <p>Yeni video eklendi</p>
                        <span class="notification-time">1 gün önce</span>
                    </div>
                </a>
                <a href="#" class="notification-item unread">
                    <div class="notification-icon-circle warning">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="notification-content">
                        <p>Sertifikanız hazır</p>
                        <span class="notification-time">2 gün önce</span>
                    </div>
                </a>
            </div>
            <div class="notification-footer">
                <a href="bildirimler.php">Tümünü Gör</a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="page-content">
