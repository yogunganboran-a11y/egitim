// Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard yüklendi');
    
    // Progress bar animasyonu
    animateProgressBars();
    
    // Stats kartları animasyonu
    animateStatNumbers();
});

// Progress bar animasyonu
function animateProgressBars() {
    const progressBars = document.querySelectorAll('.progress-fill');
    
    progressBars.forEach(bar => {
        const targetWidth = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = targetWidth;
        }, 300);
    });
}

// Stat sayılarını animasyonlu göster
function animateStatNumbers() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        let current = 0;
        const increment = target / 50;
        const duration = 1000;
        const stepTime = duration / 50;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                stat.textContent = target;
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(current);
            }
        }, stepTime);
    });
}
