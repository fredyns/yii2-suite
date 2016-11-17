
ALTER TABLE `yii2playground`.`profile`
DROP FOREIGN KEY `fk_user_profile`;

ALTER TABLE `yii2playground`.`social_account`
DROP FOREIGN KEY `fk_user_account`;

ALTER TABLE `yii2playground`.`token`
DROP FOREIGN KEY `fk_user_token`;

ALTER TABLE `yii2playground`.`social_account`
DROP INDEX `fk_user_account` ;

ALTER TABLE `yii2playground`.`token`
DROP INDEX `token_unique` ;
