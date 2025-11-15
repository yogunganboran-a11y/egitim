// Destek Sayfası - JavaScript

let currentCategory = 'toplu';
let supportRequests = {
    toplu: [],
    havale: [],
    iptal: [],
    teknik: [],
    iletisim: []
};
let selectedStatus = null;

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    loadDemoData();
    loadSupportRequests();
    updateNotificationBadges();
    
    // Yeni talep kontrolü (30 saniyede bir)
    setInterval(checkNewRequests, 30000);
});

// Kategori değiştir
function changeCategory(category, event) {
    currentCategory = category;
    
    // Sekmeleri güncelle
    document.querySelectorAll('.support-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    if (event && event.target) {
        event.target.closest('.support-tab').classList.add('active');
    }
    
    // Tabloyu güncelle
    loadSupportRequests();
}

// Destek taleplerini yükle
function loadSupportRequests() {
    updateTableHeaders(currentCategory);
    renderTable();
}

// Tablo başlıklarını güncelle
function updateTableHeaders(category) {
    const tableHead = document.getElementById('supportTableHead');
    let headers = '';

    if (category === 'toplu') {
        headers = `
            <th>TARİH</th>
            <th>KAYNAK</th>
            <th>TELEFON</th>
            <th>AD SOYAD</th>
            <th>FİRMA ADI</th>
            <th>BELGE ADEDİ</th>
            <th>DURUM</th>
        `;
    } else if (category === 'havale') {
        headers = `
            <th>TARİH</th>
            <th>KAYNAK</th>
            <th>TELEFON</th>
            <th>İSİM</th>
            <th>SOY İSİM</th>
            <th>TCKN</th>
            <th>DOĞUM TARİHİ</th>
            <th>FİYAT</th>
            <th>DEKONT</th>
            <th>BELGE TÜRÜ</th>
            <th>DURUM</th>
        `;
    } else if (category === 'iptal') {
        headers = `
            <th>TARİH</th>
            <th>KAYNAK</th>
            <th>TELEFON</th>
            <th>AD SOYAD</th>
            <th>TCKN</th>
            <th>İPTAL NEDENİ</th>
            <th>DURUM</th>
        `;
    } else if (category === 'teknik') {
        headers = `
            <th>TARİH</th>
            <th>KAYNAK</th>
            <th>TELEFON</th>
            <th>AD SOYAD</th>
            <th>TCKN</th>
            <th>SORUN DETAYI</th>
            <th>DURUM</th>
        `;
    } else if (category === 'iletisim') {
        headers = `
            <th>TARİH</th>
            <th>TELEFON</th>
            <th>AD SOYAD</th>
            <th>TALEP DETAYI</th>
            <th>DURUM</th>
        `;
    }

    tableHead.innerHTML = headers;
}

