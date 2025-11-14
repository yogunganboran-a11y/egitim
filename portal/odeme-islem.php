<?php
session_start();

// Kullanıcı kontrolü
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// GET verilerini al
$egitim_id = isset($_GET['egitim_id']) ? (int)$_GET['egitim_id'] : 0;
$egitim_adi = isset($_GET['egitim_adi']) ? $_GET['egitim_adi'] : '';
$fiyat = isset($_GET['fiyat']) ? (float)$_GET['fiyat'] : 0;

if (!$egitim_id || !$fiyat) {
    header('Location: kayit.php');
    exit;
}

$page_title = 'Güvenli Ödeme';
$page_css = 'assets/css/odeme-islem.css';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Öğrenci Portalı</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $page_css; ?>">
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <div class="logo">
                <i class="fas fa-anchor"></i>
                <span>Denizcilik Portal</span>
            </div>
            <h1>Güvenli Ödeme</h1>
        </div>
        
        <div class="payment-info">
            <div class="info-item">
                <span class="info-label">Eğitim:</span>
                <span class="info-value"><?php echo htmlspecialchars($egitim_adi); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Tutar:</span>
                <span class="info-value amount"><?php echo number_format($fiyat, 2); ?> ₺</span>
            </div>
        </div>
        
        <div class="iframe-container">
            <div class="iframe-loading" id="iframeLoading">
                <div class="spinner"></div>
                <p>Ödeme sayfası yükleniyor...</p>
            </div>
            
            <!-- PayTR iFrame buraya yüklenecek -->
            <iframe 
                id="paymentFrame" 
                name="paymentFrame"
                frameborder="0"
                scrolling="yes"
                style="display: none;">
            </iframe>
        </div>
        
        <div class="security-badges">
            <div class="badge">
                <i class="fas fa-lock"></i>
                <span>256-bit SSL</span>
            </div>
            <div class="badge">
                <i class="fas fa-shield-alt"></i>
                <span>3D Secure</span>
            </div>
            <div class="badge">
                <i class="fas fa-credit-card"></i>
                <span>Güvenli Ödeme</span>
            </div>
        </div>
        
        <a href="kayit.php" class="btn-cancel">
            <i class="fas fa-arrow-left"></i>
            Geri Dön
        </a>
    </div>
    
    <script>
        // PayTR iFrame simülasyonu (gerçek entegrasyonda PayTR URL'i kullanılacak)
        const iframe = document.getElementById('paymentFrame');
        const loading = document.getElementById('iframeLoading');
        
        // Simülasyon için timeout
        setTimeout(() => {
            loading.style.display = 'none';
            iframe.style.display = 'block';
            
            // Test için - gerçek entegrasyonda PayTR URL'i gelecek
            iframe.src = 'odeme-test-iframe.php?egitim=<?php echo urlencode($egitim_adi); ?>&fiyat=<?php echo $fiyat; ?>';
        }, 1500);
    </script>
</body>
</html>
