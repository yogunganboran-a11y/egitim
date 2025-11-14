<?php
require_once 'includes/auth-check.php';

$page_title = 'Sertifikalarım';
$page_css = 'assets/css/sertifikalarim.css';

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <i class="fas fa-file-alt"></i>
        Sertifikalarım
    </h1>
    <p>Tamamladığınız eğitimlerin sertifikalarını görüntüleyin ve indirin</p>
</div>

<!-- Certificates Grid -->
<div class="certificates-container">
    <!-- Hazır Sertifika 1 -->
    <div class="certificate-card ready">
        <div class="certificate-header">
            <div class="certificate-icon">
                <i class="fas fa-award"></i>
            </div>
            <span class="certificate-status ready">
                <i class="fas fa-check-circle"></i> Hazır
            </span>
        </div>
        
        <div class="certificate-body">
            <h3>Sertifika Bilgileriniz</h3>
            <div class="certificate-info">
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Temel Denizcilik</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <span>Kayıt Tarihi: 01.10.2024</span>
                </div>
            </div>
        </div>
        
        <div class="certificate-actions">
            <button class="btn-view" onclick="window.open('sertifika-goster.php?id=1', '_blank')">
                <i class="fas fa-eye"></i> Görüntüle
            </button>
            <button class="btn-download" onclick="window.open('sertifika-indir.php?id=1', '_blank')">
                <i class="fas fa-download"></i> İndir
            </button>
        </div>
    </div>
    
    <!-- Hazır Sertifika 2 -->
    <div class="certificate-card ready">
        <div class="certificate-header">
            <div class="certificate-icon">
                <i class="fas fa-award"></i>
            </div>
            <span class="certificate-status ready">
                <i class="fas fa-check-circle"></i> Hazır
            </span>
        </div>
        
        <div class="certificate-body">
            <h3>Sertifika Bilgileriniz</h3>
            <div class="certificate-info">
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>İlk Yardım</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <span>Kayıt Tarihi: 20.09.2024</span>
                </div>
            </div>
        </div>
        
        <div class="certificate-actions">
            <button class="btn-view" onclick="window.open('sertifika-goster.php?id=2', '_blank')">
                <i class="fas fa-eye"></i> Görüntüle
            </button>
            <button class="btn-download" onclick="window.open('sertifika-indir.php?id=2', '_blank')">
                <i class="fas fa-download"></i> İndir
            </button>
        </div>
    </div>
    
    <!-- Hazır Sertifika 3 -->
    <div class="certificate-card ready">
        <div class="certificate-header">
            <div class="certificate-icon">
                <i class="fas fa-award"></i>
            </div>
            <span class="certificate-status ready">
                <i class="fas fa-check-circle"></i> Hazır
            </span>
        </div>
        
        <div class="certificate-body">
            <h3>Sertifika Bilgileriniz</h3>
            <div class="certificate-info">
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Yangınla Mücadele</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <span>Kayıt Tarihi: 05.09.2024</span>
                </div>
            </div>
        </div>
        
        <div class="certificate-actions">
            <button class="btn-view" onclick="window.open('sertifika-goster.php?id=3', '_blank')">
                <i class="fas fa-eye"></i> Görüntüle
            </button>
            <button class="btn-download" onclick="window.open('sertifika-indir.php?id=3', '_blank')">
                <i class="fas fa-download"></i> İndir
            </button>
        </div>
    </div>
    
    <!-- İşlemde Sertifika -->
    <div class="certificate-card processing">
        <div class="certificate-header">
            <div class="certificate-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <span class="certificate-status processing">
                <i class="fas fa-spinner fa-pulse"></i> İşlemde
            </span>
        </div>
        
        <div class="certificate-body">
            <h3>Sertifika Bilgileriniz</h3>
            <div class="certificate-info">
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Güvenlik Eğitimi</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <span>Kayıt Tarihi: 15.10.2024</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Tahmini Hazır Olma: 15.11.2024 - 14:00</span>
                </div>
            </div>
            
            <div class="processing-message">
                <i class="fas fa-info-circle"></i>
                <p>Sertifikanız hazırlanıyor. Yaklaşık <strong>15.11.2024 - 14:00</strong> tarihinde bu alanda görünecektir.</p>
            </div>
        </div>
    </div>
    
    <!-- İşlemde Sertifika 2 -->
    <div class="certificate-card processing">
        <div class="certificate-header">
            <div class="certificate-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <span class="certificate-status processing">
                <i class="fas fa-spinner fa-pulse"></i> İşlemde
            </span>
        </div>
        
        <div class="certificate-body">
            <h3>Sertifika Bilgileriniz</h3>
            <div class="certificate-info">
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Denizde Hayatta Kalma</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <span>Kayıt Tarihi: 10.11.2024</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Tahmini Hazır Olma: 20.11.2024 - 16:30</span>
                </div>
            </div>
            
            <div class="processing-message">
                <i class="fas fa-info-circle"></i>
                <p>Sertifikanız hazırlanıyor. Yaklaşık <strong>20.11.2024 - 16:30</strong> tarihinde bu alanda görünecektir.</p>
            </div>
        </div>
    </div>
</div>

<!-- Info Box -->
<div class="info-box">
    <div class="info-icon">
        <i class="fas fa-lightbulb"></i>
    </div>
    <div class="info-content">
        <h3>Bilgilendirme</h3>
        <ul>
            <li>Hazır olan sertifikaları PDF formatında görüntüleyebilir ve indirebilirsiniz.</li>
            <li>Sertifikalarınız hakkında sorularınız için iletişime geçebilirsiniz.</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>