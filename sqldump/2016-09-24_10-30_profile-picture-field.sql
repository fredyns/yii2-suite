-- MySQL Workbench Synchronization
-- Generated: 2016-09-24 10:29
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Fredy

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `yii2playground`.`profile` 
ADD COLUMN `picture_id` BIGINT(19) UNSIGNED NULL DEFAULT NULL AFTER `timezone`,
ADD INDEX `picture_id` (`picture_id` ASC);

ALTER TABLE `yii2playground`.`profile` 
ADD CONSTRAINT `fk_profile_picture`
  FOREIGN KEY (`picture_id`)
  REFERENCES `yii2playground`.`uploaded_file` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
