<?php
require_once 'includes/auth-check.php';

$egitim = isset($_GET['egitim']) ? $_GET['egitim'] : 'Eğitim';
$fiyat = isset($_GET['fiyat']) ? (float)$_GET['fiyat'] : 0;

$page_title = 'Güvenli Ödeme';
$page_css = 'assets/css/odeme-test-iframe.css';

include 'includes/header.php';
?>

<div class="payment-wrapper">
    <div class="payment-header-info">
        <h1><i class="fas fa-lock"></i> Güvenli Ödeme</h1>
        <p class="subtitle">Ödemenizi yaparak kayıt etabını tamamlayın</p>
    </div>
    
    <!-- PayTR iFrame Simülasyonu -->
    <div class="paytr-iframe">
        <div class="iframe-header">
            <div class="amount-display">
                <span class="course-name"><?php echo htmlspecialchars($egitim); ?></span>
                <span class="amount"><?php echo number_format($fiyat, 2); ?> ₺</span>
            </div>
        </div>
        
        <div class="iframe-body">
            <div class="form-section">
                <h3>Kart Bilgileri</h3>
                
                <div class="form-group">
                    <label>Kart Numarası</label>
                    <input type="text" placeholder="0000 0000 0000 0000" maxlength="19">
                </div>
                
                <div class="form-group">
                    <label>Kart Üzerindeki İsim</label>
                    <input type="text" placeholder="Ad Soyad">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Son Kullanma Tarihi</label>
                        <input type="text" placeholder="AA/YY" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label>CVV</label>
                        <input type="text" placeholder="***" maxlength="3">
                    </div>
                </div>
            </div>
            
            <div class="button-group">
                <button class="btn-pay" onclick="completePayment()">
                    <i class="fas fa-lock"></i> Güvenli Ödeme Yap
                </button>
                
                <button class="btn-cancel" onclick="cancelPayment()">
                    İptal
                </button>
            </div>
            
            <div class="security-info">
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>256-bit SSL ile Korunmaktadır</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function completePayment() {
        window.location.href = 'odeme-onay.php?egitim_adi=<?php echo urlencode($egitim); ?>&fiyat=<?php echo $fiyat; ?>';
    }
    
    function cancelPayment() {
        window.location.href = 'odeme-basarisiz.php?hata=Kullanıcı ödemeyi iptal etti';
    }
</script>

<?php include 'includes/footer.php'; ?>
