-- =====================================================
-- Bhagwan Vishwakarma Mandir — Database Schema
-- Database: bvm_temple
-- Engine: InnoDB | Charset: utf8mb4
-- =====================================================

CREATE DATABASE IF NOT EXISTS `bvm_temple` 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `bvm_temple`;

-- =====================================================
-- 1. ADMINS TABLE
-- =====================================================
CREATE TABLE `admins` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('super_admin', 'admin', 'editor') NOT NULL DEFAULT 'admin',
    `avatar` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login` DATETIME DEFAULT NULL,
    `login_attempts` INT UNSIGNED NOT NULL DEFAULT 0,
    `locked_until` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_admins_email` (`email`),
    INDEX `idx_admins_active` (`is_active`)
) ENGINE=InnoDB;

-- =====================================================
-- 2. USERS TABLE (Devotees / Visitors)
-- =====================================================
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT NULL,
    `state` VARCHAR(100) DEFAULT NULL,
    `pincode` VARCHAR(10) DEFAULT NULL,
    `gotra` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_phone` (`phone`)
) ENGINE=InnoDB;

-- =====================================================
-- 3. PUJAS TABLE
-- =====================================================
CREATE TABLE `pujas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name_en` VARCHAR(200) NOT NULL,
    `name_hi` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `description_en` TEXT DEFAULT NULL,
    `description_hi` TEXT DEFAULT NULL,
    `short_desc_en` VARCHAR(500) DEFAULT NULL,
    `short_desc_hi` VARCHAR(500) DEFAULT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `duration` VARCHAR(50) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `icon` VARCHAR(50) DEFAULT 'fa-om',
    `category` ENUM('daily', 'special', 'festival', 'personal') NOT NULL DEFAULT 'daily',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_pujas_active` (`is_active`),
    INDEX `idx_pujas_category` (`category`),
    INDEX `idx_pujas_sort` (`sort_order`)
) ENGINE=InnoDB;

-- =====================================================
-- 4. BOOKINGS TABLE
-- =====================================================
CREATE TABLE `bookings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_number` VARCHAR(30) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `puja_id` INT UNSIGNED NOT NULL,
    `devotee_name` VARCHAR(100) NOT NULL,
    `devotee_email` VARCHAR(100) DEFAULT NULL,
    `devotee_phone` VARCHAR(20) NOT NULL,
    `gotra` VARCHAR(100) DEFAULT NULL,
    `puja_date` DATE NOT NULL,
    `puja_time` TIME DEFAULT NULL,
    `num_persons` INT UNSIGNED NOT NULL DEFAULT 1,
    `special_requests` TEXT DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'completed', 'cancelled', 'rejected') NOT NULL DEFAULT 'pending',
    `admin_notes` TEXT DEFAULT NULL,
    `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `payment_status` ENUM('unpaid', 'paid', 'refunded') NOT NULL DEFAULT 'unpaid',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`puja_id`) REFERENCES `pujas`(`id`) ON DELETE CASCADE,
    INDEX `idx_bookings_status` (`status`),
    INDEX `idx_bookings_date` (`puja_date`),
    INDEX `idx_bookings_number` (`booking_number`),
    INDEX `idx_bookings_payment` (`payment_status`)
) ENGINE=InnoDB;

-- =====================================================
-- 5. DONATIONS TABLE
-- =====================================================
CREATE TABLE `donations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `receipt_number` VARCHAR(30) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `donor_name` VARCHAR(100) NOT NULL,
    `donor_email` VARCHAR(100) DEFAULT NULL,
    `donor_phone` VARCHAR(20) DEFAULT NULL,
    `donor_address` TEXT DEFAULT NULL,
    `donor_pan` VARCHAR(20) DEFAULT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `category` ENUM('general', 'temple_construction', 'anna_daan', 'gaushala', 'education', 'festival', 'other') NOT NULL DEFAULT 'general',
    `purpose` VARCHAR(255) DEFAULT NULL,
    `payment_method` ENUM('online', 'cash', 'cheque', 'bank_transfer', 'upi') NOT NULL DEFAULT 'online',
    `transaction_id` VARCHAR(100) DEFAULT NULL,
    `status` ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    `is_anonymous` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_donations_status` (`status`),
    INDEX `idx_donations_category` (`category`),
    INDEX `idx_donations_receipt` (`receipt_number`),
    INDEX `idx_donations_date` (`created_at`)
) ENGINE=InnoDB;

-- =====================================================
-- 6. PAYMENTS TABLE
-- =====================================================
CREATE TABLE `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT UNSIGNED DEFAULT NULL,
    `donation_id` INT UNSIGNED DEFAULT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `currency` VARCHAR(3) NOT NULL DEFAULT 'INR',
    `gateway` ENUM('razorpay', 'stripe', 'manual', 'cash', 'upi') NOT NULL DEFAULT 'razorpay',
    `gateway_order_id` VARCHAR(100) DEFAULT NULL,
    `gateway_payment_id` VARCHAR(100) DEFAULT NULL,
    `gateway_signature` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('created', 'authorized', 'captured', 'failed', 'refunded') NOT NULL DEFAULT 'created',
    `payment_data` JSON DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`donation_id`) REFERENCES `donations`(`id`) ON DELETE SET NULL,
    INDEX `idx_payments_status` (`status`),
    INDEX `idx_payments_gateway_order` (`gateway_order_id`)
) ENGINE=InnoDB;

