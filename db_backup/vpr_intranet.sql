-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 26-03-17 01:03
-- 서버 버전: 10.4.24-MariaDB
-- PHP 버전: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `vpr_intranet`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `access_requests`
--

CREATE TABLE `access_requests` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `module_name` varchar(50) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `admin_comment` text DEFAULT NULL,
  `processed_by_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `access_requests`
--

INSERT INTO `access_requests` (`id`, `user_id`, `module_name`, `reason`, `status`, `admin_comment`, `processed_by_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'purchase', 'work', 'APPROVED', 'ok', NULL, '2026-03-16 16:23:37', '2026-03-16 22:48:24'),
(2, 1, 'vendor', 'work', 'APPROVED', 'yo apruebo', 1, '2026-03-16 16:23:37', '2026-03-16 22:59:39'),
(3, 1, 'sales', 'work', 'PENDING', NULL, NULL, '2026-03-16 16:23:37', '2026-03-16 16:23:37'),
(4, 1, 'distributor', 'work', 'PENDING', NULL, NULL, '2026-03-16 16:23:37', '2026-03-16 16:23:37'),
(5, 1, 'products', 'work', 'APPROVED', 'esto tambien apruebo', 1, '2026-03-16 16:23:37', '2026-03-16 22:59:48'),
(6, 1, 'accounts', 'work', 'REJECTED', 'estoy rechazo', 1, '2026-03-16 16:23:37', '2026-03-16 23:04:43'),
(7, 1, 'access', 'work', 'PENDING', NULL, NULL, '2026-03-16 16:23:37', '2026-03-16 16:23:37'),
(8, 1, 'system', 'work', 'PENDING', NULL, NULL, '2026-03-16 16:23:37', '2026-03-16 16:23:37'),
(9, 1, 'reports', 'work', 'PENDING', NULL, NULL, '2026-03-16 16:23:37', '2026-03-16 16:23:37');

-- --------------------------------------------------------

--
-- 테이블 구조 `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `users`
--

INSERT INTO `users` (`id`, `email`, `role`, `password`, `full_name`, `status`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'jeongwoo.park@vatechglobal.com', 'admin', '$2y$10$navGhe4.NUqXLWLNhwh82.AQA5w27r6lm929uWyZin8tEI.lrnCJe', 'Jeong Woo Park', 1, '2026-03-16 13:37:00', '2026-03-16 18:45:50', NULL);

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `access_requests`
--
ALTER TABLE `access_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_request` (`user_id`),
  ADD KEY `fk_processed_by` (`processed_by_id`);

--
-- 테이블의 인덱스 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email_unique` (`email`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `access_requests`
--
ALTER TABLE `access_requests`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 테이블의 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `access_requests`
--
ALTER TABLE `access_requests`
  ADD CONSTRAINT `fk_processed_by` FOREIGN KEY (`processed_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user_request` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
