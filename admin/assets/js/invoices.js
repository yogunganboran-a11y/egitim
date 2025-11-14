// Faturalar Sayfası - JavaScript

let currentPage = 1;
let itemsPerPage = 25;

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    // Tüm satırları başlangıçta filtrelenmiş olarak işaretle
    const rows = document.querySelectorAll('.invoices-table tbody tr');
    rows.forEach(row => {
        row.setAttribute('data-filtered', 'true');
    });
    loadInvoices();
});

// Faturaları yükle
function loadInvoices() {
    updatePagination();
}

// Türkçe karakter normalizasyonu
function normalizeTurkish(text) {
    const map = {
        'ı': 'i', 'İ': 'i', 'ş': 's', 'Ş': 's',
        'ğ': 'g', 'Ğ': 'g', 'ü': 'u', 'Ü': 'u',
        'ö': 'o', 'Ö': 'o', 'ç': 'c', 'Ç': 'c'
    };
    return text.toLowerCase().split('').map(char => map[char] || char).join('');
}

// Telefon numarasını normalize et (boşluk ve başındaki 0'ı kaldır)
function normalizePhone(phone) {
    return phone.replace(/\s/g, '').replace(/^0/, '');
}

// Fatura ara
function searchInvoices() {
    const searchTerm = document.getElementById('invoiceSearch').value.trim();
    if (!searchTerm) {
        const rows = document.querySelectorAll('.invoices-table tbody tr');
        rows.forEach(row => {
            row.setAttribute('data-filtered', 'true');
        });
        currentPage = 1;
        updatePagination();
        return;
    }

    const normalizedSearch = normalizeTurkish(searchTerm);
    const normalizedPhoneSearch = normalizePhone(searchTerm);
    const rows = document.querySelectorAll('.invoices-table tbody tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const name = cells[1].textContent;
        const surname = cells[2].textContent;
        const tckn = cells[3].textContent;
        const phone = cells[4].textContent;

        const normalizedName = normalizeTurkish(name);
        const normalizedSurname = normalizeTurkish(surname);
        const normalizedPhone = normalizePhone(phone);

        const matches = normalizedName.includes(normalizedSearch) ||
                       normalizedSurname.includes(normalizedSearch) ||
                       tckn.includes(searchTerm) ||
                       normalizedPhone.includes(normalizedPhoneSearch);

        row.setAttribute('data-filtered', matches ? 'true' : 'false');
    });

    currentPage = 1;
    updatePagination();
}

// Tarih filtresi
function filterByDate() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;

    if (!dateFrom || !dateTo) {
        showNotification('Lütfen başlangıç ve bitiş tarihlerini seçin', 'error');
        return;
    }

    const fromDate = new Date(dateFrom);
    const toDate = new Date(dateTo);
    toDate.setHours(23, 59, 59, 999); // Bitiş gününü tamamen dahil et

    const rows = document.querySelectorAll('.invoices-table tbody tr');

    rows.forEach(row => {
        const dateText = row.querySelector('td:first-child').textContent;
        const [day, month, year] = dateText.split(' ')[0].split('.');
        const rowDate = new Date(year, month - 1, day);

        if (rowDate >= fromDate && rowDate <= toDate) {
            row.setAttribute('data-filtered', 'true');
        } else {
            row.setAttribute('data-filtered', 'false');
        }
    });

    currentPage = 1;
    updatePagination();
}

// Filtreyi temizle
function clearFilter() {
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    document.getElementById('invoiceSearch').value = '';

    const rows = document.querySelectorAll('.invoices-table tbody tr');
    rows.forEach(row => {
        row.setAttribute('data-filtered', 'true');
    });

    currentPage = 1;
    updatePagination();
}

// Fatura görüntüle
function viewInvoice(invoiceUrl) {
    window.open(invoiceUrl, '_blank');
}

// Pagination güncelle
function updatePagination() {
    const rows = document.querySelectorAll('.invoices-table tbody tr');

    // Filtrelenmiş satırları al
    const filteredRows = Array.from(rows).filter(row => {
        return row.getAttribute('data-filtered') !== 'false';
    });

    const totalItems = filteredRows.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));

    // Toplam sayfa sayısını güncelle
    document.getElementById('totalPages').textContent = totalPages;

    // Mevcut sayfa sınırını kontrol et
    if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    document.getElementById('currentPage').textContent = currentPage;

    // Satırları sayfalara göre göster/gizle
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    // Önce tüm satırları gizle
    rows.forEach(row => {
        row.style.display = 'none';
    });

    // Sadece filtrelenmiş ve sayfalamadaki satırları göster
    filteredRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = '';
        }
    });

    // Pagination butonlarını güncelle
    updatePaginationButtons();
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