CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aktiv` tinyint(1) NOT NULL DEFAULT '1',
  `nev` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipus` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;