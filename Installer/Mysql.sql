CREATE DATABASE `apit.Movies` /*!40100 DEFAULT CHARACTER SET big5 */;

CREATE TABLE `apit.Movies`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(45) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `Name_UNIQUE` (`login` ASC));


CREATE TABLE `apit.Movies`.`movies` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(1000) NOT NULL,
  `date` DATETIME NULL,
  `directorname` VARCHAR(45) NULL,
  `duration` INT NULL,
  `writername` VARCHAR(45) NULL,
  `stars` INT NULL,
  `actors` VARCHAR(1000) NULL,

  PRIMARY KEY (`id`));

