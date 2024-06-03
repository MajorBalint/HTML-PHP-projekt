DROP SCHEMA IF EXISTS `mydb` ;

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Users` (
  `idUsers` INT NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(500) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `status` INT NOT NULL,
  PRIMARY KEY (`idUsers`));

CREATE TABLE IF NOT EXISTS `mydb`.`Engine` (
  `idEngine` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  `size` INT NULL,
  `consumption` FLOAT NULL,
  `horsepower` INT NOT NULL,
  `driving_range` INT NULL,
  `price` INT NULL,
  PRIMARY KEY (`idEngine`));

CREATE TABLE IF NOT EXISTS `mydb`.`Color` (
  `idColor` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `metallic` INT NOT NULL,
  `price` INT NOT NULL,
  PRIMARY KEY (`idColor`));

CREATE TABLE IF NOT EXISTS `mydb`.`Cars` (
  `idCars` INT NOT NULL AUTO_INCREMENT,
  `model` VARCHAR(45) NOT NULL,
  `price` INT NULL,
  `model_year` INT NOT NULL,
  `brand` VARCHAR(45) NOT NULL,
  `Engine_idEngine` INT NULL,
  `Color_idColor` INT NULL,
  `User_idUsers` INT NULL,
  PRIMARY KEY (`idCars`, `Engine_idEngine`, `Color_idColor`),
  INDEX `fk_Cars_Engine1_idx` (`Engine_idEngine` ASC),
  INDEX `fk_Cars_Color1_idx` (`Color_idColor` ASC));


CREATE TABLE IF NOT EXISTS `mydb`.`Extra` (
  `idExtra` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  `price` INT NULL,
  `weight` VARCHAR(45) NULL,
  `description` VARCHAR(100) NULL,
  PRIMARY KEY (`idExtra`));

CREATE TABLE IF NOT EXISTS `mydb`.`Cars_has_Extra` (
  `Cars_idCars` INT NULL,
  `Extra_idExtra` INT NULL,
  PRIMARY KEY (`Cars_idCars`, `Extra_idExtra`),
  INDEX `fk_Cars_has_Extra_Extra1_idx` (`Extra_idExtra` ASC),
  INDEX `fk_Cars_has_Extra_Cars1_idx` (`Cars_idCars` ASC),
    FOREIGN KEY (`Cars_idCars`)
    REFERENCES `mydb`.`Cars` (`idCars`) ON DELETE CASCADE,
    FOREIGN KEY (`Extra_idExtra`)
    REFERENCES `mydb`.`Extra` (`idExtra`)ON DELETE CASCADE);