// Tabloyu render et
function renderTable() {
    const tableBody = document.getElementById('supportTableBody');
    const requests = supportRequests[currentCategory];
    
    if (requests.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="12" style="text-align: center; padding: 2rem; color: #8b9cbc;">Talep bulunamadı</td></tr>';
        return;
    }
    
    let html = '';
    
    requests.forEach(request => {
        const isNew = request.isNew ? 'new-request' : '';
        html += '<tr class="' + isNew + '" data-request-id="' + request.id + '">';

        if (currentCategory === 'toplu') {
            html += '<td>' + request.date + '</td>';
            html += '<td>' + renderSourceIcon(request.source, request.phone) + '</td>';
            html += '<td>' + request.phone + '</td>';
            html += '<td>' + request.fullName + '</td>';
            html += '<td>' + request.companyName + '</td>';
            html += '<td>' + request.documentCount + '</td>';
            html += '<td><button class="status-btn ' + request.statusClass + '" onclick="openStatusModal(' + request.id + ')">' + request.status + '</button></td>';
        } else if (currentCategory === 'havale') {
            html += '<td>' + request.date + '</td>';
            html += '<td>' + renderSourceIcon(request.source, request.phone) + '</td>';
            html += '<td>' + request.phone + '</td>';
            html += '<td>' + request.firstName + '</td>';
            html += '<td>' + request.lastName + '</td>';
            html += '<td>' + request.tckn + '</td>';
            html += '<td>' + request.birthDate + '</td>';
            html += '<td>' + request.price + ' ₺</td>';
            html += '<td><button class="dekont-btn" onclick="viewDekont(\'' + request.dekontUrl + '\')"><i class="fas fa-file-image"></i> Görüntüle</button></td>';
            html += '<td>' + request.documentType + '</td>';
            html += '<td><button class="status-btn ' + request.statusClass + '" onclick="openStatusModal(' + request.id + ')">' + request.status + '</button></td>';
        } else if (currentCategory === 'iptal') {
            html += '<td>' + request.date + '</td>';
            html += '<td>' + renderSourceIcon(request.source, request.phone) + '</td>';
            html += '<td>' + request.phone + '</td>';
            html += '<td>' + request.fullName + '</td>';
            html += '<td>' + request.tckn + '</td>';
            html += '<td>' + request.cancelReason + '</td>';
            html += '<td><button class="status-btn ' + request.statusClass + '" onclick="openStatusModal(' + request.id + ')">' + request.status + '</button></td>';
        } else if (currentCategory === 'teknik') {
            html += '<td>' + request.date + '</td>';
            html += '<td>' + renderSourceIcon(request.source, request.phone) + '</td>';
            html += '<td>' + request.phone + '</td>';
            html += '<td>' + request.fullName + '</td>';
            html += '<td>' + request.tckn + '</td>';
            html += '<td>' + request.issueDetail + '</td>';
            html += '<td><button class="status-btn ' + request.statusClass + '" onclick="openStatusModal(' + request.id + ')">' + request.status + '</button></td>';
        } else if (currentCategory === 'iletisim') {
            html += '<td>' + request.date + '</td>';
            html += '<td>' + request.phone + '</td>';
            html += '<td>' + request.fullName + '</td>';
            html += '<td>' + request.requestDetail + '</td>';
            html += '<td><button class="status-btn ' + request.statusClass + '" onclick="openStatusModal(' + request.id + ')">' + request.status + '</button></td>';
        }

        html += '</tr>';
    });
    
    tableBody.innerHTML = html;
}

