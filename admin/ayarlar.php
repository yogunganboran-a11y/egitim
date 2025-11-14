<?php
$page_title = 'Ayarlar';
include 'includes/header.php';

// Demo admin verileri
$admins = [
    [
        'id' => 1,
        'name' => 'Ahmet Yılmaz',
        'email' => 'ahmet@example.com',
        'role' => 'admin',
        'role_label' => 'Yönetici',
        'status' => 'active',
        'last_login' => '2 saat önce',
        'permissions' => ['dashboard', 'sales', 'customers', 'whatsapp', 'phone', 'courses', 'settings']
    ],
    [
        'id' => 2,
        'name' => 'Mehmet Demir',
        'email' => 'mehmet@example.com',
        'role' => 'moderator',
        'role_label' => 'Personel',
        'status' => 'active',
        'last_login' => '1 gün önce',
        'permissions' => ['dashboard', 'customers', 'whatsapp', 'courses']
    ]
];

$all_permissions = [
    'sales' => 'Satışlar',
    'customers' => 'Müşteriler',
    'ip_tracking' => 'IP Takip',
    'invoices' => 'Faturalar',
    'support' => 'Destek Talepleri',
    'whatsapp' => 'WhatsApp',
    'phone' => 'Telefon',
    'courses' => 'Eğitimler',
    'settings' => 'Ayarlar'
];
?>

<link rel="stylesheet" href="assets/css/ayarlar.css">

<div class="page-header">
    <div>
        <h1><i class="fas fa-cog"></i> Ayarlar</h1>
        <p class="page-subtitle">Sistem ayarlarını yönetin</p>
    </div>
</div>

<div class="settings-tabs">
    <button class="tab-btn active" onclick="changeTab('generalTab')">
        <i class="fas fa-home"></i> Genel Ayarlar
    </button>
    <button class="tab-btn" onclick="changeTab('adminsTab')">
        <i class="fas fa-users-cog"></i> Admin Yönetimi
    </button>
    <button class="tab-btn" onclick="changeTab('ipTab')">
        <i class="fas fa-shield-alt"></i> IP Yönetimi
    </button>
    <button class="tab-btn" onclick="changeTab('apiTab')">
        <i class="fas fa-plug"></i> API Entegrasyonları
    </button>
    <button class="tab-btn" onclick="changeTab('seoTab')">
        <i class="fas fa-chart-line"></i> SEO Ayarları
    </button>
</div>

<!-- Genel Ayarlar -->
<div id="generalTab" class="tab-content active">
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-globe"></i> Site Bilgileri</h3>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label>Meta Başlık <span class="required">*</span></label>
                <input type="text" id="siteName" value="Panel Sistemi" maxlength="60">
                <small>Arama motorlarında gösterilecek başlık (50-60 karakter)</small>
            </div>
            <div class="form-group">
                <label>Meta Açıklama <span class="required">*</span></label>
                <textarea id="siteDescription" rows="3" maxlength="160">Panel sistemi açıklaması</textarea>
                <small>Arama motorlarında gösterilecek açıklama (150-160 karakter)</small>
            </div>
            <div class="form-group">
                <label>Site URL <span class="required">*</span></label>
                <input type="url" id="siteUrl" value="https://example.com">
            </div>
            <div class="form-group">
                <label>İletişim E-posta</label>
                <input type="email" id="contactEmail" value="info@example.com">
            </div>
            <div class="form-group">
                <label>İletişim Telefon</label>
                <input type="tel" id="contactPhone" placeholder="5XXXXXXXXX" maxlength="10" pattern="^5[0-9]{9}$">
                <small>Format: 5XXXXXXXXX (10 rakam, 5 ile başlamalı)</small>
            </div>
            <div class="form-group">
                <label>WhatsApp Numarası</label>
                <input type="tel" id="whatsappPhone" placeholder="5XXXXXXXXX" maxlength="10" pattern="^5[0-9]{9}$">
                <small>Format: 5XXXXXXXXX (10 rakam, 5 ile başlamalı)</small>
            </div>
            <div class="form-group">
                <label>Instagram Kullanıcı Adı</label>
                <input type="text" id="instagramUsername" placeholder="kullaniciadi">
                <small>@ işareti olmadan sadece kullanıcı adı</small>
            </div>
            <div class="form-group">
                <label>Facebook Kullanıcı Adı</label>
                <input type="text" id="facebookUsername" placeholder="kullaniciadi">
                <small>@ işareti olmadan sadece kullanıcı adı</small>
            </div>
        </div>
        
        <div class="form-grid" style="margin-top: 1.5rem;">
            <div class="form-group">
                <label>Site Logosu</label>
                <div class="file-upload-box" onclick="document.getElementById('logoUpload').click()">
                    <i class="fas fa-image"></i>
                    <p style="color: #8b9cbc; margin: 0.5rem 0 0 0;">Logo yüklemek için tıklayın</p>
                    <small style="color: #8b9cbc;">PNG, JPG (Max 2MB)</small>
                    <input type="file" id="logoUpload" accept="image/*" onchange="handleLogoUpload(event)">
                </div>
                <img id="logoPreview" class="file-preview-img" alt="Logo">
            </div>
            <div class="form-group">
                <label>Favicon</label>
                <div class="file-upload-box" onclick="document.getElementById('faviconUpload').click()">
                    <i class="fas fa-star"></i>
                    <p style="color: #8b9cbc; margin: 0.5rem 0 0 0;">Favicon yüklemek için tıklayın</p>
                    <small style="color: #8b9cbc;">ICO, PNG (32x32 veya 64x64)</small>
                    <input type="file" id="faviconUpload" accept="image/*" onchange="handleFaviconUpload(event)">
                </div>
                <img id="faviconPreview" class="file-preview-img" alt="Favicon">
            </div>
        </div>
        
        <div style="margin-top: 1.5rem;">
            <button class="btn-save" onclick="saveGeneralSettings()">
                <i class="fas fa-save"></i> Kaydet
            </button>
        </div>
    </div>
