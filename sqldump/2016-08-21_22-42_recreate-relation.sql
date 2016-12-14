--
-- restore previous relationship
--
-- execute this after installing dektrium/user & execute migration
--
-- Author: Fredy

ALTER TABLE `profile` 
ADD INDEX `user_id` (`user_id` ASC);

ALTER TABLE `social_account` 
ADD INDEX `user_id` (`user_id` ASC);

ALTER TABLE `token` 
ADD INDEX `user_id` (`user_id` ASC);

ALTER TABLE `profile` 
ADD CONSTRAINT `fk_profile_user`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `social_account` 
ADD CONSTRAINT `fk_social_account_user`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `token` 
ADD CONSTRAINT `fk_token_user`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
