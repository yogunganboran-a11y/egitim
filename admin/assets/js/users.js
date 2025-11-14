// Kullanıcılar Sayfası - JavaScript

let currentFilter = 'all'; // 'all' veya 'no-certificate'
let selectedCompany = 'none'; // Seçili firma (Tümü sayfası için) - 'none' = filtre yok (herkes)
let selectedCompanies = []; // Seçili firmalar (Sertifikasız sayfası için)
let currentPage = 1;
let itemsPerPage = 25;

// Firma renkleri
const companyColors = [
    'company-color-1',
    'company-color-2',
    'company-color-3',
    'company-color-4',
    'company-color-5'
];

// Firma dropdown'ını aç/kapa
function toggleFirmaDropdown() {
    const dropdown = document.getElementById('firmaDropdown');
    const isVisible = dropdown.style.display === 'block';
    
    // Tüm açık dropdown'ları kapat
    document.querySelectorAll('.firma-dropdown').forEach(d => d.style.display = 'none');
    
    // Bu dropdown'ı toggle et
    dropdown.style.display = isVisible ? 'none' : 'block';
    
    // Dışarı tıklanınca kapat
    if (!isVisible) {
        setTimeout(() => {
            document.addEventListener('click', closeFirmaDropdown);
        }, 0);
    }
}

// Firma dropdown'ını kapat
function closeFirmaDropdown(e) {
    const dropdown = document.getElementById('firmaDropdown');
    const btn = document.getElementById('firmaFilterBtn');
    
    if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
        dropdown.style.display = 'none';
        document.removeEventListener('click', closeFirmaDropdown);
    }
}

// Firma seç (Tümü sayfası için - dropdown'dan)
function selectFirma(company) {
    selectedCompany = company;
    
    // Dropdown'ı kapat
    document.getElementById('firmaDropdown').style.display = 'none';
    document.removeEventListener('click', closeFirmaDropdown);
    
    // Butonu güncelle
    const btn = document.getElementById('firmaFilterBtn');
    const companyNames = {
        'all': 'Tüm Firmalar',
        'individual': 'Bireysel',
        'XYZ Maritime': 'XYZ Maritime',
        'Deniz Yıldızı A.Ş.': 'Deniz Yıldızı',
        'Mavi Dalga Ltd.': 'Mavi Dalga',
        'Kıyı Shipping': 'Kıyı Shipping'
    };
    
    btn.innerHTML = `<i class="fas fa-building"></i> ${companyNames[company]} <i class="fas fa-chevron-down" style="margin-left: 0.5rem; font-size: 0.8rem;"></i>`;
    
    // Sadece Tümü sayfasında filtrele
    if (currentFilter === 'all') {
        filterUsers();
    }
    
    const displayName = company === 'all' ? 'Tüm firmalar' : 
                        company === 'individual' ? 'Bireysel kullanıcılar' : 
                        company;
    showNotification(`${displayName} gösteriliyor`, 'success');
}

// Kullanıcı sayısını güncelle
function updateUserCount() {
    const rows = document.querySelectorAll('.table tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            visibleCount++;
        }
    });
    
    document.getElementById('totalUserCount').textContent = `(${visibleCount})`;
    document.getElementById('excelCount').textContent = visibleCount;
}

// Excel indir (Sayfa bazlı)
function downloadExcel() {
    if (currentFilter === 'all') {
        // Tümü sayfası - Tüm kullanıcıları indir
        downloadAllUsersExcel();
    } else {
        // Sertifikasız sayfası - Seçili firmaları indir
        downloadNoCertificateExcel();
    }
}

