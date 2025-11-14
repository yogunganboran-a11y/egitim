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
    const totalPages = totalItems > 0 ? Math.ceil(totalItems / itemsPerPage) : 0;

    // Excel butonundaki sayıyı güncelle
    const excelCount = document.getElementById('excelCount');
    if (excelCount && currentFilter === 'no-certificate') {
        excelCount.textContent = totalItems;
    }

    // Toplam sayfa sayısını güncelle
    document.getElementById('totalPages').textContent = totalPages;

    // Mevcut sayfa sınırını kontrol et
    if (totalPages === 0) {
        currentPage = 0;
    } else if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    document.getElementById('currentPage').textContent = totalPages > 0 ? currentPage : 0;
    
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

    // Checkboxları temizle
    document.querySelectorAll('.bulk-cert-type').forEach(cb => cb.checked = false);

    // PDF tabını varsayılan olarak aç
    switchUploadTab('pdf');
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
    // Seçili belge türlerini al
    const selectedTypes = Array.from(document.querySelectorAll('.bulk-cert-type:checked')).map(cb => cb.value);

    if (selectedTypes.length === 0) {
        showNotification('Lütfen en az bir belge türü seçin!', 'error');
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
    // Modal başlığını "Müşteri Ekle" yap
    const modalHeader = document.querySelector('#userFormModal .modal-header h2');
    if (modalHeader) modalHeader.textContent = 'Müşteri Ekle';

    // Form alanlarını temizle
    const form = document.querySelector('#userFormModal form');
    if (form) form.reset();

    // Firma alanını gizle
    const companyNameGroup = document.getElementById('companyNameGroup');
    if (companyNameGroup) companyNameGroup.style.display = 'none';

    document.getElementById('userFormModal').classList.add('active');
}

// Müşteri kaydet
function saveUser(e) {
    e.preventDefault();

    const tckn = document.getElementById('userTCKN').value;
    const birthDate = document.getElementById('userBirthDate').value;
    const phone = document.getElementById('userPhone').value;

    // TCKN kontrolü (11 hane)
    if (tckn.length !== 11 || !/^\d{11}$/.test(tckn)) {
        showNotification('TC Kimlik Numarası 11 haneli olmalıdır!', 'error');
        return;
    }

    const formData = {
        name: document.getElementById('userName').value,
        surname: document.getElementById('userSurname').value,
        tckn: tckn,
        birthDate: birthDate,
        documentType: document.getElementById('userDocumentType').value,
        company: document.getElementById('userCompany').value || null,
        phone: phone
    };

    // Gerçek uygulamada AJAX ile kaydedilecek
    console.log('Yeni müşteri:', formData);

    showNotification('Müşteri başarıyla eklendi', 'success');
    closeModal('userFormModal');

    // Formu temizle
    document.querySelector('#userFormModal form').reset();
    document.getElementById('companyNameGroup').style.display = 'none';
    document.getElementById('isCompanyToggle').checked = false;

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
    // Modal başlığını değiştir
    const modalHeader = document.querySelector('#userFormModal .modal-header h2');
    if (modalHeader) modalHeader.textContent = 'Müşteri Düzenle';

    // Gerçek uygulamada kullanıcı bilgileri AJAX ile yüklenecek
    // Demo data
    const userName = document.getElementById('userName');
    const userSurname = document.getElementById('userSurname');
    const userBirthDate = document.getElementById('userBirthDate');
    const userPhone = document.getElementById('userPhone');
    const userTCKN = document.getElementById('userTCKN');
    const userDocumentType = document.getElementById('userDocumentType');
    const userCompany = document.getElementById('userCompany');
    const isCompanyToggle = document.getElementById('isCompanyToggle');
    const companyNameGroup = document.getElementById('companyNameGroup');

    if (userName) userName.value = 'Ahmet';
    if (userSurname) userSurname.value = 'Yılmaz';
    if (userBirthDate) userBirthDate.value = '15.03.1990';
    if (userPhone) userPhone.value = '532 123 45 67';
    if (userTCKN) userTCKN.value = '12345678901';
    if (userDocumentType) userDocumentType.value = 'Temel Denizcilik';

    // Firma bilgisi yoksa
    if (userCompany) userCompany.value = '';
    if (isCompanyToggle) isCompanyToggle.checked = false;
    if (companyNameGroup) companyNameGroup.style.display = 'none';

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
    // Eğer sayfa yok ise (totalPages === 0) tüm butonları devre dışı bırak
    const noPages = totalPages === 0;
    document.getElementById('firstPage').disabled = noPages || currentPage === 1;
    document.getElementById('prevPage').disabled = noPages || currentPage === 1;
    document.getElementById('nextPage').disabled = noPages || currentPage === totalPages;
    document.getElementById('lastPage').disabled = noPages || currentPage === totalPages;

    // Sayfa numaralarını oluştur
    const paginationNumbers = document.getElementById('paginationNumbers');
    paginationNumbers.innerHTML = '';

    // Eğer sayfa yoksa numara gösterme
    if (totalPages === 0) return;

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
    const companyGroup = document.getElementById('companyNameGroup');
    if (toggle && companyGroup) {
        companyGroup.style.display = toggle.checked ? 'block' : 'none';
        if (!toggle.checked) {
            document.getElementById('userCompany').value = '';
        }
    }
}

