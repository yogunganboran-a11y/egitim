<?php
$page_title = 'IP Takip';
include 'includes/header.php';

// Demo IP verileri
$ip_data = [];
$ips = ['192.168.1.', '10.0.0.', '172.16.0.', '203.0.113.'];
$devices = ['Desktop', 'Mobile', 'Tablet'];
$browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
$locations = ['İstanbul, TR', 'Ankara, TR', 'İzmir, TR', 'Bursa, TR', 'Antalya, TR'];

for ($i = 1; $i <= 100; $i++) {
    $ip = $ips[array_rand($ips)] . rand(1, 254);
    $visits = rand(1, 50);
    
    $ip_data[] = [
        'id' => $i,
        'ip' => $ip,
        'visits' => $visits,
        'location' => $locations[array_rand($locations)],
        'device' => $devices[array_rand($devices)],
        'browser' => $browsers[array_rand($browsers)],
        'os' => 'Windows 11',
        'last_visit' => date('d.m.Y H:i', strtotime("-" . rand(0, 72) . " hours")),
        'first_visit' => date('d.m.Y H:i', strtotime("-" . rand(1, 30) . " days")),
        'pages' => [
            ['url' => '/index.php', 'time' => date('d.m.Y H:i', strtotime("-1 hour"))],
            ['url' => '/egitimler.php', 'time' => date('d.m.Y H:i', strtotime("-2 hours"))],
            ['url' => '/iletisim.php', 'time' => date('d.m.Y H:i', strtotime("-3 hours"))]
        ]
    ];
}

// İstatistikler
$total_visitors = count($ip_data);
$total_visits = array_sum(array_column($ip_data, 'visits'));
$live_visitors = rand(5, 15);
$ads_visits = round($total_visitors * 0.35);
$buyers_visits = round($total_visitors * 0.18);
?>

<link rel="stylesheet" href="assets/css/ip-tracking.css">

<div class="ip-tracking-header">
    <div class="page-title">
        <h1>IP Takip</h1>
        <p class="page-subtitle">Ziyaretçi analizi ve sayfa takibi</p>
    </div>
    
    <div class="search-ip">
        <i class="fas fa-search"></i>
        <input type="text" id="ipSearch" placeholder="IP adresi, lokasyon veya cihaz ile ara..." onkeyup="searchIP()">
    </div>
</div>

<!-- Filtreler -->
<div class="filters-row">
    <div class="filter-group">
        <label>Zaman:</label>
        <select id="timeFilter" class="form-control" onchange="filterData()">
            <option value="all">Tümü</option>
            <option value="today" selected>Bugün</option>
            <option value="yesterday">Dün</option>
            <option value="week">Bu Hafta</option>
            <option value="month">Bu Ay</option>
        </select>
    </div>
    
    <div class="filter-group">
        <label>Cihaz:</label>
        <select id="deviceFilter" class="form-control" onchange="filterData()">
            <option value="all">Tümü</option>
            <option value="desktop">Desktop</option>
            <option value="mobile">Mobile</option>
            <option value="tablet">Tablet</option>
        </select>
    </div>
    
    <div class="filter-group">
        <label>İstatistik:</label>
        <select id="statFilter" class="form-control" onchange="filterData()">
            <option value="all">Tümü</option>
            <option value="visits">Toplam Ziyaret</option>
            <option value="unique">Benzersiz</option>
            <option value="live">Anlık</option>
            <option value="ads">Reklamdan</option>
            <option value="buyers">Satın Alan</option>
        </select>
    </div>
    
    <div class="filter-group">
        <label>Tarih:</label>
        <input type="date" id="dateFrom" class="form-control">
        <span style="color: #8b9cbc;">-</span>
        <input type="date" id="dateTo" class="form-control">
    </div>
    
    <button class="btn btn-primary btn-sm" onclick="filterData()">
        <i class="fas fa-filter"></i> Filtrele
    </button>
    
    <button class="btn btn-success btn-sm" onclick="exportToExcel()">
        <i class="fas fa-file-excel"></i> Excel İndir
    </button>
    
    <button class="btn btn-secondary btn-sm" onclick="clearFilters()">
        <i class="fas fa-times"></i> Temizle
    </button>