// Durum modalı aç
function openStatusModal(requestId) {
    const request = findRequest(requestId);
    if (!request) return;
    
    const modal = document.getElementById('statusModal');
    const content = document.getElementById('statusModalContent');
    
    let noteHtml = '';
    if (request.note) {
        noteHtml = '<div class="status-note"><div class="status-note-label">Not:</div><div class="status-note-text">' + request.note + '</div></div>';
    }
    
    // Geçmiş HTML'i oluştur
    let historyHtml = '';
    if (request.history && request.history.length > 0) {
        historyHtml = '<div class="history-timeline">';
        request.history.forEach(item => {
            let itemNoteHtml = item.note ? '<div class="history-note">' + item.note + '</div>' : '';
            historyHtml += '<div class="history-item">';
            historyHtml += '<div class="history-date">' + item.date + '</div>';
            historyHtml += '<div class="history-status">' + item.status + '</div>';
            historyHtml += itemNoteHtml;
            historyHtml += '</div>';
        });
        historyHtml += '</div>';
    } else {
        historyHtml = '<p style="text-align: center; color: #8b9cbc; padding: 2rem;">Henüz geçmiş kaydı yok</p>';
    }
    
    // Kategoriye göre durum seçenekleri
    let statusOptions = '';
    if (currentCategory === 'toplu') {
        statusOptions = `
            <div class="status-option" onclick="selectStatus('Alındı', this)">Alındı</div>
            <div class="status-option" onclick="selectStatus('Teklif Gönderildi', this)">Teklif Gönderildi</div>
            <div class="status-option" onclick="selectStatus('Onaylandı', this)">Onaylandı</div>
            <div class="status-option" onclick="selectStatus('Reddedildi', this)">Reddedildi</div>
        `;
    } else if (currentCategory === 'havale') {
        statusOptions = `
            <div class="status-option" onclick="selectStatus('Alındı', this)">Alındı</div>
            <div class="status-option" onclick="selectStatus('Onaylandı', this)">Onaylandı</div>
            <div class="status-option" onclick="selectStatus('Reddedildi', this)">Reddedildi</div>
        `;
    } else if (currentCategory === 'iptal') {
        statusOptions = `
            <div class="status-option" onclick="selectStatus('Alındı', this)">Alındı</div>
            <div class="status-option" onclick="selectStatus('Onaylandı', this)">Onaylandı</div>
            <div class="status-option" onclick="selectStatus('Reddedildi', this)">Reddedildi</div>
        `;
    } else if (currentCategory === 'teknik') {
        statusOptions = `
            <div class="status-option" onclick="selectStatus('Alındı', this)">Alındı</div>
            <div class="status-option" onclick="selectStatus('Çözüldü', this)">Çözüldü</div>
        `;
    } else if (currentCategory === 'iletisim') {
        statusOptions = `
            <div class="status-option" onclick="selectStatus('İletişime Geçildi', this)">İletişime Geçildi</div>
            <div class="status-option" onclick="selectStatus('Ulaşılamadı', this)">Ulaşılamadı</div>
            <div class="status-option" onclick="selectStatus('Sonlandırıldı', this)">Sonlandırıldı</div>
        `;
    }
    
    content.innerHTML = `
        <div class="modal-horizontal">
            <div class="modal-left">
                <h3 style="color: #ffffff; margin-bottom: 1rem; font-size: 1.1rem;">Talep Bilgileri</h3>
                <div class="status-info">
                    <div class="status-info-row">
                        <span class="status-info-label">Talep Tarihi:</span>
                        <span class="status-info-value">` + request.date + `</span>
                    </div>
                    <div class="status-info-row">
                        <span class="status-info-label">Mevcut Durum:</span>
                        <span class="status-info-value">` + request.status + `</span>
                    </div>
                </div>
                ` + noteHtml + `
            </div>
            
            <div class="modal-middle">
                <h3 style="color: #ffffff; margin-bottom: 1rem; font-size: 1.1rem;">Talep Geçmişi</h3>
                ` + historyHtml + `
            </div>
            
            <div class="modal-right">
                <h3 style="color: #ffffff; margin-bottom: 1rem; font-size: 1.1rem;">Güncelle</h3>
                <div class="status-update">
                    <label class="form-label">Durumu Güncelle</label>
                    <div class="status-options">
                        ` + statusOptions + `
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Not Ekle (Opsiyonel)</label>
                        <textarea class="form-control" id="statusNote" rows="4" placeholder="Durumla ilgili not ekleyin..."></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                        <button class="btn btn-primary" style="flex: 1;" onclick="updateStatus(` + requestId + `)">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <button class="btn btn-secondary" style="flex: 1;" onclick="closeModal('statusModal')">
                            <i class="fas fa-times"></i> Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    modal.classList.add('active');
}

function selectStatus(status, element) {
    document.querySelectorAll('.status-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    element.classList.add('selected');
    selectedStatus = status;
}

function updateStatus(requestId) {
    const note = document.getElementById('statusNote').value;
    
    // Durum seçilmemiş ama not varsa sadece not ekle
    if (!selectedStatus && !note) {
        showNotification('Lütfen bir durum seçin veya not ekleyin!', 'error');
        return;
    }
    
    const request = findRequest(requestId);
    
    if (request) {
        // Eğer durum seçildiyse güncelle
        if (selectedStatus) {
            request.status = selectedStatus;
            
            // Durum sınıfını güncelle
            if (selectedStatus === 'Alındı') request.statusClass = 'pending';
            else if (selectedStatus === 'Onaylandı' || selectedStatus === 'Çözüldü') request.statusClass = 'approved';
            else if (selectedStatus === 'Reddedildi') request.statusClass = 'rejected';
            else if (selectedStatus === 'Teklif Gönderildi') request.statusClass = 'pending';
        }
        
        // Not varsa ekle
        if (note) {
            request.note = note;
        }
        
        request.isNew = false;
        
        // Geçmişe ekle
        if (!request.history) request.history = [];
        request.history.push({
            date: new Date().toLocaleString('tr-TR'),
            status: selectedStatus || 'Not Eklendi',
            note: note
        });
    }
    
    showNotification('Güncelleme başarılı', 'success');
    closeModal('statusModal');
    selectedStatus = null;
    loadSupportRequests();
    updateNotificationBadges();
}

// Dekont görüntüle
function viewDekont(url) {
    window.open(url, '_blank');
}

// Talep ara
function searchSupport() {
    const searchTerm = document.getElementById('supportSearch').value.toLowerCase();
    const rows = document.querySelectorAll('.support-table tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

// Talep bul
function findRequest(requestId) {
    for (let category in supportRequests) {
        const request = supportRequests[category].find(r => r.id === requestId);
        if (request) return request;
    }
    return null;
}

// Yeni talep kontrolü
function checkNewRequests() {
    // Gerçek uygulamada AJAX ile backend'den kontrol edilecek
    const hasNewRequests = Math.random() > 0.9;
    
    if (hasNewRequests) {
        playNotificationSound();
        updateNotificationBadges();
    }
}

// Bildirim sesi çal
function playNotificationSound() {
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZUP8PP6Ld8rxjHQU2jdXy1IEyBhxrv+7lm1H/Dz+i3fK8Yx0FNo3V8tSBMgYca7/u5ZtR/w8/ot3yvGMdBTaN1fLUgTIGHGu/7uWbUf8PP6Ld8rxjHQU2jdXy1IEyBhxrv+7lm1H/Dz+i3fK8Yx0FNo3V8tSBMgYca7/u5ZtR/w8/ot3yvGMdBTaN1fLUgTIGHGu/7uWbUf8PP6Ld8rxjHQU2jdXy1IEyBhxrv+7lm1H/Dz+i3fK8Yx0FNo3V8tSBMgYca7/u5ZtR/w8/ot3yvGMdBTaN1fLUgTIGHGu/7uWbUf8PP6Ld8rxjHQU2jdXy1IEyBhxrv+7lm1H/Dz+i3fK8Yx0FNo3V8tSBMgYca7/u5ZtR/w8/ot3yvGMdBTaN1fLUgTIGHGu/7uWbUf8PP6Ld8rxjHQU2jdXy1IEyBhxrv+7lm1H/');
    audio.play().catch(e => console.log('Ses çalınamadı'));
}

// Bildirim badge'lerini güncelle
function updateNotificationBadges() {
    let totalNew = 0;

    ['toplu', 'havale', 'iptal', 'teknik', 'iletisim'].forEach(category => {
        const newCount = supportRequests[category].filter(r => r.isNew).length;
        const badge = document.querySelector('[data-category="' + category + '"] .tab-badge');

        if (badge) {
            badge.textContent = newCount;
            badge.style.display = newCount > 0 ? 'inline-flex' : 'none';
        }

        totalNew += newCount;
    });

    const sidebarBadge = document.querySelector('.sidebar a[href="destek.php"] .notification-badge');
    if (sidebarBadge) {
        sidebarBadge.textContent = totalNew;
        sidebarBadge.style.display = totalNew > 0 ? 'inline-flex' : 'none';
    }
}

// Modal kapat
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    selectedStatus = null;
}

// Modal dışına tıklayınca kapat
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});

// Demo data yükle
function loadDemoData() {
    supportRequests.toplu = [
        { id: 1, date: '12.11.2025 15:30', phone: '0532 123 4567', source: 'whatsapp', fullName: 'Ahmet Yılmaz', companyName: 'ABC Denizcilik', documentCount: 15, status: 'Beklemede', statusClass: 'pending', isNew: true, history: [] },
        { id: 2, date: '11.11.2025 14:20', phone: '0533 234 5678', source: 'phone', fullName: 'Mehmet Demir', companyName: 'XYZ Maritime', documentCount: 8, status: 'Onaylandı', statusClass: 'approved', isNew: false, history: [] }
    ];

    supportRequests.havale = [
        { id: 3, date: '12.11.2025 16:45', phone: '0534 345 6789', source: 'whatsapp', firstName: 'Ayşe', lastName: 'Kaya', tckn: '12345678901', birthDate: '15.03.1990', price: 1500, dekontUrl: '#', documentType: 'Temel Denizcilik', status: 'Beklemede', statusClass: 'pending', isNew: true, history: [] },
        { id: 4, date: '10.11.2025 10:15', phone: '0535 456 7890', source: 'phone', firstName: 'Fatma', lastName: 'Şahin', tckn: '98765432109', birthDate: '22.07.1985', price: 2000, dekontUrl: '#', documentType: 'İleri Navigasyon', status: 'Onaylandı', statusClass: 'approved', isNew: false, history: [] }
    ];

    supportRequests.iptal = [
        { id: 5, date: '11.11.2025 09:30', phone: '0536 567 8901', source: 'whatsapp', fullName: 'Ali Öztürk', tckn: '11122233344', cancelReason: 'Yanlış bilgi girişi', status: 'Beklemede', statusClass: 'pending', isNew: true, history: [] }
    ];

    supportRequests.teknik = [
        { id: 6, date: '12.11.2025 11:20', phone: '0537 678 9012', source: 'phone', fullName: 'Zeynep Aydın', tckn: '55566677788', issueDetail: 'Video oynatma sorunu', status: 'Beklemede', statusClass: 'pending', isNew: true, history: [] }
    ];

    supportRequests.iletisim = [
        { id: 7, date: '13.11.2025 09:15', phone: '0538 789 0123', source: 'web', fullName: 'Can Yılmaz', requestDetail: 'Eğitim programları hakkında detaylı bilgi almak istiyorum', status: 'Beklemede', statusClass: 'pending', isNew: true, history: [] },
        { id: 8, date: '12.11.2025 18:30', phone: '0539 890 1234', source: 'web', fullName: 'Selin Demir', requestDetail: 'Toplu eğitim fiyat teklifi talebi', status: 'İletişime Geçildi', statusClass: 'approved', isNew: false, history: [] },
        { id: 9, date: '12.11.2025 14:45', phone: '0530 901 2345', source: 'whatsapp', fullName: 'Emre Kaya', requestDetail: 'Sertifika geçerlilik süresi hakkında soru', status: 'Beklemede', statusClass: 'pending', isNew: true, history: [] }
    ];
}

// Kaynak ikonu render et (tıklanabilir)
function renderSourceIcon(source, phone) {
    if (source === 'whatsapp') {
        return '<button class="source-btn whatsapp" onclick="openWhatsAppChat(\'' + phone + '\')" title="WhatsApp\'tan geldi - Konuşmayı görüntüle">' +
               '<i class="fab fa-whatsapp"></i>' +
               '</button>';
    } else if (source === 'phone') {
        return '<button class="source-btn phone" onclick="openPhoneHistory(\'' + phone + '\')" title="Telefondan geldi - Geçmişi görüntüle">' +
               '<i class="fas fa-phone"></i>' +
               '</button>';
    } else if (source === 'web') {
        return '<button class="source-btn web" onclick="showNotification(\'Web sitesi form talebi\', \'info\')" title="Web sitesinden geldi">' +
               '<i class="fas fa-globe"></i>' +
               '</button>';
    }
    return '<span style="color: #8b9cbc;">-</span>';
}

// WhatsApp konuşmasını modal'da aç
function openWhatsAppChat(phone) {
    const modal = document.getElementById('conversationModal');
    const content = document.getElementById('conversationModalContent');
    const modalHeader = document.querySelector('#conversationModal .modal-header');
    
    // Modal header'ı tamamen yeniden oluştur
    modalHeader.innerHTML = `
        <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">AY</div>
            <div>
                <div style="font-size: 0.9rem; color: #8b9cbc;">Ahmet Yılmaz</div>
                <div style="font-size: 1.1rem; font-weight: 600; color: #fff;">${phone}</div>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <button class="btn-bot-status active" id="botStatusBtn" onclick="toggleBotStatus()">
                <i class="fas fa-robot"></i> Bot Aktif
            </button>
            <button class="btn-bot-stop" id="botStopBtn" onclick="stopBot()">Botu Durdur</button>
            <button class="close-modal" onclick="closeModal('conversationModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // WhatsApp mesajları
    const whatsappMessages = [
        { type: 'incoming', message: 'Gelen mesaj #64', time: '18:54' },
        { type: 'outgoing', message: 'Giden mesaj #65', time: '18:55', read: true },
        { type: 'outgoing', message: 'Giden mesaj #66', time: '18:56', read: true },
        { type: 'incoming', message: 'Gelen mesaj #67', time: '18:57' },
        { type: 'outgoing', message: 'Giden mesaj #68', time: '18:58', read: true },
        { type: 'outgoing', message: 'Giden mesaj #69', time: '18:59', read: true },
        { type: 'incoming', message: 'Gelen mesaj #70', time: '19:00' }
    ];
    
    let messagesHtml = '<div class="whatsapp-chat-container" id="whatsappChatContainer">';
    
    whatsappMessages.forEach(msg => {
        messagesHtml += `
            <div class="whatsapp-message ${msg.type}">
                <div class="message-content">${msg.message}</div>
                <div class="message-meta">
                    <span class="message-time">${msg.time}</span>
                    ${msg.read ? '<i class="fas fa-check-double" style="color: #25d366; margin-left: 0.25rem;"></i>' : ''}
                </div>
            </div>
        `;
    });
    
    messagesHtml += '</div>';
    
    // Mesaj yazma alanı
    messagesHtml += `
        <div class="whatsapp-input-area" id="whatsappInputArea">
            <input type="file" id="fileInput" style="display: none;" accept="image/*,video/*,.pdf,.doc,.docx" onchange="handleFileSelect(event)">
            <button class="input-icon-btn" title="Emoji" onclick="showEmojiPicker()">
                <i class="far fa-smile"></i>
            </button>
            <button class="input-icon-btn" title="Dosya Ekle" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-paperclip"></i>
            </button>
            <input type="text" placeholder="Bir mesaj yazın..." class="whatsapp-input" id="whatsappInput" onkeypress="if(event.key === 'Enter') sendWhatsAppMessage()">
            <button class="send-btn" onclick="sendWhatsAppMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
        <div class="file-upload-preview" id="fileUploadPreview" style="display: none;"></div>
    `;
    
    content.innerHTML = messagesHtml;
    
    // Drag and drop olaylarını ekle
    setupDragAndDrop();
    
    modal.classList.add('active');
}