// Tümü sayfası Excel
function downloadAllUsersExcel() {
    const visibleRows = Array.from(document.querySelectorAll('.table tbody tr')).filter(row => {
        return row.style.display !== 'none';
    });
    
    if (visibleRows.length === 0) {
        showNotification('İndirilecek kullanıcı bulunamadı', 'error');
        return;
    }
    
    const data = [];
    
    // Başlıklar
    data.push([
        'TARİH',
        'AD',
        'SOYAD',
        'TCKN',
        'DOĞUM TARİHİ',
        'TELEFON',
        'SERTİFİKA',
        'VİDEO İZLEDİ',
        'TEST TAMAMLANDI',
        'BELGE TÜRÜ',
        'FİRMA'
    ]);
    
    // Kullanıcı verileri
    visibleRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const company = row.getAttribute('data-company');
        const hasCertificate = row.getAttribute('data-has-certificate') === '1';
        
        data.push([
            cells[0].textContent.trim(),
            cells[1].textContent.trim(),
            cells[2].textContent.trim(),
            cells[3].textContent.trim(),
            cells[4].textContent.trim(),
            cells[5].textContent.trim(),
            hasCertificate ? 'Var' : 'Yok',
            cells[7].querySelector('.status-icon.success') ? 'Evet' : 'Hayır',
            cells[8].querySelector('.status-icon.success') ? 'Evet' : 'Hayır',
            cells[9].textContent.trim(),
            company && company !== 'individual' ? company : 'Bireysel'
        ]);
    });
    
    downloadCSV(data, 'Tum-Kullanicilar');
    showNotification(`${visibleRows.length} kullanıcı Excel'e aktarıldı`, 'success');
}

// Sertifikasız sayfası Excel
function downloadNoCertificateExcel() {
    const visibleRows = Array.from(document.querySelectorAll('.table tbody tr')).filter(row => {
        return row.style.display !== 'none' && row.getAttribute('data-has-certificate') === '0';
    });
    
    if (visibleRows.length === 0) {
        showNotification('İndirilecek sertifikasız kullanıcı bulunamadı', 'error');
        return;
    }
    
    const data = [];
    
    // Başlıklar
    data.push([
        'TARİH',
        'AD',
        'SOYAD',
        'TCKN',
        'DOĞUM TARİHİ',
        'TELEFON',
        'VİDEO İZLEDİ',
        'TEST TAMAMLANDI',
        'BELGE TÜRÜ',
        'FİRMA'
    ]);
    
    // Kullanıcı verileri
    visibleRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const company = row.getAttribute('data-company');
        
        data.push([
            cells[0].textContent.trim(),
            cells[1].textContent.trim(),
            cells[2].textContent.trim(),
            cells[3].textContent.trim(),
            cells[4].textContent.trim(),
            cells[5].textContent.trim(),
            cells[7].querySelector('.status-icon.success') ? 'Evet' : 'Hayır',
            cells[8].querySelector('.status-icon.success') ? 'Evet' : 'Hayır',
            cells[9].textContent.trim(),
            company && company !== 'individual' ? company : 'Bireysel'
        ]);
    });
    
    downloadCSV(data, 'Sertifikasiz-Kullanicilar');
    showNotification(`${visibleRows.length} sertifikasız kullanıcı Excel'e aktarıldı`, 'success');
}

// CSV indir
function downloadCSV(data, filename) {
    const csvContent = data.map(row => row.join(',')).join('\n');
    const BOM = '\uFEFF';
    const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
    
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    
    const now = new Date();
    const dateStr = now.toISOString().split('T')[0];
    link.setAttribute('download', `${filename}-${dateStr}.csv`);
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    
    // Drag & Drop işlemleri (TCKN)
    const tcknUploadArea = document.getElementById('tcknUploadArea');
    if (tcknUploadArea) {
        tcknUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            tcknUploadArea.classList.add('dragover');
        });
        
        tcknUploadArea.addEventListener('dragleave', () => {
            tcknUploadArea.classList.remove('dragover');
        });
        
        tcknUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            tcknUploadArea.classList.remove('dragover');
            handleTcknFiles(e.dataTransfer.files);
        });
    }

    // Drag & Drop işlemleri (PDF)
    const pdfUploadArea = document.getElementById('pdfUploadArea');
    if (pdfUploadArea) {
        pdfUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            pdfUploadArea.classList.add('dragover');
        });
        
        pdfUploadArea.addEventListener('dragleave', () => {
            pdfUploadArea.classList.remove('dragover');
        });
        
        pdfUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            pdfUploadArea.classList.remove('dragover');
            handlePdfFiles(e.dataTransfer.files);
        });
    }
});

