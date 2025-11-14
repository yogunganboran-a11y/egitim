// Ayarlar Sayfası - JavaScript

// Sekme değiştir
function changeTab(tabName) {
    // Tüm sekmeleri gizle
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Tüm butonları pasif yap
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Seçili sekmeyi göster
    document.getElementById(tabName).classList.add('active');
    document.querySelector(`[onclick="changeTab('${tabName}')"]`).classList.add('active');
}

// Admin ekle modal
function openAddAdminModal() {
    document.getElementById('addAdminModal').classList.add('active');
}

// Admin ekle
function addAdmin() {
    const name = document.getElementById('adminName').value.trim();
    const email = document.getElementById('adminEmail').value.trim();
    const password = document.getElementById('adminPassword').value.trim();
    const role = document.getElementById('adminRole').value;
    
    if (!name || !email || !password) {
        showNotification('Lütfen tüm alanları doldurun', 'error');
        return;
    }
    
    if (!validateEmail(email)) {
        showNotification('Geçerli bir email adresi girin', 'error');
        return;
    }
    
    if (password.length < 6) {
        showNotification('Şifre en az 6 karakter olmalıdır', 'error');
        return;
    }
    
    // Backend'e gönder
    console.log('Yeni admin:', { name, email, password, role });
    
    showNotification('Admin başarıyla eklendi', 'success');
    closeModal('addAdminModal');
    
    // Formu temizle
    document.getElementById('adminName').value = '';
    document.getElementById('adminEmail').value = '';
    document.getElementById('adminPassword').value = '';
    
    // Sayfayı yenile (veya dinamik olarak ekle)
    setTimeout(() => {
        location.reload();
    }, 1000);
    
    /*
    fetch('ajax/add_admin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password, role })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Admin başarıyla eklendi', 'success');
            location.reload();
        }
    });
    */
}

// Email doğrulama
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Admin durumunu değiştir
function toggleAdminStatus(adminId) {
    console.log('Admin durumu değiştirildi:', adminId);
    showNotification('Admin durumu güncellendi', 'success');
    
    /*
    fetch('ajax/toggle_admin_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ admin_id: adminId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Durum güncellendi', 'success');
            location.reload();
        }
    });
    */
}

// Admin sil
function deleteAdmin(adminId) {
    if (!confirm('Bu admini silmek istediğinize emin misiniz?')) {
        return;
    }
    
    console.log('Admin silindi:', adminId);
    showNotification('Admin silindi', 'success');
    
    // Satırı kaldır
    const row = document.querySelector(`tr[data-admin-id="${adminId}"]`);
    if (row) {
        row.remove();
    }
    
    /*
    fetch('ajax/delete_admin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ admin_id: adminId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Admin silindi', 'success');
            location.reload();
        }
    });
    */
}

// Genel ayarları kaydet
function saveGeneralSettings() {
    const siteName = document.getElementById('siteName').value;
    const siteUrl = document.getElementById('siteUrl').value;
    const contactEmail = document.getElementById('contactEmail').value;
    const contactPhone = document.getElementById('contactPhone').value;
    
    console.log('Genel ayarlar:', { siteName, siteUrl, contactEmail, contactPhone });
    
    showNotification('Ayarlar kaydedildi', 'success');
    
    /*
    fetch('ajax/save_general_settings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ siteName, siteUrl, contactEmail, contactPhone })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Ayarlar kaydedildi', 'success');
        }
    });
    */
}

// API ayarlarını kaydet
function saveAPISettings(apiType) {
    const formData = {};
    const form = document.querySelector(`#${apiType}Settings`);
    
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        formData[input.id] = input.value;
    });
    
    console.log(`${apiType} ayarları:`, formData);
    
    showNotification(`${apiType} ayarları kaydedildi`, 'success');
    
    /*
    fetch('ajax/save_api_settings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type: apiType, data: formData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('API ayarları kaydedildi', 'success');
        }
    });
    */
}

// API test et
function testAPI(apiType) {
    const resultDiv = document.getElementById(`${apiType}TestResult`);
    resultDiv.classList.remove('success', 'error');
    resultDiv.classList.add('active');
    resultDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Test ediliyor...';
    
    setTimeout(() => {
        const success = Math.random() > 0.3; // Demo için rastgele
        
        if (success) {
            resultDiv.classList.add('success');
            resultDiv.innerHTML = '<i class="fas fa-check-circle"></i> Bağlantı başarılı!';
        } else {
            resultDiv.classList.add('error');
            resultDiv.innerHTML = '<i class="fas fa-times-circle"></i> Bağlantı hatası! Lütfen API bilgilerinizi kontrol edin.';
        }
    }, 1500);
    
    /*
    fetch('ajax/test_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type: apiType })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.classList.add('success');
            resultDiv.innerHTML = '<i class="fas fa-check-circle"></i> Bağlantı başarılı!';
        } else {
            resultDiv.classList.add('error');
            resultDiv.innerHTML = '<i class="fas fa-times-circle"></i> ' + data.message;
        }
    });
    */
}

