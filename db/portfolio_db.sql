-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2025 at 05:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portfolio_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `first_name`, `last_name`, `email`, `phone`, `subject`, `message`, `created_at`, `is_read`) VALUES
(17, 'Faisal', 'Mohammed', 'info@example.com', '', 'Other', 'Hi there! Thanks for choosing my website remember to edit the login credentials from the settings. Hope you enjoy the site. \r\n\r\nاهلاً شكرا لإستخدامك موقعي اتمنى ينال على اعجابك ولا تنسى تغير بيانات تسجيل الدخول (اسم المستخدم وكلمة المرور) من الإعدادات. بالتوفيق', '2025-12-24 12:40:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `role_en` varchar(255) DEFAULT NULL,
  `role_ar` varchar(255) DEFAULT NULL,
  `company_en` varchar(255) DEFAULT NULL,
  `company_ar` varchar(255) DEFAULT NULL,
  `duration_en` varchar(100) DEFAULT NULL,
  `duration_ar` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`id`, `role_en`, `role_ar`, `company_en`, `company_ar`, `duration_en`, `duration_ar`, `created_at`) VALUES
(5, 'Software Engineer', 'مهندس برمجيات', 'Example', 'مثال', '2021-2023', '2021-2023', '2025-12-24 11:42:30'),
(6, 'Data Analyst', 'محلل بيانات', 'Example 2', 'مثال 2', '2023-2024', '2023-2024', '2025-12-24 11:43:14'),
(7, 'Database Administrator', 'مسؤول قواعد بيانات', 'Example 3', 'مثال 3', '2024-2025', '2024-2025', '2025-12-24 11:44:42'),
(8, 'Project Manager', 'مدير مشروع', 'Example 4', 'مثال 4', '2025-Present', '2025-حتى الآن', '2025-12-24 11:46:00');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `site_logo` varchar(50) DEFAULT 'John',
  `full_name_en` varchar(100) DEFAULT NULL,
  `full_name_ar` varchar(100) DEFAULT NULL,
  `career_en` varchar(100) DEFAULT NULL,
  `career_ar` varchar(100) DEFAULT NULL,
  `bio_en` text DEFAULT NULL,
  `bio_ar` text DEFAULT NULL,
  `cv_url` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'assets/img/profile.png',
  `contact_phone` varchar(50) DEFAULT '+1 234 567 890',
  `contact_email` varchar(100) DEFAULT 'info@example.com',
  `contact_address` varchar(255) DEFAULT '123 Tech Street, Web City',
  `admin_username` varchar(50) DEFAULT 'admin',
  `admin_password` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_logo`, `full_name_en`, `full_name_ar`, `career_en`, `career_ar`, `bio_en`, `bio_ar`, `cv_url`, `profile_image`, `contact_phone`, `contact_email`, `contact_address`, `admin_username`, `admin_password`) VALUES
(1, 'Faisal', 'Faisal', 'فيصل', 'Software Engineer', 'مهندس برمجيات', 'As a Software Engineer, I design and build innovative software solutions...', 'كمهندس برمجيات اصمم وابني حلول برمجية مبتكره...', '', 'assets/img/1766583372_Profile-image.png', '+966555555555', 'info@example.com', 'Riyadh, Saudi Arabia', 'admin', '$2y$10$v8ga/utd/6hTF8veHHo/zuCQ5t07E0Bz2nhmT5f0agPBUupe7AbTi');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_ar` varchar(100) DEFAULT NULL,
  `icon_class` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `name_ar`, `icon_class`, `category`) VALUES
(18, 'Php', NULL, 'fab fa-php', NULL),
(19, 'Java', NULL, 'fab fa-java', NULL),
(20, 'Office 365', NULL, 'fab fa-windows', NULL),
(21, 'Android Development', NULL, 'fab fa-android', NULL),
(24, 'JavaScript', NULL, 'fab fa-js', NULL),
(25, 'CSS3', NULL, 'fab fa-css3-alt', NULL),
(26, 'HTML5', NULL, 'fab fa-html5', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` int(11) NOT NULL,
  `platform_name` varchar(50) DEFAULT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`id`, `platform_name`, `icon_class`, `url`) VALUES
(1, 'GitHub', 'fab fa-github', 'https://github.com/'),
(2, 'LinkedIn', 'fab fa-linkedin-in', 'https://www.linkedin.com/'),
(4, 'Youtube', 'fab fa-youtube', 'https://www.youtube.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
