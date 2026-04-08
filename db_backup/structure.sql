-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 26-04-08 02:46
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

-- --------------------------------------------------------

--
-- 테이블 구조 `inbounds`
--

CREATE TABLE `inbounds` (
  `id` int(11) UNSIGNED NOT NULL,
  `inbound_number` varchar(50) NOT NULL COMMENT 'Ej: INB-2026-001',
  `source_type_id` int(11) UNSIGNED NOT NULL,
  `status_id` int(11) UNSIGNED NOT NULL,
  `source_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID de PO, Transferencia, etc.',
  `warehouse_id` int(11) UNSIGNED NOT NULL COMMENT 'Almacén de destino',
  `expected_date` date DEFAULT NULL COMMENT 'Fecha estimada de llegada',
  `arrival_date` datetime DEFAULT NULL COMMENT 'Fecha real de ingreso',
  `notes` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `inbound_items`
--

CREATE TABLE `inbound_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `inbound_id` int(11) UNSIGNED NOT NULL,
  `item_id` int(11) UNSIGNED NOT NULL COMMENT 'FK: product_items.id',
  `expected_qty` int(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad planificada',
  `received_qty` int(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad real ingresada',
  `damaged_qty` int(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad dañada/rechazada',
  `item_status_id` int(11) UNSIGNED NOT NULL,
  `bin_location` varchar(50) DEFAULT NULL COMMENT 'Ubicación específica en almacén'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) UNSIGNED NOT NULL,
  `warehouse_id` int(11) UNSIGNED NOT NULL COMMENT 'FK from warehouses',
  `item_id` int(11) UNSIGNED NOT NULL COMMENT 'FK from product_items',
  `stock_status` enum('Available','Damaged','Quarantine','Sample') DEFAULT 'Available' COMMENT 'Logical status',
  `bin_location` varchar(50) DEFAULT NULL COMMENT 'Specific location in warehouse',
  `quantity` int(11) DEFAULT 0 COMMENT 'Current quantity',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `warehouse_id` int(11) UNSIGNED NOT NULL COMMENT 'ID of the warehouse',
  `item_id` int(11) UNSIGNED NOT NULL COMMENT 'ID of the affected product_item',
  `stock_status` enum('Available','Damaged','Quarantine','Sample') NOT NULL,
  `type` enum('Inbound','Outbound','Adjustment','Transfer') NOT NULL,
  `reference_id` int(11) DEFAULT NULL COMMENT 'Reference document ID',
  `qty_before` int(11) NOT NULL,
  `qty_change` int(11) NOT NULL,
  `qty_after` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `mappings`
--

CREATE TABLE `mappings` (
  `id` int(11) UNSIGNED NOT NULL,
  `category` varchar(50) NOT NULL COMMENT 'Categoría del mapeo (ej. po_type, status, currency)',
  `code_value` varchar(50) NOT NULL COMMENT 'Valor real almacenado en la base de datos',
  `display_name` varchar(100) NOT NULL COMMENT 'Nombre descriptivo que se muestra en la interfaz (UI)',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Orden de visualización de los elementos',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Estado del registro: 1 para Activo, 0 para Inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
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

-- --------------------------------------------------------

--
-- 테이블 구조 `product_items`
--

CREATE TABLE `product_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL COMMENT 'Referencia al producto padre (FK)',
  `option` varchar(150) DEFAULT NULL COMMENT 'Descripción de la variante u opción',
  `weight` decimal(10,2) DEFAULT NULL COMMENT 'Peso en Kg (para logística)',
  `dimensions` varchar(100) DEFAULT NULL COMMENT 'Dimensiones LxWxH (para logística)',
  `barcode` varchar(100) DEFAULT NULL COMMENT 'Código de barras o EAN',
  `min_stock` int(11) DEFAULT 0 COMMENT 'Stock mínimo para alertas de reposición',
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Ítem activo, 0: Ítem inactivo',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha de modificación del ítem',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que modificó el ítem'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- 테이블 구조 `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `po_number` varchar(50) NOT NULL COMMENT 'Ej: PO-2024-001',
  `supplier_id` int(11) UNSIGNED NOT NULL,
  `warehouse_id` int(11) UNSIGNED DEFAULT NULL,
  `po_type` int(11) UNSIGNED DEFAULT NULL,
  `status` int(11) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `currency` int(11) UNSIGNED DEFAULT NULL,
  `exchange_rate` decimal(10,4) DEFAULT 1.0000,
  `incoterms` int(11) UNSIGNED DEFAULT NULL,
  `payment_terms` int(11) UNSIGNED DEFAULT NULL,
  `shipping_method` int(11) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `issue_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) UNSIGNED NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approver_comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `po_id` int(11) UNSIGNED NOT NULL,
  `item_id` int(11) UNSIGNED NOT NULL COMMENT 'FK from product_items',
  `unit_price` decimal(15,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `received_quantity` int(11) DEFAULT 0,
  `total_price` decimal(15,2) GENERATED ALWAYS AS (`unit_price` * `quantity`) VIRTUAL,
  `delivery_date` date DEFAULT NULL COMMENT 'Fecha de entrega específica para este ítem'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `sales_number` varchar(50) NOT NULL COMMENT 'Ej: SL-2026-001',
  `customer_entity_id` int(11) UNSIGNED NOT NULL COMMENT 'FK: entities.id',
  `warehouse_id` int(11) UNSIGNED NOT NULL COMMENT 'FK: warehouses.id',
  `status_id` int(11) UNSIGNED NOT NULL COMMENT 'FK: mappings.id',
  `currency_id` int(11) UNSIGNED NOT NULL COMMENT 'FK: mappings.id',
  `exchange_rate` decimal(10,4) DEFAULT 1.0000,
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `sales_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) NOT NULL COMMENT 'FK: users.id',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL COMMENT 'FK: users.id',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `sales_items`
--

CREATE TABLE `sales_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `sales_id` int(11) UNSIGNED NOT NULL,
  `item_id` int(11) UNSIGNED NOT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `total_price` decimal(15,2) GENERATED ALWAYS AS (`unit_price` * `quantity`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 테이블 구조 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
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

-- --------------------------------------------------------

--
-- 테이블 구조 `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Nombre del almacén o bodega',
  `address` varchar(255) DEFAULT NULL COMMENT 'Dirección física completa',
  `location_info` varchar(255) DEFAULT NULL COMMENT 'Referencias adicionales de ubicación',
  `contractor_entity_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Referencia a la empresa administradora',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Estado: 1=Activo, 0=Inactivo',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- 테이블의 인덱스 `entities`
--
ALTER TABLE `entities`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `inbounds`
--
ALTER TABLE `inbounds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_inbound_number` (`inbound_number`),
  ADD KEY `fk_inbound_warehouse` (`warehouse_id`),
  ADD KEY `fk_inbound_src_map_v2` (`source_type_id`),
  ADD KEY `fk_inbound_sts_map_v2` (`status_id`),
  ADD KEY `fk_inbounds_updated_by` (`updated_by`);

--
-- 테이블의 인덱스 `inbound_items`
--
ALTER TABLE `inbound_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inbound_items_master` (`inbound_id`),
  ADD KEY `fk_inbound_items_product` (`item_id`),
  ADD KEY `fk_inb_items_status_map_v4` (`item_status_id`);

--
-- 테이블의 인덱스 `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_warehouse_item_status` (`warehouse_id`,`item_id`,`stock_status`),
  ADD KEY `idx_inventory_item` (`item_id`),
  ADD KEY `idx_inventory_warehouse` (`warehouse_id`);

--
-- 테이블의 인덱스 `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_item` (`item_id`),
  ADD KEY `idx_log_warehouse` (`warehouse_id`);

--
-- 테이블의 인덱스 `mappings`
--
ALTER TABLE `mappings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`,`code_value`);

--
-- 테이블의 인덱스 `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `product_items`
--
ALTER TABLE `product_items`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_number` (`po_number`),
  ADD KEY `fk_po_user_approved` (`approved_by`),
  ADD KEY `fk_po_warehouse` (`warehouse_id`);

--
-- 테이블의 인덱스 `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_sales_number` (`sales_number`),
  ADD KEY `fk_sales_customer` (`customer_entity_id`),
  ADD KEY `fk_sales_warehouse` (`warehouse_id`),
  ADD KEY `fk_sales_status` (`status_id`),
  ADD KEY `fk_sales_created_by` (`created_by`),
  ADD KEY `fk_sales_updated_by` (`updated_by`);

--
-- 테이블의 인덱스 `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_items_master` (`sales_id`),
  ADD KEY `fk_sales_items_product` (`item_id`);

--
-- 테이블의 인덱스 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_warehouse_name_unique` (`name`),
  ADD KEY `fk_warehouses_entities` (`contractor_entity_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `inbounds`
--
ALTER TABLE `inbounds`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `inbound_items`
--
ALTER TABLE `inbound_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `mappings`
--
ALTER TABLE `mappings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `product_items`
--
ALTER TABLE `product_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `inbounds`
--
ALTER TABLE `inbounds`
  ADD CONSTRAINT `fk_inbound_src_map_v2` FOREIGN KEY (`source_type_id`) REFERENCES `mappings` (`id`),
  ADD CONSTRAINT `fk_inbound_sts_map_v2` FOREIGN KEY (`status_id`) REFERENCES `mappings` (`id`),
  ADD CONSTRAINT `fk_inbound_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `fk_inbounds_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- 테이블의 제약사항 `inbound_items`
--
ALTER TABLE `inbound_items`
  ADD CONSTRAINT `fk_inb_items_status_map_v4` FOREIGN KEY (`item_status_id`) REFERENCES `mappings` (`id`),
  ADD CONSTRAINT `fk_inbound_items_master` FOREIGN KEY (`inbound_id`) REFERENCES `inbounds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inbound_items_product` FOREIGN KEY (`item_id`) REFERENCES `product_items` (`id`);

--
-- 테이블의 제약사항 `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_item_ref` FOREIGN KEY (`item_id`) REFERENCES `product_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inventory_warehouse_ref` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `fk_po_user_approved` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- 테이블의 제약사항 `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_sales_customer` FOREIGN KEY (`customer_entity_id`) REFERENCES `entities` (`id`),
  ADD CONSTRAINT `fk_sales_status` FOREIGN KEY (`status_id`) REFERENCES `mappings` (`id`),
  ADD CONSTRAINT `fk_sales_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_sales_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- 테이블의 제약사항 `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `fk_sales_items_master` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sales_items_product` FOREIGN KEY (`item_id`) REFERENCES `product_items` (`id`);

--
-- 테이블의 제약사항 `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `fk_warehouses_entities` FOREIGN KEY (`contractor_entity_id`) REFERENCES `entities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