// Bot durumunu değiştir
function toggleBotStatus() {
    const btn = document.getElementById('botStatusBtn');
    const isActive = btn.classList.contains('active');
    
    if (isActive) {
        btn.classList.remove('active');
        btn.innerHTML = '<i class="fas fa-robot"></i> Bot Pasif';
        btn.style.background = 'rgba(239, 68, 68, 0.2)';
        btn.style.color = '#ef4444';
        btn.style.borderColor = 'rgba(239, 68, 68, 0.3)';
        showNotification('Bot devre dışı bırakıldı', 'info');
    } else {
        btn.classList.add('active');
        btn.innerHTML = '<i class="fas fa-robot"></i> Bot Aktif';
        btn.style.background = 'rgba(16, 185, 129, 0.2)';
        btn.style.color = '#10b981';
        btn.style.borderColor = 'rgba(16, 185, 129, 0.3)';
        showNotification('Bot aktif edildi', 'success');
    }
}

// Botu durdur
function stopBot() {
    const botStatusBtn = document.getElementById('botStatusBtn');
    const botStopBtn = document.getElementById('botStopBtn');
    
    // Bot Aktif butonunu Manuel Mod yap
    botStatusBtn.classList.remove('active');
    botStatusBtn.innerHTML = '<i class="fas fa-user"></i> Manuel Mod';
    botStatusBtn.style.background = 'rgba(139, 156, 188, 0.2)';
    botStatusBtn.style.color = '#8b9cbc';
    botStatusBtn.style.borderColor = 'rgba(139, 156, 188, 0.3)';
    botStatusBtn.onclick = null; // Tıklanamaz yap
    
    // Botu Durdur butonunu Botu Başlat yap
    botStopBtn.innerHTML = 'Botu Başlat';
    botStopBtn.style.background = 'rgba(16, 185, 129, 0.2)';
    botStopBtn.style.color = '#10b981';
    botStopBtn.style.borderColor = 'rgba(16, 185, 129, 0.3)';
    botStopBtn.onclick = function() { startBot(); };
    
    showNotification('Manuel moda geçildi', 'info');
    
    // Backend'e gönder
    console.log('Bot durduruldu - Manuel mod aktif');
}

