--
-- Structure de la table `mc_plugins_faq`
--

CREATE TABLE IF NOT EXISTS `mc_plugins_faq` (
  `idqa` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `idlang` int(10) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`idqa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;