<?php
$page_title = 'Meta Mesajları';
include 'includes/header.php';

// Demo sohbet verileri - Facebook ve Instagram karışık
$chats = [];
$names = ['Ahmet Yılmaz', 'Mehmet Demir', 'Ayşe Kaya', 'Fatma Özdemir', 'Ali Şahin', 'Zeynep Çelik', 'Mustafa Aydın', 'Elif Kara', 'Hasan Yıldız', 'Selin Arslan'];
$avatars = ['AY', 'MD', 'AK', 'FÖ', 'AŞ', 'ZÇ', 'MA', 'EK', 'HY', 'SA'];
$usernames = ['ahmet_ylmz', 'mehmet_dmr', 'ayse.kaya', 'fatma.ozd', 'ali.sahin', 'zeynep_clk', 'mustafa.aydn', 'elif_kara', 'hasan.yldz', 'selin.arsln'];
$messages = ['Merhaba', 'Teşekkürler 👍', 'Bu ürün hakkında bilgi alabilir miyim?', 'Kargo ne zaman gelir?', 'Eğitim fiyatları nedir?', 'Kayıt olmak istiyorum', 'Ürün var mı?', 'İade yapabilir miyim?', 'Sertifika ne zaman verilir?', 'Tamam 🙂'];
$times = ['Bugün', 'Dün', '2 gün önce', '3 gün önce', '1 hafta önce'];
$platforms = ['facebook', 'instagram'];

for ($i = 1; $i <= 60; $i++) {
    $nameIndex = ($i - 1) % 10;
    $platform = $platforms[$i % 2];

    $chats[] = [
        'id' => $i,
        'name' => $names[$nameIndex] . ' ' . $i,
        'username' => '@' . $usernames[$nameIndex] . $i,
        'avatar' => $avatars[$nameIndex],
        'platform' => $platform,
        'last_message' => $messages[($i - 1) % 10],
        'time' => $times[($i - 1) % 5],
        'unread' => $i <= 6 ? rand(0, 3) : 0,
        'online' => $i % 4 === 0
    ];
}
?>

<link rel="stylesheet" href="assets/css/meta-mesajlari.css">

<style>
/* Meta Mesajları için özel tam ekran */
body.meta-page {
    overflow: hidden;
}

body.meta-page .admin-wrapper {
    display: flex;
    height: 100vh;
    overflow: hidden;
}

body.meta-page .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    z-index: 1000;
}

body.meta-page .main-content {
    margin-left: 80px;
    width: calc(100% - 80px);
    padding: 0 !important;
    height: 100vh;
    overflow: hidden;
}

body.meta-page .meta-container {
    height: 100vh;
    margin: 0;
    border-radius: 0;
}

@media (max-width: 768px) {
    body.meta-page .main-content {
        margin-left: 0;
        width: 100%;
    }
}
</style>

<script>
document.body.classList.add('meta-page');
</script>

<div class="meta-container">
    <!-- Sol Panel - Sohbet Listesi -->
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <div>
                <h2>
                    <i class="fab fa-facebook-messenger"></i>
                    Sohbetler <span id="chatCount">(60)</span>
                </h2>
            </div>
            <div class="header-filters">
                <select id="platformFilter" class="filter-select" onchange="filterByPlatform()">
                    <option value="all" selected>Tüm Platformlar</option>
                    <option value="facebook">Facebook</option>
                    <option value="instagram">Instagram</option>
                </select>
                <select id="dateFilter" class="filter-select" onchange="filterByDate()">
                    <option value="today" selected>Bugün</option>
                    <option value="yesterday">Dün</option>
                    <option value="week">Bu Hafta</option>
                    <option value="month">Bu Ay</option>
                    <option value="all">Tümü</option>
                </select>
            </div>
        </div>

        <!-- Mobilde: Arama (%50) + Filtreler (%25 + %25) -->
        <div class="mobile-search-filters">
            <div class="chat-search mobile-search">
                <i class="fas fa-search"></i>
                <input type="text" id="chatSearch" placeholder="Sohbet ara..." onkeyup="searchChats()">
            </div>
            <div class="mobile-filters">
                <select id="platformFilterMobile" class="filter-select mobile-filter" onchange="filterByPlatform()">
                    <option value="all" selected>Tüm</option>
                    <option value="facebook">FB</option>
                    <option value="instagram">IG</option>
                </select>
                <select id="dateFilterMobile" class="filter-select mobile-filter" onchange="filterByDate()">
                    <option value="today" selected>Bugün</option>
                    <option value="yesterday">Dün</option>
                    <option value="week">Hafta</option>
                    <option value="month">Ay</option>
                    <option value="all">Tümü</option>
                </select>
            </div>
        </div>

        <div class="chat-list">
            <?php foreach ($chats as $chat): ?>
            <div class="chat-item <?php echo $chat['unread'] > 0 ? 'unread' : ''; ?>"
                 data-chat-id="<?php echo $chat['id']; ?>"
                 data-platform="<?php echo $chat['platform']; ?>"
                 data-username="<?php echo $chat['username']; ?>"
                 onclick="selectChat(<?php echo $chat['id']; ?>, '<?php echo $chat['platform']; ?>')">
                <div class="chat-avatar-wrapper">
                    <div class="chat-avatar"><?php echo $chat['avatar']; ?></div>
                    <div class="platform-badge platform-<?php echo $chat['platform']; ?>">
                        <i class="fab fa-<?php echo $chat['platform']; ?>"></i>
                    </div>
                </div>
                <div class="chat-info">
                    <div class="chat-header">
                        <span class="chat-name"><?php echo $chat['name']; ?></span>
                        <span class="chat-time"><?php echo $chat['time']; ?></span>
                    </div>
                    <div class="chat-preview">
                        <span class="chat-username"><?php echo $chat['username']; ?></span>
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
            <div class="empty-icon-wrapper">
                <i class="fab fa-facebook-messenger"></i>
                <i class="fab fa-instagram"></i>
            </div>
            <h3>Meta Messenger</h3>
            <p>Bir sohbet seçin ve mesajlaşmaya başlayın</p>
        </div>

        <!-- Aktif Sohbet Başlığı -->
        <div class="active-chat-header" style="display: none;">
            <button class="mobile-back-btn" onclick="backToChats()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="active-chat-info">
                <div class="chat-avatar-wrapper">
                    <div class="chat-avatar">AY</div>
                    <div class="platform-badge platform-facebook">
                        <i class="fab fa-facebook"></i>
                    </div>
                </div>
                <div class="active-chat-details">
                    <h3>Ahmet Yılmaz</h3>
                    <div class="active-chat-username">@ahmet_ylmz</div>
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
                <input type="file" id="fileInput" style="display: none;" accept="image/*,video/*,application/pdf" onchange="handleFileUpload(event)">
                <input type="text" id="messageInput" placeholder="Bir mesaj yazın...">
            </div>
            <button class="send-btn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script src="assets/js/meta-mesajlari.js"></script>

<?php include 'includes/footer.php'; ?>
