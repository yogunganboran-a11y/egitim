// WhatsApp Sayfası - JavaScript

let activeChat = null;
let botEnabled = {};
let messageCheckInterval = null;

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    console.log('WhatsApp sayfası yüklendi');
    
    // Mesaj kontrolünü başlat (her 3 saniyede bir)
    messageCheckInterval = setInterval(checkNewMessages, 3000);
    
    // Enter tuşu ile mesaj gönder
    const input = document.getElementById('messageInput');
    if (input) {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
    
    // Drag & Drop file upload
    const messagesContainer = document.querySelector('.messages-container');
    if (messagesContainer) {
        messagesContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.background = 'rgba(37, 211, 102, 0.1)';
        });
        
        messagesContainer.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.background = '';
        });
        
        messagesContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.background = '';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleDroppedFile(files[0]);
            }
        });
    }
    
    // İlk sohbeti otomatik seç
    setTimeout(() => {
        const firstChat = document.querySelector('.chat-item');
        if (firstChat) {
            const chatId = firstChat.getAttribute('data-chat-id');
            selectChat(chatId);
        }
        
        // Varsayılan filtreyi uygula
        filterByDate();
    }, 100);
});

// Sohbet seç
function selectChat(chatId) {
    activeChat = chatId;
    
    // Aktif sohbeti işaretle
    document.querySelectorAll('.chat-item').forEach(item => {
        item.classList.remove('active');
    });
    const selectedChat = document.querySelector(`.chat-item[data-chat-id="${chatId}"]`);
    if (selectedChat) {
        selectedChat.classList.add('active');
    }
    
    // Okunmamış badge'i kaldır
    const chatItem = document.querySelector(`.chat-item[data-chat-id="${chatId}"]`);
    if (chatItem) {
        chatItem.classList.remove('unread');
        const badge = chatItem.querySelector('.chat-unread-badge');
        if (badge) badge.remove();
    }
    
    // Boş durumu gizle, sohbet içeriğini göster
    const emptyState = document.querySelector('.chat-empty');
    const chatHeader = document.querySelector('.active-chat-header');
    const messagesContainer = document.querySelector('.messages-container');
    const inputContainer = document.querySelector('.message-input-container');
    
    if (emptyState) emptyState.style.display = 'none';
    if (chatHeader) chatHeader.style.display = 'flex';
    if (messagesContainer) messagesContainer.style.display = 'flex';
    if (inputContainer) inputContainer.style.display = 'flex';
    
    // Sohbet içeriğini yükle
    loadChatContent(chatId);
    
    // Mesaj inputunu focus et
    setTimeout(() => {
        const input = document.getElementById('messageInput');
        if (input) input.focus();
    }, 100);
}

// Sohbet içeriğini yükle
function loadChatContent(chatId) {
    const chatData = getChatData(chatId);
    
    // Başlık bilgilerini güncelle - Telefon numarası başlıkta, WhatsApp ismi altta
    const nameElement = document.querySelector('.active-chat-details h3');
    const avatarElement = document.querySelector('.active-chat-header .chat-avatar');
    const statusElement = document.querySelector('.active-chat-status');
    
    if (nameElement) nameElement.textContent = chatData.phone;
    if (avatarElement) avatarElement.textContent = chatData.avatar;
    
    // WhatsApp ismini durum alanında göster
    if (statusElement) {
        statusElement.textContent = chatData.whatsapp_name;
        statusElement.classList.remove('online');
    }
    
    // Bot durumunu güncelle
    updateBotStatus(chatId);
    
    // Mesajları göster
    displayMessages(chatData.messages);
    
    // Scroll to bottom
    setTimeout(() => {
        scrollToBottom();
    }, 100);
}

// Bot durumunu güncelle
function updateBotStatus(chatId) {
    const isActive = botEnabled[chatId] !== false; // Varsayılan true
    
    const indicator = document.querySelector('.bot-indicator');
    const statusText = document.querySelector('.bot-status');
    const toggleBtn = document.querySelector('.bot-toggle');
    
    if (!indicator || !statusText || !toggleBtn) return;
    
    if (isActive) {
        indicator.style.background = '#25d366';
        statusText.innerHTML = '<div class="bot-indicator"></div><i class="fas fa-robot"></i> Bot Aktif';
        statusText.style.color = '#25d366';
        toggleBtn.textContent = 'Botu Durdur';
        toggleBtn.classList.remove('inactive');
    } else {
        indicator.style.background = '#667781';
        statusText.innerHTML = '<div class="bot-indicator"></div><i class="fas fa-user"></i> Manuel Mod';
        statusText.style.color = '#667781';
        toggleBtn.textContent = 'Botu Başlat';
        toggleBtn.classList.add('inactive');
    }
}

