<?php
require_once 'includes/auth-check.php';

$page_title = 'Eğitimlerim';
$page_css = 'assets/css/egitimler.css';

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <i class="fas fa-graduation-cap"></i>
        Eğitimlerim
    </h1>
    <p>Satın aldığınız eğitimleri görüntüleyin ve devam edin</p>
</div>

<!-- Education Cards -->
<div class="education-container">
    <!-- Tamamlanmış Eğitim -->
    <div class="education-card completed">
        <div class="education-header">
            <div class="education-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <span class="education-status completed">
                <i class="fas fa-check-circle"></i> Tamamlandı
            </span>
        </div>
        
        <div class="education-body">
            <h3>Temel Denizcilik</h3>
            
            <div class="education-info">
                <div class="info-row">
                    <span class="info-label">Kayıt Tarihi:</span>
                    <span class="info-value">01.10.2024</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tamamlanma:</span>
                    <span class="info-value">05.11.2024</span>
                </div>
            </div>
            
            <div class="progress-section">
                <div class="progress-header">
                    <span>İlerleme</span>
                    <span class="progress-percent">100%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%"></div>
                </div>
            </div>
            
            <div class="education-steps">
                <div class="step completed">
                    <i class="fas fa-video"></i>
                    <span>Video İzlendi</span>
                </div>
                <div class="step completed">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Test Tamamlandı</span>
                </div>
            </div>
        </div>
        
        <div class="education-actions">
            <a href="egitim-video.php?id=1" class="btn-review">
                <i class="fas fa-redo"></i> Tekrar İzle
            </a>
        </div>
    </div>
    
    <!-- Devam Eden Eğitim - Video İzlenmedi -->
    <div class="education-card in-progress">
        <div class="education-header">
            <div class="education-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <span class="education-status in-progress">
                <i class="fas fa-spinner fa-pulse"></i> Devam Ediyor
            </span>
        </div>
        
        <div class="education-body">
            <h3>Güvenlik Eğitimi</h3>
            
            <div class="education-info">
                <div class="info-row">
                    <span class="info-label">Kayıt Tarihi:</span>
                    <span class="info-value">15.10.2024</span>
                </div>
            </div>
            
            <div class="progress-section">
                <div class="progress-header">
                    <span>İlerleme</span>
                    <span class="progress-percent">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
            </div>
            
            <div class="education-steps">
                <div class="step active">
                    <i class="fas fa-video"></i>
                    <span>Video Bekleniyor</span>
                </div>
                <div class="step pending">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Test Bekliyor</span>
                </div>
            </div>
        </div>
        
        <div class="education-actions">
            <a href="egitim-video.php?id=2" class="btn-continue">
                <i class="fas fa-play"></i> Videoyu İzle
            </a>
        </div>
    </div>
    
    <!-- Devam Eden Eğitim - Video İzlendi, Test Bekleniyor -->
    <div class="education-card in-progress">
        <div class="education-header">
            <div class="education-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <span class="education-status in-progress">
                <i class="fas fa-spinner fa-pulse"></i> Devam Ediyor
            </span>
        </div>
        
        <div class="education-body">
            <h3>İlk Yardım</h3>
            
            <div class="education-info">
                <div class="info-row">
                    <span class="info-label">Kayıt Tarihi:</span>
                    <span class="info-value">20.09.2024</span>
                </div>
            </div>
            
            <div class="progress-section">
                <div class="progress-header">
                    <span>İlerleme</span>
                    <span class="progress-percent">50%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 50%"></div>
                </div>
            </div>
            
            <div class="education-steps">
                <div class="step completed">
                    <i class="fas fa-video"></i>
                    <span>Video İzlendi</span>
                </div>
                <div class="step active">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Test Bekleniyor</span>
                </div>
            </div>
        </div>
        
        <div class="education-actions">
            <a href="egitim-test.php?id=3" class="btn-continue">
                <i class="fas fa-clipboard-list"></i> Teste Başla
            </a>
        </div>
    </div>
    
    <!-- Başlangıç Aşamasında -->
    <div class="education-card not-started">
        <div class="education-header">
            <div class="education-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <span class="education-status not-started">
                <i class="fas fa-clock"></i> Başlanmadı
            </span>
        </div>
        
        <div class="education-body">
            <h3>Yangınla Mücadele</h3>
            
            <div class="education-info">
                <div class="info-row">
                    <span class="info-label">Kayıt Tarihi:</span>
                    <span class="info-value">05.09.2024</span>
                </div>
            </div>
            
            <div class="progress-section">
                <div class="progress-header">
                    <span>İlerleme</span>
                    <span class="progress-percent">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
            </div>
            
            <div class="education-steps">
                <div class="step pending">
                    <i class="fas fa-video"></i>
                    <span>Video Bekliyor</span>
                </div>
                <div class="step pending">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Test Bekliyor</span>
                </div>
            </div>
        </div>
        
        <div class="education-actions">
            <a href="egitim-video.php?id=4" class="btn-start">
                <i class="fas fa-play-circle"></i> Eğitime Başla
            </a>
        </div>
    </div>
</div>

<!-- Info Box -->
<div class="info-box">
    <div class="info-icon">
        <i class="fas fa-info-circle"></i>
    </div>
    <div class="info-content">
        <h3>Eğitim Süreci</h3>
        <ul>
            <li>Önce eğitim videosunu izleyin, video bitince otomatik olarak teste yönlendirileceksiniz.</li>
            <li>Testi tamamladıktan sonra sertifikanız hazırlanacak ve bildirilecektir.</li>
            <li>Eğitimlerinizi istediğiniz zaman tekrar izleyebilirsiniz.</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>