</div>

<!-- İstatistikler -->
<div class="ip-stats">
    <div class="ip-stat-card blue">
        <div class="stat-header">
            <div>
                <div class="stat-label">Toplam Ziyaret</div>
                <div class="stat-value" id="totalVisits"><?php echo $total_visits; ?></div>
            </div>
            <div class="stat-icon-circle">
                <i class="fas fa-eye"></i>
            </div>
        </div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> %15.7 artış
        </div>
    </div>
    
    <div class="ip-stat-card green">
        <div class="stat-header">
            <div>
                <div class="stat-label">Benzersiz Ziyaretçi</div>
                <div class="stat-value" id="uniqueVisitors"><?php echo $total_visitors; ?></div>
            </div>
            <div class="stat-icon-circle">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> %12.5 artış
        </div>
    </div>
    
    <div class="ip-stat-card orange">
        <div class="stat-header">
            <div>
                <div class="stat-label">Anlık Ziyaretçi</div>
                <div class="stat-value" id="liveVisitors"><?php echo $live_visitors; ?></div>
            </div>
            <div class="stat-icon-circle">
                <i class="fas fa-broadcast-tower"></i>
            </div>
        </div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> %8.3 artış
        </div>
    </div>
    
    <div class="ip-stat-card purple">
        <div class="stat-header">
            <div>
                <div class="stat-label">Reklamdan Ziyaret</div>
                <div class="stat-value" id="adsVisits"><?php echo $ads_visits; ?></div>
            </div>
            <div class="stat-icon-circle">
                <i class="fas fa-ad"></i>
            </div>
        </div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> %22.1 artış
        </div>
    </div>
    
    <div class="ip-stat-card cyan">
        <div class="stat-header">
            <div>
                <div class="stat-label">Satın Alan Ziyaret</div>
                <div class="stat-value" id="buyersVisits"><?php echo $buyers_visits; ?></div>
            </div>
            <div class="stat-icon-circle">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> %5.2 artış
        </div>
    </div>
</div>

<!-- IP Tablosu -->
<div class="ip-table">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>IP ADRESİ</th>
                    <th>ZİYARET SAYISI</th>
                    <th>LOKASYON</th>
                    <th>CİHAZ</th>
                    <th>TARAYICI</th>
                    <th>SON ZİYARET</th>
                    <th>DETAY</th>
                </tr>
            </thead>
            <tbody id="ipTableBody">
                <?php foreach ($ip_data as $data): ?>
                <tr data-device="<?php echo strtolower($data['device']); ?>">
                    <td>
                        <span class="ip-address"><?php echo $data['ip']; ?></span>
                    </td>
                    <td>
                        <span class="visit-count"><?php echo $data['visits']; ?></span>
                    </td>
                    <td>
                        <span class="location-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo $data['location']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="device-icon">
                            <i class="fas fa-<?php echo $data['device'] == 'Desktop' ? 'desktop' : ($data['device'] == 'Mobile' ? 'mobile-alt' : 'tablet-alt'); ?>"></i>
                            <?php echo $data['device']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="browser-badge">
                            <i class="fab fa-<?php echo strtolower($data['browser']); ?>"></i>
                            <?php echo $data['browser']; ?>
                        </span>
                    </td>
                    <td><?php echo $data['last_visit']; ?></td>
                    <td>
                        <button class="detail-btn" onclick='showIPDetails(<?php echo json_encode($data); ?>)'>
                            <i class="fas fa-info-circle"></i> Detay
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

<!-- IP Detay Modal -->
<div class="modal ip-detail-modal" id="ipDetailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>IP Detayları</h2>
            <button class="close-modal" onclick="closeModal('ipDetailModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="ipDetailContent"></div>
    </div>
</div>

<script src="assets/js/ip-tracking.js"></script>

<?php include 'includes/footer.php'; ?>