// Botu başlat
function startBot() {
    const botStatusBtn = document.getElementById('botStatusBtn');
    const botStopBtn = document.getElementById('botStopBtn');
    
    // Manuel Mod butonunu Bot Aktif yap
    botStatusBtn.classList.add('active');
    botStatusBtn.innerHTML = '<i class="fas fa-robot"></i> Bot Aktif';
    botStatusBtn.style.background = 'rgba(16, 185, 129, 0.2)';
    botStatusBtn.style.color = '#10b981';
    botStatusBtn.style.borderColor = 'rgba(16, 185, 129, 0.3)';
    botStatusBtn.onclick = function() { toggleBotStatus(); };
    
    // Botu Başlat butonunu Botu Durdur yap
    botStopBtn.innerHTML = 'Botu Durdur';
    botStopBtn.style.background = 'rgba(239, 68, 68, 0.2)';
    botStopBtn.style.color = '#ef4444';
    botStopBtn.style.borderColor = 'rgba(239, 68, 68, 0.3)';
    botStopBtn.onclick = function() { stopBot(); };
    
    showNotification('Bot aktif edildi', 'success');
    
    // Backend'e gönder
    console.log('Bot başlatıldı');
}

// Emoji picker göster
function showEmojiPicker() {
    showNotification('Emoji seçici geliştiriliyor...', 'info');
    // Gerçek kullanımda emoji picker açılacak
}

