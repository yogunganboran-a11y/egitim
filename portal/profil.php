<?php
require_once 'includes/auth-check.php';

$page_title = 'Profilim';
$page_css = 'assets/css/profil.css';

include 'includes/header.php';
?>

<!-- Profile Header -->
<div class="profile-header">
    <div class="profile-avatar-large">
        <?php if ($user['avatar']): ?>
            <img src="<?php echo $user['avatar']; ?>" alt="Profil">
        <?php else: ?>
            <i class="fas fa-user"></i>
        <?php endif; ?>
    </div>
    <div class="profile-info">
        <h1><?php echo $user['ad'] . ' ' . $user['soyad']; ?></h1>
        <p class="profile-subtitle">Öğrenci</p>
        <div class="profile-badges">
            <span class="badge-item">
                <i class="fas fa-graduation-cap"></i> 5 Eğitim
            </span>
            <span class="badge-item">
                <i class="fas fa-file-alt"></i> 3 Sertifika
            </span>
        </div>
    </div>
</div>

<!-- Personal Info Section -->
<div class="section">
    <div class="section-header">
        <h2>
            <i class="fas fa-user-circle"></i>
            Kişisel Bilgiler
        </h2>
    </div>
    
    <div class="info-grid">
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="info-content">
                <span class="info-label">Ad Soyad</span>
                <span class="info-value"><?php echo $user['ad'] . ' ' . $user['soyad']; ?></span>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-id-card"></i>
            </div>
            <div class="info-content">
                <span class="info-label">TC Kimlik No</span>
                <span class="info-value">12345678901</span>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-phone"></i>
            </div>
            <div class="info-content">
                <span class="info-label">Telefon</span>
                <span class="info-value"><?php echo $user['telefon']; ?></span>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="info-content">
                <span class="info-label">Doğum Tarihi</span>
                <span class="info-value">15.05.1995</span>
            </div>
        </div>
    </div>
</div>

<!-- Education History -->
<div class="section">
    <div class="section-header">
        <h2>
            <i class="fas fa-graduation-cap"></i>
            Aldığım Eğitimler
        </h2>
    </div>
    
    <div class="education-list">
        <div class="education-item">
            <div class="education-item-icon">
                <i class="fas fa-ship"></i>
            </div>
            <div class="education-item-content">
                <h3>Temel Denizcilik</h3>
                <p class="education-date">
                    <i class="fas fa-calendar"></i> Başlangıç: 01.10.2024
                </p>
            </div>
            <div class="education-item-status completed">
                <i class="fas fa-check-circle"></i>
                <span>Tamamlandı</span>
            </div>
        </div>
        
        <div class="education-item">
            <div class="education-item-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="education-item-content">
                <h3>Güvenlik Eğitimi</h3>
                <p class="education-date">
                    <i class="fas fa-calendar"></i> Başlangıç: 15.10.2024
                </p>
            </div>
            <div class="education-item-status in-progress">
                <i class="fas fa-spinner fa-pulse"></i>
                <span>Devam Ediyor</span>
            </div>
        </div>
        
        <div class="education-item">
            <div class="education-item-icon">
                <i class="fas fa-first-aid"></i>
            </div>
            <div class="education-item-content">
                <h3>İlk Yardım</h3>
                <p class="education-date">
                    <i class="fas fa-calendar"></i> Başlangıç: 20.09.2024
                </p>
            </div>
            <div class="education-item-status completed">
                <i class="fas fa-check-circle"></i>
                <span>Tamamlandı</span>
            </div>
        </div>
    </div>
</div>

