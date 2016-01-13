
CREATE TABLE IF NOT EXISTS `pc_core_log_item` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `User` bigint(20) unsigned DEFAULT NULL,
  `Action` varchar(128) NOT NULL,
  `ObjectType` varchar(128) NOT NULL,
  `Changed` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `User` (`User`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `pc_core_log_user` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `User` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `User` (`User`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pc_core_log_page` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Page` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `Page` (`Page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `pc_core_log_site` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Site` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `Site` (`Site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `pc_core_log_layout` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Layout` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `Layout` (`Layout`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `pc_core_log_area` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Area` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `Area` (`Area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pc_core_log_content` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Content` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `Content` (`Content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pc_core_log_member` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Member` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `Member` (`Member`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pc_core_log_membergroup` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `MemberGroup` bigint(20) unsigned NOT NULL,
  `LogItem` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LogItem` (`LogItem`),
  KEY `MemberGroup` (`MemberGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
