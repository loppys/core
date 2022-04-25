CREATE TABLE IF NOT EXISTS `migration` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`file` VARCHAR(60) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`completed` VARCHAR(1) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`fail` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`query` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`date` TIMESTAMP NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
;
