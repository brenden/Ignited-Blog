SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `datamapper` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `datamapper`;

-- -----------------------------------------------------
-- Table `datamapper`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `datamapper`.`users` ;

CREATE  TABLE IF NOT EXISTS `datamapper`.`users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(20) NOT NULL ,
  `password` VARCHAR(40) NOT NULL ,
  `salt` VARCHAR(32) NOT NULL ,
  `email` VARCHAR(120) NOT NULL ,
  `created` DATETIME NULL ,
  `updated` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX username (`username` ASC) ,
  UNIQUE INDEX email (`email` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `datamapper`.`groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `datamapper`.`groups` ;

CREATE  TABLE IF NOT EXISTS `datamapper`.`groups` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX name (`name` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `datamapper`.`groups_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `datamapper`.`groups_users` ;

CREATE  TABLE IF NOT EXISTS `datamapper`.`groups_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `group_id` BIGINT UNSIGNED NOT NULL ,
  `user_id` BIGINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX group_id (`group_id` ASC) ,
  INDEX user_id (`user_id` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `datamapper`.`employees`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `datamapper`.`employees` ;

CREATE  TABLE IF NOT EXISTS `datamapper`.`employees` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(40) NOT NULL ,
  `last_name` VARCHAR(40) NOT NULL ,
  `position` ENUM('Manager','Supervisor','Underling') NOT NULL DEFAULT 'Underling' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX name (`first_name` ASC, `last_name` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `datamapper`.`managers_supervisors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `datamapper`.`managers_supervisors` ;

CREATE  TABLE IF NOT EXISTS `datamapper`.`managers_supervisors` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `manager_id` BIGINT UNSIGNED NOT NULL ,
  `supervisor_id` BIGINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX manager_id (`manager_id` ASC) ,
  INDEX supervisor_id (`supervisor_id` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `datamapper`.`supervisors_underlings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `datamapper`.`supervisors_underlings` ;

CREATE  TABLE IF NOT EXISTS `datamapper`.`supervisors_underlings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `supervisor_id` BIGINT UNSIGNED NOT NULL ,
  `underling_id` BIGINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX supervisor_id (`supervisor_id` ASC) ,
  INDEX underling_id (`underling_id` ASC) )
ENGINE = MyISAM;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
