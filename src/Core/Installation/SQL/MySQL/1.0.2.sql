
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_content_membergroup`
--

CREATE TABLE IF NOT EXISTS `pc_core_content_membergroup` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Content` bigint(20) unsigned NOT NULL,
  `MemberGroup` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Content` (`Content`),
  KEY `MemberGroup` (`MemberGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_member`
--

CREATE TABLE IF NOT EXISTS `pc_core_member` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `EMail` varchar(128) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Password` varchar(128) NOT NULL,
  `PasswordSalt` varchar(128) NOT NULL,
  `Created` datetime NOT NULL,
  `Confirmed` datetime DEFAULT NULL,
  `Active` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `EMail` (`EMail`),
  UNIQUE KEY `Name` (`Name`),
  KEY `Created` (`Created`),
  KEY `Confirmed` (`Confirmed`),
  KEY `Active` (`Active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_membergroup`
--

CREATE TABLE IF NOT EXISTS `pc_core_membergroup` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_member_membergroup`
--

CREATE TABLE IF NOT EXISTS `pc_core_member_membergroup` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Member` bigint(20) unsigned NOT NULL,
  `MemberGroup` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Member` (`Member`),
  KEY `MemberGroup` (`MemberGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_page_membergroup`
--

CREATE TABLE IF NOT EXISTS `pc_core_page_membergroup` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Page` bigint(20) unsigned NOT NULL,
  `MemberGroup` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Page` (`Page`),
  KEY `MemberGroup` (`MemberGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `pc_core_page` ADD `GuestsOnly` BOOLEAN NOT NULL DEFAULT FALSE ;
ALTER TABLE `pc_core_content` ADD `GuestsOnly` BOOLEAN NOT NULL DEFAULT FALSE ;
ALTER TABLE `pc_core_page` ADD `Publish` BOOLEAN NOT NULL DEFAULT FALSE , ADD `PublishFrom` DATETIME NULL , ADD `PublishTo` DATETIME NULL ;
ALTER TABLE `pc_core_content` ADD `Publish` BOOLEAN NOT NULL DEFAULT FALSE , ADD `PublishFrom` DATETIME NULL , ADD `PublishTo` DATETIME NULL ;
ALTER TABLE `pc_core_site` ADD `Language` BIGINT UNSIGNED NOT NULL AFTER `Url`, ADD INDEX (`Language`) ;