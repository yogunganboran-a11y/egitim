<?php
require_once 'includes/auth-check.php';

$page_title = 'Ödeme Başarılı';
$page_css = 'assets/css/odeme-sonuc.css';

include 'includes/header.php';

// Eğitim bilgisi
$egitim_adi = isset($_GET['egitim_adi']) ? $_GET['egitim_adi'] : 'Temel Denizcilik';
$fiyat = isset($_GET['fiyat']) ? (float)$_GET['fiyat'] : 299;
?>

<div class="result-container">
    <div class="result-card success">
        <div class="result-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Ödeme Başarılı!</h1>
        <p class="result-message">Eğitim kaydınız tamamlandı</p>
        
        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">Eğitim Adı:</span>
                <span class="detail-value"><?php echo htmlspecialchars($egitim_adi); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Ödeme Tutarı:</span>
                <span class="detail-value amount"><?php echo number_format($fiyat, 2); ?> ₺</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tarih:</span>
                <span class="detail-value"><?php echo date('d.m.Y H:i'); ?></span>
            </div>
        </div>
        
        <div class="success-info">
            <div class="info-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="info-text">
                <h3>Eğitime Başlayabilirsiniz</h3>
                <p>Videoyu izleyip testi tamamlayarak sertifikanızı alabilirsiniz.</p>
            </div>
        </div>
    </div>
    
    <div class="action-buttons">
        <a href="kayit.php" class="btn-secondary">
            <i class="fas fa-shopping-cart"></i>
            Başka Eğitim Al
        </a>
        <a href="egitim-video.php?id=1" class="btn-primary">
            <i class="fas fa-play-circle"></i>
            Eğitime Başla
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
