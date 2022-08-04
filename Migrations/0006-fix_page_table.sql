ALTER TABLE `pages`
	CHANGE COLUMN `template` `template` VARCHAR(20) NULL COLLATE 'utf8_general_ci' AFTER `type`,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`id`) USING BTREE,
	ADD CONSTRAINT `FK_pages_template` FOREIGN KEY (`template`) REFERENCES `template` (`group`) ON UPDATE CASCADE ON DELETE CASCADE,
	ADD CONSTRAINT `FK_pages_modules` FOREIGN KEY (`module`) REFERENCES `modules` (`module_name`) ON UPDATE CASCADE ON DELETE CASCADE;