-- =====================================================
-- 7. EVENTS TABLE
-- =====================================================
CREATE TABLE `events` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title_en` VARCHAR(255) NOT NULL,
    `title_hi` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description_en` TEXT DEFAULT NULL,
    `description_hi` TEXT DEFAULT NULL,
    `short_desc_en` VARCHAR(500) DEFAULT NULL,
    `short_desc_hi` VARCHAR(500) DEFAULT NULL,
    `start_date` DATETIME NOT NULL,
    `end_date` DATETIME DEFAULT NULL,
    `location` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `banner` VARCHAR(255) DEFAULT NULL,
    `category` ENUM('festival', 'puja', 'cultural', 'community', 'special') NOT NULL DEFAULT 'festival',
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_registration_open` TINYINT(1) NOT NULL DEFAULT 0,
    `max_attendees` INT UNSIGNED DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_events_date` (`start_date`),
    INDEX `idx_events_featured` (`is_featured`),
    INDEX `idx_events_active` (`is_active`),
    INDEX `idx_events_category` (`category`)
) ENGINE=InnoDB;

-- =====================================================
-- 8. EVENT REGISTRATIONS TABLE
-- =====================================================
CREATE TABLE `event_registrations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `event_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `num_attendees` INT UNSIGNED NOT NULL DEFAULT 1,
    `notes` TEXT DEFAULT NULL,
    `status` ENUM('registered', 'confirmed', 'cancelled') NOT NULL DEFAULT 'registered',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
    INDEX `idx_eventreg_event` (`event_id`),
    INDEX `idx_eventreg_status` (`status`)
) ENGINE=InnoDB;

-- =====================================================
-- 9. GALLERY ALBUMS TABLE
-- =====================================================
CREATE TABLE `gallery_albums` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title_en` VARCHAR(200) NOT NULL,
    `title_hi` VARCHAR(200) DEFAULT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `cover_image` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_albums_sort` (`sort_order`),
    INDEX `idx_albums_active` (`is_active`)
) ENGINE=InnoDB;

