-- ===================================
-- EĞİTİM PORTALI VERİTABANI
-- Versiyon: 1.0
-- Tarih: 2025-11-15
-- Uyumluluk: MySQL 5.7+, MariaDB 10.2+
-- ===================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+03:00";

-- Veritabanı oluştur
CREATE DATABASE IF NOT EXISTS `egitim` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `egitim`;

-- ===================================
-- 1. YÖNETİCİLER (ADMIN) TABLOSU
-- ===================================
CREATE TABLE `admins` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `full_name` VARCHAR(100) DEFAULT NULL,
  `role` ENUM('super_admin','admin','moderator') DEFAULT 'admin',
  `is_active` TINYINT(1) DEFAULT 1,
  `last_login` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan admin kullanıcısı (Şifre: admin123)
INSERT INTO `admins` (`username`, `password`, `email`, `full_name`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@egitim.com', 'Sistem Yöneticisi', 'super_admin');

-- ===================================
-- 2. FİRMALAR TABLOSU
-- ===================================
CREATE TABLE `companies` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `tax_number` VARCHAR(20) DEFAULT NULL,
  `tax_office` VARCHAR(100) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `contact_person` VARCHAR(100) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek firmalar
INSERT INTO `companies` (`name`, `phone`, `email`) VALUES
('XYZ Maritime', '0212 555 0001', 'info@xyzmaritime.com'),
('Deniz Yıldızı A.Ş.', '0212 555 0002', 'info@denizyildizi.com'),
('Mavi Dalga Ltd.', '0212 555 0003', 'info@mavidalga.com'),
('Kıyı Shipping', '0212 555 0004', 'info@kiyishipping.com');

-- ===================================
-- 3. KULLANICILAR (MÜŞTERİLER) TABLOSU
-- ===================================
CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tckn` VARCHAR(11) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `surname` VARCHAR(50) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `birth_date` DATE DEFAULT NULL,
  `password` VARCHAR(255) DEFAULT NULL,
  `company_id` INT(11) UNSIGNED DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tckn` (`tckn`),
  KEY `phone` (`phone`),
  KEY `company_id` (`company_id`),
  KEY `is_active` (`is_active`),
  CONSTRAINT `fk_users_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek kullanıcılar (Şifre: 123456)
INSERT INTO `users` (`tckn`, `name`, `surname`, `phone`, `birth_date`, `password`) VALUES
('12345678901', 'Ahmet', 'Yılmaz', '0532 123 4567', '1990-03-15', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('98765432109', 'Ayşe', 'Kaya', '0533 234 5678', '1985-07-22', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ===================================
-- 4. EĞİTİMLER TABLOSU
-- ===================================
CREATE TABLE `courses` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `duration_minutes` INT(11) DEFAULT 0,
  `video_url` VARCHAR(255) DEFAULT NULL,
  `thumbnail` VARCHAR(255) DEFAULT NULL,
  `price` DECIMAL(10,2) DEFAULT 0.00,
  `is_active` TINYINT(1) DEFAULT 1,
  `order_index` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  KEY `order_index` (`order_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek eğitimler
INSERT INTO `courses` (`title`, `description`, `duration_minutes`, `price`) VALUES
('Temel Denizcilik', 'Denizcilik temelleri ve güvenlik kuralları', 45, 500.00),
('İleri Navigasyon', 'İleri seviye navigasyon teknikleri', 60, 750.00),
('Güvenlik Eğitimi', 'Denizde güvenlik ve acil durum prosedürleri', 50, 600.00);

-- ===================================
-- 5. KAYITLAR (ENROLLMENTS) TABLOSU
-- ===================================
CREATE TABLE `enrollments` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `course_id` INT(11) UNSIGNED NOT NULL,
  `status` ENUM('enrolled','in_progress','completed','failed','cancelled') DEFAULT 'enrolled',
  `video_watched` TINYINT(1) DEFAULT 0,
  `video_watch_percentage` DECIMAL(5,2) DEFAULT 0.00,
  `video_max_watched_time` INT(11) DEFAULT 0,
  `test_completed` TINYINT(1) DEFAULT 0,
  `test_score` DECIMAL(5,2) DEFAULT NULL,
  `test_passed` TINYINT(1) DEFAULT 0,
  `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `completed_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_course` (`user_id`,`course_id`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_enrollments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_enrollments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 6. SERTİFİKALAR TABLOSU
-- ===================================
CREATE TABLE `certificates` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `course_id` INT(11) UNSIGNED NOT NULL,
  `enrollment_id` INT(11) UNSIGNED DEFAULT NULL,
  `certificate_number` VARCHAR(50) NOT NULL,
  `file_path` VARCHAR(255) DEFAULT NULL,
  `issue_date` DATE NOT NULL,
  `expiry_date` DATE DEFAULT NULL,
  `is_valid` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificate_number` (`certificate_number`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  KEY `enrollment_id` (`enrollment_id`),
  CONSTRAINT `fk_certificates_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_certificates_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_certificates_enrollment` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 7. FATURALAR TABLOSU
-- ===================================
CREATE TABLE `invoices` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `company_id` INT(11) UNSIGNED DEFAULT NULL,
  `invoice_number` VARCHAR(50) NOT NULL,
  `invoice_type` ENUM('individual','corporate') DEFAULT 'individual',
  `amount` DECIMAL(10,2) NOT NULL,
  `tax_amount` DECIMAL(10,2) DEFAULT 0.00,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','paid','cancelled','refunded') DEFAULT 'pending',
  `payment_method` ENUM('credit_card','bank_transfer','cash','other') DEFAULT NULL,
  `payment_date` DATETIME DEFAULT NULL,
  `due_date` DATE DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_invoices_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_invoices_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 8. DESTEK TALEPLERİ TABLOSU
-- ===================================
CREATE TABLE `support_tickets` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `ticket_number` VARCHAR(50) NOT NULL,
  `category` ENUM('toplu','havale','iptal','teknik','iletisim') NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('open','in_progress','resolved','closed') DEFAULT 'open',
  `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
  `assigned_to` INT(11) UNSIGNED DEFAULT NULL,
  `resolved_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_number` (`ticket_number`),
  KEY `user_id` (`user_id`),
  KEY `category` (`category`),
  KEY `status` (`status`),
  KEY `assigned_to` (`assigned_to`),
  CONSTRAINT `fk_support_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_support_admin` FOREIGN KEY (`assigned_to`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 9. DESTEK MESAJLARI TABLOSU
-- ===================================
CREATE TABLE `support_messages` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` INT(11) UNSIGNED NOT NULL,
  `sender_type` ENUM('user','admin') NOT NULL,
  `sender_id` INT(11) UNSIGNED NOT NULL,
  `message` TEXT NOT NULL,
  `is_internal` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `fk_messages_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 10. GERİ ARAMA LİSTESİ TABLOSU
-- ===================================
CREATE TABLE `callbacks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `source` ENUM('whatsapp','phone','web','other') DEFAULT 'phone',
  `status` ENUM('planned','called','positive','negative','no_answer','cancelled') DEFAULT 'planned',
  `purchase_status` ENUM('bought','not_bought','pending') DEFAULT 'pending',
  `rule_name` VARCHAR(100) DEFAULT NULL,
  `priority` ENUM('low','medium','high') DEFAULT 'medium',
  `ai_score` DECIMAL(5,2) DEFAULT NULL,
  `attempt_count` INT(11) DEFAULT 0,
  `max_attempts` INT(11) DEFAULT 3,
  `scheduled_date` DATETIME NOT NULL,
  `called_at` DATETIME DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `scheduled_date` (`scheduled_date`),
  KEY `priority` (`priority`),
  CONSTRAINT `fk_callbacks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 11. GERİ ARAMA KURALLARI TABLOSU
-- ===================================
CREATE TABLE `callback_rules` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `condition_text` VARCHAR(255) DEFAULT NULL,
  `action_text` VARCHAR(255) DEFAULT NULL,
  `retry_days` INT(11) DEFAULT 3,
  `max_attempts` INT(11) DEFAULT 3,
  `priority` ENUM('low','medium','high') DEFAULT 'medium',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek geri arama kuralları
INSERT INTO `callback_rules` (`name`, `condition_text`, `action_text`, `retry_days`, `max_attempts`) VALUES
('WhatsApp Satın Almadı', 'WhatsApp üzerinden iletişim kuruldu ama satın almadı', '2 gün sonra ara', 2, 3),
('Telefon Cevap Vermedi', 'Telefon açmadı veya meşgul', '1 gün sonra ara', 1, 5),
('Kararsız Kaldı', 'Kararsız, daha fazla bilgi istedi', '3 gün sonra ara', 3, 4);

-- ===================================
-- 12. META MESAJLARI (WhatsApp) TABLOSU
-- ===================================
CREATE TABLE `meta_messages` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `chat_id` VARCHAR(100) NOT NULL,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `last_message` TEXT DEFAULT NULL,
  `last_message_time` DATETIME DEFAULT NULL,
  `unread_count` INT(11) DEFAULT 0,
  `is_online` TINYINT(1) DEFAULT 0,
  `is_archived` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chat_id` (`chat_id`),
  KEY `user_id` (`user_id`),
  KEY `is_archived` (`is_archived`),
  CONSTRAINT `fk_meta_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 13. META MESAJ İÇERİKLERİ TABLOSU
-- ===================================
CREATE TABLE `meta_message_content` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `chat_id` VARCHAR(100) NOT NULL,
  `message_type` ENUM('text','image','video','audio','document','location','contact') DEFAULT 'text',
  `message_content` TEXT DEFAULT NULL,
  `media_url` VARCHAR(255) DEFAULT NULL,
  `sender_type` ENUM('user','admin','system') NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 14. BİLDİRİMLER TABLOSU
-- ===================================
CREATE TABLE `notifications` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('success','info','warning','danger') DEFAULT 'info',
  `icon` VARCHAR(50) DEFAULT NULL,
  `link` VARCHAR(255) DEFAULT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `read_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_read` (`is_read`),
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 15. IP TAKİP TABLOSU
-- ===================================
CREATE TABLE `ip_tracking` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `page_url` VARCHAR(255) DEFAULT NULL,
  `referrer` VARCHAR(255) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `device_type` ENUM('desktop','mobile','tablet','other') DEFAULT NULL,
  `browser` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip_address` (`ip_address`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_ip_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 16. AYARLAR TABLOSU
-- ===================================
CREATE TABLE `settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  `setting_type` ENUM('text','number','boolean','json') DEFAULT 'text',
  `description` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan ayarlar
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_type`, `description`) VALUES
('site_name', 'Eğitim Portalı', 'text', 'Site adı'),
('site_email', 'info@egitim.com', 'text', 'Site e-posta adresi'),
('site_phone', '0212 555 0000', 'text', 'Site telefon numarası'),
('video_max_skip', '10', 'number', 'Video maksimum ileri sarma süresi (saniye)'),
('test_pass_score', '70', 'number', 'Test geçme puanı (%)'),
('certificate_validity_days', '365', 'number', 'Sertifika geçerlilik süresi (gün)'),
('maintenance_mode', '0', 'boolean', 'Bakım modu'),
('whatsapp_api_token', '', 'text', 'WhatsApp API token'),
('meta_webhook_url', '', 'text', 'Meta webhook URL');

-- ===================================
-- 17. SMS GÖNDERİM GEÇMİŞİ TABLOSU
-- ===================================
CREATE TABLE `sms_history` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('pending','sent','failed','delivered') DEFAULT 'pending',
  `provider` VARCHAR(50) DEFAULT NULL,
  `sent_at` DATETIME DEFAULT NULL,
  `delivered_at` DATETIME DEFAULT NULL,
  `error_message` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `phone` (`phone`),
  KEY `status` (`status`),
  CONSTRAINT `fk_sms_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- VERİTABANI OLUŞTURMA TAMAMLANDI
-- ===================================

-- Performans için index'ler oluşturuldu
-- Foreign key ilişkileri tanımlandı
-- UTF8MB4 charset (emoji desteği)
-- InnoDB engine (transaction desteği)
-- Timestamp ve datetime alanları timezone ayarlı
-- Enum değerleri optimizasyon için kullanıldı

-- Kullanım:
-- 1. phpMyAdmin'e giriş yapın
-- 2. "İçe Aktar" (Import) bölümüne gidin
-- 3. Bu dosyayı seçin ve yükleyin
-- 4. Veritabanı otomatik oluşturulacaktır

-- Varsayılan giriş bilgileri:
-- Admin: username=admin, password=admin123
-- Kullanıcı: tckn=12345678901, password=123456
