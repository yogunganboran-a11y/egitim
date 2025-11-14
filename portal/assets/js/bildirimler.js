// Bildirimler Sayfası - JavaScript

let currentFilter = 'all';
let visibleCount = 10;
let allNotifications = [];

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    allNotifications = Array.from(document.querySelectorAll('.notification-card'));

    // İlk yükleme - sadece ilk 10 bildirimi göster
    showNotifications();

    // İstatistikleri güncelle
    updateFilterCounts();
});

// Bildirimleri göster
function showNotifications() {
    const filteredNotifications = getFilteredNotifications();

    // Tüm bildirimleri gizle
    allNotifications.forEach(notif => {
        notif.style.display = 'none';
    });

    // Filtrelenmiş ve görünür sayıdaki bildirimleri göster
    filteredNotifications.slice(0, visibleCount).forEach(notif => {
        notif.style.display = 'flex';
    });

    // Boş durum kontrolü
    const emptyState = document.getElementById('emptyState');
    const notificationsList = document.getElementById('notificationsList');

    if (filteredNotifications.length === 0) {
        emptyState.style.display = 'flex';
        notificationsList.style.display = 'none';
    } else {
        emptyState.style.display = 'none';
        notificationsList.style.display = 'flex';
    }

    // "Daha Fazla Yükle" butonu kontrolü
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const paginationContainer = document.getElementById('paginationContainer');

    if (filteredNotifications.length <= visibleCount) {
        paginationContainer.style.display = 'none';
    } else {
        paginationContainer.style.display = 'flex';
    }
}

// Filtrelenmiş bildirimleri al
function getFilteredNotifications() {
    if (currentFilter === 'all') {
        return allNotifications;
    } else if (currentFilter === 'unread') {
        return allNotifications.filter(notif => notif.dataset.unread === 'true');
    } else {
        return allNotifications.filter(notif => notif.dataset.type === currentFilter);
    }
}

// Bildirimleri filtrele
function filterNotifications(filter) {
    currentFilter = filter;
    visibleCount = 10; // Filtreyi değiştirince sayıyı sıfırla

    // Aktif tab'ı güncelle
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelector(`.filter-tab[data-filter="${filter}"]`).classList.add('active');

    // Bildirimleri göster
    showNotifications();
}

// Filtre sayılarını güncelle
function updateFilterCounts() {
    const unreadCount = allNotifications.filter(notif => notif.dataset.unread === 'true').length;
    const unreadTab = document.querySelector('.filter-tab[data-filter="unread"] .tab-count');
    if (unreadTab) {
        unreadTab.textContent = unreadCount;
    }

    const allTab = document.querySelector('.filter-tab[data-filter="all"] .tab-count');
    if (allTab) {
        allTab.textContent = allNotifications.length;
    }
}

// Tek bildirimi okundu işaretle
function markAsRead(id) {
    const notification = document.querySelector(`.notification-card[data-id="${id}"]`);
    if (!notification) return;

    notification.classList.add('marking-read');
    setTimeout(() => {
        notification.classList.remove('unread');
        notification.dataset.unread = 'false';
        notification.classList.remove('marking-read');
        updateFilterCounts();
        showNotification('Bildirim okundu olarak işaretlendi', 'success');
    }, 500);

    // Backend'e gönder
    console.log('Bildirim okundu işaretlendi:', id);
}

// Tümünü okundu işaretle
function markAllAsRead() {
    const unreadNotifications = allNotifications.filter(notif => notif.dataset.unread === 'true');

    if (unreadNotifications.length === 0) {
        showNotification('Okunmamış bildirim bulunamadı', 'info');
        return;
    }

    unreadNotifications.forEach((notif, index) => {
        setTimeout(() => {
            notif.classList.add('marking-read');
            setTimeout(() => {
                notif.classList.remove('unread');
                notif.dataset.unread = 'false';
                notif.classList.remove('marking-read');
            }, 500);
        }, index * 100);
    });

    setTimeout(() => {
        updateFilterCounts();
        showNotifications();
        showNotification('Tüm bildirimler okundu olarak işaretlendi', 'success');
    }, unreadNotifications.length * 100 + 500);

    console.log('Tüm bildirimler okundu işaretlendi');
}

// Bildirimi sil
function deleteNotification(id) {
    const notification = document.querySelector(`.notification-card[data-id="${id}"]`);
    if (!notification) return;

    notification.classList.add('removing');

    setTimeout(() => {
        notification.remove();
        allNotifications = Array.from(document.querySelectorAll('.notification-card'));
        updateFilterCounts();
        showNotifications();
        showNotification('Bildirim silindi', 'success');
    }, 300);

    console.log('Bildirim siliniyor:', id);
}

// Tümünü temizle
function clearAll() {
    if (!confirm('Tüm bildirimleri silmek istediğinizden emin misiniz?')) {
        return;
    }

    const visibleNotifications = allNotifications.filter(notif => notif.style.display !== 'none');

    visibleNotifications.forEach((notif, index) => {
        setTimeout(() => {
            notif.classList.add('removing');
            setTimeout(() => {
                notif.remove();
            }, 300);
        }, index * 50);
    });

    setTimeout(() => {
        allNotifications = Array.from(document.querySelectorAll('.notification-card'));
        updateFilterCounts();
        showNotifications();
        showNotification('Tüm bildirimler silindi', 'success');
    }, visibleNotifications.length * 50 + 300);

    console.log('Tüm bildirimler temizleniyor');
}

// Daha fazla yükle
function loadMore() {
    visibleCount += 10;
    showNotifications();
    showNotification('Daha fazla bildirim yüklendi', 'success');
}

// Bildirim göster (UI feedback)
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `toast-notification toast-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        background: ${type === 'success' ? 'linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(5, 150, 105, 0.95))' : 'linear-gradient(135deg, rgba(59, 130, 246, 0.95), rgba(37, 99, 235, 0.95))'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        font-size: 0.95rem;
        font-weight: 500;
    `;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 100);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
