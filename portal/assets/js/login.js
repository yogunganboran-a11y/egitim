// Login JavaScript - TCKN Giriş + Rate Limiting + CAPTCHA

// Global değişkenler
let failedAttempts = 0;
let captchaRequired = false;
let captchaCode = '';
let isBlocked = false;
let blockEndTime = null;

// TCKN validasyonu (demo modda basit kontrol)
function validateTCKN(tckn) {
    // Demo modda sadece 11 haneli mi kontrol et
    if (!/^\d{11}$/.test(tckn)) {
        return false;
    }
    
    // İlk hane 0 olamaz
    const digits = tckn.split('').map(Number);
    if (digits[0] === 0) {
        return false;
    }
    
    return true;
    
    /* Gerçek TCKN validasyonu - backend'de yapılacak
    const sum1 = (digits[0] + digits[2] + digits[4] + digits[6] + digits[8]) * 7;
    const sum2 = (digits[1] + digits[3] + digits[5] + digits[7]);
    const digit10 = (sum1 - sum2) % 10;
    
    if (digit10 !== digits[9]) {
        return false;
    }
    
    const sum3 = digits.slice(0, 10).reduce((a, b) => a + b, 0);
    const digit11 = sum3 % 10;
    
    return digit11 === digits[10];
    */
}

// CAPTCHA oluştur
function generateCaptcha() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    captchaCode = '';
    for (let i = 0; i < 6; i++) {
        captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    const canvas = document.getElementById('captchaCanvas');
    const ctx = canvas.getContext('2d');
    
    // Temizle
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Arka plan
    ctx.fillStyle = '#f3f4f6';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Gürültü çizgileri
    for (let i = 0; i < 5; i++) {
        ctx.strokeStyle = `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.3)`;
        ctx.beginPath();
        ctx.moveTo(Math.random() * canvas.width, Math.random() * canvas.height);
        ctx.lineTo(Math.random() * canvas.width, Math.random() * canvas.height);
        ctx.stroke();
    }
    
    // CAPTCHA metni
    ctx.font = 'bold 30px Arial';
    ctx.fillStyle = '#1f2937';
    ctx.textBaseline = 'middle';
    
    for (let i = 0; i < captchaCode.length; i++) {
        ctx.save();
        ctx.translate(20 + i * 28, 30);
        ctx.rotate((Math.random() - 0.5) * 0.4);
        ctx.fillText(captchaCode[i], 0, 0);
        ctx.restore();
    }
}

