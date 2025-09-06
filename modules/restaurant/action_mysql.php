<?php

// if (!defined('NV_IS_FILE_SQL')) {
//     die('Stop!!!');
// }

$sql_create_module = [];

/* Bảng bàn ăn */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . NV_PREFIXLANG . "_" . $module_data . "_tables` (
  `table_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` enum('trong','dat_truoc','dang_su_dung','bao_tri') DEFAULT 'trong',
  `location` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Bảng danh mục món ăn */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . NV_PREFIXLANG . "_" . $module_data . "_menu_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Bảng món ăn */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . NV_PREFIXLANG . "_" . $module_data . "_menu_items` (
  `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('con','het') DEFAULT 'con',
  PRIMARY KEY (`item_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Bảng đặt bàn */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . NV_PREFIXLANG . "_" . $module_data . "_reservations` (
  `reservation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `status` enum('cho_duyet','da_duyet','da_huy','hoan_thanh') DEFAULT 'cho_duyet',
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `table_id` (`table_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Bảng đơn hàng */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . NV_PREFIXLANG . "_" . $module_data . "_orders` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` int(11) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT 0,
  `payment_status` enum('cho_xac_nhan','da_thanh_toan','huy') DEFAULT 'cho_xac_nhan',
  PRIMARY KEY (`order_id`),
  KEY `reservation_id` (`reservation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

/* Bảng chi tiết món trong đơn hàng */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . NV_PREFIXLANG . "_" . $module_data . "_order_items` (
  `order_item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