// Detay modalını aç
function openUserDetailModal(userId) {
    // Backend'den kullanıcı detayını çek (şimdilik demo data)
    const educations = [
        { name: 'Temel Denizcilik', date: '12.11.2025', hasRegistration: true, hasCertificate: true, hasInvoice: true },
        { name: 'İleri Navigasyon', date: '05.01.2025', hasRegistration: true, hasCertificate: false, hasInvoice: true },
        { name: 'Güvenlik Eğitimi', date: '20.03.2025', hasRegistration: true, hasCertificate: true, hasInvoice: false }
    ];

    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2><i class="fas fa-user-circle" style="color: var(--blue); margin-right: 0.5rem;"></i>Kullanıcı Detayları</h2>
                <button class="close-modal" onclick="this.closest('.modal').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding: 1.5rem;">
                <!-- Kullanıcı Bilgileri Card -->
                <div style="background: linear-gradient(135deg, rgba(30, 45, 68, 0.5) 0%, rgba(26, 41, 66, 0.4) 100%); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem; color: var(--blue); font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-info-circle"></i>
                        Kullanıcı Bilgileri
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.875rem; font-size: 0.95rem;">
                        <div>
                            <span style="color: var(--text-secondary); display: block; margin-bottom: 0.25rem; font-size: 0.85rem;">Ad Soyad</span>
                            <strong style="color: var(--text-primary);">Ahmet Yılmaz</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-secondary); display: block; margin-bottom: 0.25rem; font-size: 0.85rem;">TCKN</span>
                            <strong style="color: var(--text-primary);">12345678901</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-secondary); display: block; margin-bottom: 0.25rem; font-size: 0.85rem;">Doğum Tarihi</span>
                            <strong style="color: var(--text-primary);">15.03.1990</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-secondary); display: block; margin-bottom: 0.25rem; font-size: 0.85rem;">Telefon</span>
                            <strong style="color: var(--text-primary);">0532 123 4567</strong>
                        </div>
                    </div>
                </div>

                <!-- Eğitim Bilgileri -->
                <h3 style="margin-bottom: 1rem; color: var(--blue); font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-graduation-cap"></i>
                    Eğitim Bilgileri
                </h3>

                <!-- Scrollable Education Cards -->
                <div style="max-height: 400px; overflow-y: auto; padding-right: 0.5rem;">
                    ${educations.map(edu => `
                        <div style="background: linear-gradient(135deg, rgba(30, 45, 68, 0.5) 0%, rgba(26, 41, 66, 0.4) 100%); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                <div>
                                    <h4 style="color: var(--text-primary); font-size: 1rem; margin-bottom: 0.5rem;">${edu.name}</h4>
                                    <p style="color: var(--text-secondary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="far fa-calendar-alt"></i>
                                        ${edu.date}
                                    </p>
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button class="btn btn-sm" style="padding: 0.4rem 0.875rem; font-size: 0.85rem; background: ${edu.hasRegistration ? 'linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.1) 100%)' : 'rgba(100, 100, 100, 0.2)'}; border-color: ${edu.hasRegistration ? 'rgba(239, 68, 68, 0.3)' : 'rgba(139, 156, 188, 0.2)'}; color: ${edu.hasRegistration ? '#ef4444' : 'var(--text-muted)'};" ${!edu.hasRegistration ? 'disabled' : ''}>
                                    <i class="fas fa-file-contract"></i> Kayıt
                                </button>
                                <button class="btn btn-sm" style="padding: 0.4rem 0.875rem; font-size: 0.85rem; background: ${edu.hasCertificate ? 'linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.1) 100%)' : 'rgba(100, 100, 100, 0.2)'}; border-color: ${edu.hasCertificate ? 'rgba(16, 185, 129, 0.3)' : 'rgba(139, 156, 188, 0.2)'}; color: ${edu.hasCertificate ? '#10b981' : 'var(--text-muted)'};" ${!edu.hasCertificate ? 'disabled' : ''}>
                                    <i class="fas fa-file-pdf"></i> Sertifika
                                </button>
                                <button class="btn btn-sm" style="padding: 0.4rem 0.875rem; font-size: 0.85rem; background: ${edu.hasInvoice ? 'linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.1) 100%)' : 'rgba(100, 100, 100, 0.2)'}; border-color: ${edu.hasInvoice ? 'rgba(59, 130, 246, 0.3)' : 'rgba(139, 156, 188, 0.2)'}; color: ${edu.hasInvoice ? 'var(--blue)' : 'var(--text-muted)'};" ${!edu.hasInvoice ? 'disabled' : ''}>
                                    <i class="fas fa-file-invoice"></i> Fatura
                                </button>
                            </div>
                        </div>
                    `).join('')}
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
                <h2><i class="fas fa-sms" style="color: var(--blue); margin-right: 0.5rem;"></i>Toplu SMS Gönder</h2>
                <button class="close-modal" onclick="this.closest('.modal').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding: 1.5rem;">
                <div id="smsStep1" style="display: block;">
                    <h3 style="margin-bottom: 1rem; color: var(--blue);">Alıcı Seçimi</h3>

                    <!-- Tip Seçimi -->
                    <div class="form-group" style="background: linear-gradient(135deg, rgba(30, 45, 68, 0.5) 0%, rgba(26, 41, 66, 0.4) 100%); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; cursor: pointer; color: var(--text-primary);">
                            <input type="radio" name="receiverType" value="company" checked onchange="toggleSMSReceiverType()" style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="font-size: 0.95rem; font-weight: 500;"><i class="fas fa-building" style="margin-right: 0.5rem; color: var(--blue);"></i>Firma</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; color: var(--text-primary);">
                            <input type="radio" name="receiverType" value="individual" onchange="toggleSMSReceiverType()" style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="font-size: 0.95rem; font-weight: 500;"><i class="fas fa-user" style="margin-right: 0.5rem; color: var(--blue);"></i>Bireysel</span>
                        </label>
                    </div>

                    <!-- Firma Seçimi -->
                    <div id="companySelection">
                        <label class="form-label">Firma Seçin</label>

                        <!-- Firma Arama -->
                        <div style="position: relative; margin-bottom: 1rem;">
                            <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                            <input type="text" id="companySearchInput" placeholder="Firma ara..." onkeyup="filterCompanies()" style="width: 100%; padding: 0.875rem 1rem 0.875rem 3rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; color: #ffffff; font-size: 0.95rem;">
                        </div>

                        <div id="companyCheckboxList" style="background: linear-gradient(135deg, rgba(30, 45, 68, 0.5) 0%, rgba(26, 41, 66, 0.4) 100%); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; padding: 1rem; max-height: 250px; overflow-y: auto;">
                            <label class="checkbox-item company-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; cursor: pointer;">
                                <input type="checkbox" class="sms-company" value="XYZ Maritime" style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="color: var(--text-primary); font-size: 0.95rem;">XYZ Maritime <span style="color: var(--text-secondary);">(5 kişi)</span></span>
                            </label>
                            <label class="checkbox-item company-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; cursor: pointer;">
                                <input type="checkbox" class="sms-company" value="Deniz Yıldızı A.Ş." style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="color: var(--text-primary); font-size: 0.95rem;">Deniz Yıldızı A.Ş. <span style="color: var(--text-secondary);">(3 kişi)</span></span>
                            </label>
                            <label class="checkbox-item company-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; cursor: pointer;">
                                <input type="checkbox" class="sms-company" value="Mavi Dalga Ltd." style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="color: var(--text-primary); font-size: 0.95rem;">Mavi Dalga Ltd. <span style="color: var(--text-secondary);">(4 kişi)</span></span>
                            </label>
                            <label class="checkbox-item company-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0; cursor: pointer;">
                                <input type="checkbox" class="sms-company" value="Kıyı Shipping" style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="color: var(--text-primary); font-size: 0.95rem;">Kıyı Shipping <span style="color: var(--text-secondary);">(2 kişi)</span></span>
                            </label>
                        </div>
                    </div>

                    <!-- Bireysel Filtre -->
                    <div id="individualSelection" style="display: none;">
                        <label class="form-label">Bireysel Müşteri Filtresi</label>
                        <div style="background: linear-gradient(135deg, rgba(30, 45, 68, 0.5) 0%, rgba(26, 41, 66, 0.4) 100%); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; padding: 1rem;">
                            <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; cursor: pointer;">
                                <input type="checkbox" id="smsIndividualWithCert" checked style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="color: var(--text-primary); font-size: 0.95rem;">Sertifikalı Müşteriler</span>
                            </label>
                            <label class="checkbox-item" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0; cursor: pointer;">
                                <input type="checkbox" id="smsIndividualWithoutCert" style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="color: var(--text-primary); font-size: 0.95rem;">Sertifikasız Müşteriler</span>
                            </label>
                        </div>
                        <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">
                            Görünen ${document.querySelectorAll('.users-table tbody tr:not([style*="display: none"])').length} kişiye SMS gönderilecektir.
                        </small>
                    </div>

                    <button class="btn btn-primary" onclick="showSMSStep2()" style="margin-top: 1.5rem; width: 100%;">
                        <i class="fas fa-arrow-right"></i> İlerle
                    </button>
                </div>

                <div id="smsStep2" style="display: none;">
                    <h3 style="margin-bottom: 1rem; color: var(--blue);">Mesaj İçeriği</h3>
                    <div class="form-group">
                        <label class="form-label">Mesaj Metni</label>
                        <textarea class="form-control" id="smsMessage" rows="6" placeholder="Merhaba {Ad} {Soyad}, ...">{Ad} {Soyad}, eğitiminiz için...</textarea>
                        <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">
                            <i class="fas fa-info-circle"></i> Kullanılabilir değişkenler: {Ad}, {Soyad}, {TCKN}, {Telefon}, {EgitimAdi}
                        </small>
                    </div>
                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
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

// SMS Alıcı Tipi Toggle
function toggleSMSReceiverType() {
    const receiverType = document.querySelector('input[name="receiverType"]:checked').value;
    const companySelection = document.getElementById('companySelection');
    const individualSelection = document.getElementById('individualSelection');

    if (receiverType === 'company') {
        companySelection.style.display = 'block';
        individualSelection.style.display = 'none';
    } else {
        companySelection.style.display = 'none';
        individualSelection.style.display = 'block';
    }
}

// Firma arama filtresi
function filterCompanies() {
    const input = document.getElementById('companySearchInput');
    const filter = input.value.toUpperCase();
    const items = document.querySelectorAll('.company-item');

    items.forEach(item => {
        const text = item.textContent || item.innerText;
        if (text.toUpperCase().indexOf(filter) > -1) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
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
