
CREATE TABLE IF NOT EXISTS `pc_core_settings` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `LogLifetime` int(10) unsigned NOT NULL,
  `MailFromEMail` varchar(255) NOT NULL,
  `MailFromName` varchar(128) NOT NULL,
  `SmtpHost` varchar(255) NOT NULL,
  `SmtpPort` varchar(32) NOT NULL,
  `SmtpUser` varchar(128) NOT NULL,
  `SmtpPassword` varchar(128) NOT NULL,
  `SmtpSecurity` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
