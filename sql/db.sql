--
-- Structure de la table `mc_plugins_faq`
--

CREATE TABLE IF NOT EXISTS `mc_plugins_faq` (
  `idqa` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `idlang` smallint(3) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text DEFAULT NULL,
  `qaorder` smallint(3) unsigned NOT NULL default 0,
  PRIMARY KEY (`idqa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;