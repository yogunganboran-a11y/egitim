// Admin Panel - Ana JavaScript Dosyası

document.addEventListener('DOMContentLoaded', function() {
    
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    // Sidebar toggle
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('expanded');
            sidebarBackdrop.classList.toggle('active');
        });
    }
    
    // Close sidebar when clicking backdrop
    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', function() {
            sidebar.classList.remove('expanded');
            sidebarBackdrop.classList.remove('active');
        });
    }
    
    // Close sidebar when clicking outside (only when expanded)
    document.addEventListener('click', function(e) {
        if (sidebar.classList.contains('expanded') && 
            !sidebar.contains(e.target) && 
            !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('expanded');
            sidebarBackdrop.classList.remove('active');
        }
    });
    
    // Prevent closing when clicking inside sidebar
    sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Notification sistemi
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: ${type === 'success' ? 'linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(5, 150, 105, 0.9))' : 'linear-gradient(135deg, rgba(239, 68, 68, 0.9), rgba(220, 38, 38, 0.9))'};
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
        `;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
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
    };
    
    // Confirm dialog
    window.confirmAction = function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    };
    
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Table row hover effect
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
        });
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--red)';
                } else {
                    field.style.borderColor = 'var(--border-color)';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Lütfen tüm zorunlu alanları doldurun!', 'error');
            }
        });
    });
    
});

// Helper Functions
function formatDate(date) {
    const d = new Date(date);
    return d.toLocaleDateString('tr-TR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(amount);
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Panoya kopyalandı!', 'success');
    });
}

// Destek badge güncelleme (Tüm sayfalar için)
function updateSupportBadge() {
    // Gerçek uygulamada AJAX ile backend'den alınacak
    fetch('ajax/get_support_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.sidebar a[href="destek.php"] .notification-badge');
            if (badge && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'inline-flex';
            } else if (badge) {
                badge.style.display = 'none';
            }
        })
        .catch(error => {
            console.log('Support count fetch failed');
        });
}

// Sayfa yüklendiğinde ve her 30 saniyede bir güncelle
if (document.querySelector('.sidebar a[href="destek.php"]')) {
    updateSupportBadge();
    setInterval(updateSupportBadge, 30000);
}