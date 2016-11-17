--
-- removing all relationship before altering columns
--
-- execute this after installing dektrium/user & execute migration
--

ALTER TABLE `profile`
DROP FOREIGN KEY `fk_user_profile`;

ALTER TABLE `social_account`
DROP FOREIGN KEY `fk_user_account`;

ALTER TABLE `token`
DROP FOREIGN KEY `fk_user_token`;

ALTER TABLE `social_account`
DROP INDEX `fk_user_account` ;

ALTER TABLE `token`
DROP INDEX `token_unique` ;
