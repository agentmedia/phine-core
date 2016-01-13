-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 05. Mai 2015 um 08:54
-- Server Version: 5.6.16
-- PHP-Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_area`
--

CREATE TABLE IF NOT EXISTS `pc_core_area` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  `Layout` bigint(20) unsigned NOT NULL,
  `Previous` bigint(20) unsigned DEFAULT NULL,
  `Locked` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Previous` (`Previous`),
  KEY `Layout` (`Layout`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_backend_container_rights`
--

CREATE TABLE IF NOT EXISTS `pc_core_backend_container_rights` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Edit` tinyint(1) NOT NULL,
  `Remove` tinyint(1) NOT NULL,
  `ContentRights` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ContentRights` (`ContentRights`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_backend_content_rights`
--

CREATE TABLE IF NOT EXISTS `pc_core_backend_content_rights` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Edit` tinyint(1) NOT NULL,
  `Move` tinyint(1) NOT NULL,
  `Remove` tinyint(1) NOT NULL,
  `CreateIn` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_backend_layout_rights`
--

CREATE TABLE IF NOT EXISTS `pc_core_backend_layout_rights` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Edit` tinyint(1) NOT NULL,
  `Remove` tinyint(1) NOT NULL,
  `ContentRights` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ContentRights` (`ContentRights`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_backend_page_rights`
--

CREATE TABLE IF NOT EXISTS `pc_core_backend_page_rights` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Edit` tinyint(1) NOT NULL,
  `Move` tinyint(1) NOT NULL,
  `Remove` tinyint(1) NOT NULL,
  `CreateIn` tinyint(1) NOT NULL,
  `ContentRights` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ContentRights` (`ContentRights`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_backend_site_rights`
--

CREATE TABLE IF NOT EXISTS `pc_core_backend_site_rights` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Edit` tinyint(1) NOT NULL,
  `Remove` tinyint(1) NOT NULL,
  `PageRights` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `PageRights` (`PageRights`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_container`
--

CREATE TABLE IF NOT EXISTS `pc_core_container` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  `User` bigint(20) unsigned DEFAULT NULL,
  `UserRights` bigint(20) unsigned DEFAULT NULL,
  `UserGroup` bigint(20) unsigned DEFAULT NULL,
  `UserGroupRights` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `UserRights` (`UserRights`),
  UNIQUE KEY `UserGroupRights` (`UserGroupRights`),
  KEY `User` (`User`),
  KEY `UserGroup` (`UserGroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_container_content`
--

CREATE TABLE IF NOT EXISTS `pc_core_container_content` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Container` bigint(20) unsigned NOT NULL,
  `Content` bigint(20) unsigned NOT NULL,
  `Parent` bigint(20) unsigned DEFAULT NULL,
  `Previous` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Previous` (`Previous`),
  KEY `Content` (`Content`),
  KEY `Container` (`Container`),
  KEY `Parent` (`Parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_content`
--

CREATE TABLE IF NOT EXISTS `pc_core_content` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) NOT NULL,
  `Template` varchar(128) NOT NULL,
  `CssClass` varchar(128) NOT NULL,
  `CssID` varchar(128) NOT NULL,
  `LayoutContent` bigint(20) unsigned DEFAULT NULL COMMENT 'internal use (for data clearance)',
  `PageContent` bigint(20) unsigned DEFAULT NULL COMMENT 'internal use (for data clearance)',
  `ContainerContent` bigint(20) unsigned DEFAULT NULL COMMENT 'internal use (for data clearance)',
  `User` bigint(20) unsigned DEFAULT NULL,
  `UserRights` bigint(20) unsigned DEFAULT NULL,
  `UserGroup` bigint(20) unsigned DEFAULT NULL,
  `UserGroupRights` bigint(20) unsigned DEFAULT NULL,
  `CacheLifetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'cache litetime; in seconds',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `LayoutContent` (`LayoutContent`),
  UNIQUE KEY `PageContent` (`PageContent`),
  UNIQUE KEY `ContainerContent` (`ContainerContent`),
  UNIQUE KEY `UserRights` (`UserRights`),
  UNIQUE KEY `UserGroupRights` (`UserGroupRights`),
  KEY `CssID` (`CssID`),
  KEY `User` (`User`),
  KEY `UserGroup` (`UserGroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_installed_bundle`
--

CREATE TABLE IF NOT EXISTS `pc_core_installed_bundle` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Bundle` varchar(128) NOT NULL,
  `Version` varchar(64) NOT NULL,
  `LastUpdate` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Bundle` (`Bundle`),
  KEY `Version` (`Version`),
  KEY `LastUpdate` (`LastUpdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_language`
--

CREATE TABLE IF NOT EXISTS `pc_core_language` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Code` varchar(5) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Parent` bigint(20) unsigned DEFAULT NULL,
  `IsDefault` tinyint(1) DEFAULT NULL,
  `IsBackendTranslated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Code` (`Code`),
  UNIQUE KEY `IsDefault` (`IsDefault`),
  KEY `Parent` (`Parent`),
  KEY `IsBackendTranslated` (`IsBackendTranslated`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_layout`
--

CREATE TABLE IF NOT EXISTS `pc_core_layout` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  `User` bigint(20) unsigned DEFAULT NULL,
  `UserRights` bigint(20) unsigned DEFAULT NULL,
  `UserGroup` bigint(20) unsigned DEFAULT NULL,
  `UserGroupRights` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `UserRights` (`UserRights`),
  UNIQUE KEY `UserGroupRights` (`UserGroupRights`),
  KEY `User` (`User`),
  KEY `UserGroup` (`UserGroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_layout_content`
--

CREATE TABLE IF NOT EXISTS `pc_core_layout_content` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Area` bigint(20) unsigned NOT NULL,
  `Content` bigint(20) unsigned NOT NULL,
  `Parent` bigint(20) unsigned DEFAULT NULL,
  `Previous` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Previous` (`Previous`),
  KEY `Area` (`Area`),
  KEY `Content` (`Content`),
  KEY `Parent` (`Parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_module_lock`
--

CREATE TABLE IF NOT EXISTS `pc_core_module_lock` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UserGroup` bigint(20) unsigned NOT NULL,
  `Bundle` varchar(128) NOT NULL,
  `Module` varchar(128) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `UserGroup` (`UserGroup`),
  KEY `Bundle` (`Bundle`),
  KEY `Module` (`Module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_page`
--

CREATE TABLE IF NOT EXISTS `pc_core_page` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Site` bigint(20) unsigned NOT NULL,
  `Layout` bigint(20) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Url` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `MenuAccess` varchar(32) NOT NULL DEFAULT 'Authorized',
  `Keywords` text NOT NULL,
  `Parent` bigint(20) unsigned DEFAULT NULL,
  `Previous` bigint(20) unsigned DEFAULT NULL,
  `User` bigint(20) unsigned DEFAULT NULL,
  `UserRights` bigint(20) unsigned DEFAULT NULL,
  `UserGroup` bigint(20) unsigned DEFAULT NULL,
  `UserGroupRights` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Previous` (`Previous`),
  UNIQUE KEY `UserRights` (`UserRights`),
  UNIQUE KEY `UserGroupRights` (`UserGroupRights`),
  KEY `Site` (`Site`),
  KEY `Layout` (`Layout`),
  KEY `Parent` (`Parent`),
  KEY `MenuAccess` (`MenuAccess`),
  KEY `User` (`User`),
  KEY `UserGroup` (`UserGroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_page_content`
--

CREATE TABLE IF NOT EXISTS `pc_core_page_content` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Page` bigint(20) unsigned NOT NULL,
  `Area` bigint(20) unsigned NOT NULL,
  `Content` bigint(20) unsigned NOT NULL,
  `Parent` bigint(20) unsigned DEFAULT NULL,
  `Previous` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Previous` (`Previous`),
  KEY `Page` (`Page`),
  KEY `Area` (`Area`),
  KEY `Content` (`Content`),
  KEY `Parent` (`Parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_site`
--

CREATE TABLE IF NOT EXISTS `pc_core_site` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(128) NOT NULL,
  `Url` varchar(255) NOT NULL,
  `User` bigint(20) unsigned DEFAULT NULL,
  `UserRights` bigint(20) unsigned DEFAULT NULL,
  `UserGroup` bigint(20) unsigned DEFAULT NULL,
  `UserGroupRights` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UserRights` (`UserRights`),
  UNIQUE KEY `GroupRights` (`UserGroupRights`),
  KEY `User` (`User`),
  KEY `Group` (`UserGroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_user`
--

CREATE TABLE IF NOT EXISTS `pc_core_user` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `EMail` varchar(128) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Password` varchar(128) NOT NULL,
  `PasswordSalt` varchar(128) NOT NULL,
  `FirstName` varchar(128) NOT NULL,
  `LastName` varchar(128) NOT NULL,
  `Language` bigint(20) unsigned NOT NULL,
  `IsAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`),
  KEY `IsAdmin` (`IsAdmin`),
  KEY `Language` (`Language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_usergroup`
--

CREATE TABLE IF NOT EXISTS `pc_core_usergroup` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  `CreateSites` tinyint(1) NOT NULL DEFAULT '0',
  `CreateLayouts` tinyint(1) NOT NULL DEFAULT '0',
  `CreateContainers` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_usergroup_site`
--

CREATE TABLE IF NOT EXISTS `pc_core_usergroup_site` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UserGroup` bigint(20) unsigned NOT NULL,
  `Site` bigint(20) unsigned DEFAULT NULL COMMENT 'If null, all sites are allowed',
  PRIMARY KEY (`ID`),
  KEY `Group` (`UserGroup`),
  KEY `Site` (`Site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pc_core_user_usergroup`
--

CREATE TABLE IF NOT EXISTS `pc_core_user_usergroup` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `User` bigint(20) unsigned NOT NULL,
  `UserGroup` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `User` (`User`),
  KEY `UserGroup` (`UserGroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `pc_core_language` (`ID`, `Code`, `Name`, `Parent`, `IsDefault`, `IsBackendTranslated`) VALUES
(1, 'de', 'Deutsch', NULL, NULL, 1),
(2, 'en', 'English', NULL, 1, 1),
(3, 'aa', 'Afaraf', NULL, NULL, 0),
(4, 'ab', 'аҧсуа бызшәа, аҧсшәа', NULL, NULL, 0),
(5, 'ae', 'avesta', NULL, NULL, 0),
(6, 'af', 'Afrikaans', NULL, NULL, 0),
(7, 'ak', 'Akan', NULL, NULL, 0),
(8, 'am', 'አማርኛ', NULL, NULL, 0),
(9, 'an', 'aragonés', NULL, NULL, 0),
(10, 'ar', 'العربية ', NULL, NULL, 0),
(11, 'av', 'авар мацӀ, магӀарул мацӀ', NULL, NULL, 0),
(12, 'ay', 'aymar aru', NULL, NULL, 0),
(13, 'az', 'azərbaycan dili', NULL, NULL, 0),
(14, 'ba', 'башҡорт теле', NULL, NULL, 0),
(15, 'be', 'беларуская мова', NULL, NULL, 0),
(16, 'bg', 'български език', NULL, NULL, 0),
(17, 'bh', 'भोजपुरी', NULL, NULL, 0),
(18, 'bi', 'Bislama', NULL, NULL, 0),
(19, 'bm', 'bamanankan', NULL, NULL, 0),
(20, 'br', 'brezhoneg', NULL, NULL, 0),
(21, 'bs', 'bosanski jezik', NULL, NULL, 0),
(22, 'ca', 'català', NULL, NULL, 0),
(23, 'ce', 'нохчийн мотт', NULL, NULL, 0),
(24, 'ch', 'Chamoru', NULL, NULL, 0),
(25, 'co', 'corsu, lingua corsa', NULL, NULL, 0),
(26, 'cr', 'ᓀᐦᐃᔭᐍᐏᐣ', NULL, NULL, 0),
(27, 'cs', 'čeština, český jazyk', NULL, NULL, 0),
(28, 'cu', 'ѩзыкъ словѣньскъ', NULL, NULL, 0),
(29, 'cv', 'чӑваш чӗлхи', NULL, NULL, 0),
(30, 'cy', 'Cymraeg', NULL, NULL, 0),
(31, 'da', 'dansk', NULL, NULL, 0),
(32, 'ee', 'Eʋegbe', NULL, NULL, 0),
(33, 'el', 'ελληνικά', NULL, NULL, 0),
(34, 'eo', 'Esperanto', NULL, NULL, 0),
(35, 'es', 'español', NULL, NULL, 0),
(36, 'et', 'eesti, eesti keel', NULL, NULL, 0),
(37, 'eu', 'euskara, euskera', NULL, NULL, 0),
(38, 'fa', 'فارسی ', NULL, NULL, 0),
(39, 'ff', 'Fulfulde, Pulaar, Pular', NULL, NULL, 0),
(40, 'fi', 'suomi, suomen kieli', NULL, NULL, 0),
(41, 'fj', 'vosa Vakaviti', NULL, NULL, 0),
(42, 'fo', 'føroyskt', NULL, NULL, 0),
(43, 'fr', 'français, langue française', NULL, NULL, 0),
(44, 'fy', 'Frysk', NULL, NULL, 0),
(45, 'ga', 'Gaeilge', NULL, NULL, 0),
(46, 'gd', 'Gàidhlig', NULL, NULL, 0),
(47, 'gl', 'galego', NULL, NULL, 0),
(48, 'gn', 'Avañe''ẽ', NULL, NULL, 0),
(49, 'gv', 'Gaelg, Gailck', NULL, NULL, 0),
(50, 'ha', '(Hausa) هَوُسَ ', NULL, NULL, 0),
(51, 'he', 'עברית ', NULL, NULL, 0),
(52, 'hi', 'हिन्दी, हिंदी', NULL, NULL, 0),
(53, 'ho', 'Hiri Motu', NULL, NULL, 0),
(54, 'hr', 'hrvatski jezik', NULL, NULL, 0),
(55, 'ht', 'Kreyòl ayisyen', NULL, NULL, 0),
(56, 'hu', 'magyar', NULL, NULL, 0),
(57, 'hy', 'Հայերեն', NULL, NULL, 0),
(58, 'hz', 'Otjiherero', NULL, NULL, 0),
(59, 'ia', 'Interlingua', NULL, NULL, 0),
(60, 'id', 'Bahasa Indonesia', NULL, NULL, 0),
(61, 'ig', 'Asụsụ Igbo', NULL, NULL, 0),
(62, 'ii', 'ꆈꌠ꒿ Nuosuhxop', NULL, NULL, 0),
(63, 'ik', 'Iñupiaq, Iñupiatun', NULL, NULL, 0),
(64, 'io', 'Ido', NULL, NULL, 0),
(65, 'is', 'Íslenska', NULL, NULL, 0),
(66, 'it', 'italiano', NULL, NULL, 0),
(67, 'iu', 'ᐃᓄᒃᑎᑐᑦ', NULL, NULL, 0),
(68, 'ja', '日本語 (にほんご)', NULL, NULL, 0),
(69, 'jv', 'basa Jawa', NULL, NULL, 0),
(70, 'ka', 'ქართული', NULL, NULL, 0),
(71, 'kg', 'Kikongo', NULL, NULL, 0),
(72, 'ki', 'Gĩkũyũ', NULL, NULL, 0),
(73, 'kj', 'Kuanyama', NULL, NULL, 0),
(74, 'kk', 'қазақ тілі', NULL, NULL, 0),
(75, 'kl', 'kalaallisut, kalaallit oqaasii', NULL, NULL, 0),
(76, 'ko', '한국어, 조선어', NULL, NULL, 0),
(77, 'kr', 'Kanuri', NULL, NULL, 0),
(78, 'ks', 'कश्मीरी, كشميري‎', NULL, NULL, 0),
(79, 'ku', 'Kurdî, كوردی‎', NULL, NULL, 0),
(80, 'kv', 'коми кыв', NULL, NULL, 0),
(81, 'kw', 'Kernewek', NULL, NULL, 0),
(82, 'ky', 'Кыргызча, Кыргыз тили', NULL, NULL, 0),
(83, 'la', 'latine, lingua latina', NULL, NULL, 0),
(84, 'lb', 'Lëtzebuergesch', NULL, NULL, 0),
(85, 'lg', 'Luganda', NULL, NULL, 0),
(86, 'li', 'Limburgs', NULL, NULL, 0),
(87, 'ln', 'Lingála', NULL, NULL, 0),
(88, 'lo', 'ພາສາລາວ', NULL, NULL, 0),
(89, 'lt', 'lietuvių kalba', NULL, NULL, 0),
(90, 'lu', 'Tshiluba', NULL, NULL, 0),
(91, 'lv', 'latviešu valoda', NULL, NULL, 0),
(92, 'mg', 'fiteny malagasy', NULL, NULL, 0),
(93, 'mh', 'Kajin M̧ajeļ', NULL, NULL, 0),
(94, 'mi', 'te reo Māori', NULL, NULL, 0),
(95, 'mk', 'македонски јазик', NULL, NULL, 0),
(96, 'mn', 'монгол', NULL, NULL, 0),
(97, 'mr', 'मराठी', NULL, NULL, 0),
(98, 'ms', 'bahasa Melayu, بهاس ملايو‎', NULL, NULL, 0),
(99, 'mt', 'Malti', NULL, NULL, 0),
(100, 'my', 'ဗမာစာ', NULL, NULL, 0),
(101, 'na', 'Ekakairũ Naoero', NULL, NULL, 0),
(102, 'nb', 'Norsk bokmål', NULL, NULL, 0),
(103, 'nd', 'isiNdebele', NULL, NULL, 0),
(104, 'ne', 'नेपाली', NULL, NULL, 0),
(105, 'ng', 'Owambo', NULL, NULL, 0),
(106, 'nl', 'Nederlands, Vlaams', NULL, NULL, 0),
(107, 'nn', 'Norsk nynorsk', NULL, NULL, 0),
(108, 'no', 'Norsk', NULL, NULL, 0),
(109, 'nr', 'isiNdebele', NULL, NULL, 0),
(110, 'nv', 'Diné bizaad', NULL, NULL, 0),
(111, 'ny', 'chiCheŵa, chinyanja', NULL, NULL, 0),
(112, 'oc', 'occitan, lenga d''òc', NULL, NULL, 0),
(113, 'oj', 'ᐊᓂᔑᓈᐯᒧᐎᓐ', NULL, NULL, 0),
(114, 'om', 'Afaan Oromoo', NULL, NULL, 0),
(115, 'os', 'ирон æвзаг', NULL, NULL, 0),
(116, 'pi', 'पाऴि', NULL, NULL, 0),
(117, 'pl', 'język polski, polszczyzna', NULL, NULL, 0),
(118, 'ps', 'پښتو ', NULL, NULL, 0),
(119, 'pt', 'português', NULL, NULL, 0),
(120, 'qu', 'Runa Simi, Kichwa', NULL, NULL, 0),
(121, 'rm', 'rumantsch grischun', NULL, NULL, 0),
(122, 'rn', 'Ikirundi', NULL, NULL, 0),
(123, 'ro', 'limba română', NULL, NULL, 0),
(124, 'ru', 'Русский', NULL, NULL, 0),
(125, 'rw', 'Ikinyarwanda', NULL, NULL, 0),
(126, 'sa', 'संस्कृतम्', NULL, NULL, 0),
(127, 'sc', 'sardu', NULL, NULL, 0),
(128, 'sd', 'सिन्धी, سنڌي، سندھی‎', NULL, NULL, 0),
(129, 'se', 'Davvisámegiella', NULL, NULL, 0),
(130, 'sg', 'yângâ tî sängö', NULL, NULL, 0),
(131, 'sk', 'slovenčina, slovenský jazyk', NULL, NULL, 0),
(132, 'sl', 'slovenski jezik, slovenščina', NULL, NULL, 0),
(133, 'sm', 'gagana fa''a Samoa', NULL, NULL, 0),
(134, 'sn', 'chiShona', NULL, NULL, 0),
(135, 'so', 'Soomaaliga, af Soomaali', NULL, NULL, 0),
(136, 'sq', 'Shqip', NULL, NULL, 0),
(137, 'sr', 'српски језик', NULL, NULL, 0),
(138, 'ss', 'SiSwati', NULL, NULL, 0),
(139, 'st', 'Sesotho', NULL, NULL, 0),
(140, 'su', 'Basa Sunda', NULL, NULL, 0),
(141, 'sv', 'svenska', NULL, NULL, 0),
(142, 'sw', 'Kiswahili', NULL, NULL, 0),
(143, 'tg', 'тоҷикӣ, toçikī, تاجیکی‎', NULL, NULL, 0),
(144, 'ti', 'ትግርኛ', NULL, NULL, 0),
(145, 'tk', 'Türkmen, Түркмен', NULL, NULL, 0),
(146, 'tn', 'Setswana', NULL, NULL, 0),
(147, 'to', 'faka Tonga', NULL, NULL, 0),
(148, 'tr', 'Türkçe', NULL, NULL, 0),
(149, 'ts', 'Xitsonga', NULL, NULL, 0),
(150, 'tt', 'татар теле, tatar tele', NULL, NULL, 0),
(151, 'tw', 'Twi', NULL, NULL, 0),
(152, 'ty', 'Reo Tahiti', NULL, NULL, 0),
(153, 'ug', 'ئۇيغۇرچە‎, Uyghurche', NULL, NULL, 0),
(154, 'uk', 'українська мова', NULL, NULL, 0),
(155, 'ur', 'اردو ', NULL, NULL, 0),
(156, 'uz', 'Oʻzbek, Ўзбек, أۇزبېك‎', NULL, NULL, 0),
(157, 've', 'Tshivenḓa', NULL, NULL, 0),
(158, 'vi', 'Việt Nam', NULL, NULL, 0),
(159, 'vo', 'Volapük', NULL, NULL, 0),
(160, 'wa', 'walon', NULL, NULL, 0),
(161, 'wo', 'Wollof', NULL, NULL, 0),
(162, 'xh', 'isiXhosa', NULL, NULL, 0),
(163, 'yi', 'ייִדיש ', NULL, NULL, 0),
(164, 'yo', 'Yorùbá', NULL, NULL, 0),
(165, 'za', 'Saɯ cueŋƅ, Saw cuengh', NULL, NULL, 0),
(166, 'zh', '中文 (Zhōngwén), 汉语, 漢語', NULL, NULL, 0),
(167, 'zu', 'isiZulu', NULL, NULL, 0);