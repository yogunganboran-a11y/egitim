# Eğitim Portalı Veritabanı Kurulum Kılavuzu

## 📋 Genel Bilgiler

Bu veritabanı yapısı **her türlü hosting ve sunucuda** çalışacak şekilde optimize edilmiştir.

### ✅ Uyumluluk
- MySQL 5.7+
- MariaDB 10.2+
- cPanel/Plesk hosting
- VPS/Dedicated sunucular
- Nginx/Apache sunucular
- Shared hosting

### 🎯 Özellikler
- ✅ Hafif ve hızlı
- ✅ UTF8MB4 charset (emoji desteği)
- ✅ InnoDB engine (transaction desteği)
- ✅ Optimized indexes
- ✅ Foreign key relationships
- ✅ 17 ana tablo
- ✅ Örnek veriler dahil

---

## 📊 Veritabanı Tabloları

### 1. **admins** - Yönetici Hesapları
- Admin panel giriş ve yetkilendirme
- Roller: super_admin, admin, moderator

### 2. **users** - Müşteri Hesapları
- TC kimlik numarası ile kayıt
- Firma ilişkisi
- Portal giriş sistemi

### 3. **companies** - Firmalar
- Kurumsal müşteriler
- Toplu eğitim yönetimi

### 4. **courses** - Eğitimler
- Video eğitimler
- Süre ve fiyat bilgisi

### 5. **enrollments** - Eğitime Kayıtlar
- Kullanıcı-eğitim ilişkisi
- Video izleme takibi
- Test sonuçları

### 6. **certificates** - Sertifikalar
- Otomatik sertifika numarası
- Geçerlilik tarihi
- PDF dosya yolu

### 7. **invoices** - Faturalar
- Bireysel ve kurumsal
- Ödeme durumu takibi

### 8. **support_tickets** - Destek Talepleri
- Kategori bazlı
- Durum takibi
- Admin atama

### 9. **support_messages** - Destek Mesajları
- Ticket'a bağlı mesajlar
- Kullanıcı/Admin mesajları

### 10. **callbacks** - Geri Arama Listesi
- Planlı aramalar
- AI skor sistemi
- Otomatik deneme sayacı

### 11. **callback_rules** - Geri Arama Kuralları
- Koşul-aksiyon mantığı
- Otomatik planlama

### 12. **meta_messages** - WhatsApp Sohbetleri
- Chat listeleme
- Son mesaj bilgisi
- Online durum

### 13. **meta_message_content** - WhatsApp Mesaj İçerikleri
- Mesaj detayları
- Medya desteği

### 14. **notifications** - Bildirimler
- Kullanıcı bildirimleri
- Okunma durumu

### 15. **ip_tracking** - IP Takip
- Ziyaretçi analizi
- Coğrafi konum
- Cihaz tespiti

### 16. **settings** - Sistem Ayarları
- Site ayarları
- API anahtarları
- Genel konfigürasyon

### 17. **sms_history** - SMS Geçmişi
- Gönderilen SMS'ler
- Durum takibi

---

## 🚀 Kurulum Adımları

### Yöntem 1: phpMyAdmin ile (ÖNERİLEN)

1. **phpMyAdmin'e giriş yapın**
   - cPanel > phpMyAdmin
   - Plesk > Databases > phpMyAdmin

2. **İçe Aktarma**
   - Üst menüden "Import" (İçe Aktar) sekmesine tıklayın
   - "Choose File" (Dosya Seç) butonuna tıklayın
   - `database.sql` dosyasını seçin
   - "Go" (Git) butonuna tıklayın

3. **Tamamlandı!**
   - Veritabanı `egitim` adıyla oluşturuldu
   - Tüm tablolar hazır
   - Örnek veriler yüklendi

### Yöntem 2: MySQL Komut Satırı ile

```bash
mysql -u kullanici_adi -p < database.sql
```

### Yöntem 3: cPanel File Manager ile

1. File Manager'ı açın
2. `database.sql` dosyasını yükleyin
3. phpMyAdmin > Import ile yükleyin

---

## 🔐 Varsayılan Giriş Bilgileri

### Admin Panel
```
URL: /admin
Kullanıcı Adı: admin
Şifre: admin123
```

### Kullanıcı Portalı
```
URL: /portal
TC Kimlik: 12345678901
Şifre: 123456
```

⚠️ **ÖNEMLİ:** İlk girişten sonra şifreleri mutlaka değiştirin!

