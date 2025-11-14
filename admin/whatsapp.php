<?php
$page_title = 'WhatsApp';
include 'includes/header.php';

// Demo sohbet verileri - 70 sohbet
$chats = [];
$names = ['Ahmet Yılmaz', 'Mehmet Demir', 'Ayşe Kaya', 'Fatma Özdemir', 'Ali Şahin', 'Zeynep Çelik', 'Mustafa Aydın', 'Elif Kara', 'Hasan Yıldız', 'Selin Arslan'];
$avatars = ['AY', 'MD', 'AK', 'FÖ', 'AŞ', 'ZÇ', 'MA', 'EK', 'HY', 'SA'];
$messages = ['Merhaba', 'Teşekkürler', 'Ne zaman gelir?', 'Fiyat ne kadar?', 'İptal etmek istiyorum', 'Kargo takip', 'Ürün var mı?', 'İade', 'Bilgi alabilir miyim?', 'Tamam'];
$times = ['Bugün', 'Dün', '2 gün önce', '3 gün önce', '1 hafta önce'];

for ($i = 1; $i <= 70; $i++) {
    $nameIndex = ($i - 1) % 10;
    $phoneBase = 530 + ($i % 10);
    $phoneMid = str_pad($i, 3, '0', STR_PAD_LEFT);
    $phoneEnd = str_pad($i * 10, 4, '0', STR_PAD_LEFT);
    
    $chats[] = [
        'id' => $i,
        'name' => $names[$nameIndex] . ' ' . $i,
        'whatsapp_name' => $names[$nameIndex] . ' 📱',
        'avatar' => $avatars[$nameIndex],
        'phone' => "+90 {$phoneBase} {$phoneMid} {$phoneEnd}",
        'last_message' => $messages[($i - 1) % 10],
        'time' => $times[($i - 1) % 5],
        'unread' => $i <= 5 ? rand(0, 3) : 0,
        'online' => $i % 3 === 0
    ];
}
?>

<link rel="stylesheet" href="assets/css/whatsapp.css">

<style>
/* WhatsApp için özel tam ekran */
body.whatsapp-page {
    overflow: hidden;
}

body.whatsapp-page .admin-wrapper {
    display: flex;
    height: 100vh;
    overflow: hidden;
}

body.whatsapp-page .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    z-index: 1000;
}

body.whatsapp-page .main-content {
    margin-left: 80px;
    width: calc(100% - 80px);
    padding: 0 !important;
    height: 100vh;
    overflow: hidden;
}

body.whatsapp-page .whatsapp-container {
    height: 100vh;
    margin: 0;
    border-radius: 0;
}
</style>

<script>
document.body.classList.add('whatsapp-page');
</script>

<div class="whatsapp-container">
    <!-- Sol Panel - Sohbet Listesi -->
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <div>
                <h2>
                    <i class="fab fa-whatsapp"></i>
                    Sohbetler <span id="chatCount">(70)</span>
                </h2>
            </div>
            <div class="date-filter">
                <select id="dateFilter" class="date-filter-select" onchange="filterByDate()">
                    <option value="today" selected>Bugün</option>
                    <option value="yesterday">Dün</option>
                    <option value="week">Bu Hafta</option>
                    <option value="month">Bu Ay</option>
                    <option value="all">Tümü</option>
                </select>
            </div>
        </div>
        
        <div class="chat-search">
            <i class="fas fa-search"></i>
            <input type="text" id="chatSearch" placeholder="Sohbet ara..." onkeyup="searchChats()">
        </div>
        
        <div class="chat-list">
            <?php foreach ($chats as $chat): ?>
            <div class="chat-item <?php echo $chat['unread'] > 0 ? 'unread' : ''; ?>" 
                 data-chat-id="<?php echo $chat['id']; ?>"
                 data-phone="<?php echo $chat['phone']; ?>"
                 onclick="selectChat(<?php echo $chat['id']; ?>)">
                <div class="chat-avatar"><?php echo $chat['avatar']; ?></div>
                <div class="chat-info">
                    <div class="chat-header">
                        <span class="chat-name"><?php echo $chat['phone']; ?></span>
                        <span class="chat-time"><?php echo $chat['time']; ?></span>
                    </div>
                    <div class="chat-preview">
                        <span class="chat-last-message"><?php echo $chat['last_message']; ?></span>
                        <?php if ($chat['unread'] > 0): ?>
                        <span class="chat-unread-badge"><?php echo $chat['unread']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Sağ Panel - Sohbet İçeriği -->
    <div class="chat-content">
        <!-- Boş Durum -->
        <div class="chat-empty">
            <i class="fab fa-whatsapp"></i>
            <h3>WhatsApp Web</h3>
            <p>Bir sohbet seçin ve mesajlaşmaya başlayın</p>
        </div>
        
        <!-- Aktif Sohbet Başlığı -->
        <div class="active-chat-header" style="display: none;">
            <button class="mobile-back-btn" onclick="backToChats()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="active-chat-info">
                <div class="chat-avatar">AY</div>
                <div class="active-chat-details">
                    <h3>Ahmet Yılmaz</h3>
                    <div class="active-chat-status">Çevrimiçi</div>
                </div>
            </div>
            
            <div class="bot-control">
                <div class="bot-status">
                    <div class="bot-indicator"></div>
                    <i class="fas fa-robot"></i> Bot Aktif
                </div>
                <button class="bot-toggle" onclick="toggleBot()">Botu Durdur</button>
            </div>
        </div>
        
        <!-- Mesajlar -->
        <div class="messages-container" style="display: none;"></div>
        
        <!-- Mesaj Girişi -->
        <div class="message-input-container" style="display: none;">
            <div class="message-input-wrapper">
                <button class="emoji-btn">
                    <i class="far fa-smile"></i>
                </button>
                <button class="attach-btn" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-paperclip"></i>
                </button>
                <input type="file" id="fileInput" style="display: none;" accept="image/*,video/*,application/pdf,.doc,.docx" onchange="handleFileUpload(event)">
                <input type="text" id="messageInput" placeholder="Bir mesaj yazın...">
            </div>
            <button class="send-btn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script src="assets/js/whatsapp.js"></script>

<?php include 'includes/footer.php'; ?>