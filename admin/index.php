<?php
$page_title = 'Satışlar';
include 'includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-title">
        <h1>Satışlar</h1>
        <p class="page-subtitle">Satış raporları ve istatistikler</p>
    </div>
</div>

<!-- Filtreler -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button class="btn btn-primary btn-sm active" onclick="setTimeFilter('today')">
                <i class="fas fa-calendar-day"></i> Bugün
            </button>
            <button class="btn btn-secondary btn-sm" onclick="setTimeFilter('yesterday')">
                <i class="fas fa-calendar"></i> Dün
            </button>
            <button class="btn btn-secondary btn-sm" onclick="setTimeFilter('week')">
                <i class="fas fa-calendar-week"></i> Bu Hafta
            </button>
            <button class="btn btn-secondary btn-sm" onclick="setTimeFilter('month')">
                <i class="fas fa-calendar-alt"></i> Bu Ay
            </button>
        </div>
        
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <input type="date" class="form-control" style="width: auto;" id="dateFrom" value="2025-11-01">
            <span style="color: var(--text-secondary);">-</span>
            <input type="date" class="form-control" style="width: auto;" id="dateTo" value="2025-11-12">
            <button class="btn btn-primary" onclick="applyDateFilter()">
                <i class="fas fa-filter"></i> Filtrele
            </button>
        </div>
    </div>
</div>

<!-- İstatistik Kartları -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Online Kayıtlar -->
    <div class="stat-card blue">
        <div class="stat-icon">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Online Kayıtlar</div>
            <div class="stat-value">156</div>
            <div class="stat-description">+8% geçen haftaya göre</div>
        </div>
    </div>
    
    <!-- Havale Kayıtları -->
    <div class="stat-card purple">
        <div class="stat-icon">
            <i class="fas fa-money-bill-transfer"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Havale Kayıtları</div>
            <div class="stat-value">24</div>
            <div class="stat-description">+12% geçen haftaya göre</div>
        </div>
    </div>
    
    <!-- Firma Kayıtları -->
    <div class="stat-card teal">
        <div class="stat-icon">
            <i class="fas fa-building"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Firma Kayıtları</div>
            <div class="stat-value">8</div>
            <div class="stat-description">+3 yeni firma</div>
        </div>
    </div>
    
    <!-- Toplam Kayıtlar -->
    <div class="stat-card orange">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Toplam Kayıtlar</div>
            <div class="stat-value">188</div>
            <div class="stat-description">Son 7 gün</div>
        </div>
    </div>
</div>

<!-- Grafik Alanı -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600;">Satış Grafiği</h2>
        <div style="display: flex; gap: 1rem; font-size: 0.85rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 12px; height: 12px; background: #3b82f6; border-radius: 50%;"></span>
                <span style="color: var(--text-secondary);">Online</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 12px; height: 12px; background: #8b5cf6; border-radius: 50%;"></span>
                <span style="color: var(--text-secondary);">Havale</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="width: 12px; height: 12px; background: var(--teal); border-radius: 50%;"></span>
                <span style="color: var(--text-secondary);">Firma</span>
            </div>
        </div>
    </div>
    
    <canvas id="salesChart" style="max-height: 400px;"></canvas>
</div>