-- =====================================================
-- 10. GALLERY IMAGES TABLE
-- =====================================================
CREATE TABLE `gallery_images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `album_id` INT UNSIGNED NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `thumbnail_path` VARCHAR(255) DEFAULT NULL,
    `title` VARCHAR(200) DEFAULT NULL,
    `caption` TEXT DEFAULT NULL,
    `alt_text` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`album_id`) REFERENCES `gallery_albums`(`id`) ON DELETE CASCADE,
    INDEX `idx_images_album` (`album_id`),
    INDEX `idx_images_sort` (`sort_order`)
) ENGINE=InnoDB;

-- =====================================================
-- 11. TESTIMONIALS TABLE
-- =====================================================
CREATE TABLE `testimonials` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `location` VARCHAR(100) DEFAULT NULL,
    `message_en` TEXT NOT NULL,
    `message_hi` TEXT DEFAULT NULL,
    `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `photo` VARCHAR(255) DEFAULT NULL,
    `is_approved` TINYINT(1) NOT NULL DEFAULT 0,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_testimonials_approved` (`is_approved`),
    INDEX `idx_testimonials_featured` (`is_featured`)
) ENGINE=InnoDB;

-- =====================================================
-- 12. NEWSLETTER SUBSCRIBERS TABLE
-- =====================================================
CREATE TABLE `newsletter_subscribers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `name` VARCHAR(100) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `unsubscribed_at` DATETIME DEFAULT NULL,
    INDEX `idx_newsletter_active` (`is_active`)
) ENGINE=InnoDB;

-- =====================================================
-- 13. ANNOUNCEMENTS TABLE
-- =====================================================
CREATE TABLE `announcements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `message_en` TEXT NOT NULL,
    `message_hi` TEXT DEFAULT NULL,
    `type` ENUM('info', 'warning', 'success', 'festival') NOT NULL DEFAULT 'info',
    `link` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `start_date` DATE DEFAULT NULL,
    `end_date` DATE DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_announcements_active` (`is_active`),
    INDEX `idx_announcements_dates` (`start_date`, `end_date`)
) ENGINE=InnoDB;

-- =====================================================
-- 14. CONTACT MESSAGES TABLE
-- =====================================================
CREATE TABLE `contact_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `admin_reply` TEXT DEFAULT NULL,
    `replied_at` DATETIME DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_contacts_read` (`is_read`),
    INDEX `idx_contacts_date` (`created_at`)
) ENGINE=InnoDB;

-- =====================================================
-- 15. PAGES TABLE (Dynamic CMS Pages)
-- =====================================================
CREATE TABLE `pages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `title_en` VARCHAR(255) NOT NULL,
    `title_hi` VARCHAR(255) DEFAULT NULL,
    `content_en` LONGTEXT DEFAULT NULL,
    `content_hi` LONGTEXT DEFAULT NULL,
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(500) DEFAULT NULL,
    `meta_keywords` VARCHAR(255) DEFAULT NULL,
    `og_image` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_pages_slug` (`slug`),
    INDEX `idx_pages_active` (`is_active`)
) ENGINE=InnoDB;

-- =====================================================
-- 16. SETTINGS TABLE
-- =====================================================
CREATE TABLE `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL,
    `setting_group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `setting_type` ENUM('text', 'textarea', 'number', 'boolean', 'json', 'image') NOT NULL DEFAULT 'text',
    `label` VARCHAR(200) DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_settings_group` (`setting_group`),
    INDEX `idx_settings_key` (`setting_key`)
) ENGINE=InnoDB;

-- =====================================================
-- 17. VOLUNTEERS TABLE
-- =====================================================
CREATE TABLE `volunteers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `age` INT UNSIGNED DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT NULL,
    `skills` TEXT DEFAULT NULL,
    `availability` ENUM('weekdays', 'weekends', 'festivals', 'anytime') NOT NULL DEFAULT 'anytime',
    `experience` TEXT DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'active', 'inactive') NOT NULL DEFAULT 'pending',
    `admin_notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_volunteers_status` (`status`),
    INDEX `idx_volunteers_email` (`email`)
) ENGINE=InnoDB;

-- =====================================================
-- SEED DATA: Default Admin
-- Password: Admin@123 (change immediately after setup)
-- =====================================================
INSERT INTO `admins` (`username`, `email`, `password_hash`, `full_name`, `role`) VALUES
('admin', 'admin@bvm-temple.com', '$2y$12$LJ3m4iU5MiAHz8mO3YMYfOzKFHPHxV2yV5xFXkq1FQHsJH3vqX7dG', 'Temple Administrator', 'super_admin');

