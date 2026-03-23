-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 26-03-23 17:17
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
-- 데이터베이스: `vpr_erp`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `access`
--

CREATE TABLE `access` (
  `id` int(10) UNSIGNED NOT NULL,
  `access_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `access`
--

INSERT INTO `access` (`id`, `access_name`, `description`, `status`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Compras', 'Acceso al módulo de adquisiciones, órdenes de compra y gestión de proveedores.', 1, '2026-03-17 16:25:10', 1, '2026-03-17 17:00:21'),
(2, 'Ventas', 'Acceso al módulo de facturación, pedidos de clientes y gestión de distribuidores.', 1, '2026-03-17 16:25:10', 1, '2026-03-17 16:25:10'),
(3, 'Maestro', 'Acceso a la configuración de datos base como productos, categorías y divisiones.', 1, '2026-03-17 16:25:10', 1, '2026-03-17 16:25:10'),
(4, 'Sistema', 'Acceso a la administración global, gestión de usuarios y configuraciones del sistema.', 1, '2026-03-17 16:25:10', 1, '2026-03-17 16:25:10');

-- --------------------------------------------------------

--
-- 테이블 구조 `access_requests`
--

CREATE TABLE `access_requests` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `access_id` int(10) UNSIGNED NOT NULL,
  `module_name` varchar(50) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `admin_comment` text DEFAULT NULL,
  `processed_by_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `access_requests`
--

INSERT INTO `access_requests` (`id`, `user_id`, `access_id`, `module_name`, `reason`, `status`, `updated_by`, `admin_comment`, `processed_by_id`, `created_at`, `updated_at`) VALUES
(13, 1, 1, '', 'trabajo', 'REJECTED', 1, NULL, NULL, '2026-03-17 22:47:00', '2026-03-17 22:47:21'),
(14, 1, 2, '', 'trabajo', 'REJECTED', 1, NULL, NULL, '2026-03-17 22:47:00', '2026-03-17 22:47:26'),
(15, 1, 3, '', 'trabajo', 'APPROVED', 1, NULL, NULL, '2026-03-17 22:47:00', '2026-03-17 22:47:27'),
(16, 1, 4, '', 'trabajo', 'APPROVED', 1, NULL, NULL, '2026-03-17 22:47:00', '2026-03-17 22:47:29'),
(17, 1, 1, '', 'aprueba pe', 'APPROVED', 1, NULL, NULL, '2026-03-17 22:47:39', '2026-03-17 22:47:45'),
(18, 1, 2, '', 'aprueba pe', 'APPROVED', 1, NULL, NULL, '2026-03-17 22:47:39', '2026-03-17 22:47:46'),
(19, 3, 1, '', 'trabajo', 'APPROVED', 1, NULL, NULL, '2026-03-17 18:47:40', '2026-03-17 18:57:10'),
(20, 3, 3, '', 'trabajo', 'REJECTED', 1, NULL, NULL, '2026-03-17 18:47:40', '2026-03-17 18:57:14');

-- --------------------------------------------------------

--
-- 테이블 구조 `countries`
--

CREATE TABLE `countries` (
  `id` int(11) UNSIGNED NOT NULL,
  `iso_code` varchar(2) NOT NULL COMMENT 'Código ISO (KR, PE, IT)',
  `country_name` varchar(100) NOT NULL COMMENT 'Nombre del país en español',
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `countries`
--

INSERT INTO `countries` (`id`, `iso_code`, `country_name`, `status`) VALUES
(1, 'BO', 'Bolivia', 1),
(2, 'CL', 'Chile', 1),
(3, 'PA', 'Panama', 1),
(4, 'PE', 'Peru', 1),
(5, 'KR', 'Republica de Corea', 1);

-- --------------------------------------------------------

--
-- 테이블 구조 `divisions`
--

CREATE TABLE `divisions` (
  `id` int(11) NOT NULL,
  `division_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `divisions`
--

INSERT INTO `divisions` (`id`, `division_name`, `description`, `status`, `created_at`) VALUES
(1, 'Gerencia', 'Empleados de nivel C', 1, '2026-03-17 17:21:45'),
(2, 'Administración y Marketing', '-', 1, '2026-03-17 17:25:29');

-- --------------------------------------------------------

--
-- 테이블 구조 `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `division_id` int(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
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

INSERT INTO `users` (`id`, `email`, `division_id`, `hire_date`, `role`, `password`, `full_name`, `status`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'jeongwoo.park@vatechglobal.com', 1, '2026-01-05', 'admin', '$2y$10$s.K.pjGB2piiUtav53Hv9ej7IPmkLKt/O703aKmD7P9BPfo88fY1u', 'Jeong Woo Park', 1, '2026-03-16 13:37:00', '2026-03-18 16:03:05', '2026-03-18 16:03:05'),
(3, 'dsfasf@sdafdsa.com', 2, '2026-03-26', 'user', '$2y$10$XT9RxlEQVwpjGohGsO7irebVKLF0q94laU7Tgf.zZBbSUW4i5G0R.', 'sdfsadf', 1, '2026-03-17 13:20:44', '2026-03-17 18:57:36', '2026-03-17 18:57:36');

-- --------------------------------------------------------

--
-- 테이블 구조 `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_name` varchar(150) NOT NULL COMMENT 'Nombre de la empresa',
  `country_id` int(11) UNSIGNED NOT NULL,
  `tax_id` varchar(50) DEFAULT NULL COMMENT 'RUC o Tax ID internacional',
  `address` text DEFAULT NULL COMMENT 'Dirección de la oficina principal',
  `website` varchar(255) DEFAULT NULL COMMENT 'Sitio web oficial',
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL COMMENT 'Descripción de la empresa y productos',
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `vendors`
--

INSERT INTO `vendors` (`id`, `vendor_name`, `country_id`, `tax_id`, `address`, `website`, `phone`, `mobile`, `description`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Vatech Co., Ltd.', 0, '294723489', NULL, '', '', '', '', 1, 1, '2026-03-18 17:23:10', '2026-03-18 17:49:40'),
(2, 'asfdsflk sdflk j', 3, '23482349', NULL, 'www.holamundo.com', '123 131 312', '234 2423 423', 'isadl kfjasdfk jsalfk saj flksa;j fsd\r\nsa df\r\nsa fsad\r\nfsad fsad fsa f', 1, 1, '2026-03-18 18:27:48', '2026-03-18 18:27:48');

-- --------------------------------------------------------

--
-- 테이블 구조 `vendor_contacts`
--

CREATE TABLE `vendor_contacts` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'FK de la tabla vendors',
  `contact_name` varchar(100) NOT NULL COMMENT 'Nombre del contacto',
  `position` varchar(100) DEFAULT NULL COMMENT 'Cargo o departamento',
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT 0 COMMENT '1: Contacto principal',
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `vendor_contacts`
--

INSERT INTO `vendor_contacts` (`id`, `vendor_id`, `contact_name`, `position`, `email`, `phone`, `is_main`, `status`, `created_at`) VALUES
(1, 1, 'Jeong Hu Lee', 'Exportacion', 'sdflksf@dsfsa.com', '82-1238-123123', 1, 1, '2026-03-18 17:23:10'),
(3, 1, 'hola', 'por eso', 'slkdf@sdaf.com', '298732', 0, 0, '2026-03-18 17:40:51'),
(4, 1, 'otra prueba', 'hola como estas', 'dfsd@sdaf.com', '3987123', 0, 1, '2026-03-18 17:41:15'),
(5, 2, 'mundo lee', 'vago', 'vago@ga.com', '23123 123', 1, 1, '2026-03-18 18:27:48');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_access_updated_by` (`updated_by`);

--
-- 테이블의 인덱스 `access_requests`
--
ALTER TABLE `access_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_request` (`user_id`),
  ADD KEY `fk_processed_by` (`processed_by_id`),
  ADD KEY `fk_requests_access` (`access_id`),
  ADD KEY `fk_requests_updated_by` (`updated_by`);

--
-- 테이블의 인덱스 `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email_unique` (`email`),
  ADD KEY `fk_user_division` (`division_id`);

--
-- 테이블의 인덱스 `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `vendor_contacts`
--
ALTER TABLE `vendor_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `access`
--
ALTER TABLE `access`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 테이블의 AUTO_INCREMENT `access_requests`
--
ALTER TABLE `access_requests`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 테이블의 AUTO_INCREMENT `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 테이블의 AUTO_INCREMENT `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 테이블의 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 테이블의 AUTO_INCREMENT `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 테이블의 AUTO_INCREMENT `vendor_contacts`
--
ALTER TABLE `vendor_contacts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `access`
--
ALTER TABLE `access`
  ADD CONSTRAINT `fk_access_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- 테이블의 제약사항 `access_requests`
--
ALTER TABLE `access_requests`
  ADD CONSTRAINT `fk_processed_by` FOREIGN KEY (`processed_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_requests_access` FOREIGN KEY (`access_id`) REFERENCES `access` (`id`),
  ADD CONSTRAINT `fk_requests_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user_request` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;

--
-- 테이블의 제약사항 `vendor_contacts`
--
ALTER TABLE `vendor_contacts`
  ADD CONSTRAINT `vendor_contacts_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
