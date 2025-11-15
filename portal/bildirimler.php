<?php
session_start();

// Giriş kontrolü
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: giris.php');
    exit;
}

// Sayfa bilgileri
$page_title = 'Bildirimler';
$page_css = 'assets/css/bildirimler.css';

// Site ayarları
define('SITE_NAME', 'Eğitim Portalı');

function setPageTitle($title) {
    return $title . ' - ' . SITE_NAME;
}

include 'includes/header.php';

// Demo bildirim verileri
$notifications = [];
$types = ['success', 'info', 'warning', 'error'];
$icons = ['fa-check-circle', 'fa-video', 'fa-file-alt', 'fa-bell', 'fa-graduation-cap', 'fa-certificate', 'fa-user', 'fa-comment'];
$messages = [
    ['type' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Testiniz başarıyla tamamlandı', 'details' => 'Python Web Geliştirme testinde %85 başarı ile geçtiniz.'],
    ['type' => 'info', 'icon' => 'fa-video', 'text' => 'Yeni video eklendi', 'details' => 'Python Web Geliştirme kursuna 3 yeni video eklendi.'],
    ['type' => 'warning', 'icon' => 'fa-file-alt', 'text' => 'Sertifikanız hazır', 'details' => 'Python Web Geliştirme sertifikanız indirmeye hazır.'],
    ['type' => 'info', 'icon' => 'fa-graduation-cap', 'text' => 'Eğitim kaydınız onaylandı', 'details' => 'React Native Mobil Geliştirme eğitimine kaydınız onaylandı.'],
    ['type' => 'success', 'icon' => 'fa-certificate', 'text' => 'Yeni sertifika alındı', 'details' => 'JavaScript Temelleri kursunu başarıyla tamamladınız.'],
    ['type' => 'info', 'icon' => 'fa-bell', 'text' => 'Hatırlatma: Test zamanı', 'details' => 'Python Web Geliştirme ara sınav yarın başlıyor.'],
    ['type' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Ödemeniz alındı', 'details' => 'React Native Mobil Geliştirme kurs ödemesi başarıyla alındı.'],
    ['type' => 'warning', 'icon' => 'fa-file-alt', 'text' => 'Eksik bilgi', 'details' => 'Profil bilgilerinizi tamamlayınız.'],
    ['type' => 'info', 'icon' => 'fa-comment', 'text' => 'Yeni mesaj', 'details' => 'Eğitmeniniz size bir mesaj gönderdi.'],
    ['type' => 'success', 'icon' => 'fa-video', 'text' => 'Video izleme ilerlemeniz kaydedildi', 'details' => 'Python Web Geliştirme - Ders 5 izlendi.']
];

$times = [
    '2 saat önce',
    '5 saat önce',
    '1 gün önce',
    '2 gün önce',
    '3 gün önce',
    '5 gün önce',
    '1 hafta önce',
    '2 hafta önce',
    '3 hafta önce',
    '1 ay önce'
];

// 30 bildirim oluştur
for ($i = 0; $i < 30; $i++) {
    $msgIndex = $i % 10;
    $timeIndex = $i % 10;

    $notifications[] = [
        'id' => $i + 1,
        'type' => $messages[$msgIndex]['type'],
        'icon' => $messages[$msgIndex]['icon'],
        'text' => $messages[$msgIndex]['text'],
        'details' => $messages[$msgIndex]['details'],
        'time' => $times[$timeIndex],
        'unread' => $i < 8, // İlk 8 bildirim okunmamış
        'timestamp' => time() - ($i * 3600)
    ];
}
?>

<link rel="stylesheet" href="assets/css/bildirimler.css">

<div class="page-container">
    <!-- Sayfa Başlığı -->
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="fas fa-bell"></i>
                Bildirimler
            </h1>
            <p class="page-subtitle">Tüm bildirimlerinizi bu sayfadan görüntüleyebilirsiniz</p>
        </div>

    <!-- Bildirim Listesi -->
    <div class="notifications-list" id="notificationsList">
        <?php foreach ($notifications as $notif): ?>
        <div class="notification-card <?php echo $notif['unread'] ? 'unread' : ''; ?>"
             data-id="<?php echo $notif['id']; ?>"
             data-type="<?php echo $notif['type']; ?>"
             data-unread="<?php echo $notif['unread'] ? 'true' : 'false'; ?>">

            <div class="notification-indicator"></div>

            <div class="notification-icon-wrapper">
                <div class="notification-icon <?php echo $notif['type']; ?>">
                    <i class="fas <?php echo $notif['icon']; ?>"></i>
                </div>
            </div>

            <div class="notification-body">
                <div class="notification-text">
                    <strong><?php echo $notif['text']; ?></strong>
                    <p><?php echo $notif['details']; ?></p>
                </div>
                <div class="notification-time">
                    <i class="far fa-clock"></i>
                    <?php echo $notif['time']; ?>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- Boş Durum -->
    <div class="empty-state" id="emptyState" style="display: none;">
        <div class="empty-icon">
            <i class="fas fa-bell-slash"></i>
        </div>
        <h3>Bildirim Bulunamadı</h3>
        <p>Seçili filtreye uygun bildirim bulunmuyor.</p>
    </div>

    <!-- Pagination -->
    <div class="pagination-container" id="paginationContainer">
        <button class="pagination-btn" id="loadMoreBtn" onclick="loadMore()">
            <i class="fas fa-chevron-down"></i>
            Daha Fazla Yükle
        </button>
    </div>
</div>

<script src="assets/js/bildirimler.js"></script>

<?php include 'includes/footer.php'; ?>
