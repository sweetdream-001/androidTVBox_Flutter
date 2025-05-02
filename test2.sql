SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for clients
-- ----------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `ClientID` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(255) DEFAULT NULL,
  `CPF` varchar(14) DEFAULT NULL,
  `Street` varchar(255) DEFAULT NULL,
  `StreetNumber` varchar(20) DEFAULT NULL,
  `Complement` varchar(100) DEFAULT NULL,
  `Neighborhood` varchar(100) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `State` varchar(2) DEFAULT NULL,
  `ZipCode` varchar(10) DEFAULT NULL,
  `Latitude` decimal(9,6) DEFAULT NULL,
  `Longitude` decimal(9,6) DEFAULT NULL,
  `MarketSegment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ClientID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for devices
-- ----------------------------
DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `mac_address` varchar(17) NOT NULL,
  `client` varchar(255) NOT NULL,
  `status` enum('Online','Offline') DEFAULT 'Offline',
  `last_communication` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for login
-- ----------------------------
DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for media_files
-- ----------------------------
DROP TABLE IF EXISTS `media_files`;
CREATE TABLE `media_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` bigint UNSIGNED NOT NULL,
  `client` varchar(100) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for playlists
-- ----------------------------
DROP TABLE IF EXISTS `playlists`;
CREATE TABLE `playlists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `schedule` varchar(100) DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for playlist_device_media
-- ----------------------------
DROP TABLE IF EXISTS `playlist_device_media`;
CREATE TABLE `playlist_device_media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `playlist_id` int NOT NULL,
  `device_id` int NOT NULL,
  `media_file_id` int NOT NULL,
  `position` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `playlist_id` (`playlist_id`),
  INDEX `device_id` (`device_id`),
  INDEX `media_file_id` (`media_file_id`),
  CONSTRAINT `playlist_device_media_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `playlist_device_media_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `playlist_device_media_ibfk_3` FOREIGN KEY (`media_file_id`) REFERENCES `media_files` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `login_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_products_1` (`login_id`),
  CONSTRAINT `FK_products_1` FOREIGN KEY (`login_id`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `api_integrations`;
CREATE TABLE `api_integrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `url` TEXT NOT NULL,
  `auth_key` TEXT NOT NULL,
  `status` BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SET FOREIGN_KEY_CHECKS = 1;