// SEO ayarlarını kaydet
function saveSEOSettings() {
    const googleAnalytics = document.getElementById('googleAnalytics').value;
    const googleTagManager = document.getElementById('googleTagManager').value;
    const yandexMetrika = document.getElementById('yandexMetrika').value;
    const metaDescription = document.getElementById('metaDescription').value;
    const metaKeywords = document.getElementById('metaKeywords').value;
    
    console.log('SEO ayarları:', { googleAnalytics, googleTagManager, yandexMetrika, metaDescription, metaKeywords });
    
    showNotification('SEO ayarları kaydedildi', 'success');
    
    /*
    fetch('ajax/save_seo_settings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ googleAnalytics, googleTagManager, yandexMetrika, metaDescription, metaKeywords })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('SEO ayarları kaydedildi', 'success');
        }
    });
    */
}

// Modal kapat
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Modal dışına tıklayınca kapat
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});

// AI Agent talimatları modal
function openAIAgentModal(type) {
    document.getElementById('aiAgentType').value = type;
    document.getElementById('aiAgentModalTitle').textContent = 
        type === 'phone' ? 'Telefon AI Agent Talimatları' : 'WhatsApp AI Agent Talimatları';
    
    // Mevcut talimatları yükle
    loadAIInstructions(type);
    
    document.getElementById('aiAgentModal').classList.add('active');
}

// AI talimatlarını yükle
function loadAIInstructions(type) {
    // Backend'den yükle
    // Demo veri
    const demoInstructions = {
        phone: {
            systemPrompt: 'Sen bir müşteri hizmetleri asistanısın. Nazik ve yardımsever ol.',
            greeting: 'Merhaba, size nasıl yardımcı olabilirim?',
            farewell: 'İyi günler dilerim, başka bir konuda yardımcı olabilir miyim?',
            fallback: 'Üzgünüm, tam olarak anlayamadım. Lütfen tekrar eder misiniz?'
        },
        whatsapp: {
            systemPrompt: 'Sen bir satış asistanısın. Ürünler hakkında bilgi ver ve satışı tamamlamaya yardımcı ol.',
            greeting: 'Merhaba! 👋 Size nasıl yardımcı olabilirim?',
            farewell: 'Teşekkürler! İyi günler 😊',
            fallback: 'Anlayamadım, biraz daha açıklayabilir misiniz?'
        }
    };
    
    const data = demoInstructions[type];
    if (data) {
        document.getElementById('systemPrompt').value = data.systemPrompt;
        document.getElementById('greetingMessage').value = data.greeting;
        document.getElementById('farewellMessage').value = data.farewell;
        document.getElementById('fallbackMessage').value = data.fallback;
    }
}

// AI talimatlarını kaydet
function saveAIInstructions() {
    const type = document.getElementById('aiAgentType').value;
    const systemPrompt = document.getElementById('systemPrompt').value;
    const greeting = document.getElementById('greetingMessage').value;
    const farewell = document.getElementById('farewellMessage').value;
    const fallback = document.getElementById('fallbackMessage').value;
    
    console.log('AI Talimatları:', { type, systemPrompt, greeting, farewell, fallback });
    
    showNotification('AI talimatları kaydedildi', 'success');
    closeModal('aiAgentModal');
    
    /*
    fetch('ajax/save_ai_instructions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type, systemPrompt, greeting, farewell, fallback })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('AI talimatları kaydedildi', 'success');
            closeModal('aiAgentModal');
        }
    });
    */
}

// Logo yükleme
function handleLogoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    if (!file.type.startsWith('image/')) {
        showNotification('Lütfen bir resim dosyası seçin', 'error');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('logoPreview').src = e.target.result;
        document.getElementById('logoPreview').classList.add('active');
    };
    reader.readAsDataURL(file);
}

// Favicon yükleme
function handleFaviconUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    if (!file.type.startsWith('image/')) {
        showNotification('Lütfen bir resim dosyası seçin', 'error');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('faviconPreview').src = e.target.result;
        document.getElementById('faviconPreview').classList.add('active');
    };
    reader.readAsDataURL(file);
}

// Admin düzenle
function editAdmin(adminId) {
    console.log('Admin düzenleniyor:', adminId);
    // Modal aç ve form doldur
    showNotification('Admin düzenleme özelliği hazırlanıyor...', 'info');
}

// ============================================
// IP YÖNETİMİ FONKSİYONLARI
// ============================================

// IP ekleme modalını aç
function openAddIPModal(type) {
    document.getElementById('ipType').value = type;
    document.getElementById('ipEditId').value = '';
    document.getElementById('ipAddress').value = '';
    document.getElementById('ipDescription').value = '';
    
    if (type === 'whitelist') {
        document.getElementById('ipModalTitle').textContent = 'Yeni IP Ekle (Whitelist)';
        document.getElementById('ipDescriptionLabel').innerHTML = 'Açıklama <span class="required">*</span>';
    } else {
        document.getElementById('ipModalTitle').textContent = 'IP Engelle (Blocklist)';
        document.getElementById('ipDescriptionLabel').innerHTML = 'Engelleme Sebebi <span class="required">*</span>';
    }
    
    document.getElementById('addIPModal').classList.add('active');
}

