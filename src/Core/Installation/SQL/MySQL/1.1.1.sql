

CREATE TABLE IF NOT EXISTS `pc_core_log_template` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ModuleType` varchar(255) NOT NULL,
  `Template` varchar(128) NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `ModuleType` (`ModuleType`),
  KEY `Template` (`Template`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
