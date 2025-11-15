<?php
$page_title = 'Geri Arama Listesi - Otomatik Arama Yönetimi';
include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/geri-arama-listesi.css">

<div class="page-header">
    <div class="page-title">
        <h1><i class="fas fa-robot"></i> Otomatik Geri Arama Yönetimi</h1>
        <p class="page-subtitle">AI destekli otomatik arama kuralları ve takip sistemi</p>
    </div>
</div>

<!-- KURALLAR BÖLÜMÜ -->
<div class="rules-section">
    <div class="rules-header">
        <h2><i class="fas fa-cogs"></i> Arama Kuralları</h2>
        <button class="btn btn-primary" onclick="openAddRuleModal()">
            <i class="fas fa-plus"></i> Yeni Kural Ekle
        </button>
    </div>

    <div class="rules-grid" id="rulesGrid">
        <!-- Örnek Kurallar -->
        <div class="rule-card" data-rule-id="1">
            <div class="rule-header">
                <div class="rule-title">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    <span>WhatsApp Satın Almadı</span>
                </div>
                <div class="rule-actions">
                    <button class="btn-icon edit" onclick="editRule(1)">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon delete" onclick="deleteRule(1)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="rule-body">
                <div class="rule-condition">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp'tan yazdı, satın almadı</span>
                </div>
                <div class="rule-action">
                    <i class="fas fa-clock"></i>
                    <span>15 dakika sonra ara</span>
                </div>
                <div class="rule-limit">
                    <i class="fas fa-redo"></i>
                    <span>Maksimum 3 deneme / gün</span>
                </div>
                <div class="rule-time-range">
                    <i class="far fa-clock"></i>
                    <span>09:00 - 21:00 arası</span>
                </div>
            </div>
            <div class="rule-footer">
                <span class="rule-status active">
                    <i class="fas fa-circle"></i> Aktif
                </span>
                <span class="rule-count">12 müşteri kuyrukta</span>
            </div>
        </div>

        <div class="rule-card" data-rule-id="2">
            <div class="rule-header">
                <div class="rule-title">
                    <i class="fas fa-phone" style="color: #3b82f6;"></i>
                    <span>Arandı Satın Almadı</span>
                </div>
                <div class="rule-actions">
                    <button class="btn-icon edit" onclick="editRule(2)">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon delete" onclick="deleteRule(2)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="rule-body">
                <div class="rule-condition">
                    <i class="fas fa-phone"></i>
                    <span>Arandı ama satın almadı</span>
                </div>
                <div class="rule-action">
                    <i class="fas fa-clock"></i>
                    <span>1 gün sonra tekrar ara</span>
                </div>
                <div class="rule-limit">
                    <i class="fas fa-redo"></i>
                    <span>Maksimum 2 deneme / hafta</span>
                </div>
            </div>
            <div class="rule-footer">
                <span class="rule-status active">
                    <i class="fas fa-circle"></i> Aktif
                </span>
                <span class="rule-count">8 müşteri kuyrukta</span>
            </div>
        </div>

        <div class="rule-card" data-rule-id="3">
            <div class="rule-header">
                <div class="rule-title">
                    <i class="fas fa-question-circle" style="color: #f59e0b;"></i>
                    <span>Kararsız Kaldı</span>
                </div>
                <div class="rule-actions">
                    <button class="btn-icon edit" onclick="editRule(3)">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon delete" onclick="deleteRule(3)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="rule-body">
                <div class="rule-condition">
                    <i class="fas fa-question"></i>
                    <span>Kararsız, daha fazla bilgi istedi</span>
                </div>
                <div class="rule-action">
                    <i class="fas fa-clock"></i>
                    <span>3 gün sonra ara</span>
                </div>
                <div class="rule-limit">
                    <i class="fas fa-redo"></i>
                    <span>Maksimum 4 deneme</span>
                </div>
            </div>
            <div class="rule-footer">
                <span class="rule-status active">
                    <i class="fas fa-circle"></i> Aktif
                </span>
                <span class="rule-count">5 müşteri kuyrukta</span>
            </div>
        </div>
    </div>
</div>

