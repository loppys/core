CREATE TABLE `modules` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`module_name` VARCHAR(50) NULL DEFAULT NULL,
	`handler` MEDIUMTEXT NULL,
	`module_param` LONGTEXT NULL,
	`module_type` ENUM('Global','Local','System','Empty') NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `module_name` (`module_name`)
)
COLLATE='utf8_general_ci'
;