<!-- Certificates -->
<div class="section">
    <div class="section-header">
        <h2>
            <i class="fas fa-file-alt"></i>
            Sertifikalarım
        </h2>
    </div>
    
    <div class="certificates-grid">
        <div class="certificate-card">
            <div class="certificate-icon">
                <i class="fas fa-award"></i>
            </div>
            <div class="certificate-content">
                <h3>Temel Denizcilik Sertifikası</h3>
                <p class="certificate-date">
                    <i class="fas fa-calendar"></i> 05.11.2024
                </p>
            </div>
            <div class="certificate-actions">
                <button class="btn-download">
                    <i class="fas fa-download"></i> İndir
                </button>
                <button class="btn-view">
                    <i class="fas fa-eye"></i> Görüntüle
                </button>
            </div>
        </div>
        
        <div class="certificate-card">
            <div class="certificate-icon">
                <i class="fas fa-award"></i>
            </div>
            <div class="certificate-content">
                <h3>İlk Yardım Sertifikası</h3>
                <p class="certificate-date">
                    <i class="fas fa-calendar"></i> 25.10.2024
                </p>
            </div>
            <div class="certificate-actions">
                <button class="btn-download">
                    <i class="fas fa-download"></i> İndir
                </button>
                <button class="btn-view">
                    <i class="fas fa-eye"></i> Görüntüle
                </button>
            </div>
        </div>
        
        <div class="certificate-card">
            <div class="certificate-icon">
                <i class="fas fa-award"></i>
            </div>
            <div class="certificate-content">
                <h3>Yangınla Mücadele Sertifikası</h3>
                <p class="certificate-date">
                    <i class="fas fa-calendar"></i> 10.10.2024
                </p>
            </div>
            <div class="certificate-actions">
                <button class="btn-download">
                    <i class="fas fa-download"></i> İndir
                </button>
                <button class="btn-view">
                    <i class="fas fa-eye"></i> Görüntüle
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment History -->
<div class="section">
    <div class="section-header">
        <h2>
            <i class="fas fa-credit-card"></i>
            Ödeme Geçmişi
        </h2>
    </div>
    
    <div class="table-responsive">
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Eğitim</th>
                    <th>Tutar</th>
                    <th>Fatura</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01.10.2024</td>
                    <td>Temel Denizcilik</td>
                    <td>₺850.00</td>
                    <td>
                        <button class="btn-invoice">
                            <i class="fas fa-file-invoice"></i> İndir
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>15.10.2024</td>
                    <td>Güvenlik Eğitimi</td>
                    <td>₺1,200.00</td>
                    <td>
                        <button class="btn-invoice">
                            <i class="fas fa-file-invoice"></i> İndir
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>20.09.2024</td>
                    <td>İlk Yardım</td>
                    <td>₺650.00</td>
                    <td>
                        <button class="btn-invoice">
                            <i class="fas fa-file-invoice"></i> İndir
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>05.09.2024</td>
                    <td>Yangınla Mücadele</td>
                    <td>₺950.00</td>
                    <td>
                        <button class="btn-invoice">
                            <i class="fas fa-file-invoice"></i> İndir
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Registration Documents -->
<div class="section">
    <div class="section-header">
        <h2>
            <i class="fas fa-file-alt"></i>
            Kayıt Belgeleri
        </h2>
    </div>
    
    <div class="documents-list">
        <div class="document-card">
            <div class="document-icon">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="document-content">
                <h3>Kayıt Formu</h3>
                <p class="document-info">
                    <span><i class="fas fa-calendar"></i> 01.10.2024</span>
                    <span class="document-education">Temel Denizcilik</span>
                </p>
            </div>
            <button class="btn-download-doc">
                <i class="fas fa-download"></i> İndir
            </button>
        </div>
        
        <div class="document-card">
            <div class="document-icon">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="document-content">
                <h3>Kayıt Formu</h3>
                <p class="document-info">
                    <span><i class="fas fa-calendar"></i> 15.10.2024</span>
                    <span class="document-education">Güvenlik Eğitimi</span>
                </p>
            </div>
            <button class="btn-download-doc">
                <i class="fas fa-download"></i> İndir
            </button>
        </div>
        
        <div class="document-card">
            <div class="document-icon">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="document-content">
                <h3>Kayıt Formu</h3>
                <p class="document-info">
                    <span><i class="fas fa-calendar"></i> 20.09.2024</span>
                    <span class="document-education">İlk Yardım</span>
                </p>
            </div>
            <button class="btn-download-doc">
                <i class="fas fa-download"></i> İndir
            </button>
        </div>
        
        <div class="document-card">
            <div class="document-icon">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="document-content">
                <h3>Kayıt Formu</h3>
                <p class="document-info">
                    <span><i class="fas fa-calendar"></i> 05.09.2024</span>
                    <span class="document-education">Yangınla Mücadele</span>
                </p>
            </div>
            <button class="btn-download-doc">
                <i class="fas fa-download"></i> İndir
            </button>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>