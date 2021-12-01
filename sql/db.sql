CREATE TABLE IF NOT EXISTS `mc_faq` (
  `id_page` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_faq_content` (
  `id_content` smallint(3) NOT NULL AUTO_INCREMENT,
  `id_page` smallint(3) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `name_page` varchar(180) DEFAULT NULL,
  `content_page` text,
  `seo_title_page` varchar(180) DEFAULT NULL,
  `seo_desc_page` text DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_page` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_page` (`id_page`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_faq_content`
  ADD CONSTRAINT `mc_faq_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_faq_content_ibfk_1` FOREIGN KEY (`id_page`) REFERENCES `mc_faq` (`id_page`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_qa` (
  `id_qa` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `order_qa` smallint(3) unsigned NOT NULL default 0,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_qa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_qa_content` (
  `id_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_qa` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `title_qa` varchar(200) DEFAULT NULL,
  `url_qa` varchar(200) DEFAULT NULL,
  `desc_qa` text DEFAULT NULL,
  `seo_title_qa` varchar(200) DEFAULT NULL,
  `seo_desc_qa` varchar(300) DEFAULT NULL,
  `published_qa` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_content`),
  KEY `id_adv` (`id_qa`,`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_qa_content`
  ADD CONSTRAINT `mc_qa_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_qa_content_ibfk_1` FOREIGN KEY (`id_qa`) REFERENCES `mc_qa` (`id_qa`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_faq_config` (
   `id_config` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
   `mode_faq` enum('list','pages') NOT NULL default 'list',
   `accordion_mode` smallint(1) UNSIGNED NOT NULL default 1,
   PRIMARY KEY (`id_config`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `mc_faq_config` () VALUES ();

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'faq';