// Bot durumunu değiştir
function toggleBot() {
    if (!activeChat) return;
    
    // Mevcut durumu değiştir
    const wasActive = botEnabled[activeChat] !== false;
    botEnabled[activeChat] = !wasActive;
    const isActive = botEnabled[activeChat];
    
    updateBotStatus(activeChat);
    
    // Manuel moda alındıysa
    const chatItem = document.querySelector(`.chat-item[data-chat-id="${activeChat}"]`);
    if (!isActive) {
        // Manuel mod - kırmızı border ekle
        chatItem.classList.add('manual-mode');
        
        // En üste taşı
        const chatList = document.querySelector('.chat-list');
        chatList.insertBefore(chatItem, chatList.firstChild);
        
        showNotification('Bot durduruldu, manuel moddasınız', 'info');
    } else {
        // Bot aktif - kırmızı border kaldır
        chatItem.classList.remove('manual-mode');
        
        showNotification('Bot aktif edildi', 'success');
    }
}

// Mesajları göster
function displayMessages(messages) {
    const container = document.querySelector('.messages-container');
    container.innerHTML = '';
    
    messages.forEach(msg => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${msg.type}`;
        
        let checkIcon = '';
        if (msg.type === 'sent') {
            checkIcon = msg.read ? 
                '<i class="fas fa-check-double"></i>' : 
                '<i class="fas fa-check"></i>';
        }
        
        messageDiv.innerHTML = `
            <div class="message-bubble">
                <p class="message-text">${msg.text}</p>
                <div class="message-time">
                    ${msg.time}
                    ${checkIcon}
                </div>
            </div>
        `;
        
        container.appendChild(messageDiv);
    });
    
    scrollToBottom();
}

// Mesaj gönder
function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message || !activeChat) return;
    
    const chatData = getChatData(activeChat);
    const now = new Date();
    const time = now.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
    
    // Mesajı ekle
    chatData.messages.push({
        type: 'sent',
        text: message,
        time: time,
        read: false
    });
    
    // Mesajı göster
    displayMessages(chatData.messages);
    
    // Input'u temizle
    input.value = '';
    
    // Son mesajı güncelle
    const chatItem = document.querySelector(`.chat-item[data-chat-id="${activeChat}"]`);
    chatItem.querySelector('.chat-last-message').textContent = message;
    chatItem.querySelector('.chat-time').textContent = time;
    
    // AJAX ile backend'e gönder
    sendMessageToBackend(activeChat, message);
    
    scrollToBottom();
}

// Yeni mesajları kontrol et
function checkNewMessages() {
    // AJAX ile backend'den yeni mesajları al
    // Demo için simüle ediyoruz
    if (Math.random() < 0.1) { // %10 ihtimalle yeni mesaj
        simulateNewMessage();
    }
}

// Yeni mesaj simülasyonu
function simulateNewMessage() {
    const chats = document.querySelectorAll('.chat-item');
    if (chats.length === 0) return;
    
    const randomChat = chats[Math.floor(Math.random() * chats.length)];
    const chatId = randomChat.dataset.chatId;
    const chatData = getChatData(chatId);
    
    const now = new Date();
    const time = now.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
    
    const newMessage = {
        type: 'received',
        text: 'Yeni bir mesaj geldi!',
        time: time
    };
    
    chatData.messages.push(newMessage);
    
    // Eğer aktif sohbetse mesajı göster
    if (activeChat === chatId) {
        displayMessages(chatData.messages);
    } else {
        // Okunmamış badge ekle
        randomChat.classList.add('unread');
        let badge = randomChat.querySelector('.chat-unread-badge');
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'chat-unread-badge';
            badge.textContent = '1';
            randomChat.querySelector('.chat-preview').appendChild(badge);
        } else {
            badge.textContent = parseInt(badge.textContent) + 1;
        }
    }
    
    // Son mesajı güncelle
    randomChat.querySelector('.chat-last-message').textContent = newMessage.text;
    randomChat.querySelector('.chat-time').textContent = time;
    
    // Bildiri sesi çal (opsiyonel)
    playNotificationSound();
}

// Sohbet verilerini al
function getChatData(chatId) {
    // 70 mesajlık demo veri
    const messages = [];
    const now = new Date();
    
    for (let i = 0; i < 70; i++) {
        const isReceived = i % 3 === 0; // Her 3 mesajdan 1'i gelen
        const time = new Date(now - (70 - i) * 60000); // Her mesaj 1 dk arayla
        const timeStr = time.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
        
        messages.push({
            type: isReceived ? 'received' : 'sent',
            text: isReceived ? 
                `Gelen mesaj #${i + 1}` : 
                `Giden mesaj #${i + 1}`,
            time: timeStr,
            read: !isReceived ? true : undefined
        });
    }
    
    // Demo veri - Gerçek uygulamada backend'den gelecek
    const chats = {
        '1': {
            name: 'Ahmet Yılmaz',
            whatsapp_name: 'Ahmet 🏠',
            avatar: 'AY',
            phone: '+90 532 123 4567',
            online: true,
            lastSeen: '10:30',
            messages: messages
        },
        '2': {
            name: 'Mehmet Demir',
            whatsapp_name: 'MD Store',
            avatar: 'MD',
            phone: '+90 533 234 5678',
            online: false,
            lastSeen: 'Bugün 09:45',
            messages: messages
        },
        '3': {
            name: 'Ayşe Kaya',
            whatsapp_name: 'Ayşe K.',
            avatar: 'AK',
            phone: '+90 534 345 6789',
            online: true,
            lastSeen: '11:20',
            messages: messages
        },
        '4': {
            name: 'Fatma Özdemir',
            whatsapp_name: 'Fatma 💼',
            avatar: 'FÖ',
            phone: '+90 535 456 7890',
            online: false,
            lastSeen: 'Dün 15:30',
            messages: messages
        },
        '5': {
            name: 'Ali Şahin',
            whatsapp_name: 'Ali',
            avatar: 'AŞ',
            phone: '+90 536 567 8901',
            online: false,
            lastSeen: 'Dün 18:20',
            messages: messages
        }
    };
    
    return chats[chatId] || chats['1'];
}

