CREATE TABLE `cars` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`make` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`model` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`horsepower` INT(10) UNSIGNED NOT NULL,
	`released` DATE NOT NULL,
	`modified` DATETIME NOT NULL,
	`created` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB;