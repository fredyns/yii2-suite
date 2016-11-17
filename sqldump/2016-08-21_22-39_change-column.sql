-- unsigned integer to double primary key/value range
-- also for integer column type consistency
--
-- execute this after installing dektrium/user & execute migration
--
-- Author: Fredy

ALTER TABLE `profile` 
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `social_account` 
CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `created_at` `created_at` INT(10) UNSIGNED NULL DEFAULT NULL ;

ALTER TABLE `token` 
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `created_at` `created_at` INT(10) UNSIGNED NOT NULL ;

ALTER TABLE `user` 
CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE COLUMN `created_at` `created_at` INT(10) UNSIGNED NOT NULL ,
CHANGE COLUMN `updated_at` `updated_at` INT(10) UNSIGNED NOT NULL ;

