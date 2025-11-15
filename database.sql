-- ===================================
-- EĞİTİM PORTALI VERİTABANI
-- Versiyon: 5.0 - Final & Complete
-- Tarih: 2025-11-15
-- Uyumluluk: MySQL 5.7+, MariaDB 10.2+
-- ===================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+03:00";

-- ===================================
-- 1. YÖNETİCİLER (ADMIN) TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `full_name` VARCHAR(100) DEFAULT NULL,
  `role` ENUM('super_admin','admin','moderator') DEFAULT 'admin',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `admins` (`password`, `email`, `full_name`, `role`) VALUES
('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@egitim.com', 'Sistem Yöneticisi', 'super_admin');

-- ===================================
-- 2. ADMİN YETKİLERİ TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `admin_permissions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` INT(11) UNSIGNED NOT NULL,
  `permission` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_permission` (`admin_id`, `permission`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `fk_permissions_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 3. KULLANICILAR (MÜŞTERİLER) TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tckn` VARCHAR(11) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `surname` VARCHAR(50) NOT NULL,
  `phone` VARCHAR(20) NOT NULL COMMENT 'Sadece rakam: 05321234567',
  `birth_date` DATE DEFAULT NULL,
  `password` VARCHAR(255) DEFAULT NULL,
  `company` VARCHAR(200) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tckn` (`tckn`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `users` (`tckn`, `name`, `surname`, `phone`, `birth_date`, `password`) VALUES
('12345678901', 'Ahmet', 'Yılmaz', '05321234567', '1990-03-15', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('98765432109', 'Ayşe', 'Kaya', '05332345678', '1985-07-22', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ===================================
-- 4. EĞİTİMLER TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `courses` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `short_title` VARCHAR(100) DEFAULT NULL,
  `duration_hours` DECIMAL(5,2) DEFAULT 0.00,
  `video_url` VARCHAR(255) DEFAULT NULL,
  `price` DECIMAL(10,2) DEFAULT 0.00,
  `discounted_price` DECIMAL(10,2) DEFAULT NULL,
  `certificate_days` INT(11) DEFAULT 0,
  `certificate_time` TIME DEFAULT NULL,
  `order_index` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_index` (`order_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `courses` (`title`, `short_title`, `duration_hours`, `price`, `discounted_price`, `certificate_days`, `certificate_time`) VALUES
('Temel Denizcilik', 'Temel', 8.00, 750.00, 500.00, 7, '18:30:00'),
('İleri Navigasyon', 'İleri Nav', 12.00, 1000.00, 750.00, 14, '14:00:00'),
('Güvenlik Eğitimi', 'Güvenlik', 10.00, 900.00, 600.00, 10, '16:45:00');

-- ===================================
-- 5. TEST SORULARI TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `test_questions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT(11) UNSIGNED NOT NULL,
  `question_text` TEXT NOT NULL,
  `question_order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `question_order` (`question_order`),
  CONSTRAINT `fk_questions_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 6. TEST ŞIKLARI TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `test_options` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` INT(11) UNSIGNED NOT NULL,
  `option_text` VARCHAR(500) NOT NULL,
  `option_order` INT(11) DEFAULT 0,
  `is_correct` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `option_order` (`option_order`),
  CONSTRAINT `fk_options_question` FOREIGN KEY (`question_id`) REFERENCES `test_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 7. KAYITLAR VE ÖDEMELER (ENROLLMENTS) TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `course_id` INT(11) UNSIGNED NOT NULL,
  `company` VARCHAR(200) DEFAULT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `payment_method` ENUM('online','havale','firma') DEFAULT NULL,
  `payment_token` VARCHAR(255) DEFAULT NULL,
  `video_watched` TINYINT(1) DEFAULT 0,
  `test_completed` TINYINT(1) DEFAULT 0,
  `registration_document` VARCHAR(255) DEFAULT NULL,
  `invoice` VARCHAR(255) DEFAULT NULL,
  `certificate` VARCHAR(255) DEFAULT NULL,
  `certificate_issue_date` DATE DEFAULT NULL,
  `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `completed_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_course` (`user_id`,`course_id`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `fk_enrollments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_enrollments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 8. DESTEK TALEPLERİ TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `support_tickets` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `surname` VARCHAR(100) NOT NULL,
  `tckn` VARCHAR(11) DEFAULT NULL,
  `phone` VARCHAR(20) NOT NULL COMMENT 'Sadece rakam: 05321234567',
  `birth_date` DATE DEFAULT NULL,
  `category` ENUM('bulk','transfer','cancel','technical','contact','other') DEFAULT 'other',
  `company_name` VARCHAR(200) DEFAULT NULL,
  `document_count` INT(11) DEFAULT NULL,
  `courses` TEXT DEFAULT NULL,
  `source` VARCHAR(100) DEFAULT NULL,
  `receipt` VARCHAR(255) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `status` ENUM('open','in_progress','resolved','closed','cancelled') DEFAULT 'open',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `category` (`category`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 9. DESTEK NOTLARI TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `support_notes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` INT(11) UNSIGNED NOT NULL,
  `admin_id` INT(11) UNSIGNED DEFAULT NULL,
  `note` TEXT NOT NULL,
  `status_change` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `fk_notes_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notes_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 10. GERİ ARAMA LİSTESİ TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `callback_requests` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) DEFAULT NULL,
  `phone` VARCHAR(20) NOT NULL COMMENT 'Sadece rakam: 05321234567',
  `reason` VARCHAR(255) DEFAULT NULL,
  `source` ENUM('web','phone','whatsapp','meta','other') DEFAULT 'web',
  `status` ENUM('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `priority` ENUM('low','medium','high') DEFAULT 'medium',
  `ai_analyzed` TINYINT(1) DEFAULT 0,
  `ai_score` DECIMAL(5,2) DEFAULT NULL,
  `attempt_count` INT(11) DEFAULT 0,
  `max_attempts` INT(11) DEFAULT 3,
  `scheduled_date` DATETIME NOT NULL,
  `called_at` DATETIME DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `scheduled_date` (`scheduled_date`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 11. GERİ ARAMA KURALLARI TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `callback_rules` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `action_time` INT(11) DEFAULT 3,
  `retry_days` INT(11) DEFAULT 3,
  `max_attempts` INT(11) DEFAULT 3,
  `call_start` TIME DEFAULT NULL,
  `call_end` TIME DEFAULT NULL,
  `prompt` TEXT DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `callback_rules` (`name`, `action_time`, `retry_days`, `max_attempts`, `call_start`, `call_end`) VALUES
('WhatsApp Satın Almadı', 2, 2, 3, '09:00:00', '18:00:00'),
('Telefon Cevap Vermedi', 1, 1, 5, '10:00:00', '20:00:00'),
('Kararsız Kaldı', 3, 3, 4, '09:00:00', '17:00:00');

-- ===================================
-- 12. TELEFON ARAMALARI TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `phone_calls` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` VARCHAR(20) NOT NULL COMMENT 'Sadece rakam: 05321234567',
  `type` ENUM('incoming','outgoing','missed') NOT NULL,
  `call_start` DATETIME NOT NULL,
  `call_end` DATETIME DEFAULT NULL,
  `recording` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`),
  KEY `type` (`type`),
  KEY `call_start` (`call_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 13. TELEFON GÖRÜŞMESİ METİNLERİ TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `phone_transcripts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `call_id` INT(11) UNSIGNED NOT NULL,
  `speaker` ENUM('user','agent') NOT NULL,
  `text` TEXT NOT NULL,
  `timestamp` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `call_id` (`call_id`),
  KEY `speaker` (`speaker`),
  CONSTRAINT `fk_transcript_call` FOREIGN KEY (`call_id`) REFERENCES `phone_calls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 14. WHATSAPP SOHBETLER TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `whatsapp_chat` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `chat_id` VARCHAR(100) NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `last_message_at` DATETIME DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bot_active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chat_id` (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 15. WHATSAPP MESAJLAR TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `whatsapp_messages` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `chat_id` VARCHAR(100) NOT NULL,
  `direction` ENUM('incoming','outgoing') NOT NULL,
  `body` TEXT DEFAULT NULL,
  `message_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),
  KEY `direction` (`direction`),
  KEY `message_at` (`message_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 16. META SOHBETLER TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `meta_chat` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform` ENUM('facebook','instagram') NOT NULL,
  `chat_id` VARCHAR(100) NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `last_message_at` DATETIME DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bot_active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform_chat` (`platform`, `chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 17. META MESAJLAR TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `meta_messages` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform` ENUM('facebook','instagram') NOT NULL,
  `chat_id` VARCHAR(100) NOT NULL,
  `direction` ENUM('incoming','outgoing') NOT NULL,
  `body` TEXT DEFAULT NULL,
  `message_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `platform` (`platform`),
  KEY `chat_id` (`chat_id`),
  KEY `direction` (`direction`),
  KEY `message_at` (`message_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 18. IP TAKİP TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `ip_tracking` (
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
-- 19. IP BLACKLIST (ENGELLİ IP'LER) TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `ip_blacklist` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `reason` VARCHAR(255) DEFAULT NULL,
  `blocked_by` INT(11) UNSIGNED DEFAULT NULL,
  `is_blocked` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_address` (`ip_address`),
  KEY `is_blocked` (`is_blocked`),
  KEY `blocked_by` (`blocked_by`),
  CONSTRAINT `fk_blacklist_admin` FOREIGN KEY (`blocked_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 20. IP WHITELIST (İZİN VERİLEN IP'LER) TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `ip_whitelist` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `added_by` INT(11) UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_address` (`ip_address`),
  KEY `added_by` (`added_by`),
  CONSTRAINT `fk_whitelist_admin` FOREIGN KEY (`added_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 21. AYARLAR TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  `setting_type` ENUM('text','number','boolean','json','password') DEFAULT 'text',
  `category` VARCHAR(50) DEFAULT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`, `setting_type`, `category`, `description`) VALUES
-- GENEL AYARLAR
('site_name', 'Eğitim Portalı', 'text', 'general', 'Site adı'),
('site_title', 'Panel Sistemi', 'text', 'general', 'Meta başlık'),
('site_description', 'Panel sistemi açıklaması', 'text', 'general', 'Meta açıklama'),
('site_url', 'https://example.com', 'text', 'general', 'Site URL'),
('site_email', 'info@egitim.com', 'text', 'general', 'İletişim e-posta'),
('site_phone', '0212 555 0000', 'text', 'general', 'İletişim telefon'),
('whatsapp_phone', '', 'text', 'general', 'WhatsApp numarası'),
('instagram_username', '', 'text', 'general', 'Instagram kullanıcı adı'),
('facebook_username', '', 'text', 'general', 'Facebook kullanıcı adı'),
('site_logo_url', '', 'text', 'general', 'Site logosu URL'),
('site_favicon_url', '', 'text', 'general', 'Favicon URL'),

-- SEO & ANALİZ
('google_analytics_code', '', 'text', 'seo', 'Google Analytics kodu'),
('google_tag_manager_code', '', 'text', 'seo', 'Google Tag Manager kodu'),
('yandex_metrika_code', '', 'text', 'seo', 'Yandex Metrika kodu'),
('bing_webmaster_code', '', 'text', 'seo', 'Bing Webmaster kodu'),

-- API - PARAŞÜT FATURA
('parasut_account_id', '', 'text', 'api_parasut', 'Paraşüt Account ID'),
('parasut_company_id', '', 'text', 'api_parasut', 'Paraşüt Company ID'),
('parasut_kdv', '18', 'number', 'api_parasut', 'KDV Oranı'),
('parasut_username', '', 'text', 'api_parasut', 'Paraşüt kullanıcı adı'),
('parasut_password', '', 'password', 'api_parasut', 'Paraşüt şifre'),
('parasut_client_id', '', 'text', 'api_parasut', 'Paraşüt Client ID'),
('parasut_client_secret', '', 'password', 'api_parasut', 'Paraşüt Client Secret'),

-- API - NETGSM SMS
('netgsm_username', '', 'text', 'api_sms', 'NetGSM kullanıcı adı'),
('netgsm_password', '', 'password', 'api_sms', 'NetGSM şifre'),
('netgsm_header', '', 'text', 'api_sms', 'SMS başlık'),
('sms_welcome_active', '1', 'boolean', 'api_sms', 'Hoşgeldin SMS aktif'),
('sms_welcome_template', 'Hoş geldiniz {name}!', 'text', 'api_sms', 'Hoşgeldin SMS şablonu'),
('sms_certificate_active', '1', 'boolean', 'api_sms', 'Sertifika SMS aktif'),
('sms_certificate_template', 'Tebrikler {name}! Sertifikanız hazır.', 'text', 'api_sms', 'Sertifika SMS şablonu'),

-- API - PAYTR ÖDEME
('paytr_merchant_id', '', 'text', 'api_paytr', 'PayTR Merchant ID'),
('paytr_merchant_key', '', 'password', 'api_paytr', 'PayTR Merchant Key'),
('paytr_merchant_salt', '', 'password', 'api_paytr', 'PayTR Merchant Salt'),
('paytr_notification_url', '', 'text', 'api_paytr', 'PayTR bildirim URL'),

-- API - META (Facebook/Instagram)
('meta_app_id', '', 'text', 'api_meta', 'Facebook App ID'),
('meta_app_secret', '', 'password', 'api_meta', 'Facebook App Secret'),
('meta_access_token', '', 'password', 'api_meta', 'Meta Access Token'),
('meta_page_id', '', 'text', 'api_meta', 'Facebook Page ID'),
('meta_ig_account_id', '', 'text', 'api_meta', 'Instagram Business Account ID'),
('meta_webhook_token', '', 'text', 'api_meta', 'Webhook Verify Token'),

-- TELEFON - SIP TRUNK
('sip_server', '', 'text', 'api_phone', 'SIP sunucu'),
('sip_port', '5060', 'number', 'api_phone', 'SIP port'),
('sip_username', '', 'text', 'api_phone', 'SIP kullanıcı adı'),
('sip_password', '', 'password', 'api_phone', 'SIP şifre'),
('sip_netmask', '255.255.255.0', 'text', 'api_phone', 'SIP netmask'),
('sip_outbound_protocol', 'udp', 'text', 'api_phone', 'Outbound protocol'),
('allow_inbound_calls', '1', 'boolean', 'api_phone', 'Gelen aramalara izin'),
('allow_outbound_calls', '1', 'boolean', 'api_phone', 'Giden aramalara izin'),
('enable_options_ping', '1', 'boolean', 'api_phone', 'Options ping aktif'),

-- AI - OPENAI (Telefon)
('phone_openai_api_key', '', 'password', 'ai_phone', 'OpenAI API Key (Telefon)'),
('phone_openai_model', 'gpt-4o', 'text', 'ai_phone', 'OpenAI Model'),

-- AI - AZURE SPEECH
('azure_speech_key', '', 'password', 'ai_phone', 'Azure Speech Key'),
('azure_speech_region', 'westeurope', 'text', 'ai_phone', 'Azure Speech Region'),
('azure_stt_language', 'tr-TR', 'text', 'ai_phone', 'STT dil kodu'),
('azure_tts_voice', 'tr-TR-AhmetNeural', 'text', 'ai_phone', 'TTS ses adı'),

-- AI - VOICE AGENT AYARLARI
('voice_speech_rate', 'medium', 'text', 'ai_phone', 'Ses hızı'),
('voice_pitch', 'medium', 'text', 'ai_phone', 'Ses tonu'),
('response_delay', '500', 'number', 'ai_phone', 'Yanıt gecikmesi (ms)'),
('silence_timeout', '2000', 'number', 'ai_phone', 'Sessizlik süresi (ms)'),
('max_speech_duration', '60', 'number', 'ai_phone', 'Maks konuşma süresi (sn)'),
('voice_volume', '80', 'number', 'ai_phone', 'Ses seviyesi (0-100)'),
('enable_background_noise', '1', 'boolean', 'ai_phone', 'Arka plan gürültüsü filtresi'),
('enable_echo_cancellation', '1', 'boolean', 'ai_phone', 'Eko iptali'),

-- WHATSAPP CLOUD API
('whatsapp_phone_number_id', '', 'text', 'api_whatsapp', 'WhatsApp Phone Number ID'),
('whatsapp_access_token', '', 'password', 'api_whatsapp', 'WhatsApp Access Token'),
('whatsapp_business_id', '', 'text', 'api_whatsapp', 'Business Account ID'),
('whatsapp_client_id', '', 'text', 'api_whatsapp', 'WhatsApp Client ID'),
('whatsapp_client_secret', '', 'password', 'api_whatsapp', 'WhatsApp Client Secret'),
('whatsapp_webhook_url', '', 'text', 'api_whatsapp', 'Webhook URL'),
('whatsapp_verify_token', '', 'text', 'api_whatsapp', 'Verify Token'),

-- AI - OPENAI (WhatsApp)
('whatsapp_openai_key', '', 'password', 'ai_whatsapp', 'OpenAI API Key (WhatsApp)'),
('whatsapp_openai_model', 'gpt-4o', 'text', 'ai_whatsapp', 'OpenAI Model'),
('whatsapp_max_tokens', '500', 'number', 'ai_whatsapp', 'Maksimum token'),
('whatsapp_temperature', '0.7', 'number', 'ai_whatsapp', 'Temperature');

-- ===================================
-- 22. AI TALİMATLARI TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `ai_instructions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform` ENUM('phone','whatsapp') NOT NULL,
  `system_prompt` TEXT DEFAULT NULL,
  `greeting_message` TEXT DEFAULT NULL,
  `farewell_message` TEXT DEFAULT NULL,
  `fallback_message` TEXT DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `platform` (`platform`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `ai_instructions` (`platform`, `system_prompt`, `greeting_message`, `farewell_message`, `fallback_message`) VALUES
('phone', 'Sen bir müşteri hizmetleri asistanısın. Nazik, yardımsever ve profesyonel ol.', 'Merhaba, size nasıl yardımcı olabilirim?', 'Görüşmek üzere, iyi günler!', 'Üzgünüm, sizi anlayamadım. Lütfen tekrar söyler misiniz?'),
('whatsapp', 'Sen bir WhatsApp müşteri destek botusun. Kısa, net ve yardımsever yanıtlar ver.', 'Merhaba! 👋 Size nasıl yardımcı olabilirim?', 'İyi günler! 😊', 'Anlayamadım 🤔 Daha açık yazabilir misiniz?');

-- ===================================
-- 23. SMS GÖNDERİM GEÇMİŞİ TABLOSU
-- ===================================
CREATE TABLE IF NOT EXISTS `sms_history` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `company` VARCHAR(200) DEFAULT NULL,
  `phone` VARCHAR(20) NOT NULL COMMENT 'Sadece rakam: 05321234567',
  `message` TEXT NOT NULL,
  `status` ENUM('pending','sent','failed','delivered') DEFAULT 'pending',
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


