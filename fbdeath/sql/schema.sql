SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`user` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`user` (
  `id` INT NOT NULL ,
  `fb_user_id` INT NULL ,
  `fb_token` VARCHAR(256) NULL ,
  `fb_token_exp` DATETIME NULL ,
  `fb_last_update` DATETIME NULL ,
  `added` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`profile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`profile` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`profile` (
  `id` INT NOT NULL ,
  `user_id` VARCHAR(45) NULL ,
  `attr_name` VARCHAR(45) NULL ,
  `attr_value` VARCHAR(45) NULL ,
  `added` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`delegation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`delegation` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`delegation` (
  `id` INT NOT NULL ,
  `provider_user_id` INT NULL ,
  `recipient_user_id` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`fb_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`fb_user` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`fb_user` (
  `id` INT NOT NULL ,
  `fb_id` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`relationship`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`relationship` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`relationship` (
  `id` INT NOT NULL ,
  `fb_user_id1` INT NULL ,
  `fb_user_id2` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
