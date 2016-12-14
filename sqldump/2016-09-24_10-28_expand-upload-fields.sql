-- unsigned big integer to expand primary key/value range
-- also for integer column type consistency
--
-- execute this after installing mdmsoft/upload-file & execute migration
--
-- Author: Fredy

ALTER TABLE `uploaded_file` 
CHANGE COLUMN `id` `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE COLUMN `filename` `filename` TEXT NULL DEFAULT NULL ;
