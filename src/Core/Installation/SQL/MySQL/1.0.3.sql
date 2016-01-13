CREATE TABLE IF NOT EXISTS `pc_core_content_wording` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Content` bigint(20) unsigned NOT NULL,
  `Placeholder` varchar(255) NOT NULL,
  `Text` mediumtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Content` (`Content`),
  KEY `Placeholder` (`Placeholder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
