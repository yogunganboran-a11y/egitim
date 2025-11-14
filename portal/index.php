<?php
require_once 'includes/auth-check.php';

$page_title = 'Dashboard';
$page_css = 'assets/css/dashboard.css';
$page_js = 'assets/js/dashboard.js';

include 'includes/header.php';
?>

<!-- Welcome Section -->
<div class="welcome-section">
    <h1>Hoş Geldin, <?php echo $user['ad'] . ' ' . $user['soyad']; ?>! 👋</h1>
    <p>Eğitimlerinize kaldığınız yerden devam edebilirsiniz.</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <!-- Sertifikalarım -->
    <div class="stat-card">
        <div class="stat-icon certificates">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-content">
            <h3>Sertifikalarım</h3>
            <a href="sertifikalarim.php" class="stat-link">
                Görüntüle (3) <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Eğitimlerim -->
    <div class="stat-card">
        <div class="stat-icon education">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="stat-content">
            <h3>Eğitimlerim</h3>
            <a href="egitimler.php" class="stat-link">
                Görüntüle (5) <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Profilim -->
    <div class="stat-card">
        <div class="stat-icon profile">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="stat-content">
            <h3>Profilim</h3>
            <a href="profil.php" class="stat-link">
                Görüntüle <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Devam Eden Eğitimler -->
<div class="section">
    <div class="section-header">
        <h2>
            <i class="fas fa-play-circle"></i>
            Eğitimlerim
        </h2>
        <a href="egitimler.php" class="btn-view-all">Tümünü Gör <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <div class="education-grid">
        <!-- Eğitim 1 -->
        <div class="education-card">
            <div class="education-header">
                <div class="education-icon">
                    <i class="fas fa-ship"></i>
                </div>
                <div class="education-badge ongoing">
                    <i class="fas fa-circle"></i> Devam Ediyor
                </div>
            </div>
            
            <h3 class="education-title">Temel Denizcilik</h3>
            
            <div class="progress-section">
                <div class="progress-info">
                    <span>İlerleme</span>
                    <span class="progress-percentage">60%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 60%"></div>
                </div>
            </div>
            
            <div class="education-status-list">
                <div class="status-item completed">
                    <div class="status-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="status-text">
                        <span class="status-label">Video</span>
                        <span class="status-value">İzlendi</span>
                    </div>
                    <i class="fas fa-check-circle status-check"></i>
                </div>
                
                <div class="status-item pending">
                    <div class="status-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="status-text">
                        <span class="status-label">Test</span>
                        <span class="status-value">Yapılmadı</span>
                        <span class="status-info">Testi çözmek için tıklayın</span>
                    </div>
                    <i class="fas fa-times-circle status-check"></i>
                </div>
                
                <div class="status-item waiting">
                    <div class="status-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="status-text">
                        <span class="status-label">Sertifika</span>
                        <span class="status-value">Beklemede</span>
                        <span class="status-info">Test tamamlanınca hazır olacak</span>
                    </div>
                    <i class="fas fa-clock status-check"></i>
                </div>
            </div>
            
            <a href="egitim-detay.php?id=1" class="btn-education">
                <i class="fas fa-arrow-right"></i> Teste Geç
            </a>
        </div>
        
        <!-- Eğitim 2 -->
        <div class="education-card">
            <div class="education-header">
                <div class="education-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="education-badge ongoing">
                    <i class="fas fa-circle"></i> Devam Ediyor
                </div>
            </div>
            
            <h3 class="education-title">Güvenlik Eğitimi</h3>
            
            <div class="progress-section">
                <div class="progress-info">
                    <span>İlerleme</span>
                    <span class="progress-percentage">30%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 30%"></div>
                </div>
            </div>
            
            <div class="education-status-list">
                <div class="status-item in-progress">
                    <div class="status-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="status-text">
                        <span class="status-label">Video</span>
                        <span class="status-value">%30 İzlendi</span>
                        <span class="status-info">Videoyu izlemeye devam edin</span>
                    </div>
                    <i class="fas fa-spinner fa-pulse status-check"></i>
                </div>
                
                <div class="status-item locked">
                    <div class="status-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="status-text">
                        <span class="status-label">Test</span>
                        <span class="status-value">Kilitli</span>
                        <span class="status-info">Önce videoyu tamamlayın</span>
                    </div>
                    <i class="fas fa-lock status-check"></i>
                </div>
                
                <div class="status-item locked">
                    <div class="status-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="status-text">
                        <span class="status-label">Sertifika</span>
                        <span class="status-value">Kilitli</span>
                        <span class="status-info">Testi tamamlayın</span>
                    </div>
                    <i class="fas fa-lock status-check"></i>
                </div>
            </div>
            
            <a href="egitim-detay.php?id=2" class="btn-education">
                <i class="fas fa-play"></i> Videoyu Devam Ettir
            </a>
        </div>
    </div>
</div>

<!-- Son Bildirimler -->
<div class="section">
    <div class="section-header">
        <h2>
            <i class="fas fa-bell"></i>
            Son Bildirimler
        </h2>
        <a href="bildirimler.php" class="btn-view-all">Tümünü Gör <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <div class="notifications-container">
        <div class="notification-card">
            <div class="notification-icon-wrapper success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-body">
                <h4>Testiniz başarıyla tamamlandı</h4>
                <p>Temel Denizcilik testinde 85 puan aldınız.</p>
                <span class="notification-time">
                    <i class="fas fa-clock"></i> 2 saat önce
                </span>
            </div>
        </div>
        
        <div class="notification-card">
            <div class="notification-icon-wrapper info">
                <i class="fas fa-video"></i>
            </div>
            <div class="notification-body">
                <h4>Yeni video eklendi</h4>
                <p>Güvenlik Eğitimi için yeni içerik yayınlandı.</p>
                <span class="notification-time">
                    <i class="fas fa-clock"></i> 1 gün önce
                </span>
            </div>
        </div>
        
        <div class="notification-card">
            <div class="notification-icon-wrapper warning">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="notification-body">
                <h4>Sertifikanız hazır</h4>
                <p>Temel Denizcilik sertifikanızı indirebilirsiniz.</p>
                <span class="notification-time">
                    <i class="fas fa-clock"></i> 2 gün önce
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Eğitim Al -->
<div class="cta-banner">
    <div class="cta-content">
        <div class="cta-icon">
            <i class="fas fa-plus-circle"></i>
        </div>
        <div class="cta-text">
            <h3>Yeni Eğitim Almak İster misiniz?</h3>
            <p>Kariyer hedeflerinize uygun eğitimleri keşfedin ve hemen başlayın.</p>
        </div>
    </div>
    <a href="kayit.php" class="btn-cta">
        <i class="fas fa-shopping-cart"></i> Eğitimleri İncele
    </a>
</div>

<?php include 'includes/footer.php'; ?>
