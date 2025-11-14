<?php
$page_title = 'Destek Talepleri';
include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/support.css">

<div class="page-header">
    <div class="page-title">
        <h1>Destek Talepleri</h1>
        <p class="page-subtitle">AI agent tarafından alınan destek taleplerini yönetin</p>
    </div>
    
    <div class="search-support">
        <i class="fas fa-search"></i>
        <input type="text" id="supportSearch" placeholder="Numara, ad veya soyad ile ara..." onkeyup="searchSupport()">
    </div>
</div>

<!-- Kategori Sekmeleri -->
<div class="support-tabs">
    <div class="support-tab active" data-category="toplu" onclick="changeCategory('toplu', event)">
        <i class="fas fa-users"></i>
        <span>Toplu</span>
        <span class="tab-badge" style="display: none;">0</span>
    </div>

    <div class="support-tab" data-category="havale" onclick="changeCategory('havale', event)">
        <i class="fas fa-money-bill-wave"></i>
        <span>Havale</span>
        <span class="tab-badge" style="display: none;">0</span>
    </div>

    <div class="support-tab" data-category="iptal" onclick="changeCategory('iptal', event)">
        <i class="fas fa-times-circle"></i>
        <span>İptal</span>
        <span class="tab-badge" style="display: none;">0</span>
    </div>

    <div class="support-tab" data-category="teknik" onclick="changeCategory('teknik', event)">
        <i class="fas fa-tools"></i>
        <span>Teknik</span>
        <span class="tab-badge" style="display: none;">0</span>
    </div>

    <div class="support-tab" data-category="iletisim" onclick="changeCategory('iletisim', event)">
        <i class="fas fa-comment-dots"></i>
        <span>İletişim Talepleri</span>
        <span class="tab-badge" style="display: none;">0</span>
    </div>
</div>

<!-- Destek Tablosu -->
<div class="support-table">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr id="supportTableHead">
                    <th>TARİH</th>
                    <th>TELEFON</th>
                    <th>AD SOYAD</th>
                    <th>FİRMA ADI</th>
                    <th>BELGE ADEDİ</th>
                    <th>DURUM</th>
                </tr>
            </thead>
            <tbody id="supportTableBody">
                <tr>
                    <td colspan="10" style="text-align: center; padding: 2rem; color: #8b9cbc;">
                        Yükleniyor...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Durum Modal -->
<div class="modal" id="statusModal">
    <div class="modal-content status-modal-content">
        <div class="modal-header">
            <h2>Talep Durumu</h2>
            <button class="close-modal" onclick="closeModal('statusModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="statusModalContent"></div>
    </div>
</div>

<!-- Konuşma Geçmişi Modal -->
<div class="modal" id="conversationModal">
    <div class="modal-content conversation-modal-content">
        <div class="modal-header">
            <h2 id="conversationModalTitle">Konuşma Geçmişi</h2>
            <button class="close-modal" onclick="closeModal('conversationModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="conversationModalContent"></div>
    </div>
</div>

<script src="assets/js/support.js"></script>

<?php include 'includes/footer.php'; ?>