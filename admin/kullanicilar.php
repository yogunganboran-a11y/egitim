<?php
$page_title = 'Kullanıcılar';
include 'includes/header.php';

// Örnek kullanıcı verileri (Gerçek uygulamada veritabanından gelecek)
$users = [
    ['id' => 1, 'date' => '12.11.2025 15:30', 'name' => 'Ahmet', 'surname' => 'Yılmaz', 'tckn' => '12345678901', 'birth_date' => '15.03.1990', 'phone' => '0532 123 4567', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 2, 'date' => '11.11.2025 14:20', 'name' => 'Mehmet', 'surname' => 'Demir', 'tckn' => '98765432109', 'birth_date' => '22.07.1985', 'phone' => '0533 234 5678', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => true, 'completed_test' => false, 'document_type' => 'İleri Navigasyon', 'company' => null],
    ['id' => 3, 'date' => '10.11.2025 16:45', 'name' => 'Ayşe', 'surname' => 'Kaya', 'tckn' => '11122233344', 'birth_date' => '10.12.1992', 'phone' => '0534 345 6789', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
    ['id' => 4, 'date' => '09.11.2025 10:15', 'name' => 'Fatma', 'surname' => 'Şahin', 'tckn' => '55566677788', 'birth_date' => '05.05.1988', 'phone' => '0535 456 7890', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => false, 'completed_test' => false, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 5, 'date' => '08.11.2025 09:30', 'name' => 'Ali', 'surname' => 'Öztürk', 'tckn' => '22233344455', 'birth_date' => '18.06.1995', 'phone' => '0536 567 8901', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => true, 'completed_test' => true, 'document_type' => 'İleri Navigasyon', 'company' => null],
    ['id' => 6, 'date' => '07.11.2025 13:45', 'name' => 'Zeynep', 'surname' => 'Aydın', 'tckn' => '33344455566', 'birth_date' => '25.09.1987', 'phone' => '0537 678 9012', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
    ['id' => 7, 'date' => '06.11.2025 11:20', 'name' => 'Hasan', 'surname' => 'Çelik', 'tckn' => '44455566677', 'birth_date' => '12.02.1991', 'phone' => '0538 789 0123', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => false, 'completed_test' => false, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 8, 'date' => '05.11.2025 14:50', 'name' => 'Elif', 'surname' => 'Yıldız', 'tckn' => '55566677789', 'birth_date' => '30.11.1993', 'phone' => '0539 890 1234', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'İleri Navigasyon', 'company' => null],
    ['id' => 9, 'date' => '04.11.2025 10:30', 'name' => 'Mustafa', 'surname' => 'Arslan', 'tckn' => '66677788890', 'birth_date' => '08.04.1989', 'phone' => '0530 901 2345', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => true, 'completed_test' => false, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
    ['id' => 10, 'date' => '03.11.2025 15:15', 'name' => 'Selin', 'surname' => 'Koç', 'tckn' => '77788899001', 'birth_date' => '19.08.1994', 'phone' => '0531 012 3456', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 11, 'date' => '02.11.2025 12:40', 'name' => 'Emre', 'surname' => 'Aksoy', 'tckn' => '88899900112', 'birth_date' => '14.01.1986', 'phone' => '0532 123 4568', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => false, 'completed_test' => false, 'document_type' => 'İleri Navigasyon', 'company' => null],
    ['id' => 12, 'date' => '01.11.2025 09:25', 'name' => 'Derya', 'surname' => 'Polat', 'tckn' => '99900011223', 'birth_date' => '27.05.1992', 'phone' => '0533 234 5679', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
    ['id' => 13, 'date' => '31.10.2025 16:30', 'name' => 'Burak', 'surname' => 'Erdoğan', 'tckn' => '10011122334', 'birth_date' => '03.12.1990', 'phone' => '0534 345 6780', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 14, 'date' => '30.10.2025 11:10', 'name' => 'Gül', 'surname' => 'Kara', 'tckn' => '11122233445', 'birth_date' => '21.07.1988', 'phone' => '0535 456 7891', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'İleri Navigasyon', 'company' => null],
    ['id' => 15, 'date' => '29.10.2025 13:55', 'name' => 'Kerem', 'surname' => 'Güneş', 'tckn' => '22233344556', 'birth_date' => '16.03.1991', 'phone' => '0536 567 8902', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => false, 'completed_test' => false, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
    ['id' => 16, 'date' => '28.10.2025 10:20', 'name' => 'Merve', 'surname' => 'Avcı', 'tckn' => '33344455667', 'birth_date' => '09.09.1993', 'phone' => '0537 678 9013', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 17, 'date' => '27.10.2025 14:45', 'name' => 'Can', 'surname' => 'Özel', 'tckn' => '44455566778', 'birth_date' => '28.11.1987', 'phone' => '0538 789 0124', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => true, 'completed_test' => false, 'document_type' => 'İleri Navigasyon', 'company' => null],
    ['id' => 18, 'date' => '26.10.2025 09:30', 'name' => 'Deniz', 'surname' => 'Turan', 'tckn' => '55566677890', 'birth_date' => '05.04.1995', 'phone' => '0539 890 1235', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
    ['id' => 19, 'date' => '25.10.2025 15:50', 'name' => 'Ece', 'surname' => 'Bulut', 'tckn' => '66677788901', 'birth_date' => '13.06.1989', 'phone' => '0530 901 2346', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => false, 'completed_test' => false, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 20, 'date' => '24.10.2025 12:15', 'name' => 'Onur', 'surname' => 'Işık', 'tckn' => '77788899012', 'birth_date' => '22.08.1992', 'phone' => '0531 012 3457', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'İleri Navigasyon', 'company' => null],
    ['id' => 21, 'date' => '23.10.2025 10:40', 'name' => 'Gamze', 'surname' => 'Çiçek', 'tckn' => '88899900123', 'birth_date' => '07.02.1990', 'phone' => '0532 123 4569', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
    ['id' => 22, 'date' => '22.10.2025 13:25', 'name' => 'Serkan', 'surname' => 'Demirci', 'tckn' => '99900011234', 'birth_date' => '31.10.1988', 'phone' => '0533 234 5670', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 23, 'date' => '21.10.2025 11:50', 'name' => 'Nisa', 'surname' => 'Kurt', 'tckn' => '10011122345', 'birth_date' => '18.05.1994', 'phone' => '0534 345 6781', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => false, 'completed_test' => false, 'document_type' => 'İleri Navigasyon', 'company' => 'XYZ Maritime'],
    ['id' => 24, 'date' => '20.10.2025 14:35', 'name' => 'Barış', 'surname' => 'Yavuz', 'tckn' => '11122233456', 'birth_date' => '26.09.1991', 'phone' => '0535 456 7892', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
    ['id' => 25, 'date' => '19.10.2025 09:15', 'name' => 'Pınar', 'surname' => 'Taş', 'tckn' => '22233344567', 'birth_date' => '11.01.1987', 'phone' => '0536 567 8903', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => true, 'completed_test' => false, 'document_type' => 'Temel Denizcilik', 'company' => 'Deniz Yıldızı A.Ş.'],
    ['id' => 26, 'date' => '18.10.2025 15:40', 'name' => 'Tolga', 'surname' => 'Özkan', 'tckn' => '33344455678', 'birth_date' => '04.07.1993', 'phone' => '0537 678 9014', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'İleri Navigasyon', 'company' => null],
    ['id' => 27, 'date' => '17.10.2025 12:20', 'name' => 'Sibel', 'surname' => 'Yurt', 'tckn' => '44455566789', 'birth_date' => '29.11.1989', 'phone' => '0538 789 0125', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => false, 'completed_test' => false, 'document_type' => 'Güvenlik Eğitimi', 'company' => 'Mavi Dalga Ltd.'],
    ['id' => 28, 'date' => '16.10.2025 10:55', 'name' => 'Murat', 'surname' => 'Şen', 'tckn' => '55566677801', 'birth_date' => '15.03.1992', 'phone' => '0539 890 1236', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Temel Denizcilik', 'company' => null],
    ['id' => 29, 'date' => '15.10.2025 13:30', 'name' => 'Ceren', 'surname' => 'Aslan', 'tckn' => '66677788912', 'birth_date' => '23.08.1990', 'phone' => '0530 901 2347', 'has_certificate' => false, 'certificate_url' => null, 'watched_video' => true, 'completed_test' => true, 'document_type' => 'İleri Navigasyon', 'company' => 'Kıyı Shipping'],
    ['id' => 30, 'date' => '14.10.2025 11:10', 'name' => 'Oğuz', 'surname' => 'Keskin', 'tckn' => '77788899023', 'birth_date' => '06.12.1988', 'phone' => '0531 012 3458', 'has_certificate' => true, 'certificate_url' => '#', 'watched_video' => true, 'completed_test' => true, 'document_type' => 'Güvenlik Eğitimi', 'company' => null],
];
?>

<link rel="stylesheet" href="assets/css/users.css">

<div class="page-header">
    <div class="page-title">
        <h1>Kullanıcılar <span class="user-count" id="totalUserCount">(30)</span></h1>
        <p class="page-subtitle">Tüm kullanıcıları ve sertifikaları yönetin</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-secondary" onclick="openSMSModal()">
            <i class="fas fa-sms"></i> SMS Gönder
        </button>
        <button class="btn btn-success" onclick="openAddUserModal()">
            <i class="fas fa-user-plus"></i> Müşteri Ekle
        </button>
        <button class="btn btn-primary" onclick="openBulkAddModal()">
            <i class="fas fa-users"></i> Toplu Müşteri Ekle
        </button>
    </div>
</div>

<!-- Arama ve Filtreler -->
<div class="filters-container">
    <div class="search-filter-row">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="userSearch" placeholder="Ad, soyad, TCKN veya telefon ile ara..." onkeyup="searchUsers()">
        </div>
        
        <div class="date-filter">
            <input type="date" class="form-control" id="dateFrom" onchange="filterByDate()">
            <span style="color: #8b9cbc;">-</span>
            <input type="date" class="form-control" id="dateTo" onchange="filterByDate()">
        </div>
    </div>
    
    <div class="filter-buttons-row">
        <div class="btn-group">
            <button class="btn btn-primary btn-sm" onclick="setFilter('all', event)">
                <i class="fas fa-users"></i> Tümü
            </button>
            <button class="btn btn-secondary btn-sm" onclick="setFilter('no-certificate', event)">
                <i class="fas fa-file-alt"></i> Sertifikasız
            </button>
            <button class="btn btn-secondary btn-sm" onclick="openBulkCertificateModal()">
                <i class="fas fa-upload"></i> Toplu Sertifika Yükle
            </button>
            
            <!-- Firmalar Dropdown (Sadece Tümü modunda görünür) -->
            <div class="dropdown-wrapper" id="firmaDropdownWrapper" style="position: relative; display: inline-block;">
                <button class="btn btn-secondary btn-sm" id="firmaFilterBtn" onclick="toggleFirmaDropdown()">
                    <i class="fas fa-building"></i> Firmalar <i class="fas fa-chevron-down" style="margin-left: 0.5rem; font-size: 0.8rem;"></i>
                </button>
                <div class="firma-dropdown" id="firmaDropdown" style="display: none;">
                    <div class="firma-dropdown-item" onclick="selectFirma('all')">
                        <i class="fas fa-building"></i> Tüm Firmalar
                    </div>
                    <div class="firma-dropdown-item" onclick="selectFirma('individual')">
                        <i class="fas fa-user"></i> Bireysel
                    </div>
                    <div class="firma-dropdown-divider"></div>
                    <div class="firma-dropdown-item" onclick="selectFirma('XYZ Maritime')">
                        <i class="fas fa-ship"></i> XYZ Maritime
                    </div>
                    <div class="firma-dropdown-item" onclick="selectFirma('Deniz Yıldızı A.Ş.')">
                        <i class="fas fa-anchor"></i> Deniz Yıldızı A.Ş.
                    </div>
                    <div class="firma-dropdown-item" onclick="selectFirma('Mavi Dalga Ltd.')">
                        <i class="fas fa-water"></i> Mavi Dalga Ltd.
                    </div>
                    <div class="firma-dropdown-item" onclick="selectFirma('Kıyı Shipping')">
                        <i class="fas fa-globe"></i> Kıyı Shipping
                    </div>
                </div>
            </div>
        </div>
        
        <button class="btn btn-excel-export" id="excelDownloadBtn" onclick="downloadExcel()">
            <i class="fas fa-file-excel"></i> Excel İndir
            <span class="excel-count" id="excelCount">30</span>
        </button>
    </div>
    
    <!-- Firma Etiketleri (Sadece sertifikasız filtresinde görünür) -->
    <div id="companyTags" class="company-tags" style="display: none;"></div>
</div>

<!-- Kullanıcı Tablosu -->
<div class="users-table">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>TARİH</th>
                    <th>AD</th>
                    <th>SOYAD</th>
                    <th>TCKN</th>
                    <th>DOĞUM T.</th>
                    <th>TELEFON</th>
                    <th>SERTİFİKA</th>
                    <th>VİDEO</th>
                    <th>TEST</th>
                    <th>BELGE TÜRÜ</th>
                    <th>İŞLEM</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($users as $user): 
                    // Firma renk sınıfını belirle
                    $companyClass = '';
                    if ($user['company']) {
                        switch ($user['company']) {
                            case 'XYZ Maritime':
                                $companyClass = 'user-row-company-1';
                                break;
                            case 'Deniz Yıldızı A.Ş.':
                                $companyClass = 'user-row-company-2';
                                break;
                            case 'Mavi Dalga Ltd.':
                                $companyClass = 'user-row-company-3';
                                break;
                            case 'Kıyı Shipping':
                                $companyClass = 'user-row-company-4';
                                break;
                            default:
                                $companyClass = 'user-row-company-5';
                        }
                    }
                ?>
                <tr class="<?php echo $companyClass; ?>" data-user-id="<?php echo $user['id']; ?>" data-company="<?php echo $user['company'] ? htmlspecialchars($user['company']) : 'individual'; ?>" data-has-certificate="<?php echo $user['has_certificate'] ? '1' : '0'; ?>">
                    <td><?php echo $user['date']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['surname']; ?></td>
                    <td><?php echo $user['tckn']; ?></td>
                    <td><?php echo $user['birth_date']; ?></td>
                    <td><?php echo $user['phone']; ?></td>
                    <td>
                        <?php if ($user['has_certificate']): ?>
                            <a href="<?php echo $user['certificate_url']; ?>" target="_blank" class="pdf-link">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        <?php else: ?>
                            <button class="upload-btn" onclick="uploadCertificate(<?php echo $user['id']; ?>)">
                                <i class="fas fa-upload"></i> Yükle
                            </button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['watched_video']): ?>
                            <span class="status-icon success">
                                <i class="fas fa-check"></i>
                            </span>
                        <?php else: ?>
                            <span class="status-icon error">
                                <i class="fas fa-times"></i>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['completed_test']): ?>
                            <span class="status-icon success">
                                <i class="fas fa-check"></i>
                            </span>
                        <?php else: ?>
                            <span class="status-icon error">
                                <i class="fas fa-times"></i>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $user['document_type']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon info" onclick="openUserDetailModal(<?php echo $user['id']; ?>)" title="Detay">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button class="btn-icon edit" onclick="editUser(<?php echo $user['id']; ?>)" title="Düzenle">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($user['has_certificate']): ?>
                                <button class="btn-icon remove" onclick="removeCertificate(<?php echo $user['id']; ?>)" title="Belgeyi Kaldır">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                            <?php endif; ?>
                            <button class="btn-icon delete" onclick="deleteUser(<?php echo $user['id']; ?>)" title="Sil">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination">
        <button class="pagination-btn" onclick="changePage(1)" id="firstPage">
            <i class="fas fa-angle-double-left"></i>
        </button>
        <button class="pagination-btn" onclick="changePage('prev')" id="prevPage">
            <i class="fas fa-angle-left"></i>
        </button>
        
        <div class="pagination-numbers" id="paginationNumbers">
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <button class="pagination-btn">4</button>
            <button class="pagination-btn">5</button>
        </div>
        
        <button class="pagination-btn" onclick="changePage('next')" id="nextPage">
            <i class="fas fa-angle-right"></i>
        </button>
        <button class="pagination-btn" onclick="changePage('last')" id="lastPage">
            <i class="fas fa-angle-double-right"></i>
        </button>
        
        <span class="pagination-info">
            Sayfa <span id="currentPage">1</span> / <span id="totalPages">5</span>
        </span>
    </div>
</div>

<!-- Toplu Sertifika Yükleme Modal -->
<div class="modal" id="bulkCertificateModal">
    <div class="modal-content large">
        <div class="modal-header">
            <h2>Toplu Sertifika Yükle</h2>
            <button class="close-modal" onclick="closeModal('bulkCertificateModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="form-group">
            <label class="form-label">Belge Türü (Birden fazla seçilebilir)</label>
            <div style="background: linear-gradient(135deg, rgba(30, 45, 68, 0.5) 0%, rgba(26, 41, 66, 0.4) 100%); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; padding: 1rem;">
                <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; cursor: pointer;">
                    <input type="checkbox" class="bulk-cert-type" value="Temel Denizcilik" style="width: 18px; height: 18px; cursor: pointer;">
                    <span style="color: var(--text-primary); font-size: 0.95rem;">Temel Denizcilik</span>
                </label>
                <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; cursor: pointer;">
                    <input type="checkbox" class="bulk-cert-type" value="İleri Navigasyon" style="width: 18px; height: 18px; cursor: pointer;">
                    <span style="color: var(--text-primary); font-size: 0.95rem;">İleri Navigasyon</span>
                </label>
                <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0; cursor: pointer;">
                    <input type="checkbox" class="bulk-cert-type" value="Güvenlik Eğitimi" style="width: 18px; height: 18px; cursor: pointer;">
                    <span style="color: var(--text-primary); font-size: 0.95rem;">Güvenlik Eğitimi</span>
                </label>
            </div>
            <small style="color: #8b9cbc; margin-top: 0.5rem; display: block;">Birden fazla belge türü seçebilirsiniz</small>
        </div>

        <div class="upload-tabs">
            <div class="upload-tab active" onclick="switchUploadTab('pdf', event)">
                <i class="fas fa-file-pdf"></i> PDF İçeriğinden
            </div>
            <div class="upload-tab" onclick="switchUploadTab('tckn', event)">
                <i class="fas fa-id-card"></i> Dosya Adı TCKN
            </div>
        </div>

        <!-- PDF Upload (Varsayılan) -->
        <div id="pdfUploadArea" class="upload-area" onclick="selectPdfFiles()">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Dosyaları buraya sürükleyip bırakın veya tıklayın</p>
            <p class="file-info">PDF içeriğinden TCKN okunacak</p>
            <p class="file-info">Maksimum dosya boyutu: 10MB</p>
        </div>
        <input type="file" id="pdfFileInput" multiple accept=".pdf" style="display: none;" onchange="handlePdfFiles(this.files)">

        <!-- TCKN Upload -->
        <div id="tcknUploadArea" class="upload-area" onclick="selectTcknFiles()" style="display: none;">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Dosyaları buraya sürükleyip bırakın veya tıklayın</p>
            <p class="file-info">Dosya adı TCKN olmalı (örnek: 12345678901.pdf)</p>
            <p class="file-info">Maksimum dosya boyutu: 10MB</p>
        </div>
        <input type="file" id="tcknFileInput" multiple accept=".pdf" style="display: none;" onchange="handleTcknFiles(this.files)">
        
        <!-- Upload Progress -->
        <div id="uploadProgress" class="upload-progress"></div>
    </div>
</div>

<!-- Müşteri Ekle Modal -->
<div class="modal" id="userFormModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Müşteri Ekle</h2>
            <button class="close-modal" onclick="closeModal('userFormModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form onsubmit="saveUser(event)">
            <!-- Ad ve Soyad yan yana -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Ad</label>
                    <input type="text" class="form-control" id="userName" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Soyad</label>
                    <input type="text" class="form-control" id="userSurname" required>
                </div>
            </div>

            <!-- Doğum Tarihi ve Telefon yan yana -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Doğum Tarihi</label>
                    <input type="text" class="form-control" id="userBirthDate" placeholder="GG.AA.YYYY" maxlength="10" inputmode="numeric" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Telefon</label>
                    <input type="tel" class="form-control" id="userPhone" placeholder="5XX XXX XX XX" maxlength="13" inputmode="numeric" required>
                </div>
            </div>

            <!-- Başlık ve Firma Toggle Yan Yana -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: #8b9cbc; font-size: 0.95rem; margin: 0;">Belge Bilgileri</h3>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label class="checkbox-toggle" style="flex-shrink: 0;">
                        <input type="checkbox" id="isCompanyToggle" onchange="toggleCompanyField()">
                        <span class="checkbox-slider"></span>
                    </label>
                    <span style="font-size: 0.9rem; color: #8b9cbc;">Firma mı?</span>
                </div>
            </div>

            <!-- Belge Türü ve TCKN yan yana -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Belge Türü</label>
                    <select class="form-control" id="userDocumentType" required>
                        <option value="">Seçiniz</option>
                        <option value="Temel Denizcilik">Temel Denizcilik</option>
                        <option value="İleri Navigasyon">İleri Navigasyon</option>
                        <option value="Güvenlik Eğitimi">Güvenlik Eğitimi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">TC Kimlik Numarası</label>
                    <input type="text" class="form-control" id="userTCKN" placeholder="11 haneli TC kimlik no" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" required>
                    <small style="color: #8b9cbc; font-size: 0.8rem; margin-top: 0.25rem; display: block;">11 hane</small>
                </div>
            </div>

            <!-- Firma Adı (Koşullu Görünür) -->
            <div class="form-group" id="companyNameGroup" style="display: none;">
                <label class="form-label">Firma Adı</label>
                <input type="text" class="form-control" id="userCompany" placeholder="Firma adını girin">
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Kaydet
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('userFormModal')" style="flex: 1;">
                    <i class="fas fa-times"></i> İptal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toplu Müşteri Ekle Modal -->
<div class="modal" id="bulkAddModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Toplu Müşteri Ekle</h2>
            <button class="close-modal" onclick="closeModal('bulkAddModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div style="margin-bottom: 2rem; text-align: center;">
            <a href="#" class="download-sample" onclick="downloadSampleExcel(); return false;">
                <i class="fas fa-download"></i> Örnek Excel Dosyasını İndir
            </a>
        </div>
        
        <div class="upload-area" onclick="selectBulkExcel()">
            <i class="fas fa-file-excel"></i>
            <p>Excel dosyasını buraya sürükleyip bırakın veya tıklayın</p>
            <p class="file-info">Sadece .xlsx veya .xls formatında</p>
        </div>
        <input type="file" id="bulkExcelInput" accept=".xlsx,.xls" style="display: none;" onchange="handleBulkExcel(event)">
    </div>
</div>

<script src="assets/js/users.js"></script>

<?php include 'includes/footer.php'; ?>