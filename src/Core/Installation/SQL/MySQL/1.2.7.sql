ALTER TABLE `pc_core_settings` ADD `ChangeRequestLifetime` INT UNSIGNED NOT NULL AFTER `LogLifetime`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_member_change_request`
--

CREATE TABLE IF NOT EXISTS `pc_core_member_change_request` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Member` bigint(20) unsigned NOT NULL,
  `Purpose` varchar(32) NOT NULL COMMENT 'NewEMail or NewPassword',
  `NewValue` varchar(128) NOT NULL,
  `MailSalt` varchar(32) NOT NULL,
  `MailSent` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Purpose` (`Purpose`),
  KEY `Member` (`Member`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