// Dosya seçme
function handleFileSelect(event) {
    const files = event.target.files;
    if (!files.length) return;
    
    handleFiles(files);
}

// Dosyaları işle
function handleFiles(files) {
    const preview = document.getElementById('fileUploadPreview');
    preview.innerHTML = '';
    preview.style.display = 'flex';
    
    Array.from(files).forEach(file => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-preview-item';
        
        const fileIcon = getFileIcon(file.type);
        const fileSize = formatFileSize(file.size);
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fileItem.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}">
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${fileSize}</div>
                    </div>
                    <button class="remove-file-btn" onclick="removeFile(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            fileItem.innerHTML = `
                <div class="file-icon">${fileIcon}</div>
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${fileSize}</div>
                </div>
                <button class="remove-file-btn" onclick="removeFile(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
        }
        
        preview.appendChild(fileItem);
    });
    
    showNotification(`${files.length} dosya seçildi`, 'success');
}

// Dosya ikonunu al
function getFileIcon(fileType) {
    if (fileType.startsWith('image/')) return '<i class="fas fa-image"></i>';
    if (fileType.startsWith('video/')) return '<i class="fas fa-video"></i>';
    if (fileType.includes('pdf')) return '<i class="fas fa-file-pdf"></i>';
    if (fileType.includes('word')) return '<i class="fas fa-file-word"></i>';
    return '<i class="fas fa-file"></i>';
}

