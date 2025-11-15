<?php
$page_title = 'Geri Arama Listesi';
include 'includes/header.php';

// Demo geri arama verileri - AI tarafından oluşturulmuş
$callbacks = [];
$names = ['Ahmet Yılmaz', 'Mehmet Demir', 'Ayşe Kaya', 'Fatma Özdemir', 'Ali Şahin', 'Zeynep Çelik', 'Mustafa Aydın', 'Elif Kara', 'Hasan Yıldız', 'Selin Arslan'];
$sources = ['whatsapp', 'phone'];
$reasons = [
    'Eğitim fiyatı sordu, karar vermedi',
    'Ürün stoğu hakkında bilgi almak istedi',
    'İade süreci hakkında soru sordu',
    'Sertifika geçerliliği konusunda detay istedi',
    'Taksit seçenekleri hakkında bilgi istedi',
    'Kargo takibi yapmak istedi',
    'Video içerikleri hakkında soru sordu',
    'Eğitim süresi ve içeriği hakkında detay istedi',
    'Ödeme yöntemleri hakkında bilgi aldı',
    'Toplu alım indirimi sordu'
];
$priorities = ['high', 'medium', 'low'];
$priorityLabels = ['Yüksek', 'Orta', 'Düşük'];
$statuses = ['pending', 'called', 'completed', 'failed'];
$statusLabels = ['Bekliyor', 'Arandı', 'Tamamlandı', 'Ulaşılamadı'];

for ($i = 1; $i <= 45; $i++) {
    $nameIndex = ($i - 1) % 10;
    $source = $sources[$i % 2];
    $priorityIndex = ($i - 1) % 3;
    $statusIndex = 0; // Varsayılan bekliyor

    if ($i % 5 === 0) $statusIndex = 1; // Arandı
    if ($i % 7 === 0) $statusIndex = 2; // Tamamlandı
    if ($i % 11 === 0) $statusIndex = 3; // Ulaşılamadı

    $phoneBase = 530 + ($i % 10);
    $phoneMid = str_pad($i, 3, '0', STR_PAD_LEFT);
    $phoneEnd = str_pad($i * 10, 4, '0', STR_PAD_LEFT);

    $hoursAgo = $i;
    $dateTime = date('d.m.Y H:i', strtotime("-{$hoursAgo} hours"));

    $callbacks[] = [
        'id' => $i,
        'name' => $names[$nameIndex] . ' ' . $i,
        'phone' => "0{$phoneBase}{$phoneMid}{$phoneEnd}",
        'source' => $source,
        'reason' => $reasons[($i - 1) % 10],
        'priority' => $priorities[$priorityIndex],
        'priority_label' => $priorityLabels[$priorityIndex],
        'status' => $statuses[$statusIndex],
        'status_label' => $statusLabels[$statusIndex],
        'detected_at' => $dateTime,
        'ai_score' => rand(65, 98)
    ];
}
?>

<link rel="stylesheet" href="assets/css/geri-arama-listesi.css">

