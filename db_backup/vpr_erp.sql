-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 26-03-27 01:11
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
-- 테이블 구조 `entities`
--

CREATE TABLE `entities` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL COMMENT 'Nombre de la empresa',
  `country_id` int(11) UNSIGNED NOT NULL,
  `tax_id` varchar(50) DEFAULT NULL COMMENT 'RUC o Tax ID internacional',
  `is_vendor` tinyint(1) DEFAULT 1,
  `is_dealer` tinyint(1) DEFAULT 0,
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
-- 테이블의 덤프 데이터 `entities`
--

INSERT INTO `entities` (`id`, `name`, `country_id`, `tax_id`, `is_vendor`, `is_dealer`, `address`, `website`, `phone`, `mobile`, `description`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Vatech Co., Ltd.', 0, '294723489', 1, 0, NULL, '', '', '', '', 1, 1, '2026-03-18 17:23:10', '2026-03-18 17:49:40'),
(2, 'asfdsflk sdflk j', 3, '23482349', 1, 0, NULL, 'www.holamundo.com', '123 131 312', '234 2423 423', 'isadl kfjasdfk jsalfk saj flksa;j fsd\r\nsa df\r\nsa fsad\r\nfsad fsad fsa f', 1, 1, '2026-03-18 18:27:48', '2026-03-18 18:27:48'),
(3, 'testeeeeeeetesteeeeeee', 1, 'testeeeeeee', 1, 1, 'testeeeeeeetesteeeeeee', 'testeeeeeeetesteeeeeee', 'testeeeeeeetesteeeeeee', 'testeeeeeeetesteeeeeee', 'testeeeeeeetesteeeeeee', 0, 1, '2026-03-25 12:09:20', '2026-03-25 12:11:03'),
(4, 'eeestedte', 4, 'eeestedte', 1, 0, 'eeestedte', 'eeestedte.co', 'eeestedte', 'eeestedte', 'eeestedte', 1, 1, '2026-03-25 12:11:33', '2026-03-25 12:11:33');

-- --------------------------------------------------------

--
-- 테이블 구조 `entity_contacts`
--

CREATE TABLE `entity_contacts` (
  `id` int(11) UNSIGNED NOT NULL,
  `entity_id` int(11) UNSIGNED NOT NULL COMMENT 'FK de la tabla entities',
  `contact_name` varchar(100) NOT NULL COMMENT 'Nombre del contacto',
  `position` varchar(100) DEFAULT NULL COMMENT 'Cargo o departamento',
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT 0 COMMENT '1: Contacto principal',
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `entity_contacts`
--

INSERT INTO `entity_contacts` (`id`, `entity_id`, `contact_name`, `position`, `email`, `phone`, `is_main`, `status`, `created_at`) VALUES
(1, 3, 'testeeeeeee', 'testeeeeeee', 'testeeeeeee@testeeeeeee.testeeeeeee', 'testeeeeeee', 0, 0, '2026-03-25 12:09:20'),
(2, 3, 'testeeeeeeetesteeeeeee', 'testeeeeeeetesteeeeeee', 'testeeeeeee@d1.1', 'testeeeeeee', 1, 1, '2026-03-25 12:09:58'),
(3, 1, 'testeeeeeee', 'testeeeeeee', 'testeeeeeee@testeeeeeee.co', 'testeeeeeee', 0, 1, '2026-03-25 12:10:52'),
(4, 4, 'eeestedte', 'eeestedte', 'eeestedte@df.co', 'eeestedte', 1, 1, '2026-03-25 12:11:33'),
(5, 3, 'ho1111', 'ho1111', 'ho1111@ho1111.c', 'ho1111', 0, 1, '2026-03-25 12:21:53');

-- --------------------------------------------------------

--
-- 테이블 구조 `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` int(11) NOT NULL,
  `base_currency` varchar(3) DEFAULT 'USD',
  `target_currency` varchar(3) DEFAULT 'PEN',
  `rate` decimal(10,4) NOT NULL,
  `effective_date` date NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `exchange_rates`
--

INSERT INTO `exchange_rates` (`id`, `base_currency`, `target_currency`, `rate`, `effective_date`, `created_by`, `created_at`) VALUES
(3, 'USD', 'PEN', '3.7623', '2026-02-25', 1, '2026-03-26 21:17:12'),
(4, 'USD', 'PEN', '3.7419', '2026-02-26', 1, '2026-03-26 21:17:12'),
(5, 'USD', 'PEN', '3.7624', '2026-02-27', 1, '2026-03-26 21:17:12'),
(6, 'USD', 'PEN', '3.7463', '2026-02-28', 1, '2026-03-26 21:17:12'),
(7, 'USD', 'PEN', '3.7244', '2026-03-01', 1, '2026-03-26 21:17:12'),
(8, 'USD', 'PEN', '3.7231', '2026-03-02', 1, '2026-03-26 21:17:12'),
(9, 'USD', 'PEN', '3.7222', '2026-03-03', 1, '2026-03-26 21:17:12'),
(10, 'USD', 'PEN', '3.7217', '2026-03-04', 1, '2026-03-26 21:17:12'),
(11, 'USD', 'PEN', '3.7219', '2026-03-05', 1, '2026-03-26 21:17:12'),
(12, 'USD', 'PEN', '3.7244', '2026-03-06', 1, '2026-03-26 21:17:12'),
(13, 'USD', 'PEN', '3.7362', '2026-03-07', 1, '2026-03-26 21:17:12'),
(14, 'USD', 'PEN', '3.7277', '2026-03-08', 1, '2026-03-26 21:17:12'),
(15, 'USD', 'PEN', '3.7700', '2026-03-09', 1, '2026-03-26 21:17:12'),
(16, 'USD', 'PEN', '3.7671', '2026-03-10', 1, '2026-03-26 21:17:12'),
(17, 'USD', 'PEN', '3.7453', '2026-03-11', 1, '2026-03-26 21:17:12'),
(18, 'USD', 'PEN', '3.7653', '2026-03-12', 1, '2026-03-26 21:17:12'),
(19, 'USD', 'PEN', '3.7505', '2026-03-13', 1, '2026-03-26 21:17:12'),
(20, 'USD', 'PEN', '3.7367', '2026-03-14', 1, '2026-03-26 21:17:12'),
(21, 'USD', 'PEN', '3.7718', '2026-03-15', 1, '2026-03-26 21:17:12'),
(22, 'USD', 'PEN', '3.7491', '2026-03-16', 1, '2026-03-26 21:17:12'),
(23, 'USD', 'PEN', '3.7701', '2026-03-17', 1, '2026-03-26 21:17:12'),
(24, 'USD', 'PEN', '3.7630', '2026-03-18', 1, '2026-03-26 21:17:12'),
(25, 'USD', 'PEN', '3.7250', '2026-03-19', 1, '2026-03-26 21:17:12'),
(26, 'USD', 'PEN', '3.7358', '2026-03-20', 1, '2026-03-26 21:17:12'),
(27, 'USD', 'PEN', '3.7240', '2026-03-21', 1, '2026-03-26 21:17:12'),
(28, 'USD', 'PEN', '3.7525', '2026-03-22', 1, '2026-03-26 21:17:12'),
(29, 'USD', 'PEN', '3.7507', '2026-03-23', 1, '2026-03-26 21:17:12'),
(30, 'USD', 'PEN', '3.7758', '2026-03-24', 1, '2026-03-26 21:17:12'),
(31, 'USD', 'PEN', '3.7272', '2026-03-25', 1, '2026-03-26 21:17:12'),
(32, 'USD', 'PEN', '3.7683', '2026-03-26', 1, '2026-03-26 21:17:12');

-- --------------------------------------------------------

--
-- 테이블 구조 `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` enum('GOODS','SERVICE') NOT NULL DEFAULT 'GOODS' COMMENT 'GOODS: Bien físico, SERVICE: Servicio o soporte',
  `category_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'FK de la tabla product_categories',
  `code` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Nombre del producto o servicio',
  `brand` varchar(100) DEFAULT NULL COMMENT 'Marca o fabricante (ej. Vatech, LG)',
  `origin_country` varchar(50) DEFAULT NULL COMMENT 'País de origen',
  `unit` varchar(20) DEFAULT 'EA' COMMENT 'Unidad de medida (EA, SET, HORA, etc.)',
  `description` text DEFAULT NULL COMMENT 'Especificaciones detalladas y notas',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: Disponible para venta, 0: Descontinuado',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'Fecha de registro inicial',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha de última modificación',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID del último empleado que realizó cambios (FK de la tabla users)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `products`
--

INSERT INTO `products` (`id`, `type`, `category_id`, `code`, `name`, `brand`, `origin_country`, `unit`, `description`, `is_active`, `created_at`, `updated_at`, `updated_by`) VALUES
(1, 'GOODS', 1, 'MOD-1-688', 'Producto de Prueba 1', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 1', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(2, 'GOODS', 2, 'MOD-2-808', 'Producto de Prueba 2', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 2', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(3, 'SERVICE', 4, 'MOD-3-960', 'Producto de Prueba 3', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 3', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(4, 'GOODS', 4, 'MOD-4-317', 'Producto de Prueba 4', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 4', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(5, 'GOODS', 1, 'MOD-5-907', 'Producto de Prueba 5', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 5', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(6, 'GOODS', 3, 'MOD-6-545', 'Producto de Prueba 6', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 6', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(7, 'GOODS', 2, 'MOD-7-809', 'Producto de Prueba 7', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 7', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(8, 'GOODS', 3, 'MOD-8-716', 'Producto de Prueba 8', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 8', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(9, 'SERVICE', 3, 'MOD-9-306', 'Producto de Prueba 9', 'Vatech', 'China', 'HORA', 'Descripción automática para el producto de prueba número 9', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(10, 'GOODS', 3, 'MOD-10-531', 'Producto de Prueba 10', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 10', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(11, 'SERVICE', 4, 'MOD-11-875', 'Producto de Prueba 11', 'Vatech', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 11', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(12, 'SERVICE', 2, 'MOD-12-239', 'Producto de Prueba 12', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 12', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(13, 'GOODS', 1, 'MOD-13-605', 'Producto de Prueba 13', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 13', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(14, 'GOODS', 1, 'MOD-14-219', 'Producto de Prueba 14', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 14', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(15, 'GOODS', 1, 'MOD-15-428', 'Producto de Prueba 15', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 15', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(16, 'GOODS', 4, 'MOD-16-554', 'Producto de Prueba 16', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 16', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(17, 'GOODS', 3, 'MOD-17-526', 'Producto de Prueba 17', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 17', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(18, 'SERVICE', 1, 'MOD-18-361', 'Producto de Prueba 18', 'LG', 'China', 'HORA', 'Descripción automática para el producto de prueba número 18', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(19, 'GOODS', 2, 'MOD-19-533', 'Producto de Prueba 19', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 19', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(20, 'GOODS', 2, 'MOD-20-899', 'Producto de Prueba 20', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 20', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(21, 'GOODS', 2, 'MOD-21-978', 'Producto de Prueba 21', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 21', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(22, 'GOODS', 3, 'MOD-22-680', 'Producto de Prueba 22', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 22', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(23, 'GOODS', 4, 'MOD-23-597', 'Producto de Prueba 23', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 23', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(24, 'GOODS', 1, 'MOD-24-310', 'Producto de Prueba 24', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 24', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(25, 'GOODS', 3, 'MOD-25-183', 'Producto de Prueba 25', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 25', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(26, 'GOODS', 3, 'MOD-26-697', 'Producto de Prueba 26', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 26', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(27, 'GOODS', 2, 'MOD-27-789', 'Producto de Prueba 27', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 27', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(28, 'GOODS', 4, 'MOD-28-934', 'Producto de Prueba 28', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 28', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(29, 'GOODS', 3, 'MOD-29-831', 'Producto de Prueba 29', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 29', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(30, 'GOODS', 1, 'MOD-30-739', 'Producto de Prueba 30', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 30', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(31, 'GOODS', 3, 'MOD-31-764', 'Producto de Prueba 31', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 31', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(32, 'SERVICE', 4, 'MOD-32-695', 'Producto de Prueba 32', 'Vatech', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 32', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(33, 'SERVICE', 4, 'MOD-33-779', 'Producto de Prueba 33', 'Vatech', 'China', 'HORA', 'Descripción automática para el producto de prueba número 33', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(34, 'GOODS', 3, 'MOD-34-829', 'Producto de Prueba 34', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 34', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(35, 'GOODS', 4, 'MOD-35-839', 'Producto de Prueba 35', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 35', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(36, 'GOODS', 4, 'MOD-36-972', 'Producto de Prueba 36', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 36', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(37, 'GOODS', 2, 'MOD-37-562', 'Producto de Prueba 37', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 37', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(38, 'GOODS', 3, 'MOD-38-693', 'Producto de Prueba 38', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 38', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(39, 'GOODS', 3, 'MOD-39-137', 'Producto de Prueba 39', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 39', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(40, 'GOODS', 1, 'MOD-40-693', 'Producto de Prueba 40', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 40', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(41, 'GOODS', 1, 'MOD-41-382', 'Producto de Prueba 41', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 41', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(42, 'GOODS', 4, 'MOD-42-478', 'Producto de Prueba 42', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 42', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(43, 'GOODS', 4, 'MOD-43-526', 'Producto de Prueba 43', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 43', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(44, 'GOODS', 2, 'MOD-44-437', 'Producto de Prueba 44', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 44', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(45, 'GOODS', 4, 'MOD-45-851', 'Producto de Prueba 45', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 45', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(46, 'GOODS', 3, 'MOD-46-803', 'Producto de Prueba 46', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 46', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(47, 'GOODS', 3, 'MOD-47-505', 'Producto de Prueba 47', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 47', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(48, 'GOODS', 1, 'MOD-48-440', 'Producto de Prueba 48', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 48', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(49, 'GOODS', 4, 'MOD-49-291', 'Producto de Prueba 49', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 49', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(50, 'GOODS', 2, 'MOD-50-366', 'Producto de Prueba 50', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 50', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(51, 'GOODS', 2, 'MOD-51-762', 'Producto de Prueba 51', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 51', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(52, 'GOODS', 4, 'MOD-52-802', 'Producto de Prueba 52', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 52', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(53, 'SERVICE', 1, 'MOD-53-880', 'Producto de Prueba 53', 'Vatech', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 53', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(54, 'GOODS', 4, 'MOD-54-689', 'Producto de Prueba 54', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 54', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(55, 'GOODS', 4, 'MOD-55-743', 'Producto de Prueba 55', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 55', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(56, 'GOODS', 3, 'MOD-56-282', 'Producto de Prueba 56', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 56', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(57, 'GOODS', 4, 'MOD-57-673', 'Producto de Prueba 57', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 57', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(58, 'SERVICE', 3, 'MOD-58-483', 'Producto de Prueba 58', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 58', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(59, 'GOODS', 3, 'MOD-59-692', 'Producto de Prueba 59', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 59', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(60, 'GOODS', 2, 'MOD-60-664', 'Producto de Prueba 60', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 60', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(61, 'GOODS', 4, 'MOD-61-653', 'Producto de Prueba 61', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 61', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(62, 'GOODS', 2, 'MOD-62-593', 'Producto de Prueba 62', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 62', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(63, 'GOODS', 2, 'MOD-63-542', 'Producto de Prueba 63', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 63', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(64, 'GOODS', 2, 'MOD-64-440', 'Producto de Prueba 64', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 64', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(65, 'GOODS', 1, 'MOD-65-502', 'Producto de Prueba 65', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 65', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(66, 'GOODS', 4, 'MOD-66-798', 'Producto de Prueba 66', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 66', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(67, 'GOODS', 1, 'MOD-67-853', 'Producto de Prueba 67', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 67', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(68, 'GOODS', 1, 'MOD-68-106', 'Producto de Prueba 68', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 68', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(69, 'SERVICE', 1, 'MOD-69-838', 'Producto de Prueba 69', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 69', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(70, 'GOODS', 4, 'MOD-70-131', 'Producto de Prueba 70', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 70', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(71, 'GOODS', 2, 'MOD-71-163', 'Producto de Prueba 71', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 71', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(72, 'GOODS', 4, 'MOD-72-679', 'Producto de Prueba 72', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 72', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(73, 'SERVICE', 1, 'MOD-73-720', 'Producto de Prueba 73', 'Vatech', 'China', 'HORA', 'Descripción automática para el producto de prueba número 73', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(74, 'SERVICE', 2, 'MOD-74-299', 'Producto de Prueba 74', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 74', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(75, 'GOODS', 3, 'MOD-75-299', 'Producto de Prueba 75', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 75', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(76, 'SERVICE', 1, 'MOD-76-806', 'Producto de Prueba 76', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 76', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(77, 'SERVICE', 1, 'MOD-77-788', 'Producto de Prueba 77', 'Vatech', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 77', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(78, 'GOODS', 4, 'MOD-78-128', 'Producto de Prueba 78', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 78', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(79, 'SERVICE', 1, 'MOD-79-621', 'Producto de Prueba 79', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 79', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(80, 'GOODS', 3, 'MOD-80-503', 'Producto de Prueba 80', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 80', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(81, 'GOODS', 1, 'MOD-81-200', 'Producto de Prueba 81', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 81', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(82, 'GOODS', 4, 'MOD-82-685', 'Producto de Prueba 82', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 82', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(83, 'GOODS', 1, 'MOD-83-573', 'Producto de Prueba 83', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 83', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(84, 'GOODS', 3, 'MOD-84-158', 'Producto de Prueba 84', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 84', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(85, 'GOODS', 2, 'MOD-85-749', 'Producto de Prueba 85', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 85', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(86, 'SERVICE', 2, 'MOD-86-777', 'Producto de Prueba 86', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 86', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(87, 'GOODS', 1, 'MOD-87-787', 'Producto de Prueba 87', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 87', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(88, 'SERVICE', 4, 'MOD-88-292', 'Producto de Prueba 88', 'LG', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 88', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(89, 'GOODS', 3, 'MOD-89-365', 'Producto de Prueba 89', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 89', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(90, 'SERVICE', 3, 'MOD-90-457', 'Producto de Prueba 90', 'LG', 'China', 'HORA', 'Descripción automática para el producto de prueba número 90', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(91, 'GOODS', 3, 'MOD-91-423', 'Producto de Prueba 91', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 91', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(92, 'GOODS', 4, 'MOD-92-889', 'Producto de Prueba 92', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 92', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(93, 'GOODS', 1, 'MOD-93-336', 'Producto de Prueba 93', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 93', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(94, 'GOODS', 4, 'MOD-94-101', 'Producto de Prueba 94', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 94', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(95, 'GOODS', 4, 'MOD-95-440', 'Producto de Prueba 95', 'LG', 'China', 'EA', 'Descripción automática para el producto de prueba número 95', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(96, 'GOODS', 4, 'MOD-96-655', 'Producto de Prueba 96', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 96', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(97, 'GOODS', 1, 'MOD-97-391', 'Producto de Prueba 97', 'Vatech', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 97', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(98, 'SERVICE', 3, 'MOD-98-236', 'Producto de Prueba 98', 'Vatech', 'Corea del Sur', 'HORA', 'Descripción automática para el producto de prueba número 98', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(99, 'GOODS', 3, 'MOD-99-485', 'Producto de Prueba 99', 'LG', 'Corea del Sur', 'EA', 'Descripción automática para el producto de prueba número 99', 1, '2026-03-25 13:42:57', '2026-03-25 13:42:57', 1),
(100, 'GOODS', 2, 'MOD-100-120', 'Producto de Prueba 100', 'Vatech', 'China', 'EA', 'Descripción automática para el producto de prueba número 100', 1, '2026-03-25 13:42:57', '2026-03-25 13:43:36', 1),
(101, 'GOODS', 1, 'Green X18', 'Green X18', NULL, NULL, NULL, NULL, 1, '2026-03-26 16:43:22', '2026-03-26 16:43:22', 1),
(105, 'SERVICE', 3, NULL, 'Mantenimiento basico', 'Vatech Peru', 'Perú', 'Vez', 'Hola', 1, '2026-03-26 18:44:33', '2026-03-26 18:44:33', 1);

-- --------------------------------------------------------

--
-- 테이블 구조 `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL COMMENT 'Nombre de la categoría',
  `description` text DEFAULT NULL COMMENT 'Descripción de la categoría',
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Activo, 0: Inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `product_categories`
--

INSERT INTO `product_categories` (`id`, `category_name`, `description`, `status`) VALUES
(1, 'Equipos Digitales', 'Equipos de Rayos X y sensores intraorales', 1),
(2, 'Repuestos', 'Componentes y piezas de repuesto técnicas', 1),
(3, 'Servicio Técnico', 'Servicios de mantenimiento y reparación', 1),
(4, 'Software', 'Licencias de software y soporte técnico', 1);

-- --------------------------------------------------------

--
-- 테이블 구조 `product_items`
--

CREATE TABLE `product_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL COMMENT 'Referencia al producto padre (FK)',
  `sku_code` varchar(100) NOT NULL COMMENT 'Código final del SKU (incluye variante)',
  `option_name` varchar(50) DEFAULT NULL COMMENT 'Tipo de opción (Tamaño, Color, Voltaje, Licencia)',
  `option_value` varchar(50) DEFAULT NULL COMMENT 'Valor de la opción (XL, 220V, 1 Año, etc.)',
  `weight` decimal(10,2) DEFAULT NULL COMMENT 'Peso en Kg (para logística)',
  `dimensions` varchar(100) DEFAULT NULL COMMENT 'Dimensiones LxWxH (para logística)',
  `barcode` varchar(100) DEFAULT NULL COMMENT 'Código de barras o EAN',
  `min_stock` int(11) DEFAULT 0 COMMENT 'Stock mínimo para alertas de reposición',
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Ítem activo, 0: Ítem inactivo',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha de modificación del ítem',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que modificó el ítem'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `product_items`
--

INSERT INTO `product_items` (`id`, `product_id`, `sku_code`, `option_name`, `option_value`, `weight`, `dimensions`, `barcode`, `min_stock`, `status`, `updated_at`, `updated_by`) VALUES
(1, 1, 'MOD-1-688-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 2, 1, '2026-03-25 13:42:57', 1),
(2, 1, 'MOD-1-688-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(3, 2, 'MOD-2-808-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(4, 2, 'MOD-2-808-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(5, 3, 'MOD-3-960-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(6, 4, 'MOD-4-317-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(7, 4, 'MOD-4-317-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(8, 5, 'MOD-5-907-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(9, 6, 'MOD-6-545-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(10, 6, 'MOD-6-545-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(11, 7, 'MOD-7-809-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(12, 8, 'MOD-8-716-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(13, 8, 'MOD-8-716-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(14, 9, 'MOD-9-306-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(15, 10, 'MOD-10-531-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(16, 11, 'MOD-11-875-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(17, 12, 'MOD-12-239-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(18, 13, 'MOD-13-605-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(19, 14, 'MOD-14-219-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(20, 14, 'MOD-14-219-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(21, 15, 'MOD-15-428-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(22, 15, 'MOD-15-428-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(23, 16, 'MOD-16-554-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(24, 17, 'MOD-17-526-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(25, 17, 'MOD-17-526-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(26, 18, 'MOD-18-361-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(27, 19, 'MOD-19-533-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(28, 19, 'MOD-19-533-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 2, 1, '2026-03-25 13:42:57', 1),
(29, 20, 'MOD-20-899-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(30, 21, 'MOD-21-978-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(31, 21, 'MOD-21-978-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(32, 22, 'MOD-22-680-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(33, 23, 'MOD-23-597-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(34, 24, 'MOD-24-310-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(35, 25, 'MOD-25-183-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(36, 25, 'MOD-25-183-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(37, 26, 'MOD-26-697-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(38, 27, 'MOD-27-789-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(39, 28, 'MOD-28-934-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(40, 29, 'MOD-29-831-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(41, 29, 'MOD-29-831-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(42, 30, 'MOD-30-739-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(43, 31, 'MOD-31-764-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(44, 32, 'MOD-32-695-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(45, 33, 'MOD-33-779-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(46, 34, 'MOD-34-829-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(47, 35, 'MOD-35-839-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(48, 36, 'MOD-36-972-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(49, 36, 'MOD-36-972-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(50, 37, 'MOD-37-562-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(51, 38, 'MOD-38-693-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(52, 39, 'MOD-39-137-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(53, 40, 'MOD-40-693-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(54, 41, 'MOD-41-382-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(55, 41, 'MOD-41-382-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(56, 42, 'MOD-42-478-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(57, 43, 'MOD-43-526-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(58, 43, 'MOD-43-526-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(59, 44, 'MOD-44-437-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(60, 45, 'MOD-45-851-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(61, 46, 'MOD-46-803-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(62, 47, 'MOD-47-505-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(63, 47, 'MOD-47-505-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(64, 48, 'MOD-48-440-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(65, 49, 'MOD-49-291-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(66, 50, 'MOD-50-366-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(67, 51, 'MOD-51-762-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(68, 52, 'MOD-52-802-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(69, 52, 'MOD-52-802-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(70, 53, 'MOD-53-880-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(71, 54, 'MOD-54-689-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(72, 55, 'MOD-55-743-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(73, 55, 'MOD-55-743-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(74, 56, 'MOD-56-282-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(75, 57, 'MOD-57-673-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(76, 58, 'MOD-58-483-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 2, 1, '2026-03-25 13:42:57', 1),
(77, 59, 'MOD-59-692-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(78, 60, 'MOD-60-664-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(79, 61, 'MOD-61-653-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(80, 62, 'MOD-62-593-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(81, 62, 'MOD-62-593-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(82, 63, 'MOD-63-542-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(83, 64, 'MOD-64-440-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(84, 65, 'MOD-65-502-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(85, 66, 'MOD-66-798-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(86, 67, 'MOD-67-853-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(87, 68, 'MOD-68-106-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(88, 69, 'MOD-69-838-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(89, 70, 'MOD-70-131-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(90, 71, 'MOD-71-163-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(91, 72, 'MOD-72-679-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(92, 72, 'MOD-72-679-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 2, 1, '2026-03-25 13:42:57', 1),
(93, 73, 'MOD-73-720-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(94, 74, 'MOD-74-299-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(95, 75, 'MOD-75-299-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(96, 75, 'MOD-75-299-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(97, 76, 'MOD-76-806-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 2, 1, '2026-03-25 13:42:57', 1),
(98, 77, 'MOD-77-788-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(99, 78, 'MOD-78-128-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(100, 79, 'MOD-79-621-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(101, 80, 'MOD-80-503-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(102, 81, 'MOD-81-200-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(103, 82, 'MOD-82-685-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 5, 1, '2026-03-25 13:42:57', 1),
(104, 82, 'MOD-82-685-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(105, 83, 'MOD-83-573-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(106, 84, 'MOD-84-158-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 2, 1, '2026-03-25 13:42:57', 1),
(107, 85, 'MOD-85-749-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 2, 1, '2026-03-25 13:42:57', 1),
(108, 86, 'MOD-86-777-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(109, 87, 'MOD-87-787-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(110, 88, 'MOD-88-292-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 4, 1, '2026-03-25 13:42:57', 1),
(111, 89, 'MOD-89-365-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 1, 1, '2026-03-25 13:42:57', 1),
(112, 90, 'MOD-90-457-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(113, 91, 'MOD-91-423-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(114, 91, 'MOD-91-423-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(115, 92, 'MOD-92-889-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(116, 93, 'MOD-93-336-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 8, 1, '2026-03-25 13:42:57', 1),
(117, 93, 'MOD-93-336-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(118, 94, 'MOD-94-101-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(119, 95, 'MOD-95-440-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 7, 1, '2026-03-25 13:42:57', 1),
(120, 96, 'MOD-96-655-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 6, 1, '2026-03-25 13:42:57', 1),
(121, 97, 'MOD-97-391-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 9, 1, '2026-03-25 13:42:57', 1),
(122, 97, 'MOD-97-391-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(123, 98, 'MOD-98-236-SKU1', 'Tipo Soporte', 'Remoto', NULL, NULL, NULL, 2, 1, '2026-03-25 13:42:57', 1),
(124, 99, 'MOD-99-485-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 10, 1, '2026-03-25 13:42:57', 1),
(125, 99, 'MOD-99-485-SKU2', 'Voltaje', '110V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(126, 100, 'MOD-100-120-SKU1', 'Voltaje', '220V', NULL, NULL, NULL, 3, 1, '2026-03-25 13:42:57', 1),
(127, 101, 'con ceph', 'con ceph', '-', NULL, NULL, NULL, 0, 1, '2026-03-26 16:43:22', 1),
(128, 101, 'sin ceph', 'sin ceph', '-', NULL, NULL, NULL, 0, 1, '2026-03-26 16:43:22', 1),
(134, 105, 'Hola', 'Hola', 'Hola', NULL, NULL, NULL, 0, 1, '2026-03-26 18:44:33', 1),
(135, 105, 'HolaHola', 'HolaHola', 'HolaHola', NULL, NULL, NULL, 0, 1, '2026-03-26 18:44:33', 1);

-- --------------------------------------------------------

--
-- 테이블 구조 `product_price_history`
--

CREATE TABLE `product_price_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `purchase_price_usd` decimal(12,2) DEFAULT 0.00,
  `purchase_price_pen` decimal(12,2) DEFAULT 0.00,
  `sale_price_usd` decimal(12,2) DEFAULT 0.00,
  `sale_price_pen` decimal(12,2) DEFAULT 0.00,
  `applied_rate` decimal(10,4) DEFAULT 0.0000,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `product_price_history`
--

INSERT INTO `product_price_history` (`id`, `item_id`, `purchase_price_usd`, `purchase_price_pen`, `sale_price_usd`, `sale_price_pen`, `applied_rate`, `created_by`, `created_at`) VALUES
(1, 127, '1000.00', '3768.00', '1300.00', '4898.40', '3.7683', 1, '2026-03-26 21:43:22'),
(2, 128, '600.00', '2260.80', '1100.00', '4144.80', '3.7683', 1, '2026-03-26 21:43:22'),
(6, 134, '1000.00', '3768.30', '1300.00', '4898.79', '3.7683', 1, '2026-03-26 23:44:33'),
(7, 135, '2000.00', '7536.60', '2500.00', '9420.75', '3.7683', 1, '2026-03-26 23:44:33');

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
(1, 'jeongwoo.park@vatechglobal.com', 1, '2026-01-05', 'admin', '$2y$10$s.K.pjGB2piiUtav53Hv9ej7IPmkLKt/O703aKmD7P9BPfo88fY1u', 'Jeong Woo Park', 1, '2026-03-16 13:37:00', '2026-03-26 15:30:44', '2026-03-26 15:30:44'),
(3, 'dsfasf@sdafdsa.com', 2, '2026-03-26', 'user', '$2y$10$XT9RxlEQVwpjGohGsO7irebVKLF0q94laU7Tgf.zZBbSUW4i5G0R.', 'sdfsadf', 1, '2026-03-17 13:20:44', '2026-03-17 18:57:36', '2026-03-17 18:57:36');

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
-- 테이블의 인덱스 `entities`
--
ALTER TABLE `entities`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `entity_contacts`
--
ALTER TABLE `entity_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_entity_id` (`entity_id`);

--
-- 테이블의 인덱스 `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_exchange_date` (`base_currency`,`target_currency`,`effective_date`),
  ADD UNIQUE KEY `idx_unique_exchange` (`base_currency`,`target_currency`,`effective_date`),
  ADD KEY `effective_date` (`effective_date`);

--
-- 테이블의 인덱스 `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `fk_product_category` (`category_id`),
  ADD KEY `fk_product_updated_by` (`updated_by`);

--
-- 테이블의 인덱스 `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `product_items`
--
ALTER TABLE `product_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku_code` (`sku_code`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `fk_item_updated_by` (`updated_by`);

--
-- 테이블의 인덱스 `product_price_history`
--
ALTER TABLE `product_price_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_item_price` (`item_id`);

--
-- 테이블의 인덱스 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email_unique` (`email`),
  ADD KEY `fk_user_division` (`division_id`);

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
-- 테이블의 AUTO_INCREMENT `entities`
--
ALTER TABLE `entities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 테이블의 AUTO_INCREMENT `entity_contacts`
--
ALTER TABLE `entity_contacts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 테이블의 AUTO_INCREMENT `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- 테이블의 AUTO_INCREMENT `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- 테이블의 AUTO_INCREMENT `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 테이블의 AUTO_INCREMENT `product_items`
--
ALTER TABLE `product_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- 테이블의 AUTO_INCREMENT `product_price_history`
--
ALTER TABLE `product_price_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 테이블의 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- 테이블의 제약사항 `entity_contacts`
--
ALTER TABLE `entity_contacts`
  ADD CONSTRAINT `fk_entity_contacts_entity` FOREIGN KEY (`entity_id`) REFERENCES `entities` (`id`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- 테이블의 제약사항 `product_items`
--
ALTER TABLE `product_items`
  ADD CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- 테이블의 제약사항 `product_price_history`
--
ALTER TABLE `product_price_history`
  ADD CONSTRAINT `fk_product_item_price` FOREIGN KEY (`item_id`) REFERENCES `product_items` (`id`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