</div>

<!-- Admin Yönetimi -->
<div id="adminsTab" class="tab-content">
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-users"></i> Adminler</h3>
            <button class="btn-add-admin" onclick="openAddAdminModal()">
                <i class="fas fa-plus"></i> Yeni Admin Ekle
            </button>
        </div>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>AD SOYAD</th>
                    <th>E-POSTA</th>
                    <th>YETKİ</th>
                    <th>DURUM</th>
                    <th>SON GİRİŞ</th>
                    <th>İŞLEMLER</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                <tr data-admin-id="<?php echo $admin['id']; ?>">
                    <td><strong style="color: #e9edef;"><?php echo $admin['name']; ?></strong></td>
                    <td><?php echo $admin['email']; ?></td>
                    <td>
                        <span class="role-badge <?php echo $admin['role']; ?>">
                            <i class="fas fa-shield-alt"></i>
                            <?php echo $admin['role_label']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge <?php echo $admin['status']; ?>">
                            <i class="fas fa-circle"></i>
                            <?php echo $admin['status'] === 'active' ? 'Aktif' : 'Pasif'; ?>
                        </span>
                    </td>
                    <td><?php echo $admin['last_login']; ?></td>
                    <td>
                        <button class="btn" onclick="editAdmin(<?php echo $admin['id']; ?>)" style="color: #3b82f6; margin-right: 0.5rem;" title="Düzenle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn" onclick="viewAdminHistory(<?php echo $admin['id']; ?>)" style="color: #8b5cf6; margin-right: 0.5rem;" title="İşlem Geçmişi">
                            <i class="fas fa-history"></i>
                        </button>
                        <button class="btn" onclick="deleteAdmin(<?php echo $admin['id']; ?>)" style="color: #ef4444;" title="Sil">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="help-box">
            <h4><i class="fas fa-info-circle"></i> Yetki Seviyeleri</h4>
            <ul>
                <li><strong>Yönetici:</strong> Tüm yetkilere sahiptir</li>
                <li><strong>Personel:</strong> Belirlenen sayfalara erişim yetkisine sahiptir</li>
            </ul>
        </div>
    </div>
</div>

