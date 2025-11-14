// IP Takip Sayfası - JavaScript

let currentPage = 1;
let itemsPerPage = 25;
let allRows = [];

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    allRows = Array.from(document.querySelectorAll('#ipTableBody tr'));
    // Varsayılan filtreyi uygula (Bugün)
    filterData();
});

// IP ara
function searchIP() {
    const searchTerm = document.getElementById('ipSearch').value.toLowerCase();
    
    allRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.removeAttribute('data-hidden-by-search');
        } else {
            row.setAttribute('data-hidden-by-search', 'true');
        }
    });
    
    currentPage = 1;
    updatePagination();
}

// Filtrele
function filterData() {
    const timeFilter = document.getElementById('timeFilter').value;
    const deviceFilter = document.getElementById('deviceFilter').value;
    const statFilter = document.getElementById('statFilter').value;
    
    // Filtre işaretlerini temizle
    allRows.forEach(row => {
        row.removeAttribute('data-hidden-by-filter');
    });
    
    // Cihaz filtresini uygula
    if (deviceFilter !== 'all') {
        allRows.forEach(row => {
            const device = row.getAttribute('data-device');
            if (device !== deviceFilter) {
                row.setAttribute('data-hidden-by-filter', 'true');
            }
        });
    }
    
    // İstatistikleri güncelle
    updateStats(timeFilter);
    
    currentPage = 1;
    updatePagination();
    
    showNotification('Filtreler uygulandı', 'success');
}

// İstatistikleri güncelle
function updateStats(timeFilter) {
    const stats = {
        'today': { visits: 245, unique: 189, live: 12, ads: 67, buyers: 34 },
        'yesterday': { visits: 312, unique: 241, live: 0, ads: 85, buyers: 42 },
        'week': { visits: 1847, unique: 1203, live: 12, ads: 421, buyers: 216 },
        'month': { visits: 7892, unique: 5124, live: 12, ads: 1794, buyers: 920 },
        'all': { visits: 28934, unique: 18472, live: 12, ads: 6462, buyers: 3321 }
    };
    
    const current = stats[timeFilter] || stats['all'];
    
    document.getElementById('totalVisits').textContent = current.visits;
    document.getElementById('uniqueVisitors').textContent = current.unique;
    document.getElementById('liveVisitors').textContent = current.live;
    document.getElementById('adsVisits').textContent = current.ads;
    document.getElementById('buyersVisits').textContent = current.buyers;
}

// Filtreleri temizle
function clearFilters() {
    document.getElementById('timeFilter').value = 'today';
    document.getElementById('deviceFilter').value = 'all';
    document.getElementById('statFilter').value = 'all';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    
    allRows.forEach(row => {
        row.removeAttribute('data-hidden-by-filter');
        row.removeAttribute('data-hidden-by-search');
    });
    
    currentPage = 1;
    filterData();
}

// Excel'e aktar
function exportToExcel() {
    const visibleRows = allRows.filter(row => 
        !row.hasAttribute('data-hidden-by-filter') && 
        !row.hasAttribute('data-hidden-by-search')
    );
    
    if (visibleRows.length === 0) {
        showNotification('Dışa aktarılacak veri bulunamadı', 'error');
        return;
    }
    
    let csv = 'IP Adresi,Ziyaret Sayısı,Lokasyon,Cihaz,Tarayıcı,Son Ziyaret\n';
    
    visibleRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const ip = cells[0].textContent.trim();
        const visits = cells[1].textContent.trim();
        const location = cells[2].textContent.trim();
        const device = cells[3].textContent.trim();
        const browser = cells[4].textContent.trim();
        const lastVisit = cells[5].textContent.trim();
        
        csv += `"${ip}","${visits}","${location}","${device}","${browser}","${lastVisit}"\n`;
    });
    
    const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    const date = new Date().toISOString().split('T')[0];
    
    link.setAttribute('href', url);
    link.setAttribute('download', `ip-takip-${date}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('Excel dosyası indirildi', 'success');
}

// IP detaylarını göster
function showIPDetails(ipData) {
    const modal = document.getElementById('ipDetailModal');
    const content = document.getElementById('ipDetailContent');
    
    let pageVisitsHtml = '';
    if (ipData.pages && ipData.pages.length > 0) {
        ipData.pages.forEach(page => {
            pageVisitsHtml += `
                <div class="page-visit-item">
                    <span class="page-url">${page.url}</span>
                    <span class="visit-time">${page.time}</span>
                </div>
            `;
        });
    } else {
        pageVisitsHtml = '<p style="text-align: center; color: #8b9cbc; padding: 2rem;">Sayfa ziyaret kaydı bulunamadı</p>';
    }
    
    content.innerHTML = `
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">IP Adresi</div>
                <div class="detail-value">${ipData.ip}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Toplam Ziyaret</div>
                <div class="detail-value">${ipData.visits}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Lokasyon</div>
                <div class="detail-value">${ipData.location}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">İlk Ziyaret</div>
                <div class="detail-value">${ipData.first_visit}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Son Ziyaret</div>
                <div class="detail-value">${ipData.last_visit}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Cihaz</div>
                <div class="detail-value">${ipData.device}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Tarayıcı</div>
                <div class="detail-value">${ipData.browser}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">İşletim Sistemi</div>
                <div class="detail-value">${ipData.os || 'Bilinmiyor'}</div>
            </div>
        </div>
        
        <div class="page-visits">
            <h3>Ziyaret Edilen Sayfalar</h3>
            <div class="page-visit-list">
                ${pageVisitsHtml}
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

// Pagination güncelle - TAMAMEN YENİDEN YAZILDI
function updatePagination() {
    // Görünür satırları al (filtre ve arama tarafından gizlenmemiş)
    const visibleRows = allRows.filter(row => 
        !row.hasAttribute('data-hidden-by-filter') && 
        !row.hasAttribute('data-hidden-by-search')
    );
    
    const totalItems = visibleRows.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
    
    // Sayfa sınırını kontrol et
    if (currentPage > totalPages) {
        currentPage = totalPages;
    }
    if (currentPage < 1) {
        currentPage = 1;
    }
    
    // Sayfa bilgilerini güncelle
    document.getElementById('totalPages').textContent = totalPages;
    document.getElementById('currentPage').textContent = currentPage;
    
    // ÖNCE TÜM SATIRLARI GİZLE
    allRows.forEach(row => {
        row.style.display = 'none';
    });
    
    // Sadece mevcut sayfa için görünür satırları göster
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    
    visibleRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = '';
        }
    });
    
    updatePaginationButtons(totalPages);
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
    
    // En az 5 sayfa göster (mümkünse)
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