<!-- ARAMA LİSTESİ BÖLÜMÜ -->
<div class="callback-list-section">
    <div class="list-header">
        <h2><i class="fas fa-list"></i> Arama Kuyruğu</h2>
        <div class="list-filters">
            <button class="filter-btn active" data-filter="planned" onclick="filterCallbacks('planned')">
                <i class="far fa-clock"></i>
                Planlanan <span class="filter-count">25</span>
            </button>
            <button class="filter-btn" data-filter="previous" onclick="filterCallbacks('previous')">
                <i class="fas fa-history"></i>
                Önceki <span class="filter-count">48</span>
            </button>
            <button class="filter-btn" data-filter="negative" onclick="filterCallbacks('negative')">
                <i class="fas fa-ban"></i>
                Olumsuzlar <span class="filter-count">12</span>
            </button>
        </div>
    </div>

    <div class="callback-table">
        <table class="table">
            <thead>
                <tr>
                    <th>TARİH</th>
                    <th>AD SOYAD</th>
                    <th>TELEFON</th>
                    <th>KAYNAK</th>
                    <th>DURUM</th>
                    <th>SATIN ALMA</th>
                    <th>KURAL</th>
                    <th>ÖNCELİK</th>
                    <th>AI SKOR</th>
                    <th>DENEME</th>
                    <th>İŞLEMLER</th>
                </tr>
            </thead>
            <tbody id="callbackTableBody">
                <!-- Örnek Satırlar -->
                <tr class="callback-row priority-high" data-callback-id="1" data-status="planned">
                    <td>14.11.2025 16:30</td>
                    <td>Ahmet Yılmaz</td>
                    <td>0532 123 4567</td>
                    <td><i class="fab fa-whatsapp" style="color: #25d366;"></i> WhatsApp</td>
                    <td><span class="status-badge waiting">Bekleniyor</span></td>
                    <td><span class="purchase-badge no">Almadı</span></td>
                    <td>WhatsApp Satın Almadı</td>
                    <td><span class="priority-badge high">Yüksek</span></td>
                    <td><span class="ai-score high">85%</span></td>
                    <td>1/3</td>
                    <td>
                        <button class="btn-icon info" onclick="viewCallbackDetail(1)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-icon delete" onclick="cancelCallback(1)">
                            <i class="fas fa-ban"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- KURAL EKLEME/DÜZENLEME MODAL -->
<div class="modal" id="ruleModal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h2><i class="fas fa-cogs"></i> <span id="ruleModalTitle">Yeni Kural Ekle</span></h2>
            <button class="close-modal" onclick="closeModal('ruleModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form onsubmit="saveRule(event)" style="padding: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Kural Adı</label>
                <input type="text" id="ruleName" class="form-control" placeholder="Örn: WhatsApp Satın Almadı" required>
            </div>

            <div class="form-group">
                <label class="form-label">Koşul Tipi</label>
                <select id="ruleCondition" class="form-control" required>
                    <option value="">Seçiniz</option>
                    <option value="whatsapp_no_purchase">WhatsApp'tan yazdı, satın almadı</option>
                    <option value="call_no_purchase">Arandı ama satın almadı</option>
                    <option value="form_no_purchase">Form doldurdu, satın almadı</option>
                    <option value="cart_abandoned">Sepete ekledi, satın almadı</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Bekleme Süresi (dakika)</label>
                <input type="number" id="ruleDelay" class="form-control" placeholder="15" min="1" required>
                <small style="color: var(--text-secondary);">Koşul gerçekleştikten kaç dakika sonra arama yapılacak</small>
            </div>

            <div class="form-group">
                <label class="form-label">Maksimum Deneme Sayısı</label>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <input type="number" id="ruleMaxAttempts" class="form-control" placeholder="3" min="1" max="10" required>
                    <select id="ruleAttemptPeriod" class="form-control" required>
                        <option value="day">/ gün</option>
                        <option value="week">/ hafta</option>
                        <option value="month">/ ay</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Arama Saatleri</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <input type="time" id="ruleTimeStart" class="form-control" value="09:00" required>
                    <input type="time" id="ruleTimeEnd" class="form-control" value="21:00" required>
                </div>
                <small style="color: var(--text-secondary);">Bu saat aralığında arama yapılacak</small>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                    <input type="checkbox" id="ruleActive" checked style="width: 18px; height: 18px;">
                    <span style="color: var(--text-primary);">Kural aktif</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Kaydet
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('ruleModal')" style="flex: 1;">
                    <i class="fas fa-times"></i> İptal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DETAY MODAL -->
<div class="modal" id="callbackDetailModal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h2><i class="fas fa-info-circle"></i> Arama Detayı & Süreç</h2>
            <button class="close-modal" onclick="closeModal('callbackDetailModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="callbackDetailContent">
            <!-- Timeline gösterimi buraya gelecek -->
        </div>
    </div>
</div>

<script src="assets/js/geri-arama-listesi.js"></script>

<?php include 'includes/footer.php'; ?>
