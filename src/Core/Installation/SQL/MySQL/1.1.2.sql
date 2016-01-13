CREATE TABLE IF NOT EXISTS `pc_core_log_usergroup` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UserGroup` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `UserGroup` (`UserGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

