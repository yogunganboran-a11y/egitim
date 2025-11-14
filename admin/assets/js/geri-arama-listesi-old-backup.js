// Geri Arama Listesi - JavaScript

let currentPage = 1;
let itemsPerPage = 25;
let allRows = [];
let sortDirection = {
    name: 'asc',
    reason: 'asc',
    priority: 'desc',
    score: 'desc',
    date: 'desc'
};

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    allRows = Array.from(document.querySelectorAll('#callbackTableBody tr'));

    // Varsayılan filtreyi uygula (Bekleyen)
    applyFilters();

    // İstatistikleri güncelle
    updateStats();
});

// Filtreleri uygula
function applyFilters() {
    const statusFilter = document.getElementById('statusFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    const sourceFilter = document.getElementById('sourceFilter').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();

    allRows.forEach(row => {
        let show = true;

        // Durum filtresi
        if (statusFilter !== 'all' && row.dataset.status !== statusFilter) {
            show = false;
        }

        // Öncelik filtresi
        if (priorityFilter !== 'all' && row.dataset.priority !== priorityFilter) {
            show = false;
        }

        // Kaynak filtresi
        if (sourceFilter !== 'all' && row.dataset.source !== sourceFilter) {
            show = false;
        }

        // Arama filtresi
        if (searchTerm) {
            const name = row.dataset.name.toLowerCase();
            const phone = row.dataset.phone.toLowerCase();
            if (!name.includes(searchTerm) && !phone.includes(searchTerm)) {
                show = false;
            }
        }

        row.style.display = show ? '' : 'none';
    });

    currentPage = 1;
    updatePagination();
}

// Filtreleri temizle
function clearFilters() {
    document.getElementById('statusFilter').value = 'pending';
    document.getElementById('priorityFilter').value = 'all';
    document.getElementById('sourceFilter').value = 'all';
    document.getElementById('searchInput').value = '';

    applyFilters();
    showNotification('Filtreler temizlendi', 'success');
}

// İstatistikleri güncelle
function updateStats() {
    const pending = allRows.filter(row => row.dataset.status === 'pending').length;
    const called = allRows.filter(row => row.dataset.status === 'called').length;
    const completed = allRows.filter(row => row.dataset.status === 'completed').length;
    const failed = allRows.filter(row => row.dataset.status === 'failed').length;

    document.getElementById('statPending').textContent = pending;
    document.getElementById('statCalled').textContent = called;
    document.getElementById('statCompleted').textContent = completed;
    document.getElementById('statFailed').textContent = failed;
}

// Durum güncelle
function updateStatus(id, newStatus) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;

    row.dataset.status = newStatus;

    // Select elementinin rengini güncelle
    const select = row.querySelector('.status-select');
    select.className = `status-select status-${newStatus}`;

    // İstatistikleri güncelle
    updateStats();

    // Filtreleri yeniden uygula
    applyFilters();

    // Backend'e gönder
    console.log('Durum güncelleniyor:', { id, newStatus });

    showNotification('Durum güncellendi', 'success');
}

// Müşteriyi ara
function callCustomer(phone) {
    window.location.href = `tel:${phone}`;
    showNotification(`${phone} aranıyor...`, 'info');
}

// WhatsApp aç
function openWhatsApp(phone) {
    const cleanPhone = phone.replace(/\D/g, '');
    const whatsappUrl = `https://wa.me/90${cleanPhone}`;
    window.open(whatsappUrl, '_blank');
    showNotification('WhatsApp açılıyor...', 'info');
}