// Backend'e mesaj gönder
function sendMessageToBackend(chatId, message) {
    // AJAX ile WhatsApp Cloud API'ye gönder
    console.log('Mesaj gönderiliyor:', { chatId, message });
    
    // Gerçek implementasyon:
    /*
    fetch('ajax/send_whatsapp_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            chat_id: chatId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Mesaj gönderildi:', data);
    });
    */
}

// Scroll to bottom
function scrollToBottom() {
    const container = document.querySelector('.messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

// Bildirim sesi çal
function playNotificationSound() {
    // Ses dosyası eklenebilir
    // const audio = new Audio('assets/sounds/notification.mp3');
    // audio.play();
}

// Dosya yükleme
function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    processFile(file);
    
    // Input'u temizle
    event.target.value = '';
}

// Sürükle-bırak ile dosya
function handleDroppedFile(file) {
    if (!file) return;
    processFile(file);
}

// Dosyayı işle
function processFile(file) {
    const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
    const maxSize = 16; // 16 MB WhatsApp limiti
    
    if (fileSize > maxSize) {
        showNotification(`Dosya boyutu ${maxSize}MB'dan küçük olmalı`, 'error');
        return;
    }
    
    // Dosya tipini kontrol et
    const fileType = file.type.split('/')[0];
    let emoji = '📄';
    if (fileType === 'image') emoji = '🖼️';
    else if (fileType === 'video') emoji = '🎥';
    else if (file.type === 'application/pdf') emoji = '📑';
    
    // Mesaj olarak göster
    const chatData = getChatData(activeChat);
    const now = new Date();
    const time = now.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
    
    chatData.messages.push({
        type: 'sent',
        text: `${emoji} ${file.name} (${fileSize} MB)`,
        time: time,
        read: false
    });
    
    displayMessages(chatData.messages);
    
    // Dosyayı backend'e yükle
    uploadFileToBackend(activeChat, file);
}

// Backend'e dosya yükle
function uploadFileToBackend(chatId, file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('chat_id', chatId);
    
    showNotification('Dosya gönderiliyor...', 'info');
    
    // AJAX ile yükle
    /*
    fetch('ajax/upload_whatsapp_file.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Dosya gönderildi', 'success');
        }
    });
    */
}

// Sohbetleri ara
function searchChats() {
    const searchTerm = document.getElementById('chatSearch').value.toLowerCase().replace(/\s/g, '');
    const chats = document.querySelectorAll('.chat-item');
    
    chats.forEach(chat => {
        const phone = chat.getAttribute('data-phone').toLowerCase().replace(/\s/g, '');
        const message = chat.querySelector('.chat-last-message').textContent.toLowerCase();
        
        if (phone.includes(searchTerm) || message.includes(searchTerm)) {
            chat.style.display = 'flex';
        } else {
            chat.style.display = 'none';
        }
    });
    
    updateChatCount();
}

// Tarihe göre filtrele
function filterByDate() {
    const dateFilter = document.getElementById('dateFilter').value;
    const chats = document.querySelectorAll('.chat-item');
    
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);
    const weekAgo = new Date(today);
    weekAgo.setDate(weekAgo.getDate() - 7);
    const monthAgo = new Date(today);
    monthAgo.setDate(monthAgo.getDate() - 30);
    
    chats.forEach(chat => {
        const timeText = chat.querySelector('.chat-time').textContent;
        let show = true;
        
        if (dateFilter === 'today') {
            show = timeText.includes('Bugün') || timeText.includes(':');
        } else if (dateFilter === 'yesterday') {
            show = timeText.includes('Dün');
        } else if (dateFilter === 'week') {
            show = !timeText.includes('hafta önce') && !timeText.includes('gün önce') || 
                   timeText.includes('Bugün') || timeText.includes('Dün') || 
                   timeText.includes(':') || parseInt(timeText) <= 7;
        } else if (dateFilter === 'month') {
            show = true; // Hepsini göster
        } else if (dateFilter === 'all') {
            show = true;
        }
        
        chat.style.display = show ? 'flex' : 'none';
    });
    
    updateChatCount();
}

// Sohbet sayısını güncelle
function updateChatCount() {
    const chats = document.querySelectorAll('.chat-item');
    const visibleChats = Array.from(chats).filter(chat => chat.style.display !== 'none');
    document.getElementById('chatCount').textContent = `(${visibleChats.length})`;
}