<style>
    .stat-card {
        background: linear-gradient(135deg, rgba(30, 45, 68, 0.95) 0%, rgba(26, 41, 66, 0.9) 100%);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.75rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        border: 1px solid;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }
    
    .stat-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
    }
    
    .stat-card.blue { 
        border-color: var(--blue);
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
    }
    .stat-card.blue:hover { 
        box-shadow: 0 8px 32px var(--blue-glow);
    }
    
    .stat-card.purple { 
        border-color: var(--purple);
        box-shadow: 0 4px 20px rgba(139, 92, 246, 0.15);
    }
    .stat-card.purple:hover { 
        box-shadow: 0 8px 32px var(--purple-glow);
    }
    
    .stat-card.teal { 
        border-color: var(--teal);
        box-shadow: 0 4px 20px rgba(20, 184, 166, 0.15);
    }
    .stat-card.teal:hover { 
        box-shadow: 0 8px 32px var(--teal-glow);
    }
    
    .stat-card.orange { 
        border-color: var(--orange);
        box-shadow: 0 4px 20px rgba(249, 115, 22, 0.15);
    }
    .stat-card.orange:hover { 
        box-shadow: 0 8px 32px var(--orange-glow);
    }
    
    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.9rem;
        flex-shrink: 0;
        position: relative;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    .stat-card.blue .stat-icon {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.25) 0%, rgba(59, 130, 246, 0.1) 100%);
        color: var(--blue);
        box-shadow: 0 4px 16px var(--blue-glow);
    }
    
    .stat-card.purple .stat-icon {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.25) 0%, rgba(139, 92, 246, 0.1) 100%);
        color: var(--purple);
        box-shadow: 0 4px 16px var(--purple-glow);
    }
    
    .stat-card.teal .stat-icon {
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.25) 0%, rgba(20, 184, 166, 0.1) 100%);
        color: var(--teal);
        box-shadow: 0 4px 16px var(--teal-glow);
    }
    
    .stat-card.orange .stat-icon {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.25) 0%, rgba(249, 115, 22, 0.1) 100%);
        color: var(--orange);
        box-shadow: 0 4px 16px var(--orange-glow);
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .stat-description {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }
    
    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .btn-sm.active {
        box-shadow: 0 4px 16px var(--blue-glow);
    }
</style>

