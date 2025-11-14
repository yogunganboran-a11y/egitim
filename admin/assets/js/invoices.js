// Faturalar Sayfası - JavaScript

let currentPage = 1;
let itemsPerPage = 25;

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    loadInvoices();
});

// Faturaları yükle
function loadInvoices() {
    updatePagination();
}

// Fatura ara
function searchInvoices() {
    const searchTerm = document.getElementById('invoiceSearch').value.toLowerCase();
    const rows = document.querySelectorAll('.invoices-table tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
    
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
    
    const rows = document.querySelectorAll('.invoices-table tbody tr');
    
    rows.forEach(row => {
        const dateText = row.querySelector('td:first-child').textContent;
        const [day, month, year] = dateText.split(' ')[0].split('.');
        const rowDate = new Date(year, month - 1, day);
        
        if (rowDate >= fromDate && rowDate <= toDate) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updatePagination();
}

// Filtreyi temizle
function clearFilter() {
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    
    const rows = document.querySelectorAll('.invoices-table tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    updatePagination();
}

// Fatura görüntüle
function viewInvoice(invoiceUrl) {
    window.open(invoiceUrl, '_blank');
}

// Pagination güncelle
function updatePagination() {
    const rows = document.querySelectorAll('.invoices-table tbody tr');
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    const totalItems = visibleRows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
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