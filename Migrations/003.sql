CREATE TABLE IF NOT EXISTS `pages` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`url` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`controller` VARCHAR(200) NULL DEFAULT 'default' COLLATE 'utf8_general_ci',
	`path` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci',
	`render` VARCHAR(30) NULL DEFAULT 'standart' COLLATE 'utf8_general_ci',
	`visible` TINYINT(1) UNSIGNED NULL DEFAULT '1',
	`js` LONGTEXT NULL,
	`tpl` LONGTEXT NULL,
	`default` TINYINT(1) UNSIGNED NULL DEFAULT '0',
	`type` VARCHAR(50) NULL DEFAULT 'page',
  `template` VARCHAR(20) NULL DEFAULT 'template',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
;
