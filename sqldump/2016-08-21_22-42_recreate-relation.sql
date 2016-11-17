-- MySQL Workbench Synchronization
-- Generated: 2016-08-21 22:42
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Fredy

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `yii2playground`.`profile` 
ADD INDEX `user_id` (`user_id` ASC);

ALTER TABLE `yii2playground`.`social_account` 
ADD INDEX `user_id` (`user_id` ASC);

ALTER TABLE `yii2playground`.`token` 
ADD INDEX `user_id` (`user_id` ASC);

ALTER TABLE `yii2playground`.`profile` 
ADD CONSTRAINT `fk_profile_user`
  FOREIGN KEY (`user_id`)
  REFERENCES `yii2playground`.`user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `yii2playground`.`social_account` 
ADD CONSTRAINT `fk_social_account_user`
  FOREIGN KEY (`user_id`)
  REFERENCES `yii2playground`.`user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `yii2playground`.`token` 
ADD CONSTRAINT `fk_token_user`
  FOREIGN KEY (`user_id`)
  REFERENCES `yii2playground`.`user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
