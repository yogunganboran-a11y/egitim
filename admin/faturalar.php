<?php
$page_title = 'Faturalar';
include 'includes/header.php';

// Demo fatura verileri (Gerçek uygulamada veritabanından gelecek)
$invoices = [];
for ($i = 1; $i <= 50; $i++) {
    $invoices[] = [
        'id' => $i,
        'date' => date('d.m.Y H:i', strtotime("-$i days")),
        'name' => 'Kullanıcı',
        'surname' => 'Test ' . $i,
        'tckn' => '1234567890' . $i,
        'phone' => '0532 ' . str_pad($i, 3, '0', STR_PAD_LEFT) . ' 4567',
        'invoice_url' => '#'
    ];
}
?>

<link rel="stylesheet" href="assets/css/invoices.css">

<div class="page-header">
    <div class="page-title">
        <h1>Faturalar</h1>
        <p class="page-subtitle">Otomatik kesilen faturaları görüntüleyin</p>
    </div>
    
    <div class="search-invoice">
        <i class="fas fa-search"></i>
        <input type="text" id="invoiceSearch" placeholder="Ad, soyad, TCKN veya telefon ile ara..." onkeyup="searchInvoices()">
    </div>
</div>

<!-- İstatistikler -->
<div class="invoice-stats">
    <div class="stat-card">
        <i class="fas fa-file-invoice stat-icon"></i>
        <div class="stat-label">Toplam Fatura</div>
        <div class="stat-value"><?php echo count($invoices); ?></div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-calendar-alt stat-icon"></i>
        <div class="stat-label">Bu Ay</div>
        <div class="stat-value"><?php echo min(30, count($invoices)); ?></div>
    </div>
    
    <div class="stat-card">
        <i class="fas fa-calendar-day stat-icon"></i>
        <div class="stat-label">Bugün</div>
        <div class="stat-value"><?php echo min(5, count($invoices)); ?></div>
    </div>
</div>

<!-- Tarih Filtresi -->
<div class="date-filter-container">
    <label>Tarih Aralığı:</label>
    <input type="date" id="dateFrom" class="form-control">
    <span style="color: #8b9cbc;">-</span>
    <input type="date" id="dateTo" class="form-control">
    <button class="btn btn-primary btn-sm" onclick="filterByDate()">
        <i class="fas fa-filter"></i> Filtrele
    </button>
    <button class="btn btn-secondary btn-sm" onclick="clearFilter()">
        <i class="fas fa-times"></i> Temizle
    </button>
</div>

<!-- Fatura Tablosu -->
<div class="invoices-table">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>TARİH</th>
                    <th>AD</th>
                    <th>SOYAD</th>
                    <th>TCKN</th>
                    <th>TELEFON</th>
                    <th>GÖRÜNTÜLE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?php echo $invoice['date']; ?></td>
                    <td><?php echo $invoice['name']; ?></td>
                    <td><?php echo $invoice['surname']; ?></td>
                    <td><?php echo $invoice['tckn']; ?></td>
                    <td><?php echo $invoice['phone']; ?></td>
                    <td>
                        <button class="view-invoice-btn" onclick="viewInvoice('<?php echo $invoice['invoice_url']; ?>')">
                            <i class="fas fa-file-pdf"></i>
                            PDF
                        </button>
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
        </div>
        
        <button class="pagination-btn" onclick="changePage('next')" id="nextPage">
            <i class="fas fa-angle-right"></i>
        </button>
        <button class="pagination-btn" onclick="changePage('last')" id="lastPage">
            <i class="fas fa-angle-double-right"></i>
        </button>
        
        <span class="pagination-info">
            Sayfa <span id="currentPage">1</span> / <span id="totalPages">2</span>
        </span>
    </div>
</div>

<script src="assets/js/invoices.js"></script>

<?php include 'includes/footer.php'; ?>