<div class="page-container">
    <!-- Sayfa Başlığı -->
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="fas fa-phone-volume"></i>
                Geri Arama Listesi
            </h1>
            <p class="page-subtitle">AI tarafından analiz edilen konuşmalardan oluşturulmuş geri arama listesi</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-secondary" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i>
                Excel'e Aktar
            </button>
            <button class="btn btn-primary" onclick="refreshAIAnalysis()">
                <i class="fas fa-sync-alt"></i>
                AI Analizi Yenile
            </button>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="stats-grid">
        <div class="stat-card stat-pending">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <div class="stat-value" id="statPending">28</div>
                <div class="stat-label">Bekleyen</div>
            </div>
        </div>

        <div class="stat-card stat-called">
            <div class="stat-icon">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div class="stat-details">
                <div class="stat-value" id="statCalled">9</div>
                <div class="stat-label">Arandı</div>
            </div>
        </div>

        <div class="stat-card stat-completed">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <div class="stat-value" id="statCompleted">6</div>
                <div class="stat-label">Tamamlandı</div>
            </div>
        </div>

        <div class="stat-card stat-failed">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-details">
                <div class="stat-value" id="statFailed">2</div>
                <div class="stat-label">Ulaşılamadı</div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="filters-container">
        <div class="filter-group">
            <label><i class="fas fa-filter"></i> Durum</label>
            <select id="statusFilter" class="filter-select" onchange="applyFilters()">
                <option value="all">Tümü</option>
                <option value="pending" selected>Bekleyen</option>
                <option value="called">Arandı</option>
                <option value="completed">Tamamlandı</option>
                <option value="failed">Ulaşılamadı</option>
            </select>
        </div>

        <div class="filter-group">
            <label><i class="fas fa-exclamation-triangle"></i> Öncelik</label>
            <select id="priorityFilter" class="filter-select" onchange="applyFilters()">
                <option value="all">Tümü</option>
                <option value="high">Yüksek</option>
                <option value="medium">Orta</option>
                <option value="low">Düşük</option>
            </select>
        </div>

        <div class="filter-group">
            <label><i class="fas fa-comment-dots"></i> Kaynak</label>
            <select id="sourceFilter" class="filter-select" onchange="applyFilters()">
                <option value="all">Tümü</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="phone">Telefon</option>
            </select>
        </div>

        <div class="filter-group">
            <label><i class="fas fa-search"></i> Ara</label>
            <input type="text" id="searchInput" class="filter-input" placeholder="İsim veya telefon ara..." onkeyup="applyFilters()">
        </div>

        <button class="btn-clear-filters" onclick="clearFilters()">
            <i class="fas fa-times"></i>
            Filtreleri Temizle
        </button>
    </div>

    <!-- Tablo -->
    <div class="table-container">
        <table class="callback-table">
            <thead>
                <tr>
                    <th class="sortable" onclick="sortTable('name')">
                        İsim
                        <i class="fas fa-sort sort-icon"></i>
                    </th>
                    <th>Telefon</th>
                    <th>Kaynak</th>
                    <th class="sortable" onclick="sortTable('reason')">
                        Arama Sebebi
                        <i class="fas fa-sort sort-icon"></i>
                    </th>
                    <th class="sortable" onclick="sortTable('priority')">
                        Öncelik
                        <i class="fas fa-sort sort-icon"></i>
                    </th>
                    <th class="sortable" onclick="sortTable('score')">
                        AI Skoru
                        <i class="fas fa-sort sort-icon"></i>
                    </th>
                    <th class="sortable" onclick="sortTable('date')">
                        Tespit Zamanı
                        <i class="fas fa-sort sort-icon"></i>
                    </th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody id="callbackTableBody">
                <?php foreach ($callbacks as $callback): ?>
                <tr data-id="<?php echo $callback['id']; ?>"
                    data-status="<?php echo $callback['status']; ?>"
                    data-priority="<?php echo $callback['priority']; ?>"
                    data-source="<?php echo $callback['source']; ?>"
                    data-name="<?php echo strtolower($callback['name']); ?>"
                    data-phone="<?php echo $callback['phone']; ?>">

                    <td class="name-cell">
                        <strong><?php echo $callback['name']; ?></strong>
                    </td>

                    <td class="phone-cell">
                        <a href="tel:<?php echo $callback['phone']; ?>" class="phone-link">
                            <i class="fas fa-phone"></i>
                            <?php echo $callback['phone']; ?>
                        </a>
                    </td>

                    <td>
                        <span class="source-badge source-<?php echo $callback['source']; ?>">
                            <i class="fab fa-<?php echo $callback['source']; ?>"></i>
                            <?php echo $callback['source'] === 'whatsapp' ? 'WhatsApp' : 'Telefon'; ?>
                        </span>
                    </td>

                    <td class="reason-cell">
                        <span class="reason-text"><?php echo $callback['reason']; ?></span>
                    </td>

                    <td>
                        <span class="priority-badge priority-<?php echo $callback['priority']; ?>">
                            <?php echo $callback['priority_label']; ?>
                        </span>
                    </td>

                    <td>
                        <div class="ai-score">
                            <div class="score-bar">
                                <div class="score-fill" style="width: <?php echo $callback['ai_score']; ?>%"></div>
                            </div>
                            <span class="score-text"><?php echo $callback['ai_score']; ?>%</span>
                        </div>
                    </td>

                    <td class="date-cell">
                        <?php echo $callback['detected_at']; ?>
                    </td>

                    <td>
                        <select class="status-select status-<?php echo $callback['status']; ?>"
                                onchange="updateStatus(<?php echo $callback['id']; ?>, this.value)">
                            <option value="pending" <?php echo $callback['status'] === 'pending' ? 'selected' : ''; ?>>Bekleyor</option>
                            <option value="called" <?php echo $callback['status'] === 'called' ? 'selected' : ''; ?>>Arandı</option>
                            <option value="completed" <?php echo $callback['status'] === 'completed' ? 'selected' : ''; ?>>Tamamlandı</option>
                            <option value="failed" <?php echo $callback['status'] === 'failed' ? 'selected' : ''; ?>>Ulaşılamadı</option>
                        </select>
                    </td>

                    <td class="actions-cell">
                        <button class="action-btn btn-call" onclick="callCustomer('<?php echo $callback['phone']; ?>')" title="Ara">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="action-btn btn-whatsapp" onclick="openWhatsApp('<?php echo $callback['phone']; ?>')" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button class="action-btn btn-details" onclick="showDetails(<?php echo $callback['id']; ?>)" title="Detaylar">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button class="action-btn btn-delete" onclick="deleteCallback(<?php echo $callback['id']; ?>)" title="Sil">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        <div class="pagination-info">
            Toplam <strong id="totalItems">45</strong> kayıt
        </div>
        <div class="pagination-controls">
            <button class="pagination-btn" id="firstPage" onclick="changePage(1)">
                <i class="fas fa-angle-double-left"></i>
            </button>
            <button class="pagination-btn" id="prevPage" onclick="changePage('prev')">
                <i class="fas fa-angle-left"></i>
            </button>
            <div id="paginationNumbers" class="pagination-numbers"></div>
            <button class="pagination-btn" id="nextPage" onclick="changePage('next')">
                <i class="fas fa-angle-right"></i>
            </button>
            <button class="pagination-btn" id="lastPage" onclick="changePage('last')">
                <i class="fas fa-angle-double-right"></i>
            </button>
        </div>
        <div class="pagination-size">
            <select id="itemsPerPage" onchange="changeItemsPerPage()">
                <option value="10">10 / sayfa</option>
                <option value="25" selected>25 / sayfa</option>
                <option value="50">50 / sayfa</option>
                <option value="100">100 / sayfa</option>
            </select>
        </div>
    </div>
</div>

<!-- Detay Modalı -->
<div class="modal" id="detailModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2><i class="fas fa-info-circle"></i> Geri Arama Detayları</h2>
            <button class="close-modal" onclick="closeModal('detailModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="detailModalContent">
            <!-- Dinamik olarak doldurulacak -->
        </div>
    </div>
</div>

<script src="assets/js/geri-arama-listesi.js"></script>

<?php include 'includes/footer.php'; ?>
