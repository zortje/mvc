DROP TABLE IF EXISTS `cars`;
CREATE TABLE `cars` (
	`uuid` CHAR(36) NOT NULL,
	`make` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`model` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`horsepower` INT(10) UNSIGNED NOT NULL,
	`doors` ENUM('TWO', 'FOUR') NOT NULL,
	`released` DATE NOT NULL,
	`modified` DATETIME NOT NULL,
	`created` DATETIME NOT NULL,
	PRIMARY KEY (`uuid`)
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB;