<!-- IP Yönetimi -->
<div id="ipTab" class="tab-content">
    <!-- IP Whitelist -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-check-circle"></i> IP Whitelist (İzin Verilen IP'ler)</h3>
            <button class="btn-add-admin" onclick="openAddIPModal('whitelist')">
                <i class="fas fa-plus"></i> Yeni IP Ekle
            </button>
        </div>
        
        <p style="color: #8b9cbc; margin-bottom: 1.5rem;">
            Admin paneline sadece aşağıdaki IP adreslerinden erişim sağlanabilir. Güvenlik için kendi IP adresinizi mutlaka ekleyin.
        </p>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>IP ADRESİ</th>
                    <th>AÇIKLAMA</th>
                    <th>EKLENME TARİHİ</th>
                    <th>DURUM</th>
                    <th>İŞLEMLER</th>
                </tr>
            </thead>
            <tbody id="whitelistTable">
                <tr data-ip-id="1">
                    <td><strong style="color: #10b981; font-family: monospace;">192.168.1.100</strong></td>
                    <td>Ofis Bağlantısı</td>
                    <td>10.11.2025</td>
                    <td>
                        <span class="status-badge active">
                            <i class="fas fa-circle"></i> Aktif
                        </span>
                    </td>
                    <td>
                        <button class="btn" onclick="editIP(1, 'whitelist')" style="color: #3b82f6; margin-right: 0.5rem;" title="Düzenle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn" onclick="deleteIP(1, 'whitelist')" style="color: #ef4444;" title="Sil">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr data-ip-id="2">
                    <td><strong style="color: #10b981; font-family: monospace;">203.0.113.45</strong></td>
                    <td>Ev Bağlantısı</td>
                    <td>08.11.2025</td>
                    <td>
                        <span class="status-badge active">
                            <i class="fas fa-circle"></i> Aktif
                        </span>
                    </td>
                    <td>
                        <button class="btn" onclick="editIP(2, 'whitelist')" style="color: #3b82f6; margin-right: 0.5rem;" title="Düzenle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn" onclick="deleteIP(2, 'whitelist')" style="color: #ef4444;" title="Sil">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="help-box">
            <h4><i class="fas fa-info-circle"></i> Önemli Bilgi</h4>
            <p>Şu anki IP adresiniz: <strong style="color: #3b82f6;">203.0.113.45</strong></p>
            <p>IP whitelist aktif olduğunda, sadece listedeki IP adreslerinden admin paneline giriş yapılabilir.</p>
        </div>
    </div>
    
    <!-- IP Blocklist -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-ban"></i> IP Blocklist (Engellenen IP'ler)</h3>
            <button class="btn-add-admin" onclick="openAddIPModal('blocklist')" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #ef4444;">
                <i class="fas fa-plus"></i> IP Engelle
            </button>
        </div>
        
        <p style="color: #8b9cbc; margin-bottom: 1.5rem;">
            Aşağıdaki IP adreslerinden siteye erişim tamamen engellenmiştir.
        </p>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>IP ADRESİ</th>
                    <th>SEBEP</th>
                    <th>EKLENME TARİHİ</th>
                    <th>DURUM</th>
                    <th>İŞLEMLER</th>
                </tr>
            </thead>
            <tbody id="blocklistTable">
                <tr data-ip-id="101">
                    <td><strong style="color: #ef4444; font-family: monospace;">198.51.100.23</strong></td>
                    <td>Brute force saldırısı</td>
                    <td>12.11.2025</td>
                    <td>
                        <span class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                            <i class="fas fa-circle"></i> Engellenmiş
                        </span>
                    </td>
                    <td>
                        <button class="btn" onclick="editIP(101, 'blocklist')" style="color: #3b82f6; margin-right: 0.5rem;" title="Düzenle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn" onclick="deleteIP(101, 'blocklist')" style="color: #ef4444;" title="Sil">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Şüpheli IP'ler -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Şüpheli IP'ler</h3>
        </div>
        
        <p style="color: #f97316; margin-bottom: 1.5rem;">
            <i class="fas fa-info-circle"></i> Aşağıdaki IP adresleri şüpheli aktivite gösterdi. İstediğiniz IP'leri engelleyebilirsiniz.
        </p>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>IP ADRESİ</th>
                    <th>ŞÜPHELI AKTİVİTE</th>
                    <th>BAŞARISIZ GİRİŞ</th>
                    <th>SON AKTİVİTE</th>
                    <th>İŞLEM</th>
                </tr>
            </thead>
            <tbody id="suspiciousTable">
                <tr data-ip-id="201">
                    <td><strong style="color: #f97316; font-family: monospace;">203.0.113.77</strong></td>
                    <td>
                        <span style="background: rgba(249, 115, 22, 0.1); color: #f97316; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">
                            <i class="fas fa-exclamation-triangle"></i> Çoklu başarısız giriş
                        </span>
                    </td>
                    <td>
                        <strong style="color: #ef4444;">15 deneme</strong>
                    </td>
                    <td>2 saat önce</td>
                    <td>
                        <button class="btn" onclick="blockSuspiciousIP(201, '203.0.113.77')" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 0.5rem 1rem; border-radius: 8px;">
                            <i class="fas fa-ban"></i> Engelle
                        </button>
                        <button class="btn" onclick="ignoreSuspiciousIP(201)" style="color: #8b9cbc; margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Yoksay
                        </button>
                    </td>
                </tr>
                <tr data-ip-id="202">
                    <td><strong style="color: #f97316; font-family: monospace;">198.51.100.88</strong></td>
                    <td>
                        <span style="background: rgba(249, 115, 22, 0.1); color: #f97316; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">
                            <i class="fas fa-exclamation-triangle"></i> SQL injection denemesi
                        </span>
                    </td>
                    <td>
                        <strong style="color: #ef4444;">8 deneme</strong>
                    </td>
                    <td>5 saat önce</td>
                    <td>
                        <button class="btn" onclick="blockSuspiciousIP(202, '198.51.100.88')" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 0.5rem 1rem; border-radius: 8px;">
                            <i class="fas fa-ban"></i> Engelle
                        </button>
                        <button class="btn" onclick="ignoreSuspiciousIP(202)" style="color: #8b9cbc; margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Yoksay
                        </button>
                    </td>
                </tr>
                <tr data-ip-id="203">
                    <td><strong style="color: #f97316; font-family: monospace;">192.0.2.100</strong></td>
                    <td>
                        <span style="background: rgba(249, 115, 22, 0.1); color: #f97316; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">
                            <i class="fas fa-exclamation-triangle"></i> Hızlı istek (DDoS)
                        </span>
                    </td>
                    <td>
                        <strong style="color: #ef4444;">200+ istek/dk</strong>
                    </td>
                    <td>1 gün önce</td>
                    <td>
                        <button class="btn" onclick="blockSuspiciousIP(203, '192.0.2.100')" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 0.5rem 1rem; border-radius: 8px;">
                            <i class="fas fa-ban"></i> Engelle
                        </button>
                        <button class="btn" onclick="ignoreSuspiciousIP(203)" style="color: #8b9cbc; margin-left: 0.5rem;">
                            <i class="fas fa-times"></i> Yoksay
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- API Entegrasyonları -->
<div id="apiTab" class="tab-content">
    <!-- Paraşüt Fatura API -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-file-invoice"></i> Paraşüt Fatura API</h3>
        </div>
        <div id="parasutSettings">
            <div class="form-grid">
                <div class="form-group">
                    <label>Account ID <span class="required">*</span></label>
                    <input type="text" id="parasutAccountId" placeholder="Account ID">
                </div>
                <div class="form-group">
                    <label>Company ID <span class="required">*</span></label>
                    <input type="text" id="parasutCompanyId" placeholder="Company ID">
                </div>
                <div class="form-group">
                    <label>KDV Oranı <span class="required">*</span></label>
                    <input type="number" id="parasutKdv" placeholder="18">
                </div>
                <div class="form-group">
                    <label>Kullanıcı Adı <span class="required">*</span></label>
                    <input type="text" id="parasutUsername" placeholder="Kullanıcı adı">
                </div>
                <div class="form-group">
                    <label>Şifre <span class="required">*</span></label>
                    <input type="password" id="parasutPassword" placeholder="Şifre">
                </div>
                <div class="form-group">
                    <label>Client ID <span class="required">*</span></label>
                    <input type="text" id="parasutClientId" placeholder="Client ID">
                </div>
                <div class="form-group">
                    <label>Client Secret <span class="required">*</span></label>
                    <input type="password" id="parasutClientSecret" placeholder="Client Secret">
                </div>
            </div>
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button class="btn-save" onclick="saveAPISettings('parasut')">
                    <i class="fas fa-save"></i> Kaydet
                </button>
                <button class="btn-test" onclick="testAPI('parasut')">
                    <i class="fas fa-vial"></i> Bağlantıyı Test Et
                </button>
            </div>
            <div class="test-result" id="parasutTestResult"></div>
        </div>
    </div>
    
    <!-- NetGSM SMS API -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-sms"></i> NetGSM SMS API</h3>
        </div>
        <div id="netgsmSettings">
            <div class="form-grid">
                <div class="form-group">
                    <label>Kullanıcı Adı <span class="required">*</span></label>
                    <input type="text" id="netgsmUsername" placeholder="NetGSM kullanıcı adınız">
                </div>
                <div class="form-group">
                    <label>Şifre <span class="required">*</span></label>
                    <input type="password" id="netgsmPassword" placeholder="NetGSM şifreniz">
                </div>
                <div class="form-group">
                    <label>Başlık</label>
                    <input type="text" id="netgsmHeader" placeholder="SMS başlığınız">
                </div>
            </div>
            
            <h4 style="color: #3b82f6; margin-top: 2rem; margin-bottom: 1rem;">
                <i class="fas fa-envelope-open-text"></i> SMS Şablonları
            </h4>
            
            <div class="sms-template-item">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h4><i class="fas fa-user-plus"></i> Kayıt Hoşgeldin Mesajı</h4>
                    <label class="toggle-switch">
                        <input type="checkbox" id="smsWelcomeActive" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <textarea id="smsWelcome" placeholder="Hoş geldiniz {name}! Aramıza katıldığınız için teşekkürler."></textarea>
                <small>Değişkenler: {name}, {email}, {phone}</small>
            </div>
            
            <div class="sms-template-item">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h4><i class="fas fa-file-alt"></i> Sertifika Yükleme Mesajı</h4>
                    <label class="toggle-switch">
                        <input type="checkbox" id="smsCertificateActive" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <textarea id="smsCertificate" placeholder="Tebrikler {name}! Sertifikanız başarıyla yüklendi."></textarea>
                <small>Değişkenler: {name}, {course_name}, {certificate_url}</small>
            </div>
            
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button class="btn-save" onclick="saveAPISettings('netgsm')">
                    <i class="fas fa-save"></i> Kaydet
                </button>
                <button class="btn-test" onclick="testAPI('netgsm')">
                    <i class="fas fa-vial"></i> Bağlantıyı Test Et
                </button>
            </div>
            <div class="test-result" id="netgsmTestResult"></div>
        </div>
    </div>
    
    <!-- PayTR Ödeme API -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-credit-card"></i> PayTR Ödeme API</h3>
        </div>
        <div id="paytrSettings">
            <div class="form-grid">
                <div class="form-group">
                    <label>Merchant ID <span class="required">*</span></label>
                    <input type="text" id="paytrMerchantId" placeholder="Merchant ID">
                </div>
                <div class="form-group">
                    <label>Merchant Key <span class="required">*</span></label>
                    <input type="password" id="paytrMerchantKey" placeholder="Merchant Key">
                </div>
                <div class="form-group">
                    <label>Merchant Salt <span class="required">*</span></label>
                    <input type="password" id="paytrMerchantSalt" placeholder="Merchant Salt">
                </div>
                <div class="form-group">
                    <label>Bildirim URL</label>
                    <input type="url" id="paytrNotificationUrl" placeholder="https://example.com/paytr-callback" readonly>
                    <small>PayTR'de bu URL'yi bildirim adresi olarak ayarlayın</small>
                </div>
            </div>
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button class="btn-save" onclick="saveAPISettings('paytr')">
                    <i class="fas fa-save"></i> Kaydet
                </button>
                <button class="btn-test" onclick="testAPI('paytr')">
                    <i class="fas fa-vial"></i> Bağlantıyı Test Et
                </button>
            </div>
            <div class="test-result" id="paytrTestResult"></div>
        </div>
    </div>
    
    <!-- Telefon Ayarları -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-phone-volume"></i> Telefon Ayarları</h3>
        </div>
        <div id="phoneSettings">
            <h4 style="color: #3b82f6; margin-bottom: 1rem;">
                <i class="fas fa-server"></i> SIP Trunk Ayarları
            </h4>
            <div class="form-grid">
                <div class="form-group">
                    <label>SIP Sunucu <span class="required">*</span></label>
                    <input type="text" id="sipServer" placeholder="sip.example.com">
                </div>
                <div class="form-group">
                    <label>Port <span class="required">*</span></label>
                    <input type="number" id="sipPort" value="5060">
                </div>
                <div class="form-group">
                    <label>Kullanıcı Adı <span class="required">*</span></label>
                    <input type="text" id="sipUsername" placeholder="SIP kullanıcı adı">
                </div>
                <div class="form-group">
                    <label>Şifre <span class="required">*</span></label>
                    <input type="password" id="sipPassword" placeholder="SIP şifre">
                </div>
                <div class="form-group">
                    <label>Netmask</label>
                    <input type="text" id="sipNetmask" placeholder="255.255.255.0">
                </div>
                <div class="form-group">
                    <label>Outbound Protocol</label>
                    <select id="sipOutboundProtocol">
                        <option value="udp">UDP</option>
                        <option value="tcp">TCP</option>
                        <option value="tls">TLS</option>
                    </select>
                </div>
            </div>
            
            <div class="form-grid" style="margin-top: 1rem;">
                <div class="form-group">
                    <label class="permission-item">
                        <input type="checkbox" id="allowInboundCalls" checked>
                        <span>Gelen aramalara izin ver</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="permission-item">
                        <input type="checkbox" id="allowOutboundCalls" checked>
                        <span>Giden aramalara izin ver</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="permission-item">
                        <input type="checkbox" id="enableOptionsPing" checked>
                        <span>Options Ping aktif</span>
                    </label>
                </div>
            </div>
            
            <h4 style="color: #8b5cf6; margin-top: 2rem; margin-bottom: 1rem;">
                <i class="fas fa-brain"></i> AI Servisleri
            </h4>
            
            <!-- OpenAI LLM -->
            <div style="background: rgba(139, 92, 246, 0.05); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
                <h4 style="color: #8b5cf6; margin-bottom: 1rem;">
                    <i class="fas fa-robot"></i> OpenAI LLM (Language Model)
                </h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label>OpenAI API Key <span class="required">*</span></label>
                        <input type="password" id="openaiApiKey" placeholder="sk-...">
                        <small>OpenAI hesabınızdan API key alın</small>
                    </div>
                    <div class="form-group">
                        <label>Model Seçimi <span class="required">*</span></label>
                        <select id="openaiModel">
                            <option value="gpt-4o">GPT-4 Turbo (Önerilen)</option>
                            <option value="gpt-4">GPT-4</option>
                            <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                            <option value="gpt-4o-mini">GPT-4 Mini</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Azure Speech Services -->
            <div style="background: rgba(59, 130, 246, 0.05); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
                <h4 style="color: #3b82f6; margin-bottom: 1rem;">
                    <i class="fas fa-microphone"></i> Azure Speech Services (STT & TTS)
                </h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Azure Subscription Key <span class="required">*</span></label>
                        <input type="password" id="azureSpeechKey" placeholder="Azure Speech subscription key">
                    </div>
                    <div class="form-group">
                        <label>Azure Region <span class="required">*</span></label>
                        <input type="text" id="azureSpeechRegion" placeholder="westeurope, eastus, vb." value="westeurope">
                        <small>Örnek: westeurope, eastus, southeastasia</small>
                    </div>
                    <div class="form-group">
                        <label>STT Dil Kodu</label>
                        <input type="text" id="azureSttLanguage" placeholder="tr-TR" value="tr-TR">
                        <small>Konuşmayı tanıma dili</small>
                    </div>
                    <div class="form-group">
                        <label>TTS Ses (Voice Name)</label>
                        <input type="text" id="azureTtsVoice" placeholder="tr-TR-AhmetNeural" value="tr-TR-AhmetNeural">
                        <small>Örnek: tr-TR-AhmetNeural, tr-TR-EmelNeural</small>
                    </div>
                </div>
            </div>
            
            <!-- Voice Agent Settings -->
            <div style="background: rgba(16, 185, 129, 0.05); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
                <h4 style="color: #10b981; margin-bottom: 1rem;">
                    <i class="fas fa-sliders-h"></i> Sesli AI Agent Ayarları
                </h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Ses Hızı (Speech Rate)</label>
                        <select id="voiceSpeechRate">
                            <option value="slow">Yavaş</option>
                            <option value="medium" selected>Normal</option>
                            <option value="fast">Hızlı</option>
                        </select>
                        <small>AI'nın konuşma hızı</small>
                    </div>
                    <div class="form-group">
                        <label>Ses Tonu (Pitch)</label>
                        <select id="voicePitch">
                            <option value="low">Alçak</option>
                            <option value="medium" selected>Normal</option>
                            <option value="high">Yüksek</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Yanıt Gecikmesi (ms)</label>
                        <input type="number" id="responseDelay" value="500" min="0" max="3000" step="100">
                        <small>AI'nın yanıt vermeden önce bekleme süresi</small>
                    </div>
                    <div class="form-group">
                        <label>Sessizlik Süresi (ms)</label>
                        <input type="number" id="silenceTimeout" value="2000" min="500" max="5000" step="100">
                        <small>Konuşma bitmeden önce beklenen sessizlik süresi</small>
                    </div>
                    <div class="form-group">
                        <label>Maksimum Konuşma Süresi (saniye)</label>
                        <input type="number" id="maxSpeechDuration" value="60" min="10" max="300">
                        <small>Tek seferde konuşulabilecek maksimum süre</small>
                    </div>
                    <div class="form-group">
                        <label>Ses Seviyesi</label>
                        <input type="range" id="voiceVolume" min="0" max="100" value="80" style="width: 100%;">
                        <small>AI sesinin yüksekliği (0-100)</small>
                    </div>
                </div>
                
                <div class="form-group" style="margin-top: 1rem;">
                    <label class="permission-item">
                        <input type="checkbox" id="enableBackgroundNoise" checked>
                        <span>Arka plan gürültüsü filtresi aktif</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="permission-item">
                        <input type="checkbox" id="enableEchoCancellation" checked>
                        <span>Eko iptali aktif</span>
                    </label>
                </div>
            </div>
            
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button class="btn-save" onclick="saveAPISettings('phone')">
                    <i class="fas fa-save"></i> Kaydet
                </button>
                <button class="btn-test" onclick="testAPI('phone')">
                    <i class="fas fa-vial"></i> Bağlantıyı Test Et
                </button>
                <button class="btn-ai-agent" onclick="openAIAgentModal('phone')">
                    <i class="fas fa-robot"></i> AI Agent Talimatları
                </button>
            </div>
            <div class="test-result" id="phoneTestResult"></div>
        </div>
    </div>
    
    <!-- WhatsApp Cloud API -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fab fa-whatsapp"></i> WhatsApp Cloud API</h3>
        </div>
        <div id="whatsappSettings">
            <div class="form-grid">
                <div class="form-group">
                    <label>Phone Number ID <span class="required">*</span></label>
                    <input type="text" id="whatsappPhoneNumberId" placeholder="Phone Number ID">
                </div>
                <div class="form-group">
                    <label>Access Token <span class="required">*</span></label>
                    <input type="password" id="whatsappAccessToken" placeholder="Access Token">
                </div>
                <div class="form-group">
                    <label>Business Account ID</label>
                    <input type="text" id="whatsappBusinessId" placeholder="Business Account ID">
                </div>
                <div class="form-group">
                    <label>Client ID</label>
                    <input type="text" id="whatsappClientId" placeholder="Client ID">
                </div>
                <div class="form-group">
                    <label>Client Secret</label>
                    <input type="password" id="whatsappClientSecret" placeholder="Client Secret">
                </div>
                <div class="form-group">
                    <label>Webhook URL</label>
                    <input type="url" id="whatsappWebhookUrl" placeholder="https://example.com/webhook">
                </div>
                <div class="form-group">
                    <label>Verify Token</label>
                    <input type="text" id="whatsappVerifyToken" placeholder="Verify Token">
                </div>
            </div>
            
            <!-- WhatsApp AI Settings -->
            <div style="background: rgba(139, 92, 246, 0.05); padding: 1.5rem; border-radius: 12px; margin-top: 1.5rem;">
                <h4 style="color: #8b5cf6; margin-bottom: 1rem;">
                    <i class="fas fa-robot"></i> WhatsApp AI Ayarları
                </h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label>OpenAI API Key <span class="required">*</span></label>
                        <input type="password" id="whatsappOpenaiKey" placeholder="sk-...">
                        <small>WhatsApp için OpenAI API anahtarı</small>
                    </div>
                    <div class="form-group">
                        <label>Model Seçimi <span class="required">*</span></label>
                        <select id="whatsappOpenaiModel">
                            <option value="gpt-4o">GPT-4 Turbo (Önerilen)</option>
                            <option value="gpt-4">GPT-4</option>
                            <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                            <option value="gpt-4o-mini">GPT-4 Mini</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Maksimum Token</label>
                        <input type="number" id="whatsappMaxTokens" value="500" min="100" max="4000">
                        <small>AI yanıtının maksimum uzunluğu</small>
                    </div>
                    <div class="form-group">
                        <label>Temperature (Yaratıcılık)</label>
                        <input type="number" id="whatsappTemperature" value="0.7" min="0" max="2" step="0.1">
                        <small>0-2 arası (Düşük: Tutarlı, Yüksek: Yaratıcı)</small>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button class="btn-save" onclick="saveAPISettings('whatsapp')">
                    <i class="fas fa-save"></i> Kaydet
                </button>
                <button class="btn-test" onclick="testAPI('whatsapp')">
                    <i class="fas fa-vial"></i> Bağlantıyı Test Et
                </button>
                <button class="btn-ai-agent" onclick="openAIAgentModal('whatsapp')">
                    <i class="fas fa-robot"></i> AI Agent Talimatları
                </button>
            </div>
            <div class="test-result" id="whatsappTestResult"></div>
        </div>
    </div>