// IP düzenle
function editIP(ipId, type) {
    // Modal'ı düzenleme modunda aç
    document.getElementById('ipType').value = type;
    document.getElementById('ipEditId').value = ipId;
    
    // Mevcut verileri yükle (backend'den)
    // Demo veri
    if (type === 'whitelist') {
        document.getElementById('ipModalTitle').textContent = 'IP Düzenle (Whitelist)';
        document.getElementById('ipAddress').value = '192.168.1.100';
        document.getElementById('ipDescription').value = 'Ofis Bağlantısı';
    } else {
        document.getElementById('ipModalTitle').textContent = 'IP Düzenle (Blocklist)';
        document.getElementById('ipAddress').value = '198.51.100.23';
        document.getElementById('ipDescription').value = 'Brute force saldırısı';
    }
    
    document.getElementById('addIPModal').classList.add('active');
}

// IP kaydet
function saveIP() {
    const type = document.getElementById('ipType').value;
    const editId = document.getElementById('ipEditId').value;
    const ipAddress = document.getElementById('ipAddress').value.trim();
    const description = document.getElementById('ipDescription').value.trim();
    
    // Validasyon
    if (!ipAddress) {
        showNotification('IP adresi boş olamaz', 'error');
        return;
    }
    
    if (!validateIPAddress(ipAddress)) {
        showNotification('Geçerli bir IP adresi girin', 'error');
        return;
    }
    
    if (!description) {
        showNotification(type === 'whitelist' ? 'Açıklama boş olamaz' : 'Engelleme sebebi boş olamaz', 'error');
        return;
    }
    
    console.log('IP kaydediliyor:', { type, editId, ipAddress, description });
    
    showNotification(editId ? 'IP güncellendi' : 'IP eklendi', 'success');
    closeModal('addIPModal');
    
    // Tabloyu güncelle
    setTimeout(() => {
        location.reload();
    }, 1000);
    
    /*
    fetch('ajax/save_ip.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type, id: editId, ip: ipAddress, description })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(editId ? 'IP güncellendi' : 'IP eklendi', 'success');
            closeModal('addIPModal');
            location.reload();
        }
    });
    */
}

// IP validasyonu
function validateIPAddress(ip) {
    const ipRegex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return ipRegex.test(ip);
}

// IP durumunu değiştir
function toggleIPStatus(ipId, type) {
    console.log('IP durumu değiştiriliyor:', ipId, type);
    showNotification('IP durumu güncellendi', 'success');
    
    /*
    fetch('ajax/toggle_ip_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: ipId, type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('IP durumu güncellendi', 'success');
        }
    });
    */
}

// IP sil
function deleteIP(ipId, type) {
    if (!confirm('Bu IP adresini silmek istediğinize emin misiniz?')) {
        return;
    }
    
    console.log('IP siliniyor:', ipId, type);
    showNotification('IP silindi', 'success');
    
    // Satırı kaldır
    const row = document.querySelector(`tr[data-ip-id="${ipId}"]`);
    if (row) {
        row.remove();
    }
    
    /*
    fetch('ajax/delete_ip.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: ipId, type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('IP silindi', 'success');
            location.reload();
        }
    });
    */
}

// Şüpheli IP'yi engelle
function blockSuspiciousIP(ipId, ipAddress) {
    if (!confirm(`${ipAddress} IP adresini engellemek istediğinize emin misiniz?`)) {
        return;
    }
    
    console.log('Şüpheli IP engelleniyor:', ipId, ipAddress);
    
    // Şüpheli listeden kaldır
    const row = document.querySelector(`#suspiciousTable tr[data-ip-id="${ipId}"]`);
    if (row) {
        row.remove();
    }
    
    showNotification(`${ipAddress} blocklist'e eklendi`, 'success');
    
    /*
    fetch('ajax/block_suspicious_ip.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: ipId, ip: ipAddress })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${ipAddress} blocklist'e eklendi`, 'success');
            location.reload();
        }
    });
    */
}

// Şüpheli IP'yi yoksay
function ignoreSuspiciousIP(ipId) {
    if (!confirm('Bu IP adresini şüpheli listesinden kaldırmak istediğinize emin misiniz?')) {
        return;
    }
    
    console.log('Şüpheli IP yoksayılıyor:', ipId);
    
    // Şüpheli listeden kaldır
    const row = document.querySelector(`#suspiciousTable tr[data-ip-id="${ipId}"]`);
    if (row) {
        row.remove();
    }
    
    showNotification('IP şüpheli listesinden kaldırıldı', 'success');
    
    /*
    fetch('ajax/ignore_suspicious_ip.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: ipId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('IP şüpheli listesinden kaldırıldı', 'success');
            location.reload();
        }
    });
    */
}