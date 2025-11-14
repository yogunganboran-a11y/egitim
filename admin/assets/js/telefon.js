// Telefon Sayfası - JavaScript

let currentPage = 1;
let itemsPerPage = 25;
let allCalls = [];

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    allCalls = Array.from(document.querySelectorAll('.calls-table tbody tr'));
    updatePagination();
});

// Arama ara
function searchCalls() {
    applyFilters();
}

// Telefon numarasını normalize et (boşluk ve başındaki 0'ı kaldır)
function normalizePhone(phone) {
    return phone.replace(/\s/g, '').replace(/^0/, '');
}

// Filtrele - Birleştirilmiş fonksiyon
function applyFilters() {
    const searchTerm = document.getElementById('callSearch').value.toLowerCase().trim();
    const typeFilter = document.getElementById('typeFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;

    allCalls.forEach(row => {
        row.removeAttribute('data-hidden-by-search');
        row.removeAttribute('data-hidden-by-filter');
        row.removeAttribute('data-hidden-by-date');
    });

    // Arama filtresi
    if (searchTerm) {
        const normalizedSearchPhone = normalizePhone(searchTerm);
        const normalizedSearchText = searchTerm.replace(/\s/g, '');

        allCalls.forEach(row => {
            const cells = row.querySelectorAll('td');
            const phone = cells[2] ? cells[2].textContent : '';
            const name = cells[1] ? cells[1].textContent.toLowerCase() : '';
            const callType = row.textContent.toLowerCase();

            const normalizedPhone = normalizePhone(phone);

            // Match on phone (with normalization) or name (case-insensitive)
            const matchesPhone = normalizedPhone.includes(normalizedSearchPhone);
            const matchesName = name.includes(searchTerm);
            const matchesOther = callType.replace(/\s/g, '').includes(normalizedSearchText);

            if (!matchesPhone && !matchesName && !matchesOther) {
                row.setAttribute('data-hidden-by-search', 'true');
            }
        });
    }
    
    // Tip filtresi
    if (typeFilter !== 'all') {
        allCalls.forEach(row => {
            const type = row.getAttribute('data-type');
            if (type !== typeFilter) {
                row.setAttribute('data-hidden-by-filter', 'true');
            }
        });
    }
    
    // Tarih filtresi
    if (dateFrom && dateTo) {
        const fromDate = new Date(dateFrom);
        fromDate.setHours(0, 0, 0, 0);
        const toDate = new Date(dateTo);
        toDate.setHours(23, 59, 59, 999);
        
        allCalls.forEach(row => {
            const dateText = row.cells[0].textContent; // İlk sütun tarih
            const [datePart, timePart] = dateText.split(' ');
            const [day, month, year] = datePart.split('.');
            const rowDate = new Date(year, month - 1, day);
            
            if (rowDate < fromDate || rowDate > toDate) {
                row.setAttribute('data-hidden-by-date', 'true');
            }
        });
    }
    
    currentPage = 1;
    updateStats();
    updatePagination();
}

// Filtreleri temizle
function clearFilters() {
    document.getElementById('callSearch').value = '';
    document.getElementById('typeFilter').value = 'all';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    
    allCalls.forEach(row => {
        row.removeAttribute('data-hidden-by-filter');
        row.removeAttribute('data-hidden-by-search');
        row.removeAttribute('data-hidden-by-date');
    });
    
    currentPage = 1;
    updateStats();
    updatePagination();
}

// İstatistikleri güncelle
function updateStats() {
    const visibleCalls = allCalls.filter(row => 
        !row.hasAttribute('data-hidden-by-filter') && 
        !row.hasAttribute('data-hidden-by-search') &&
        !row.hasAttribute('data-hidden-by-date')
    );
    
    const total = visibleCalls.length;
    const incoming = visibleCalls.filter(row => row.getAttribute('data-type') === 'incoming').length;
    const outgoing = visibleCalls.filter(row => row.getAttribute('data-type') === 'outgoing').length;
    
    document.getElementById('totalCalls').textContent = total;
    document.getElementById('incomingCalls').textContent = incoming;
    document.getElementById('outgoingCalls').textContent = outgoing;
}

// Filtrele (eski fonksiyon - şimdi applyFilters'ı çağırır)
function filterCalls() {
    applyFilters();
}

// Arama detaylarını göster
function showCallDetails(callId) {
    const callData = getCallData(callId);
    const modal = document.getElementById('callDetailModal');
    const content = document.getElementById('callDetailContent');
    
    // Transkript HTML'i oluştur
    let transcriptHtml = '';
    if (callData.transcript && callData.transcript.length > 0) {
        callData.transcript.forEach(line => {
            transcriptHtml += `
                <div class="transcript-line ${line.speaker === 'Agent' ? 'agent' : 'customer'}">
                    <span class="transcript-speaker">${line.speaker === 'Agent' ? 'Temsilci' : 'Müşteri'}:</span>
                    <span class="transcript-text">${line.text}</span>
                    <span class="transcript-time">${line.time}</span>
                </div>
            `;
        });
    } else {
        transcriptHtml = '<p style="text-align: center; color: #8b9cbc; padding: 2rem;">Transkript mevcut değil</p>';
    }
    
    content.innerHTML = `
        <div class="call-detail-header">
            <div class="call-info">
                <div class="call-avatar">
                    <i class="fas fa-${callData.type === 'incoming' ? 'phone-alt' : callData.type === 'outgoing' ? 'phone' : 'phone-slash'}"></i>
                </div>
                <div class="call-details">
                    <h3>${callData.phone}</h3>
                    <p>${callData.date} - ${callData.duration}</p>
                </div>
            </div>
            <div class="call-type ${callData.type}">
                <i class="fas fa-${callData.type === 'incoming' ? 'phone-alt' : callData.type === 'outgoing' ? 'phone' : 'phone-slash'}"></i>
                ${callData.type === 'incoming' ? 'Gelen' : callData.type === 'outgoing' ? 'Giden' : 'Cevapsız'}
            </div>
        </div>
        
        ${callData.audioUrl ? `
        <div class="audio-player">
            <h4><i class="fas fa-play-circle"></i> Ses Kaydı</h4>
            <audio controls>
                <source src="${callData.audioUrl}" type="audio/mpeg">
                Tarayıcınız ses çalmayı desteklemiyor.
            </audio>
        </div>
        ` : ''}
        
        <div class="transcript">
            <h4><i class="fas fa-file-alt"></i> Görüşme Metni</h4>
            <div class="transcript-content">
                ${transcriptHtml}
            </div>
        </div>
    `;
    
    modal.classList.add('active');
}

// Arama verilerini al
function getCallData(callId) {
    // Demo veri - Gerçek uygulamada backend'den gelecek
    const calls = {
        '1': {
            phone: '+90 532 123 4567',
            type: 'incoming',
            date: '12.11.2025 10:15',
            duration: '5:23',
            audioUrl: 'assets/audio/call1.mp3',
            transcript: [
                { speaker: 'Customer', text: 'Merhaba, siparişim hakkında bilgi alabilir miyim?', time: '00:05' },
                { speaker: 'Agent', text: 'Tabii, sipariş numaranızı alabilir miyim?', time: '00:12' },
                { speaker: 'Customer', text: 'Evet, #12345', time: '00:18' },
                { speaker: 'Agent', text: 'Siparişiniz kargoya verildi, yarın elinizde olacak.', time: '00:25' },
                { speaker: 'Customer', text: 'Teşekkür ederim', time: '00:35' }
            ]
        }
    };
    
    return calls[callId] || {
        phone: '+90 532 000 0000',
        type: 'incoming',
        date: new Date().toLocaleString('tr-TR'),
        duration: '0:00',
        audioUrl: null,
        transcript: []
    };
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

// Pagination güncelle
function updatePagination() {
    const visibleCalls = allCalls.filter(row => 
        !row.hasAttribute('data-hidden-by-filter') && 
        !row.hasAttribute('data-hidden-by-search') &&
        !row.hasAttribute('data-hidden-by-date')
    );
    
    const totalItems = visibleCalls.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
    
    if (currentPage > totalPages) {
        currentPage = totalPages;
    }
    if (currentPage < 1) {
        currentPage = 1;
    }
    
    document.getElementById('totalPages').textContent = totalPages;
    document.getElementById('currentPage').textContent = currentPage;
    
    // Tüm satırları gizle
    allCalls.forEach(row => {
        row.style.display = 'none';
    });
    
    // Sadece mevcut sayfa satırlarını göster
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    
    visibleCalls.forEach((row, index) => {
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