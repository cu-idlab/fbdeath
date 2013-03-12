SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `fbdeath` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `fbdeath` ;

-- -----------------------------------------------------
-- Table `fbdeath`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fbdeath`.`user` ;

CREATE  TABLE IF NOT EXISTS `fbdeath`.`user` (
  `id` INT NOT NULL ,
  `fb_user_id` INT NULL ,
  `fb_token` VARCHAR(256) NULL ,
  `fb_token_exp` DATETIME NULL ,
  `fb_last_update` DATETIME NULL ,
  `added` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fbdeath`.`profile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fbdeath`.`profile` ;

CREATE  TABLE IF NOT EXISTS `fbdeath`.`profile` (
  `id` INT NOT NULL ,
  `user_id` VARCHAR(45) NULL ,
  `attr_name` VARCHAR(45) NULL ,
  `attr_value` VARCHAR(45) NULL ,
  `added` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fbdeath`.`delegation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fbdeath`.`delegation` ;

CREATE  TABLE IF NOT EXISTS `fbdeath`.`delegation` (
  `id` INT NOT NULL ,
  `provider_user_id` INT NULL ,
  `recipient_user_id` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fbdeath`.`fb_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fbdeath`.`fb_user` ;

CREATE  TABLE IF NOT EXISTS `fbdeath`.`fb_user` (
  `id` INT NOT NULL ,
  `fb_id` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `fbdeath`.`relationship`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fbdeath`.`relationship` ;

CREATE  TABLE IF NOT EXISTS `fbdeath`.`relationship` (
  `id` INT NOT NULL ,
  `fb_user_id1` INT NULL ,
  `fb_user_id2` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
