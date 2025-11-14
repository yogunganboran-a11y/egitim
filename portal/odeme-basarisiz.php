<?php
require_once 'includes/auth-check.php';

$page_title = 'Ödeme Başarısız';
$page_css = 'assets/css/odeme-sonuc.css';

include 'includes/header.php';

$failed_reason_msg = isset($_GET['hata']) ? $_GET['hata'] : 'Ödeme işlemi tamamlanamadı';
?>

<div class="result-container">
    <div class="result-card failed">
        <div class="result-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        
        <h1>Ödeme Başarısız</h1>
        <p class="result-message">İşleminiz tamamlanamadı</p>
        
        <div class="error-details">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="error-text">
                <h3>İşleminiz Tamamlanamadı</h3>
                <p><?php echo htmlspecialchars($failed_reason_msg); ?></p>
            </div>
        </div>
        
        <div class="help-info-compact">
            <p><strong>Ne Yapmalıyım?</strong> Kart bilgilerinizi kontrol edin, yeterli bakiye olduğundan emin olun ve tekrar deneyin.</p>
        </div>
    </div>
    
    <div class="action-buttons">
        <a href="kayit.php" class="btn-primary">
            <i class="fas fa-redo"></i>
            Tekrar Dene
        </a>
        <a href="index.php" class="btn-secondary">
            <i class="fas fa-home"></i>
            Ana Sayfa
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