// Arama fonksiyonu
function searchUsers() {
    const searchTerm = document.getElementById('userSearch').value.toLowerCase();
    const rows = document.querySelectorAll('.users-table tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowCompany = row.getAttribute('data-company');
        const hasCertificate = row.getAttribute('data-has-certificate') === '1';
        
        let shouldShow = text.includes(searchTerm);
        
        // Sertifika filtresi
        if (shouldShow && currentFilter === 'no-certificate') {
            shouldShow = !hasCertificate;
            
            // Firma kontrolü
            if (shouldShow) {
                if (selectedCompanies.length === 0) {
                    shouldShow = !rowCompany || rowCompany === 'individual';
                } else {
                    shouldShow = selectedCompanies.includes(rowCompany);
                }
            }
        } else if (shouldShow && currentFilter === 'all') {
            // Tümü modunda dropdown firma filtresi
            if (selectedCompany === 'none') {
                // Hiçbir filtre yok - herkesi göster
                shouldShow = true;
            } else if (selectedCompany === 'all') {
                // Tüm Firmalar - sadece firma kayıtlı kullanıcılar
                shouldShow = rowCompany && rowCompany !== 'individual';
            } else if (selectedCompany === 'individual') {
                // Bireysel
                shouldShow = !rowCompany || rowCompany === 'individual';
            } else {
                // Belirli bir firma
                shouldShow = rowCompany === selectedCompany;
            }
        }
        
        if (shouldShow) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    updateUserCount();
}

// Tarih filtresi
function filterByDate() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    if (dateFrom && dateTo) {
        showNotification('Tarih aralığı uygulanıyor...', 'success');
        // Gerçek uygulamada AJAX ile filtrelenecek
        loadUsers();
    }
}

// Filtre değiştir
function setFilter(filter, event) {
    currentFilter = filter;
    currentPage = 1;
    
    // Buton aktif durumunu güncelle
    document.querySelectorAll('.filter-buttons-row .btn-group .btn').forEach(btn => {
        if (!btn.id && !btn.classList.contains('dropdown-wrapper')) {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-secondary');
        }
    });
    
    if (event && event.target) {
        event.target.classList.remove('btn-secondary');
        event.target.classList.add('btn-primary');
    }
    
    // Firmalar dropdown'ını göster/gizle
    const firmaDropdownWrapper = document.getElementById('firmaDropdownWrapper');
    
    // Sertifikasız modunda firma etiketlerini göster, dropdown gizle
    const companyTagsContainer = document.getElementById('companyTags');
    if (filter === 'no-certificate') {
        companyTagsContainer.style.display = 'flex';
        loadCompanyTags();
        selectedCompanies = [];
        
        // Firmalar dropdown'ını gizle
        if (firmaDropdownWrapper) {
            firmaDropdownWrapper.style.display = 'none';
        }
    } else {
        companyTagsContainer.style.display = 'none';
        selectedCompanies = [];
        
        // Firmalar dropdown'ını göster
        if (firmaDropdownWrapper) {
            firmaDropdownWrapper.style.display = 'inline-block';
        }
    }
    
    // Firma dropdown'ı sıfırla - Tüm kullanıcıları göster (bireysel + firma)
    selectedCompany = 'none'; // Hiçbir filtre yok
    const btn = document.getElementById('firmaFilterBtn');
    if (btn) {
        btn.innerHTML = `<i class="fas fa-building"></i> Firmalar <i class="fas fa-chevron-down" style="margin-left: 0.5rem; font-size: 0.8rem;"></i>`;
    }
    
    // Kullanıcıları filtrele
    filterUsers();
}

