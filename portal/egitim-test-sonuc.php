<?php
require_once 'includes/auth-check.php';

$page_title = 'Test Sonucu';
$page_css = 'assets/css/egitim-test-sonuc.css';

include 'includes/header.php';

$egitim_adi = "Temel Denizcilik";
$dogru_cevap = isset($_GET['dogru']) ? (int)$_GET['dogru'] : 4;
$yanlis_cevap = isset($_GET['yanlis']) ? (int)$_GET['yanlis'] : 1;
$puan = isset($_GET['puan']) ? (int)$_GET['puan'] : 80;
$toplam_soru = $dogru_cevap + $yanlis_cevap;
$gecti = $puan >= 70;

$sertifika_tarihi = date('d.m.Y', strtotime('+7 days'));
$sertifika_saati = '14:00';
?>

<!-- Result Container -->
<div class="result-container">
    <div class="result-card <?php echo $gecti ? 'success' : 'failed'; ?>">
        <div class="result-top">
            <div class="result-left">
                <div class="result-icon">
                    <i class="fas <?php echo $gecti ? 'fa-trophy' : 'fa-times-circle'; ?>"></i>
                </div>
                <div class="result-text">
                    <h1><?php echo $gecti ? 'Tebrikler!' : 'Üzgünüz'; ?></h1>
                    <p class="result-message"><?php echo $gecti ? 'Testi başarıyla tamamladınız' : 'Geçme notunu alamadınız'; ?></p>
                </div>
            </div>
            
            <div class="score-display">
                <span class="score-number"><?php echo $puan; ?></span>
                <span class="score-label">Puan</span>
            </div>
        </div>
        
        <?php if ($gecti): ?>
        <div class="certificate-info">
            <div class="certificate-icon">
                <i class="fas fa-award"></i>
            </div>
            <div class="certificate-text">
                <h3>Sertifikanız Hazırlanıyor</h3>
                <p>
                    <strong><?php echo $egitim_adi; ?></strong> eğitimi için sertifikanız 
                    yaklaşık <strong><?php echo $sertifika_tarihi; ?> - <?php echo $sertifika_saati; ?></strong> 
                    tarihinde "Sertifikalarım" sayfasında görünecektir.
                </p>
            </div>
        </div>
        <?php else: ?>
        <div class="retry-info">
            <i class="fas fa-info-circle"></i>
            <p>Videoyu tekrar izleyerek teste yeniden girebilirsiniz.</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Actions -->
    <div class="result-actions">
        <a href="egitimler.php" class="btn-home">
            <i class="fas fa-home"></i> Eğitimlerim
        </a>
        <?php if (!$gecti): ?>
        <a href="egitim-video.php?id=1" class="btn-retry">
            <i class="fas fa-redo"></i> Tekrar Dene
        </a>
        <?php else: ?>
        <a href="sertifikalarim.php" class="btn-certificate">
            <i class="fas fa-award"></i> Sertifikalarım
        </a>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