// Dosya boyutunu formatla
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Dosyayı kaldır
function removeFile(btn) {
    btn.parentElement.remove();
    const preview = document.getElementById('fileUploadPreview');
    if (!preview.children.length) {
        preview.style.display = 'none';
    }
}

// Drag and Drop kurulumu
function setupDragAndDrop() {
    const inputArea = document.getElementById('whatsappInputArea');
    const chatContainer = document.getElementById('whatsappChatContainer');
    
    [inputArea, chatContainer].forEach(element => {
        element.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('drag-over');
        });
        
        element.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('drag-over');
        });
        
        element.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length) {
                handleFiles(files);
            }
        });
    });
}

// WhatsApp mesaj gönder
function sendWhatsAppMessage() {
    const input = document.getElementById('whatsappInput');
    const message = input.value.trim();
    const preview = document.getElementById('fileUploadPreview');
    const hasFiles = preview.style.display !== 'none' && preview.children.length > 0;
    
    if (!message && !hasFiles) return;
    
    const container = document.querySelector('.whatsapp-chat-container');
    const time = new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
    
    // Dosyalar varsa önce onları gönder
    if (hasFiles) {
        const fileItems = preview.querySelectorAll('.file-preview-item');
        fileItems.forEach(item => {
            const fileName = item.querySelector('.file-name').textContent;
            const messageHtml = `
                <div class="whatsapp-message outgoing">
                    <div class="message-content">
                        <i class="fas fa-paperclip"></i> ${fileName}
                    </div>
                    <div class="message-meta">
                        <span class="message-time">${time}</span>
                        <i class="fas fa-check" style="color: #9ca3af; margin-left: 0.25rem;"></i>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', messageHtml);
        });
        
        preview.innerHTML = '';
        preview.style.display = 'none';
    }
    
    // Mesaj varsa gönder
    if (message) {
        const messageHtml = `
            <div class="whatsapp-message outgoing">
                <div class="message-content">${message}</div>
                <div class="message-meta">
                    <span class="message-time">${time}</span>
                    <i class="fas fa-check" style="color: #9ca3af; margin-left: 0.25rem;"></i>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', messageHtml);
        input.value = '';
    }
    
    // En alta kaydır
    container.scrollTop = container.scrollHeight;
    
    // Backend'e gönder
    console.log('Mesaj gönderildi:', message, 'Dosya sayısı:', hasFiles ? preview.children.length : 0);
}

// Telefon geçmişini modal'da aç
function openPhoneHistory(phone) {
    const modal = document.getElementById('conversationModal');
    const content = document.getElementById('conversationModalContent');
    const modalHeader = document.querySelector('#conversationModal .modal-header');
    
    // Modal header'ı tamamen yeniden oluştur
    modalHeader.innerHTML = `
        <div style="display: flex; align-items: center; gap: 1.5rem; flex: 1;">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.8rem;">
                <i class="fas fa-phone"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 0.9rem; color: #8b9cbc;">Ayşe Kaya</div>
                <h3 style="color: #fff; margin: 0.25rem 0 0 0; font-size: 1.3rem;">${phone}</h3>
                <p style="color: #8b9cbc; margin: 0.25rem 0 0 0; font-size: 0.85rem;">12.11.2025 10:15 - 5:23</p>
            </div>
            <button class="btn-call-type incoming">
                <i class="fas fa-phone"></i> Gelen
            </button>
        </div>
        <button class="close-modal" onclick="closeModal('conversationModal')">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Telefon içeriği
    let phoneHtml = `
        <div class="phone-call-content">
            <div class="audio-player-section">
                <h4><i class="fas fa-volume-up"></i> Ses Kaydı</h4>
                <audio controls style="width: 100%; margin-top: 1rem;">
                    <source src="#" type="audio/mpeg">
                    Tarayıcınız ses oynatmayı desteklemiyor.
                </audio>
            </div>
            
            <div class="transcript-section">
                <h4><i class="fas fa-file-alt"></i> Görüşme Metni</h4>
                <div class="transcript-messages">
                    <div class="transcript-item customer">
                        <div class="transcript-label">Müşteri:</div>
                        <div class="transcript-text">Merhaba, siparişim hakkında bilgi alabilir miyim?</div>
                        <div class="transcript-time">00:05</div>
                    </div>
                    <div class="transcript-item agent">
                        <div class="transcript-label">Temsilci:</div>
                        <div class="transcript-text">Tabii, sipariş numaranızı alabilir miyim?</div>
                        <div class="transcript-time">00:12</div>
                    </div>
                    <div class="transcript-item customer">
                        <div class="transcript-label">Müşteri:</div>
                        <div class="transcript-text">Evet, #12345</div>
                        <div class="transcript-time">00:18</div>
                    </div>
                    <div class="transcript-item agent">
                        <div class="transcript-label">Temsilci:</div>
                        <div class="transcript-text">Siparişiniz kargoya verildi, yarın elinizde olacak.</div>
                        <div class="transcript-time">00:25</div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    content.innerHTML = phoneHtml;
    modal.classList.add('active');
}

// Ses kaydını oynat (telefon sayfasında kullanılacak)
function playAudio(audioUrl) {
    const audio = new Audio(audioUrl);
    audio.play().catch(e => {
        console.log('Ses oynatılamadı:', e);
        showNotification('Ses kaydı oynatılamadı', 'error');
    });
}