// Kullanıcıları filtrele (sertifika + firma)
function filterUsers() {
    const rows = document.querySelectorAll('.table tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const rowCompany = row.getAttribute('data-company');
        const hasCertificate = row.getAttribute('data-has-certificate') === '1';
        
        let shouldShow = true;
        
        // Sertifika filtresi
        if (currentFilter === 'no-certificate') {
            shouldShow = !hasCertificate;
            
            // Sertifikasız modunda firma kontrolü
            if (shouldShow) {
                if (selectedCompanies.length === 0) {
                    // Hiç firma seçilmemişse sadece bireysel göster
                    shouldShow = !rowCompany || rowCompany === 'individual';
                } else {
                    // Seçili firmaları göster
                    shouldShow = selectedCompanies.includes(rowCompany);
                }
            }
        } else {
            // Tümü modunda dropdown firma filtresi
            if (selectedCompany === 'none') {
                // Hiçbir filtre yok - herkesi göster
                shouldShow = true;
            } else if (selectedCompany === 'all') {
                // Tüm Firmalar seçili - SADECE firma kayıtlı kullanıcıları göster
                shouldShow = rowCompany && rowCompany !== 'individual';
            } else if (selectedCompany === 'individual') {
                // Bireysel seçili
                shouldShow = !rowCompany || rowCompany === 'individual';
            } else {
                // Belirli bir firma seçili
                shouldShow = rowCompany === selectedCompany;
            }
        }
        
        if (shouldShow) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Kullanıcı sayısını güncelle
    updateUserCount();
}

// Firma etiketlerini yükle
function loadCompanyTags() {
    const companies = [
        'ABC Denizcilik',
        'XYZ Maritime',
        'Deniz Yıldızı A.Ş.',
        'Mavi Dalga Ltd.',
        'Kıyı Shipping'
    ];
    
    const container = document.getElementById('companyTags');
    container.innerHTML = '';
    
    companies.forEach((company, index) => {
        const colorClass = companyColors[index % companyColors.length];
        const tag = document.createElement('div');
        tag.className = `company-tag ${colorClass}`;
        tag.setAttribute('data-company', company);
        tag.setAttribute('data-color-index', index + 1);
        tag.innerHTML = `
            <i class="fas fa-building"></i>
            <span>${company}</span>
        `;
        tag.onclick = () => toggleCompany(company, tag, index + 1);
        container.appendChild(tag);
    });
}

// Firma seçimini değiştir
function toggleCompany(company, element, colorIndex) {
    const index = selectedCompanies.indexOf(company);
    
    if (index > -1) {
        // Zaten seçili, kaldır
        selectedCompanies.splice(index, 1);
        element.classList.remove('active');
    } else {
        // Seçili değil, ekle
        selectedCompanies.push(company);
        element.classList.add('active');
    }
    
    // Kullanıcıları filtrele
    filterUsers();
    
    showNotification(
        selectedCompanies.length === 0 ? 'Sadece bireysel kullanıcılar gösteriliyor' : 
        `${selectedCompanies.length} firma seçili`, 
        'info'
    );
}

// Kullanıcıları yükle
function loadUsers() {
    const rows = document.querySelectorAll('.users-table tbody tr');
    
    rows.forEach(row => {
        const hasCertificate = row.getAttribute('data-has-certificate') === '1';
        const rowCompany = row.getAttribute('data-company');
        
        // Önce tüm renkleri temizle
        for (let i = 1; i <= 5; i++) {
            row.classList.remove(`user-row-company-${i}`);
        }
        
        // Filtre kontrolü
        if (currentFilter === 'no-certificate') {
            // Sertifikası olanlari gizle
            if (hasCertificate) {
                row.style.display = 'none';
                return;
            }
            
            // Sertifikası yok
            if (!rowCompany) {
                // Firma olmayan - her zaman göster
                row.style.display = '';
            } else {
                // Firmaya kayıtlı
                const selectedCompany = selectedCompanies.find(c => c.name === rowCompany);
                if (selectedCompany) {
                    // Bu firma seçiliyse göster ve renklendir
                    row.style.display = '';
                    row.classList.add(`user-row-company-${selectedCompany.colorIndex}`);
                } else {
                    // Bu firma seçili değilse gizle
                    row.style.display = 'none';
                }
            }
        } else {
            // Tümü - hepsini göster
            row.style.display = '';
        }
    });
    
    updatePagination();
}

// Pagination güncelle
function updatePagination() {
    const rows = document.querySelectorAll('.users-table tbody tr');
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    const totalItems = visibleRows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    // Excel butonundaki sayıyı güncelle
    const excelCount = document.getElementById('excelCount');
    if (excelCount && currentFilter === 'no-certificate') {
        excelCount.textContent = totalItems;
    }
    
    // Toplam sayfa sayısını güncelle
    document.getElementById('totalPages').textContent = totalPages;
    
    // Mevcut sayfa sınırını kontrol et
    if (currentPage > totalPages) {
        currentPage = Math.max(1, totalPages);
    }
    
    document.getElementById('currentPage').textContent = currentPage;
    
    // Satırları sayfalara göre göster/gizle
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    
    visibleRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Pagination butonlarını güncelle
    updatePaginationButtons();
}

// Toplu sertifika yükleme modalı aç
function openBulkCertificateModal() {
    document.getElementById('bulkCertificateModal').classList.add('active');
    
    // Belge türü kontrolü - tek belge varsa otomatik seç
    const documentTypeSelect = document.getElementById('bulkDocumentType');
    if (documentTypeSelect.options.length === 2) { // "Seçiniz" + 1 belge
        documentTypeSelect.selectedIndex = 1;
    }
    
    switchUploadTab('tckn');
}

// Yükleme sekmesi değiştir
function switchUploadTab(tab, event) {
    // Tab butonlarını güncelle
    document.querySelectorAll('.upload-tab').forEach(t => {
        t.classList.remove('active');
    });
    
    if (event && event.target) {
        event.target.classList.add('active');
    }
    
    // İçeriği güncelle
    const tcknArea = document.getElementById('tcknUploadArea');
    const pdfArea = document.getElementById('pdfUploadArea');
    
    if (tab === 'tckn') {
        tcknArea.style.display = 'block';
        pdfArea.style.display = 'none';
    } else {
        tcknArea.style.display = 'none';
        pdfArea.style.display = 'block';
    }
}

// Dosya seçimi (TCKN)
function selectTcknFiles() {
    document.getElementById('tcknFileInput').click();
}

// Dosya seçimi (PDF)
function selectPdfFiles() {
    document.getElementById('pdfFileInput').click();
}

// Dosyalar seçildiğinde (TCKN)
function handleTcknFiles(files) {
    uploadCertificates(files, 'tckn');
}

// Dosyalar seçildiğinde (PDF)
function handlePdfFiles(files) {
    uploadCertificates(files, 'pdf');
}

// Sertifikaları yükle
function uploadCertificates(files, type) {
    const documentType = document.getElementById('bulkDocumentType').value;
    
    if (!documentType) {
        showNotification('Lütfen belge türü seçin!', 'error');
        return;
    }
    
    const progressDiv = document.getElementById('uploadProgress');
    progressDiv.classList.add('active');
    progressDiv.innerHTML = '';
    
    Array.from(files).forEach((file, index) => {
        const progressItem = document.createElement('div');
        progressItem.className = 'progress-item';
        progressItem.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span class="filename">${file.name}</span>
            <span class="status">Yükleniyor...</span>
        `;
        progressDiv.appendChild(progressItem);
        
        // Simüle edilmiş yükleme
        setTimeout(() => {
            progressItem.querySelector('.status').textContent = 'Tamamlandı';
            progressItem.querySelector('i').style.color = '#10b981';
            
            // Son dosya yüklendiyse
            if (index === files.length - 1) {
                setTimeout(() => {
                    showNotification(`${files.length} sertifika başarıyla yüklendi`, 'success');
                    closeModal('bulkCertificateModal');
                    location.reload();
                }, 500);
            }
        }, (index + 1) * 500);
    });
}


// Müşteri ekle modalı aç
function openAddUserModal() {
    document.getElementById('userFormModal').classList.add('active');
}

// Müşteri kaydet
function saveUser(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('userName').value,
        surname: document.getElementById('userSurname').value,
        documentType: document.getElementById('userDocumentType').value,
        company: document.getElementById('userCompany').value,
        price: document.getElementById('userPrice').value,
        phone: document.getElementById('userPhone').value
    };
    
    // Gerçek uygulamada AJAX ile kaydedilecek
    console.log('Yeni müşteri:', formData);
    
    showNotification('Müşteri başarıyla eklendi', 'success');
    closeModal('userFormModal');
    loadUsers();
}

// Toplu müşteri ekle modalı aç
function openBulkAddModal() {
    document.getElementById('bulkAddModal').classList.add('active');
}

// Örnek Excel indir
function downloadSampleExcel() {
    showNotification('Örnek Excel dosyası indiriliyor...', 'success');
    // Gerçek uygulamada örnek Excel dosyası indirilecek
}

// Toplu Excel yükle
function selectBulkExcel() {
    document.getElementById('bulkExcelInput').click();
}

function handleBulkExcel(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    showNotification('Excel dosyası işleniyor...', 'success');
    
    // Simüle edilmiş işleme
    setTimeout(() => {
        showNotification('25 müşteri başarıyla eklendi', 'success');
        closeModal('bulkAddModal');
        location.reload();
    }, 2000);
}

// Sertifikasız kullanıcıları Excel'e aktar
function exportNoCertificateUsers() {
    showNotification('Excel dosyası hazırlanıyor...', 'success');
    // Gerçek uygulamada Excel oluşturulup indirilecek
}

// Sertifika yükle (tekil)
function uploadCertificate(userId) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.pdf';
    input.onchange = (e) => {
        const file = e.target.files[0];
        if (file) {
            showNotification('Sertifika yükleniyor...', 'success');
            // Gerçek uygulamada AJAX ile yüklenecek
            setTimeout(() => {
                showNotification('Sertifika başarıyla yüklendi', 'success');
                // Sayfayı yenile veya sadece o satırı güncelle
                location.reload();
            }, 1000);
        }
    };
    input.click();
}

// Sertifikayı görüntüle
function viewCertificate(url) {
    window.open(url, '_blank');
}

// Sertifikayı kaldır
function removeCertificate(userId) {
    if (confirm('Sertifikayı kaldırmak istediğinizden emin misiniz?')) {
        showNotification('Sertifika kaldırılıyor...', 'success');
        // Gerçek uygulamada AJAX ile kaldırılacak
        setTimeout(() => {
            showNotification('Sertifika başarıyla kaldırıldı', 'success');
            location.reload();
        }, 500);
    }
}

// Kullanıcı düzenle
function editUser(userId) {
    // Gerçek uygulamada kullanıcı bilgileri AJAX ile yüklenecek
    document.getElementById('userName').value = 'Ahmet';
    document.getElementById('userSurname').value = 'Yılmaz';
    document.getElementById('userPhone').value = '0532 123 4567';
    document.getElementById('userDocumentType').value = 'Temel Denizcilik';
    document.getElementById('userPrice').value = '1500';
    
    document.getElementById('userFormModal').classList.add('active');
}

// Kullanıcı sil
function deleteUser(userId) {
    if (confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')) {
        showNotification('Kullanıcı siliniyor...', 'success');
        // Gerçek uygulamada AJAX ile silinecek
        setTimeout(() => {
            showNotification('Kullanıcı başarıyla silindi', 'success');
            location.reload();
        }, 500);
    }
}

// Modal kapat
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    
    // Upload progress'i sıfırla
    const progressDiv = document.getElementById('uploadProgress');
    if (progressDiv) {
        progressDiv.classList.remove('active');
        progressDiv.innerHTML = '';
    }
}

// Sayfa değiştir
function changePage(page) {
    const totalPages = parseInt(document.getElementById('totalPages').textContent);
    
    if (page === 'prev') {
        if (currentPage > 1) currentPage--;
    } else if (page === 'next') {
        if (currentPage < totalPages) currentPage++;
    } else if (page === 'last') {
        currentPage = totalPages;
    } else if (typeof page === 'number') {
        currentPage = page;
    }
    
    // Kullanıcıları tekrar yükle (sayfalama uygula)
    loadUsers();
}

// Pagination butonlarını güncelle
function updatePaginationButtons() {
    const totalPages = parseInt(document.getElementById('totalPages').textContent);
    
    // Önceki/Sonraki butonlarını devre dışı bırak
    document.getElementById('firstPage').disabled = currentPage === 1;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
    document.getElementById('lastPage').disabled = currentPage === totalPages;
    
    // Sayfa numaralarını oluştur
    const paginationNumbers = document.getElementById('paginationNumbers');
    paginationNumbers.innerHTML = '';
    
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        const btn = document.createElement('button');
        btn.className = 'pagination-btn';
        if (i === currentPage) btn.classList.add('active');
        btn.textContent = i;
        btn.onclick = () => changePage(i);
        paginationNumbers.appendChild(btn);
    }
}

// Modal dışına tıklayınca kapat
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});
// Doğum tarihi otomatik nokta ekleme
document.addEventListener('DOMContentLoaded', function() {
    const birthDateInput = document.getElementById('userBirthDate');
    if (birthDateInput) {
        birthDateInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Sadece rakamlar
            if (value.length >= 2) {
                value = value.substring(0, 2) + '.' + value.substring(2);
            }
            if (value.length >= 5) {
                value = value.substring(0, 5) + '.' + value.substring(5);
            }
            e.target.value = value.substring(0, 10); // Max 10 karakter
        });
    }

    // Telefon formatı (5XX XXX XX XX)
    const phoneInput = document.getElementById('userPhone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Sadece rakamlar
            
            // 5 ile başlamalı
            if (value.length > 0 && value.charAt(0) !== '5') {
                value = value.substring(1);
            }
            
            // Format: 5XX XXX XX XX
            if (value.length > 0) {
                let formatted = value.charAt(0);
                if (value.length > 1) formatted += value.substring(1, 3);
                if (value.length > 3) formatted += ' ' + value.substring(3, 6);
                if (value.length > 6) formatted += ' ' + value.substring(6, 8);
                if (value.length > 8) formatted += ' ' + value.substring(8, 10);
                e.target.value = formatted;
            }
        });
    }
});

// Firma toggle
function toggleCompanyField() {
    const toggle = document.getElementById('isCompanyToggle');
    const companyInput = document.getElementById('userCompany');
    if (toggle && companyInput) {
        companyInput.style.display = toggle.checked ? 'block' : 'none';
        if (!toggle.checked) {
            companyInput.value = '';
        }
    }
}

// Detay modalını aç
function openUserDetailModal(userId) {
    // Backend'den kullanıcı detayını çek (şimdilik demo)
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Kullanıcı Detayları</h2>
                <button class="close-modal" onclick="this.closest('.modal').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem; color: #3b82f6;">Kullanıcı Bilgileri</h3>
                <div style="display: grid; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <p><strong>Ad Soyad:</strong> Ahmet Yılmaz</p>
                    <p><strong>TCKN:</strong> 12345678901</p>
                    <p><strong>Doğum Tarihi:</strong> 15.03.1990</p>
                    <p><strong>Telefon:</strong> 0532 123 4567</p>
                </div>
                
                <h3 style="margin-bottom: 1rem; color: #3b82f6;">Eğitim Bilgileri</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="background: rgba(59, 130, 246, 0.1); padding: 1rem; border-radius: 8px;">
                        <p><strong>Eğitim:</strong> Temel Denizcilik</p>
                        <p><strong>Kayıt Tarihi:</strong> 12.11.2025</p>
                        <p><strong>Kayıt Belgesi:</strong> <i class="fas fa-file-pdf" style="color: #ef4444;"></i></p>
                        <p><strong>Sertifika:</strong> <i class="fas fa-certificate" style="color: #10b981;"></i></p>
                        <p><strong>Fatura:</strong> <i class="fas fa-file-invoice" style="color: #3b82f6;"></i></p>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// SMS Gönder modalını aç
function openSMSModal() {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.id = 'smsModal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Toplu SMS Gönder</h2>
                <button class="close-modal" onclick="this.closest('.modal').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding: 1.5rem;">
                <div id="smsStep1" style="display: block;">
                    <h3 style="margin-bottom: 1rem;">Alıcı Seçimi</h3>
                    <div class="form-group">
                        <label><input type="radio" name="receiverType" value="company" checked> Firma</label>
                        <label style="margin-left: 1rem;"><input type="radio" name="receiverType" value="individual"> Bireysel</label>
                    </div>
                    <div id="companySelection" style="margin-top: 1rem;">
                        <select class="form-control" multiple style="height: 150px;">
                            <option>XYZ Maritime (5 kişi)</option>
                            <option>Deniz Yıldızı A.Ş. (3 kişi)</option>
                            <option>Mavi Dalga Ltd. (4 kişi)</option>
                            <option>Kıyı Shipping (2 kişi)</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" onclick="showSMSStep2()" style="margin-top: 1rem; width: 100%;">
                        <i class="fas fa-arrow-right"></i> İlerle
                    </button>
                </div>
                
                <div id="smsStep2" style="display: none;">
                    <h3 style="margin-bottom: 1rem;">Mesaj İçeriği</h3>
                    <div class="form-group">
                        <label class="form-label">Mesaj (PHP kodları kullanılabilir)</label>
                        <textarea class="form-control" rows="6" placeholder="Merhaba {Ad} {Soyad}, ...">{Ad} {Soyad}, eğitiminiz için...</textarea>
                        <small style="color: #8b9cbc; display: block; margin-top: 0.5rem;">
                            Kullanılabilir değişkenler: {Ad}, {Soyad}, {TCKN}, {Telefon}, {EgitimAdi}
                        </small>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button class="btn btn-secondary" onclick="showSMSStep1()" style="flex: 1;">
                            <i class="fas fa-arrow-left"></i> Geri
                        </button>
                        <button class="btn btn-success" onclick="sendSMS()" style="flex: 1;">
                            <i class="fas fa-paper-plane"></i> Gönder
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function showSMSStep2() {
    document.getElementById('smsStep1').style.display = 'none';
    document.getElementById('smsStep2').style.display = 'block';
}

function showSMSStep1() {
    document.getElementById('smsStep1').style.display = 'block';
    document.getElementById('smsStep2').style.display = 'none';
}

function sendSMS() {
    showNotification('SMS gönderiliyor...', 'info');
    setTimeout(() => {
        showNotification('SMS başarıyla gönderildi', 'success');
        document.getElementById('smsModal').remove();
    }, 1500);
}
