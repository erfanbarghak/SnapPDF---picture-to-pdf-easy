-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 11, 2026 at 08:37 PM
-- Server version: 8.0.46-cll-lve
-- PHP Version: 8.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snappdf_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversions`
--

CREATE TABLE `conversions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `temp_file_id` int DEFAULT NULL,
  `original_images_count` tinyint NOT NULL,
  `pdf_file_name` varchar(255) NOT NULL,
  `pdf_file_size` int NOT NULL,
  `pdf_file_path` varchar(500) NOT NULL,
  `is_compressed` tinyint(1) DEFAULT '0',
  `has_page_numbers` tinyint(1) DEFAULT '0',
  `status` enum('pending','processing','completed','failed','deleted') DEFAULT 'pending',
  `download_count` int DEFAULT '0',
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `failed_attempts`
--

CREATE TABLE `failed_attempts` (
  `id` int NOT NULL,
  `phone` varchar(15) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `action` varchar(50) NOT NULL,
  `attempt_time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `otp_logs`
--

CREATE TABLE `otp_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `type` enum('login','register','reset_password') NOT NULL,
  `status` enum('pending','used','expired') DEFAULT 'pending',
  `attempts` tinyint DEFAULT '0',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` int UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int UNSIGNED NOT NULL,
  `setting_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'file_max_size_mb', '10', '2026-06-28 13:44:13', '2026-06-28 13:44:13'),
(2, 'allowed_image_types', 'jpg,jpeg,png,webp,bmp,tiff', '2026-06-28 13:44:13', '2026-06-28 13:44:13'),
(3, 'otp_expiry_seconds', '120', '2026-06-28 13:44:13', '2026-06-28 13:44:13'),
(4, 'file_expiry_seconds', '120', '2026-06-28 13:44:13', '2026-06-28 13:44:13'),
(5, 'max_otp_attempts', '5', '2026-06-28 13:44:13', '2026-06-28 13:44:13'),
(6, 'max_login_attempts', '5', '2026-06-28 13:44:13', '2026-06-28 13:44:13'),
(7, 'conversion_timeout_seconds', '30', '2026-06-28 13:44:13', '2026-06-28 13:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `temp_files`
--

CREATE TABLE `temp_files` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int NOT NULL,
  `file_mime` varchar(100) NOT NULL,
  `file_type` enum('image','pdf') DEFAULT 'image',
  `order_index` tinyint DEFAULT '0',
  `pdf_converted_at` datetime DEFAULT NULL,
  `pdf_file_path` varchar(500) DEFAULT NULL,
  `pdf_file_name` varchar(255) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `status` enum('active','blocked') DEFAULT 'active',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `phone`, `password_hash`, `is_verified`, `status`, `last_login_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Erfan Erebus', '9122189090', '$2y$10$FufV55oW4HW2ly0rEYLmMOQxCHgHVNN/VVM.klCalHP1Z.fcrEM4W', 1, 'active', '2026-08-11 20:35:55', '2026-06-28 14:07:36', '2026-08-11 20:36:14', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conversions`
--
ALTER TABLE `conversions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `failed_attempts`
--
ALTER TABLE `failed_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone` (`phone`);

--
-- Indexes for table `otp_logs`
--
ALTER TABLE `otp_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone` (`phone`),
  ADD KEY `otp_code` (`otp_code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `temp_files`
--
ALTER TABLE `temp_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `phone_2` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conversions`
--
ALTER TABLE `conversions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `failed_attempts`
--
ALTER TABLE `failed_attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_logs`
--
ALTER TABLE `otp_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `temp_files`
--
ALTER TABLE `temp_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