<!-- Detaylı Rapor Tablosu -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600;">Detaylı Satış Raporu</h2>
        <button class="btn btn-success" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Excel'e Aktar
        </button>
    </div>
    
    <!-- Online Satışlar -->
    <div style="background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 600; color: #3b82f6; margin-bottom: 0.25rem;">
                    <i class="fas fa-credit-card"></i> Online Satışlar
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary);">Kredi kartı / Banka kartı ödemeleri</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.25rem;">156 Adet</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #3b82f6;">70.200 ₺</div>
            </div>
        </div>
    </div>
    
    <!-- Havale Satışlar -->
    <div style="background: rgba(139, 92, 246, 0.1); border-left: 4px solid #8b5cf6; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 600; color: #8b5cf6; margin-bottom: 0.25rem;">
                    <i class="fas fa-money-bill-transfer"></i> Havale Satışlar
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary);">Banka havalesi ödemeleri</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.25rem;">24 Adet</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #8b5cf6;">10.800 ₺</div>
            </div>
        </div>
    </div>
    
    <!-- Firma Satışları -->
    <div style="background: rgba(20, 184, 166, 0.1); border-left: 4px solid #14b8a6; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="margin-bottom: 1rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #14b8a6; margin-bottom: 0.5rem;">
                <i class="fas fa-building"></i> Firma Satışları
            </h3>
            <p style="font-size: 0.9rem; color: var(--text-secondary);">Toplu eğitim alımları</p>
        </div>
        
        <!-- Firma Listesi -->
        <div style="display: grid; gap: 0.75rem;">
            <div style="background: rgba(255, 255, 255, 0.03); border-radius: 8px; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">ABC Lojistik Ltd. Şti.</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">15 kişi - İSG Eğitimi</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.25rem;">15 Adet</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #14b8a6;">6.750 ₺</div>
                </div>
            </div>
            
            <div style="background: rgba(255, 255, 255, 0.03); border-radius: 8px; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">XYZ İnşaat A.Ş.</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">8 kişi - Forklift + İSG</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.25rem;">8 Adet</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #14b8a6;">4.800 ₺</div>
                </div>
            </div>
            
            <div style="background: rgba(255, 255, 255, 0.03); border-radius: 8px; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">Mega Depo Tic. Ltd.</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">5 kişi - İSG Eğitimi</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.25rem;">5 Adet</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: #14b8a6;">2.250 ₺</div>
                </div>
            </div>
        </div>
        
        <!-- Firma Toplamı -->
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(20, 184, 166, 0.2); display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 600; color: var(--text-primary);">Toplam Firma Satışları</div>
            <div style="text-align: right;">
                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.25rem;">28 Adet</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #14b8a6;">13.800 ₺</div>
            </div>
        </div>
    </div>
    
    <!-- Genel Toplam -->
    <div style="background: linear-gradient(135deg, rgba(249, 115, 22, 0.15), rgba(249, 115, 22, 0.05)); border: 2px solid #f97316; border-radius: 12px; padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #f97316; margin-bottom: 0.25rem;">
                    <i class="fas fa-chart-pie"></i> Genel Toplam
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary);">Tüm satış kanalları</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.25rem;">208 Toplam Satış</div>
                <div style="font-size: 2rem; font-weight: 700; color: #f97316;">94.800 ₺</div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik oluşturma
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
        datasets: [
            {
                label: 'Online',
                data: [1, 0, 0, 2, 1, 3, 5, 8, 12, 15, 18, 22, 25, 20, 18, 22, 19, 16, 14, 12, 10, 8, 5, 3],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#3b82f6',
                pointHoverBorderColor: '#fff'
            },
            {
                label: 'Havale',
                data: [0, 0, 0, 1, 0, 1, 2, 3, 1, 5, 4, 8, 6, 5, 6, 4, 3, 4, 2, 3, 1, 2, 1, 0],
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#8b5cf6',
                pointHoverBorderColor: '#fff'
            },
            {
                label: 'Firma',
                data: [0, 0, 0, 0, 0, 0, 1, 1, 1, 2, 3, 3, 4, 3, 2, 3, 2, 1, 1, 0, 0, 0, 0, 0],
                borderColor: '#14b8a6',
                backgroundColor: 'rgba(20, 184, 166, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#14b8a6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#14b8a6',
                pointHoverBorderColor: '#fff'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(10, 22, 40, 0.95)',
                titleColor: '#fff',
                bodyColor: '#8b9cbc',
                borderColor: 'rgba(59, 130, 246, 0.3)',
                borderWidth: 1,
                padding: 12,
                displayColors: true,
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y + ' satış';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(255, 255, 255, 0.05)'
                },
                ticks: {
                    color: '#8b9cbc',
                    stepSize: 5
                }
            },
            x: {
                grid: {
                    color: 'rgba(255, 255, 255, 0.05)'
                },
                ticks: {
                    color: '#8b9cbc',
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        }
    }
});

// Filtre fonksiyonları
let currentFilter = 'today';

const filterNames = {
    'today': 'Bugün',
    'yesterday': 'Dün',
    'week': 'Bu Hafta',
    'month': 'Bu Ay'
};

function setTimeFilter(period) {
    currentFilter = period;
    
    const buttons = document.querySelectorAll('.btn-sm');
    buttons.forEach(btn => {
        btn.classList.remove('active');
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
    });
    
    event.target.classList.add('active');
    event.target.classList.add('btn-primary');
    event.target.classList.remove('btn-secondary');
    
    updateChart(period);
    showNotification('Filtre uygulandı: ' + filterNames[period], 'success');
}

