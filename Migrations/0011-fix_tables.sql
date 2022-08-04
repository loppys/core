ALTER TABLE `pages`
	CHANGE COLUMN `module` `module` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_general_ci';

ALTER TABLE `pages`
	ADD INDEX `module` (`module`);