</div>

<!-- SEO Ayarları -->
<div id="seoTab" class="tab-content">
    <div class="settings-card">
        <div class="settings-card-header">
            <h3><i class="fas fa-chart-bar"></i> Analiz Kodları</h3>
        </div>
        <div class="form-grid full">
            <div class="form-group">
                <label>Google Analytics Kodu</label>
                <textarea id="googleAnalytics" placeholder="G-XXXXXXXXXX veya UA-XXXXXXXX-X" rows="3"></textarea>
                <small>Google Analytics izleme kimliğinizi girin</small>
            </div>
            <div class="form-group">
                <label>Google Tag Manager Kodu</label>
                <textarea id="googleTagManager" placeholder="GTM-XXXXXXX" rows="3"></textarea>
                <small>Google Tag Manager container ID'nizi girin</small>
            </div>
            <div class="form-group">
                <label>Yandex Metrika Kodu</label>
                <textarea id="yandexMetrika" placeholder="12345678" rows="3"></textarea>
                <small>Yandex Metrika sayaç numaranızı girin</small>
            </div>
            <div class="form-group">
                <label>Bing Webmaster Kodu</label>
                <textarea id="bingWebmaster" placeholder="<meta name='msvalidate.01' content='...' />" rows="3"></textarea>
                <small>Bing Webmaster doğrulama kodunuzu girin</small>
            </div>
        </div>
    </div>
