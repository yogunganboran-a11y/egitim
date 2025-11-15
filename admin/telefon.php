<?php
$page_title = 'Telefon';
include 'includes/header.php';

// Demo arama verileri
$calls = [];
$types = ['incoming', 'outgoing'];
$typeLabels = ['Gelen', 'Giden'];
$durations = ['2:15', '5:42', '1:30', '8:45', '3:20', '6:10', '12:05', '4:18'];

for ($i = 1; $i <= 100; $i++) {
    $typeIndex = ($i - 1) % 2;
    $type = $types[$typeIndex];
    
    $phoneBase = 530 + ($i % 10);
    $phoneMid = str_pad($i, 3, '0', STR_PAD_LEFT);
    $phoneEnd = str_pad($i * 10, 4, '0', STR_PAD_LEFT);
    
    $calls[] = [
        'id' => $i,
        'phone' => "+90 {$phoneBase} {$phoneMid} {$phoneEnd}",
        'type' => $type,
        'type_label' => $typeLabels[$typeIndex],
        'date' => date('d.m.Y H:i', strtotime("-{$i} hours")),
        'duration' => $durations[($i - 1) % 8],
        'has_recording' => true
    ];
}

// İstatistikler
$total_calls = count($calls);
$incoming_calls = count(array_filter($calls, fn($c) => $c['type'] === 'incoming'));
$outgoing_calls = count(array_filter($calls, fn($c) => $c['type'] === 'outgoing'));
?>

<link rel="stylesheet" href="assets/css/telefon.css">

<div class="page-header">
    <h1><i class="fas fa-phone"></i> Telefon Aramaları</h1>
    <p class="page-subtitle">Gelen ve giden çağrı kayıtları</p>
</div>

<!-- Filtreler -->
<div class="phone-filters">
    <div class="filter-group">
        <label>Ara:</label>
        <input type="text" id="callSearch" class="form-control" placeholder="Telefon numarası..." onkeyup="applyFilters()">
    </div>
    
    <div class="filter-group">
        <label>Tip:</label>
        <select id="typeFilter" class="form-control" onchange="applyFilters()">
            <option value="all">Tümü</option>
            <option value="incoming">Gelen</option>
            <option value="outgoing">Giden</option>
        </select>
    </div>
    
    <div class="filter-group">
        <label>Tarih:</label>
        <input type="date" id="dateFrom" class="form-control">
        <span style="color: #8b9cbc;">-</span>
        <input type="date" id="dateTo" class="form-control">
    </div>
    
    <button class="btn btn-primary btn-sm" onclick="applyFilters()">
        <i class="fas fa-filter"></i> Filtrele
    </button>
    
    <button class="btn btn-secondary btn-sm" onclick="clearFilters()">
        <i class="fas fa-times"></i> Temizle
    </button>
</div>

<!-- İstatistikler -->
<div class="phone-stats">
    <div class="phone-stat-card total">
        <div class="stat-header">
            <div>
                <div class="stat-label">Toplam Arama</div>
                <div class="stat-value" id="totalCalls"><?php echo $total_calls; ?></div>
            </div>
            <div class="stat-icon-circle">
                <i class="fas fa-phone"></i>
            </div>
        </div>
    </div>
    
    <div class="phone-stat-card incoming">
        <div class="stat-header">
            <div>
                <div class="stat-label">Gelen Aramalar</div>
                <div class="stat-value" id="incomingCalls"><?php echo $incoming_calls; ?></div>
            </div>
            <div class="stat-icon-circle">
                <i class="fas fa-phone"></i>
            </div>
        </div>
    </div>
    
    <div class="phone-stat-card outgoing">
        <div class="stat-header">
            <div>
                <div class="stat-label">Giden Aramalar</div>
                <div class="stat-value" id="outgoingCalls"><?php echo $outgoing_calls; ?></div>
            </div>
            <div class="stat-icon-circle">
                <i class="fas fa-phone"></i>
            </div>
        </div>
    </div>
</div>

<!-- Arama Tablosu -->
<div class="calls-table">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>TARİH</th>
                    <th>TELEFON</th>
                    <th>TİP</th>
                    <th>SÜRE</th>
                    <th>KAYIT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($calls as $call): ?>
                <tr data-type="<?php echo $call['type']; ?>" onclick="showCallDetails(<?php echo $call['id']; ?>)">
                    <td><?php echo $call['date']; ?></td>
                    <td>
                        <span class="phone-number"><?php echo $call['phone']; ?></span>
                    </td>
                    <td>
                        <span class="call-type <?php echo $call['type']; ?>">
                            <i class="fas fa-<?php echo $call['type'] === 'incoming' ? 'phone-alt' : ($call['type'] === 'outgoing' ? 'phone' : 'phone-slash'); ?>"></i>
                            <?php echo $call['type_label']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="duration-badge">
                            <i class="far fa-clock"></i>
                            <?php echo $call['duration']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($call['has_recording']): ?>
                        <i class="fas fa-microphone" style="color: #10b981;" title="Kayıt mevcut"></i>
                        <?php else: ?>
                        <i class="fas fa-times" style="color: #ef4444;" title="Kayıt yok"></i>
                        <?php endif; ?>
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
        
        <div class="pagination-numbers" id="paginationNumbers"></div>
        
        <button class="pagination-btn" onclick="changePage('next')" id="nextPage">
            <i class="fas fa-angle-right"></i>
        </button>
        <button class="pagination-btn" onclick="changePage('last')" id="lastPage">
            <i class="fas fa-angle-double-right"></i>
        </button>
        
        <span class="pagination-info">
            Sayfa <span id="currentPage">1</span> / <span id="totalPages">4</span>
        </span>
    </div>
</div>

<!-- Arama Detay Modal -->
<div class="modal call-detail-modal" id="callDetailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Arama Detayları</h2>
            <button class="close-modal" onclick="closeModal('callDetailModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="callDetailContent"></div>
    </div>
</div>

<script src="assets/js/telefon.js"></script>

<?php include 'includes/footer.php'; ?>