// Detayları göster
function showDetails(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;

    const name = row.querySelector('.name-cell strong').textContent;
    const phone = row.dataset.phone;
    const source = row.dataset.source;
    const reason = row.querySelector('.reason-text').textContent;
    const priority = row.dataset.priority;
    const score = row.querySelector('.score-text').textContent;
    const detectedAt = row.querySelector('.date-cell').textContent;
    const status = row.dataset.status;

    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailModalContent');

    const priorityLabels = { high: 'Yüksek', medium: 'Orta', low: 'Düşük' };
    const statusLabels = { pending: 'Bekleyor', called: 'Arandı', completed: 'Tamamlandı', failed: 'Ulaşılamadı' };
    const sourceLabels = { whatsapp: 'WhatsApp', phone: 'Telefon' };

    content.innerHTML = `
        <div style="display: grid; gap: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div>
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">İsim</div>
                    <div style="color: #e9edef; font-size: 1.1rem; font-weight: 600;">${name}</div>
                </div>
                <div>
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Telefon</div>
                    <div style="color: #3b82f6; font-size: 1.1rem; font-weight: 600; font-family: monospace;">${phone}</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div>
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Kaynak</div>
                    <div style="color: #e9edef;">${sourceLabels[source]}</div>
                </div>
                <div>
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Öncelik</div>
                    <div style="color: #e9edef;">${priorityLabels[priority]}</div>
                </div>
            </div>

            <div>
                <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Arama Sebebi</div>
                <div style="color: #e9edef; line-height: 1.6; background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 8px; border-left: 3px solid #3b82f6;">
                    ${reason}
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div>
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">AI Skoru</div>
                    <div style="color: #3b82f6; font-size: 1.5rem; font-weight: 700;">${score}</div>
                </div>
                <div>
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Durum</div>
                    <div style="color: #e9edef;">${statusLabels[status]}</div>
                </div>
            </div>

            <div>
                <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Tespit Zamanı</div>
                <div style="color: #e9edef;">${detectedAt}</div>
            </div>

            <div style="display: flex; gap: 1rem; padding-top: 1rem; border-top: 1px solid rgba(59, 130, 246, 0.2);">
                <button class="btn btn-primary" onclick="callCustomer('${phone}'); closeModal('detailModal');" style="flex: 1;">
                    <i class="fas fa-phone"></i> Ara
                </button>
                <button class="btn btn-secondary" onclick="openWhatsApp('${phone}'); closeModal('detailModal');" style="flex: 1;">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </button>
            </div>

            <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 8px; border-left: 3px solid #3b82f6;">
                <div style="font-weight: 600; color: #3b82f6; margin-bottom: 0.5rem;">
                    <i class="fas fa-robot"></i> AI Analiz Notları
                </div>
                <div style="color: #8b9cbc; font-size: 0.9rem; line-height: 1.6;">
                    Bu müşteri ${source === 'whatsapp' ? 'WhatsApp' : 'telefon'} üzerinden ${reason.toLowerCase()} Bu nedenle geri arama yapılması önerilmektedir. AI skoru %${score.replace('%', '')} olarak hesaplanmıştır.
                </div>
            </div>
        </div>
    `;

    modal.classList.add('active');
}

// Modal kapat
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Modal dışına tıklayınca kapat
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});

// Geri aramayı sil
function deleteCallback(id) {
    if (!confirm('Bu geri arama kaydını silmek istediğinizden emin misiniz?')) {
        return;
    }

    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
        row.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            row.remove();
            allRows = Array.from(document.querySelectorAll('#callbackTableBody tr'));
            updateStats();
            updatePagination();
        }, 300);
    }

    console.log('Geri arama siliniyor:', id);
    showNotification('Kayıt silindi', 'success');
}

// AI analizi yenile
function refreshAIAnalysis() {
    showNotification('AI analizi yenileniyor...', 'info');

    // Demo için simülasyon
    setTimeout(() => {
        showNotification('AI analizi tamamlandı', 'success');
    }, 2000);

    console.log('AI analizi yenileniyor');
}

