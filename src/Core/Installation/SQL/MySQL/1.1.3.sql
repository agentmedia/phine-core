CREATE TABLE IF NOT EXISTS `pc_core_log_container` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Container` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `Container` (`Container`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