// Mesaj göster
function showMessage(message, type = 'error') {
    const messageBox = document.getElementById('messageBox');
    messageBox.className = `message-box ${type}`;
    messageBox.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i> ${message}`;
    messageBox.style.display = 'flex';
    
    // 5 saniye sonra gizle
    setTimeout(() => {
        messageBox.style.display = 'none';
    }, 5000);
}

// Deneme uyarısı göster
function showAttemptWarning() {
    const warning = document.getElementById('attemptWarning');
    const text = document.getElementById('attemptText');
    const remaining = 5 - failedAttempts;
    
    if (remaining > 0 && failedAttempts > 0) {
        text.textContent = `Kalan deneme hakkı: ${remaining}`;
        warning.style.display = 'flex';
    } else {
        warning.style.display = 'none';
    }
}

// CAPTCHA göster/gizle
function toggleCaptcha(show) {
    const container = document.getElementById('captchaContainer');
    if (show) {
        captchaRequired = true;
        container.style.display = 'block';
        generateCaptcha();
    } else {
        captchaRequired = false;
        container.style.display = 'none';
    }
}

// Block kontrolü
function checkBlock() {
    if (blockEndTime && Date.now() < blockEndTime) {
        isBlocked = true;
        const remainingSeconds = Math.ceil((blockEndTime - Date.now()) / 1000);
        showMessage(`Çok fazla başarısız deneme! ${remainingSeconds} saniye sonra tekrar deneyebilirsiniz.`, 'error');
        return true;
    }
    isBlocked = false;
    return false;
}

// Form submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const tcknInput = document.getElementById('tckn');
    const captchaInput = document.getElementById('captcha');
    const loginBtn = document.getElementById('loginBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const refreshBtn = document.getElementById('refreshCaptcha');
    
    // TCKN sadece rakam
    tcknInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    
    // CAPTCHA yenile
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            generateCaptcha();
            captchaInput.value = '';
        });
    }
    
    // Form submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Block kontrolü
        if (checkBlock()) {
            return;
        }
        
        const tckn = tcknInput.value.trim();
        const captcha = captchaInput ? captchaInput.value.trim().toUpperCase() : '';
        
        // TCKN kontrolü
        if (!tckn) {
            showMessage('TCKN giriniz', 'error');
            return;
        }
        
        if (!validateTCKN(tckn)) {
            showMessage('Geçersiz TCKN', 'error');
            failedAttempts++;
            showAttemptWarning();
            
            // 5 başarısız denemeden sonra CAPTCHA
            if (failedAttempts >= 5 && !captchaRequired) {
                toggleCaptcha(true);
            }
            
            // 10 başarısız denemeden sonra 30 saniye block
            if (failedAttempts >= 10) {
                blockEndTime = Date.now() + (30 * 1000);
                isBlocked = true;
                showMessage('Çok fazla başarısız deneme! 30 saniye sonra tekrar deneyebilirsiniz.', 'error');
                
                // Countdown göster
                const interval = setInterval(() => {
                    if (checkBlock()) {
                        const remaining = Math.ceil((blockEndTime - Date.now()) / 1000);
                        showMessage(`Kalan süre: ${remaining} saniye`, 'error');
                    } else {
                        clearInterval(interval);
                        failedAttempts = 0;
                        toggleCaptcha(false);
                        showAttemptWarning();
                    }
                }, 1000);
            }
            
            return;
        }
        
        // CAPTCHA kontrolü
        if (captchaRequired) {
            if (!captcha) {
                showMessage('Güvenlik kodunu giriniz', 'error');
                return;
            }
            
            if (captcha !== captchaCode) {
                showMessage('Yanlış güvenlik kodu', 'error');
                generateCaptcha();
                captchaInput.value = '';
                return;
            }
        }
        
        // Loading göster
        loginBtn.disabled = true;
        loginBtn.style.display = 'none';
        loadingSpinner.style.display = 'block';
        
        // Simüle giriş (backend hazır olunca AJAX ile değiştirilecek)
        setTimeout(() => {
            // Demo kullanıcılar
            const validTCKNs = ['12345678901', '98765432109'];
            
            if (validTCKNs.includes(tckn)) {
                // Başarılı giriş
                showMessage('Giriş başarılı! Yönlendiriliyorsunuz...', 'success');
                
                // Demo kullanıcı bilgileri
                const demoUsers = {
                    '12345678901': {
                        tckn: '12345678901',
                        ad: 'Ahmet',
                        soyad: 'Yılmaz',
                        telefon: '0532 123 4567',
                        dogum_tarihi: '15.03.1990'
                    },
                    '98765432109': {
                        tckn: '98765432109',
                        ad: 'Ayşe',
                        soyad: 'Kaya',
                        telefon: '0533 234 5678',
                        dogum_tarihi: '22.07.1985'
                    }
                };
                
                // PHP session oluşturmak için backend'e gönder
                fetch('login-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `tckn=${tckn}`
                })
                .then(response => response.json())
                .then(data => {
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 1000);
                })
                .catch(error => {
                    // Backend yoksa yine de devam et
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 1000);
                });
            } else {
                showMessage('Geçersiz TCKN! Demo TCKN: 12345678901 veya 98765432109', 'error');
                failedAttempts++;
                showAttemptWarning();
                
                if (failedAttempts >= 5) {
                    toggleCaptcha(true);
                }
                
                loginBtn.disabled = false;
                loginBtn.style.display = 'flex';
                loadingSpinner.style.display = 'none';
            }
        }, 1500);
    });
});