---

## 🔧 PHP Bağlantı Örneği

### config.php Dosyası Oluşturun

```php
<?php
// Veritabanı bağlantı bilgileri
define('DB_HOST', 'localhost');     // Hosting'e göre değişebilir
define('DB_USER', 'kullanici_adi'); // Veritabanı kullanıcı adı
define('DB_PASS', 'sifre');         // Veritabanı şifresi
define('DB_NAME', 'egitim');        // Veritabanı adı

// Bağlantı oluştur
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Karakter seti ayarla
$conn->set_charset("utf8mb4");
?>
```

---

## 🌐 Hosting'e Göre Özel Ayarlar

### cPanel Hosting
- Veritabanı adı genelde `kullanici_egitim` şeklindedir
- MySQL Databases bölümünden kullanıcı oluşturun
- Kullanıcıyı veritabanına ekleyin (ALL PRIVILEGES)

### Plesk Hosting
- Databases > Add Database
- Kullanıcı oluşturun
- Import ile SQL yükleyin

### VPS/Dedicated
- SSH ile bağlanın
- MySQL'e root olarak girin
- SQL dosyasını import edin

---

## 📝 Önemli Notlar

### 1. Veritabanı Prefixi
Eğer hosting'iniz veritabanı prefixi gerektiriyorsa:
- SQL dosyasını text editor ile açın
- `egitim` kelimesini `kullanici_egitim` ile değiştirin
- Kaydedin ve yükleyin

### 2. Maksimum Yükleme Boyutu
SQL dosyası ~30KB gibi çok küçük. Sorun yaşamazsınız.

### 3. Charset Sorunu
UTF8MB4 desteklemeyen eski sunucularda:
- SQL dosyasında `utf8mb4` yerine `utf8` yazın
- Emoji desteği olmayacaktır

### 4. Foreign Key Hatası
Bazı shared hosting'lerde foreign key kapalı olabilir:
```sql
SET FOREIGN_KEY_CHECKS=0;
-- SQL sorguları
SET FOREIGN_KEY_CHECKS=1;
```

---

## 🔍 Sorun Giderme

### "Access denied for user" Hatası
✅ Kullanıcı adı ve şifreyi kontrol edin
✅ Kullanıcının veritabanına yetkisi olduğundan emin olun

### "Unknown database" Hatası
✅ Veritabanı oluşturulmamış olabilir
✅ config.php'deki DB_NAME'i kontrol edin

### "Table already exists" Hatası
✅ Eski veritabanını silin veya yedekleyin
✅ Yeni import yapın

### Türkçe Karakter Sorunu
✅ `set_charset("utf8mb4")` komutunu kullanın
✅ HTML'de `<meta charset="UTF-8">` olsun

---

## 📞 Destek

Sorun yaşarsanız:
1. phpMyAdmin hata mesajını kontrol edin
2. PHP error_log dosyasına bakın
3. Hosting sağlayıcınıza danışın

---

## 🎓 Örnek Sorgular

### Tüm Kullanıcıları Listele
```sql
SELECT * FROM users ORDER BY created_at DESC;
```

### Sertifikalı Kullanıcılar
```sql
SELECT u.*, c.certificate_number
FROM users u
JOIN certificates c ON u.id = c.user_id
WHERE c.is_valid = 1;
```

### Bekleyen Destek Talepleri
```sql
SELECT * FROM support_tickets
WHERE status = 'open'
ORDER BY priority DESC, created_at ASC;
```

### Bugünkü Geri Aramalar
```sql
SELECT * FROM callbacks
WHERE DATE(scheduled_date) = CURDATE()
AND status = 'planned'
ORDER BY priority DESC;
```

---

## 📊 Veritabanı Boyutu

- Boş veritabanı: ~100 KB
- 1000 kullanıcı ile: ~2-3 MB
- 10000 kullanıcı ile: ~15-20 MB

**Sonuç:** Çok hafif ve hızlı! 🚀

---

## ✨ Güvenlik Önerileri

1. ✅ `admin` kullanıcısının şifresini değiştirin
2. ✅ `settings` tablosundaki API tokenları güncelleyin
3. ✅ SQL injection'a karşı prepared statements kullanın
4. ✅ Hassas verileri şifreleyin (password hash)
5. ✅ Düzenli yedekleme yapın

---

**Başarılar! 🎉**
