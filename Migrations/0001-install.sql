CREATE TABLE IF NOT EXISTS `cfg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cfg_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cfg_value` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(50) DEFAULT NULL,
  `handler` mediumtext DEFAULT NULL,
  `module_param` longtext DEFAULT NULL,
  `module_type` enum('Global','Local','System','Empty') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module_name` (`module_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(500) DEFAULT NULL,
  `request_method` varchar(25) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `method` varchar(255) NOT NULL,
  `access` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
