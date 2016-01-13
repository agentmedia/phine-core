SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_page_url`
--

CREATE TABLE IF NOT EXISTS `pc_core_page_url` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Page` bigint(20) unsigned NOT NULL,
  `Fragment` varchar(128) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Page` (`Page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_page_url_parameter`
--

CREATE TABLE IF NOT EXISTS `pc_core_page_url_parameter` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `PageUrl` bigint(20) unsigned NOT NULL,
  `Previous` bigint(20) unsigned DEFAULT NULL,
  `Name` varchar(128) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Previous` (`Previous`),
  KEY `PageUrl` (`PageUrl`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;