-- =====================================================
-- SEED DATA: Site Settings
-- =====================================================
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`, `setting_type`, `label`) VALUES
-- General
('site_name_en', 'Bhagwan Vishwakarma Mandir', 'general', 'text', 'Site Name (English)'),
('site_name_hi', 'भगवान विश्वकर्मा मंदिर', 'general', 'text', 'Site Name (Hindi)'),
('site_tagline_en', 'Divine Architect of the Universe', 'general', 'text', 'Tagline (English)'),
('site_tagline_hi', 'ब्रह्मांड के दिव्य वास्तुकार', 'general', 'text', 'Tagline (Hindi)'),
('site_email', 'info@bvm-temple.com', 'general', 'text', 'Contact Email'),
('site_phone', '+91-XXXXXXXXXX', 'general', 'text', 'Contact Phone'),
('site_whatsapp', '+91-XXXXXXXXXX', 'general', 'text', 'WhatsApp Number'),
('site_address_en', 'Bhagwan Vishwakarma Mandir, Temple Road, City, State - PIN', 'general', 'textarea', 'Address (English)'),
('site_address_hi', 'भगवान विश्वकर्मा मंदिर, मंदिर रोड, शहर, राज्य - पिन', 'general', 'textarea', 'Address (Hindi)'),

-- Timings
('morning_opening', '05:00', 'timings', 'text', 'Morning Opening'),
('morning_closing', '12:00', 'timings', 'text', 'Morning Closing'),
('evening_opening', '16:00', 'timings', 'text', 'Evening Opening'),
('evening_closing', '21:00', 'timings', 'text', 'Evening Closing'),
('morning_aarti', '06:00', 'timings', 'text', 'Morning Aarti'),
('evening_aarti', '19:00', 'timings', 'text', 'Evening Aarti'),
('special_darshan', '11:00 - 12:00', 'timings', 'text', 'Special Darshan'),

-- Social Media
('facebook_url', 'https://facebook.com/', 'social', 'text', 'Facebook URL'),
('instagram_url', 'https://instagram.com/', 'social', 'text', 'Instagram URL'),
('youtube_url', 'https://youtube.com/', 'social', 'text', 'YouTube URL'),
('twitter_url', 'https://twitter.com/', 'social', 'text', 'Twitter URL'),

-- Payment
('razorpay_key_id', '', 'payment', 'text', 'Razorpay Key ID'),
('razorpay_key_secret', '', 'payment', 'text', 'Razorpay Key Secret'),
('razorpay_test_mode', '1', 'payment', 'boolean', 'Razorpay Test Mode'),
('currency', 'INR', 'payment', 'text', 'Currency'),

-- SEO
('meta_title', 'Bhagwan Vishwakarma Mandir - Divine Architect Temple', 'seo', 'text', 'Default Meta Title'),
('meta_description', 'Welcome to Bhagwan Vishwakarma Mandir - A sacred temple dedicated to Lord Vishwakarma, the divine architect of the universe. Book puja, make donations, and plan your visit.', 'seo', 'textarea', 'Default Meta Description'),
('google_analytics_id', '', 'seo', 'text', 'Google Analytics ID'),
('google_maps_embed', '', 'seo', 'textarea', 'Google Maps Embed URL'),
('google_maps_api_key', '', 'seo', 'text', 'Google Maps API Key'),

-- Features
('enable_donations', '1', 'features', 'boolean', 'Enable Donations'),
('enable_bookings', '1', 'features', 'boolean', 'Enable Puja Bookings'),
('enable_newsletter', '1', 'features', 'boolean', 'Enable Newsletter'),
('enable_dark_mode', '0', 'features', 'boolean', 'Enable Dark Mode Toggle'),
('default_language', 'en', 'features', 'text', 'Default Language');

-- =====================================================
-- SEED DATA: Sample Pujas
-- =====================================================
INSERT INTO `pujas` (`name_en`, `name_hi`, `slug`, `description_en`, `description_hi`, `short_desc_en`, `short_desc_hi`, `price`, `duration`, `icon`, `category`, `sort_order`) VALUES
('Vishwakarma Puja', 'विश्वकर्मा पूजा', 'vishwakarma-puja', 'Special puja dedicated to Lord Vishwakarma, the divine architect and creator of the universe. This puja invokes blessings for craftsmen, engineers, and artisans.', 'भगवान विश्वकर्मा को समर्पित विशेष पूजा, जो ब्रह्मांड के दिव्य वास्तुकार और निर्माता हैं। यह पूजा शिल्पकारों, इंजीनियरों और कारीगरों के लिए आशीर्वाद का आह्वान करती है।', 'Sacred puja for Lord Vishwakarma blessings', 'भगवान विश्वकर्मा के आशीर्वाद के लिए पवित्र पूजा', 1100.00, '2 hours', 'fa-om', 'special', 1),
('Ganesh Puja', 'गणेश पूजा', 'ganesh-puja', 'Auspicious puja to Lord Ganesha for removing obstacles and seeking divine blessings for new beginnings.', 'बाधाओं को दूर करने और नई शुरुआत के लिए दिव्य आशीर्वाद प्राप्त करने हेतु भगवान गणेश की शुभ पूजा।', 'Remove obstacles with Ganesh blessings', 'गणेश आशीर्वाद से बाधाएं दूर करें', 501.00, '1 hour', 'fa-om', 'daily', 2),
('Satyanarayan Katha', 'सत्यनारायण कथा', 'satyanarayan-katha', 'Sacred recitation of Lord Satyanarayan Katha for peace, prosperity, and fulfillment of desires.', 'शांति, समृद्धि और मनोकामना पूर्ति के लिए भगवान सत्यनारायण कथा का पवित्र पाठ।', 'Katha for peace and prosperity', 'शांति और समृद्धि के लिए कथा', 2100.00, '3 hours', 'fa-book-open', 'special', 3),
('Rudrabhishek', 'रुद्राभिषेक', 'rudrabhishek', 'Powerful Vedic ritual of offering sacred items to Lord Shiva for health, wealth, and spiritual growth.', 'स्वास्थ्य, धन और आध्यात्मिक विकास के लिए भगवान शिव को पवित्र वस्तुओं का अर्पण करने का शक्तिशाली वैदिक अनुष्ठान।', 'Sacred Shiva abhishek ritual', 'पवित्र शिव अभिषेक अनुष्ठान', 1500.00, '2 hours', 'fa-fire', 'special', 4),
('Daily Aarti', 'दैनिक आरती', 'daily-aarti', 'Participate in the daily morning and evening aarti ceremony at the temple.', 'मंदिर में दैनिक प्रातः और सायं आरती समारोह में भाग लें।', 'Daily morning & evening aarti', 'दैनिक प्रातः और सायं आरती', 0.00, '30 minutes', 'fa-fire', 'daily', 5),
('Havan / Yagna', 'हवन / यज्ञ', 'havan-yagna', 'Sacred fire ritual performed with Vedic mantras for purification and blessings.', 'शुद्धि और आशीर्वाद के लिए वैदिक मंत्रों के साथ किया जाने वाला पवित्र अग्नि अनुष्ठान।', 'Vedic fire ritual for purification', 'शुद्धि के लिए वैदिक अग्नि अनुष्ठान', 5100.00, '4 hours', 'fa-fire', 'personal', 6),
('Akhand Path', 'अखंड पाठ', 'akhand-path', 'Continuous non-stop recitation of holy scriptures for 48 hours for divine grace and protection.', '48 घंटे तक पवित्र ग्रंथों का निरंतर पाठ, दिव्य कृपा और सुरक्षा के लिए।', '48-hour continuous scripture recitation', '48 घंटे का निरंतर ग्रंथ पाठ', 11000.00, '48 hours', 'fa-book-open', 'personal', 7),
('Navgraha Shanti Puja', 'नवग्रह शांति पूजा', 'navgraha-shanti', 'Puja to pacify all nine planets for harmony, success, and relief from planetary afflictions.', 'सभी नौ ग्रहों को शांत करने के लिए पूजा - सामंजस्य, सफलता और ग्रह पीड़ा से मुक्ति हेतु।', 'Pacify nine planets for harmony', 'सामंजस्य के लिए नवग्रह शांति', 3100.00, '3 hours', 'fa-star', 'personal', 8);

-- =====================================================
-- SEED DATA: Sample Events
-- =====================================================
INSERT INTO `events` (`title_en`, `title_hi`, `slug`, `description_en`, `description_hi`, `short_desc_en`, `start_date`, `end_date`, `category`, `is_featured`, `is_registration_open`, `is_active`) VALUES
('Vishwakarma Jayanti 2026', 'विश्वकर्मा जयंती 2026', 'vishwakarma-jayanti-2026', 'Grand celebration of Vishwakarma Jayanti with special puja, cultural programs, and community feast.', 'विशेष पूजा, सांस्कृतिक कार्यक्रमों और सामुदायिक भोज के साथ विश्वकर्मा जयंती का भव्य उत्सव।', 'Annual celebration of Lord Vishwakarma birthday', '2026-09-17 06:00:00', '2026-09-17 21:00:00', 'festival', 1, 1, 1),
('Diwali Celebration 2026', 'दीपावली उत्सव 2026', 'diwali-2026', 'Experience the festival of lights with grand decorations, fireworks, Lakshmi Puja, and community celebrations.', 'भव्य सजावट, आतिशबाजी, लक्ष्मी पूजा और सामुदायिक उत्सव के साथ रोशनी के त्योहार का अनुभव करें।', 'Festival of lights celebration', '2026-10-20 17:00:00', '2026-10-20 23:00:00', 'festival', 1, 1, 1),
('Weekly Satsang', 'साप्ताहिक सत्संग', 'weekly-satsang', 'Join our weekly spiritual gathering with bhajan, kirtan, and discourse on sacred scriptures.', 'भजन, कीर्तन और पवित्र ग्रंथों पर प्रवचन के साथ हमारी साप्ताहिक आध्यात्मिक सभा में शामिल हों।', 'Weekly spiritual gathering with bhajan & kirtan', '2026-06-01 18:00:00', '2026-06-01 20:00:00', 'community', 0, 0, 1);

-- =====================================================
-- SEED DATA: Sample Testimonials
-- =====================================================
INSERT INTO `testimonials` (`name`, `location`, `message_en`, `message_hi`, `rating`, `is_approved`, `is_featured`) VALUES
('Rajesh Kumar', 'Delhi', 'The temple has a divine atmosphere. The priests are very knowledgeable and the puja arrangements are excellent. I feel truly blessed every time I visit.', 'मंदिर का वातावरण दिव्य है। पुजारी बहुत ज्ञानी हैं और पूजा की व्यवस्था उत्कृष्ट है। हर बार यहाँ आकर मैं धन्य महसूस करता हूँ।', 5, 1, 1),
('Priya Sharma', 'Mumbai', 'I booked a Vishwakarma Puja online and the experience was seamless. The temple management is very professional and responsive.', 'मैंने ऑनलाइन विश्वकर्मा पूजा बुक की और अनुभव बहुत अच्छा रहा। मंदिर प्रबंधन बहुत पेशेवर और उत्तरदायी है।', 5, 1, 1),
('Amit Vishwakarma', 'Varanasi', 'As a devotee of Lord Vishwakarma, this temple holds special significance. The architecture and maintenance are commendable.', 'भगवान विश्वकर्मा के भक्त के रूप में, इस मंदिर का विशेष महत्व है। वास्तुकला और रखरखाव सराहनीय है।', 5, 1, 1);

-- =====================================================
-- SEED DATA: Sample Announcements
-- =====================================================
INSERT INTO `announcements` (`message_en`, `message_hi`, `type`, `is_active`) VALUES
('🙏 Welcome to Bhagwan Vishwakarma Mandir! Darshan timings: 5:00 AM - 12:00 PM & 4:00 PM - 9:00 PM', '🙏 भगवान विश्वकर्मा मंदिर में आपका स्वागत है! दर्शन समय: सुबह 5:00 - दोपहर 12:00 और शाम 4:00 - रात 9:00', 'info', 1),
('🎉 Vishwakarma Jayanti 2026 — Grand celebrations on September 17th! Register now for special puja.', '🎉 विश्वकर्मा जयंती 2026 — 17 सितंबर को भव्य उत्सव! विशेष पूजा के लिए अभी पंजीकरण करें।', 'festival', 1);

-- =====================================================
-- SEED DATA: Default Pages
-- =====================================================
INSERT INTO `pages` (`slug`, `title_en`, `title_hi`, `content_en`, `content_hi`, `meta_title`, `meta_description`) VALUES
('privacy-policy', 'Privacy Policy', 'गोपनीयता नीति', '<h2>Privacy Policy</h2><p>At Bhagwan Vishwakarma Mandir, we are committed to protecting your privacy. This policy explains how we collect, use, and safeguard your personal information.</p><h3>Information We Collect</h3><p>We collect information you provide directly, such as your name, email, phone number, and address when you book a puja, make a donation, or register for events.</p><h3>How We Use Your Information</h3><p>Your information is used to process bookings, donations, and event registrations. We may also use it to send you updates about temple events and activities with your consent.</p><h3>Data Security</h3><p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure.</p><h3>Contact Us</h3><p>For questions about this policy, contact us at info@bvm-temple.com.</p>', '<h2>गोपनीयता नीति</h2><p>भगवान विश्वकर्मा मंदिर में, हम आपकी गोपनीयता की रक्षा के लिए प्रतिबद्ध हैं।</p>', 'Privacy Policy - Bhagwan Vishwakarma Mandir', 'Read our privacy policy to understand how we collect and protect your personal information.'),
('terms', 'Terms & Conditions', 'नियम और शर्तें', '<h2>Terms & Conditions</h2><p>By using the Bhagwan Vishwakarma Mandir website, you agree to the following terms and conditions.</p><h3>Puja Bookings</h3><p>All puja bookings are subject to availability and temple schedule. Cancellations must be made at least 24 hours in advance.</p><h3>Donations</h3><p>All donations are voluntary and non-refundable. Donation receipts will be provided for all transactions.</p><h3>Content Usage</h3><p>All content on this website is the property of Bhagwan Vishwakarma Mandir. Unauthorized reproduction is prohibited.</p>', '<h2>नियम और शर्तें</h2><p>भगवान विश्वकर्मा मंदिर की वेबसाइट का उपयोग करके, आप निम्नलिखित नियमों और शर्तों से सहमत होते हैं।</p>', 'Terms & Conditions - Bhagwan Vishwakarma Mandir', 'Read our terms and conditions for using the Bhagwan Vishwakarma Mandir website and services.');

-- =====================================================
-- SEED DATA: Sample Gallery Albums
-- =====================================================
INSERT INTO `gallery_albums` (`title_en`, `title_hi`, `slug`, `description`, `sort_order`) VALUES
('Temple Architecture', 'मंदिर वास्तुकला', 'temple-architecture', 'Beautiful architectural views of Bhagwan Vishwakarma Mandir', 1),
('Festivals & Events', 'त्योहार और कार्यक्रम', 'festivals-events', 'Celebrations and cultural events at the temple', 2),
('Daily Rituals', 'दैनिक अनुष्ठान', 'daily-rituals', 'Daily puja, aarti, and rituals performed at the temple', 3),
('Community Service', 'सामुदायिक सेवा', 'community-service', 'Anna daan, education, and community welfare activities', 4);