</div>

<!-- Admin Ekleme Modal -->
<div class="modal" id="addAdminModal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h2>Yeni Admin Ekle</h2>
            <button class="close-modal" onclick="closeModal('addAdminModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div style="padding: 1rem 0;">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>Ad Soyad <span class="required">*</span></label>
                <input type="text" id="adminName" placeholder="Ad Soyad">
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>E-posta <span class="required">*</span></label>
                <input type="email" id="adminEmail" placeholder="email@example.com">
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>Şifre <span class="required">*</span></label>
                <input type="password" id="adminPassword" placeholder="En az 6 karakter">
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>Yetki <span class="required">*</span></label>
                <select id="adminRole" onchange="togglePermissions()">
                    <option value="admin">Yönetici</option>
                    <option value="moderator">Personel</option>
                </select>
            </div>
            <div id="permissionsSection" style="display: none; margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 1rem;">Sayfa Yetkileri</label>
                <div class="permissions-grid">
                    <?php foreach ($all_permissions as $key => $label): ?>
                    <div class="permission-item">
                        <input type="checkbox" id="perm_<?php echo $key; ?>" value="<?php echo $key; ?>">
                        <label for="perm_<?php echo $key; ?>"><?php echo $label; ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeModal('addAdminModal')" style="flex: 1;">
                İptal
            </button>
            <button type="button" class="btn-save" onclick="addAdmin()" style="flex: 1;">
                <i class="fas fa-plus"></i> Ekle
            </button>
        </div>
    </div>
