ALTER TABLE `pc_core_page` ADD `Type` VARCHAR(32) NOT NULL DEFAULT 'Normal' AFTER `Name`, ADD INDEX (`Type`) ;
ALTER TABLE `pc_core_page` ADD `RedirectTarget` BIGINT UNSIGNED NULL AFTER `Type`, ADD UNIQUE (`RedirectTarget`) ;