// Excel'e aktar
function exportToExcel() {
    const visibleRows = allRows.filter(row => row.style.display !== 'none');

    if (visibleRows.length === 0) {
        showNotification('Dışa aktarılacak veri bulunamadı', 'error');
        return;
    }

    let csv = 'İsim,Telefon,Kaynak,Arama Sebebi,Öncelik,AI Skoru,Tespit Zamanı,Durum\\n';

    visibleRows.forEach(row => {
        const name = row.querySelector('.name-cell strong').textContent;
        const phone = row.dataset.phone;
        const source = row.dataset.source === 'whatsapp' ? 'WhatsApp' : 'Telefon';
        const reason = row.querySelector('.reason-text').textContent;
        const priority = row.dataset.priority === 'high' ? 'Yüksek' :
                        row.dataset.priority === 'medium' ? 'Orta' : 'Düşük';
        const score = row.querySelector('.score-text').textContent;
        const date = row.querySelector('.date-cell').textContent;
        const status = row.querySelector('.status-select').selectedOptions[0].text;

        csv += `"${name}","${phone}","${source}","${reason}","${priority}","${score}","${date}","${status}"\\n`;
    });

    const blob = new Blob(['\\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    const date = new Date().toISOString().split('T')[0];

    link.setAttribute('href', url);
    link.setAttribute('download', `geri-arama-listesi-${date}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showNotification('Excel dosyası indirildi', 'success');
}

// Tablo sıralama
function sortTable(column) {
    const tbody = document.getElementById('callbackTableBody');

    // Sıralama yönünü değiştir
    sortDirection[column] = sortDirection[column] === 'asc' ? 'desc' : 'asc';

    // Sırala
    allRows.sort((a, b) => {
        let aValue, bValue;

        if (column === 'name') {
            aValue = a.dataset.name;
            bValue = b.dataset.name;
        } else if (column === 'reason') {
            aValue = a.querySelector('.reason-text').textContent;
            bValue = b.querySelector('.reason-text').textContent;
        } else if (column === 'priority') {
            const priorityOrder = { high: 3, medium: 2, low: 1 };
            aValue = priorityOrder[a.dataset.priority];
            bValue = priorityOrder[b.dataset.priority];
        } else if (column === 'score') {
            aValue = parseInt(a.querySelector('.score-text').textContent);
            bValue = parseInt(b.querySelector('.score-text').textContent);
        } else if (column === 'date') {
            aValue = a.querySelector('.date-cell').textContent;
            bValue = b.querySelector('.date-cell').textContent;
        }

        if (sortDirection[column] === 'asc') {
            return aValue > bValue ? 1 : -1;
        } else {
            return aValue < bValue ? 1 : -1;
        }
    });

    // Tabloyu güncelle
    tbody.innerHTML = '';
    allRows.forEach(row => tbody.appendChild(row));

    // Sıralama ikonlarını güncelle
    document.querySelectorAll('.sortable .sort-icon').forEach(icon => {
        icon.className = 'fas fa-sort sort-icon';
    });

    const activeIcon = document.querySelector(`.sortable[onclick*="${column}"] .sort-icon`);
    if (activeIcon) {
        activeIcon.className = `fas fa-sort-${sortDirection[column] === 'asc' ? 'up' : 'down'} sort-icon active`;
    }

    // Sayfayı güncelle
    updatePagination();
    showNotification(`${column === 'name' ? 'İsim' :
                     column === 'reason' ? 'Sebep' :
                     column === 'priority' ? 'Öncelik' :
                     column === 'score' ? 'Skor' : 'Tarih'} sıralandı`, 'success');
}

// Pagination güncelle
function updatePagination() {
    const visibleRows = allRows.filter(row => row.style.display !== 'none');
    const totalItems = visibleRows.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));

    if (currentPage > totalPages) {
        currentPage = totalPages;
    }
    if (currentPage < 1) {
        currentPage = 1;
    }

    // Tüm satırları gizle
    allRows.forEach(row => {
        if (row.style.display !== 'none') {
            row.style.display = 'none';
            row.setAttribute('data-pagination-hidden', 'true');
        }
    });

    // Sadece mevcut sayfa için görünür satırları göster
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    visibleRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = '';
            row.removeAttribute('data-pagination-hidden');
        }
    });

    // Bilgileri güncelle
    document.getElementById('totalItems').textContent = totalItems;

    // Butonları güncelle
    updatePaginationButtons(totalPages);
}

// Sayfa değiştir
function changePage(page) {
    const totalPages = parseInt(document.querySelector('.pagination-numbers').childElementCount) || 1;

    if (page === 'prev') {
        if (currentPage > 1) currentPage--;
    } else if (page === 'next') {
        if (currentPage < totalPages) currentPage++;
    } else if (page === 'last') {
        currentPage = totalPages;
    } else if (typeof page === 'number') {
        currentPage = page;
    }

    updatePagination();
}

// Sayfa başına öğe sayısını değiştir
function changeItemsPerPage() {
    itemsPerPage = parseInt(document.getElementById('itemsPerPage').value);
    currentPage = 1;
    updatePagination();
}

// Pagination butonlarını güncelle
function updatePaginationButtons(totalPages) {
    document.getElementById('firstPage').disabled = currentPage === 1;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage >= totalPages;
    document.getElementById('lastPage').disabled = currentPage >= totalPages;

    const paginationNumbers = document.getElementById('paginationNumbers');
    paginationNumbers.innerHTML = '';

    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);

    if (endPage - startPage < 4) {
        if (startPage === 1) {
            endPage = Math.min(totalPages, startPage + 4);
        } else if (endPage === totalPages) {
            startPage = Math.max(1, endPage - 4);
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        const btn = document.createElement('button');
        btn.className = 'pagination-btn';
        if (i === currentPage) btn.classList.add('active');
        btn.textContent = i;
        btn.onclick = () => changePage(i);
        paginationNumbers.appendChild(btn);
    }
}

// Fade out animasyonu
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.95); }
    }
`;
document.head.appendChild(style);