function updateChart(period) {
    let labels, onlineData, havaleData, firmaData;
    
    if (period === 'today' || period === 'yesterday') {
        // Saatlik veri
        labels = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];
        onlineData = [1, 0, 0, 2, 1, 3, 5, 8, 12, 15, 18, 22, 25, 20, 18, 22, 19, 16, 14, 12, 10, 8, 5, 3];
        havaleData = [0, 0, 0, 1, 0, 1, 2, 3, 1, 5, 4, 8, 6, 5, 6, 4, 3, 4, 2, 3, 1, 2, 1, 0];
        firmaData = [0, 0, 0, 0, 0, 0, 1, 1, 1, 2, 3, 3, 4, 3, 2, 3, 2, 1, 1, 0, 0, 0, 0, 0];
    } else if (period === 'week') {
        // Günlük veri - 7 gün
        labels = ['06.11.2025', '07.11.2025', '08.11.2025', '09.11.2025', '10.11.2025', '11.11.2025', '12.11.2025'];
        onlineData = [145, 160, 155, 170, 165, 158, 156];
        havaleData = [18, 22, 20, 25, 23, 26, 24];
        firmaData = [5, 8, 6, 10, 7, 9, 8];
    } else if (period === 'month') {
        // Bulunduğumuz ayın 1'inden bugüne kadar
        const today = new Date();
        const currentDay = today.getDate();
        labels = [];
        
        for (let i = 1; i <= currentDay; i++) {
            const date = new Date(today.getFullYear(), today.getMonth(), i);
            labels.push(date.toLocaleDateString('tr-TR', { day: '2-digit', month: '2-digit', year: 'numeric' }));
        }
        
        // Örnek veri - ayın bugüne kadar
        onlineData = Array.from({length: currentDay}, () => Math.floor(Math.random() * 50) + 100);
        havaleData = Array.from({length: currentDay}, () => Math.floor(Math.random() * 15) + 15);
        firmaData = Array.from({length: currentDay}, () => Math.floor(Math.random() * 8) + 3);
    }
    
    // Grafiği güncelle
    salesChart.data.labels = labels;
    salesChart.data.datasets[0].data = onlineData;
    salesChart.data.datasets[1].data = havaleData;
    salesChart.data.datasets[2].data = firmaData;
    
    // X eksenini güncelle
    if (period === 'month') {
        salesChart.options.scales.x.ticks.maxRotation = 90;
        salesChart.options.scales.x.ticks.minRotation = 90;
        salesChart.options.scales.x.ticks.maxTicksLimit = 15;
    } else if (period === 'week') {
        salesChart.options.scales.x.ticks.maxRotation = 45;
        salesChart.options.scales.x.ticks.minRotation = 45;
        salesChart.options.scales.x.ticks.maxTicksLimit = 7;
    } else {
        salesChart.options.scales.x.ticks.maxRotation = 45;
        salesChart.options.scales.x.ticks.minRotation = 45;
        salesChart.options.scales.x.ticks.maxTicksLimit = 24;
    }
    
    salesChart.update();
}

function applyDateFilter() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    if (!dateFrom || !dateTo) {
        showNotification('Lütfen tarih aralığı seçin', 'error');
        return;
    }
    
    // Tarih aralığını hesapla ve grafiği güncelle
    const start = new Date(dateFrom);
    const end = new Date(dateTo);
    const daysDiff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
    
    let labels = [];
    for (let i = 0; i < daysDiff; i++) {
        const date = new Date(start);
        date.setDate(date.getDate() + i);
        labels.push(date.toLocaleDateString('tr-TR', { day: '2-digit', month: '2-digit', year: 'numeric' }));
    }
    
    // Örnek veri
    const onlineData = Array.from({length: daysDiff}, () => Math.floor(Math.random() * 50) + 100);
    const havaleData = Array.from({length: daysDiff}, () => Math.floor(Math.random() * 15) + 15);
    const firmaData = Array.from({length: daysDiff}, () => Math.floor(Math.random() * 8) + 3);
    
    salesChart.data.labels = labels;
    salesChart.data.datasets[0].data = onlineData;
    salesChart.data.datasets[1].data = havaleData;
    salesChart.data.datasets[2].data = firmaData;
    
    salesChart.options.scales.x.ticks.maxRotation = 90;
    salesChart.options.scales.x.ticks.minRotation = 90;
    
    salesChart.update();
    
    showNotification('Tarih filtresi uygulandı', 'success');
}

function exportToExcel() {
    showNotification('Excel dosyası indiriliyor...', 'success');
}
</script>

<?php include 'includes/footer.php'; ?>
