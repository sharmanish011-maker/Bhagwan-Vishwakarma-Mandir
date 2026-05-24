USE `bvm_temple`;

-- Clear any existing rows to avoid duplicate entries in seed
DELETE FROM `gallery_images` WHERE `image_path` IN ('temple_architecture.jpg', 'festival_celebration.jpg', 'daily_aarti.jpg', 'community_service.jpg');

-- Seed gallery images
INSERT INTO `gallery_images` (`album_id`, `image_path`, `thumbnail_path`, `title`, `alt_text`, `sort_order`, `is_active`) VALUES
(1, 'temple_architecture.jpg', 'temple_architecture.jpg', 'Temple Architecture', 'Grand architectural view of the temple spires', 1, 1),
(2, 'festival_celebration.jpg', 'festival_celebration.jpg', 'Festival Celebrations', 'Vishwakarma Jayanti floral decorations', 1, 1),
(3, 'daily_aarti.jpg', 'daily_aarti.jpg', 'Daily Aarti', 'Holy aarti ceremony inside the temple shrine', 1, 1),
(4, 'community_service.jpg', 'community_service.jpg', 'Community Food Service', 'Volunteers conducting Anna Daan service', 1, 1);
