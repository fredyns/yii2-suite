-- adding profile picture attribute in profile data
-- profile picture using mdmsoft/upload-file
--
-- Author: Fredy

ALTER TABLE `profile` 
ADD COLUMN `picture_id` BIGINT(19) UNSIGNED NULL DEFAULT NULL AFTER `timezone`,
ADD INDEX `picture_id` (`picture_id` ASC);

ALTER TABLE `profile` 
ADD CONSTRAINT `fk_profile_picture`
  FOREIGN KEY (`picture_id`)
  REFERENCES `uploaded_file` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