</div>

<!-- IP Ekleme/Düzenleme Modal -->
<div class="modal" id="addIPModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 id="ipModalTitle">Yeni IP Ekle</h2>
            <button class="close-modal" onclick="closeModal('addIPModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <input type="hidden" id="ipType">
        <input type="hidden" id="ipEditId">
        
        <div style="padding: 1rem 0;">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>IP Adresi <span class="required">*</span></label>
                <input type="text" id="ipAddress" placeholder="192.168.1.1" pattern="^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$">
                <small>IPv4 formatında girin (örn: 192.168.1.1)</small>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label id="ipDescriptionLabel">Açıklama <span class="required">*</span></label>
                <textarea id="ipDescription" rows="3" placeholder="Bu IP'nin kim/nereye ait olduğunu yazın..."></textarea>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeModal('addIPModal')" style="flex: 1;">
                İptal
            </button>
            <button type="button" class="btn-save" onclick="saveIP()" style="flex: 1;">
                <i class="fas fa-save"></i> Kaydet
            </button>
        </div>
    </div>
</div>

<!-- AI Agent Talimatları Modal -->
<div class="modal ai-agent-modal" id="aiAgentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="aiAgentModalTitle">AI Agent Talimatları</h2>
            <button class="close-modal" onclick="closeModal('aiAgentModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <input type="hidden" id="aiAgentType">
        <div class="ai-instructions-area">
            <h4><i class="fas fa-brain"></i> Sistem Prompt'u</h4>
            <textarea id="systemPrompt" placeholder="AI'nın genel davranışını ve rolünü tanımlayın..." rows="5"></textarea>
            <small style="color: #8b9cbc; display: block; margin-top: 0.5rem;">Örnek: Sen bir müşteri hizmetleri asistanısın. Nazik, yardımsever ve profesyonel ol.</small>
        </div>
        <div class="ai-instructions-area">
            <h4><i class="fas fa-comment-dots"></i> Karşılama Mesajı</h4>
            <textarea id="greetingMessage" placeholder="İlk karşılama mesajını yazın..." rows="3"></textarea>
        </div>
        <div class="ai-instructions-area">
            <h4><i class="fas fa-hand-peace"></i> Veda Mesajı</h4>
            <textarea id="farewellMessage" placeholder="Görüşmeyi bitirirken kullanılacak mesajı yazın..." rows="3"></textarea>
        </div>
        <div class="ai-instructions-area">
            <h4><i class="fas fa-question-circle"></i> Anlayamadım Mesajı</h4>
            <textarea id="fallbackMessage" placeholder="AI anlamadığında söyleyeceği mesajı yazın..." rows="3"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal('aiAgentModal')" style="flex: 1;">
                İptal
            </button>
            <button class="btn-save" onclick="saveAIInstructions()" style="flex: 1;">
                <i class="fas fa-save"></i> Kaydet
            </button>
        </div>
    </div>
</div>

<script src="assets/js/ayarlar.js"></script>
<script>
function togglePermissions() {
    const role = document.getElementById('adminRole').value;
    const permSection = document.getElementById('permissionsSection');
    permSection.style.display = role === 'moderator' ? 'block' : 'none';
}
</script>

<?php include 'includes/footer.php'; ?>