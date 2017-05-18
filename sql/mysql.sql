#
# Table structure for table `faq_categories`
#

CREATE TABLE `xoopsfaq_categories` (
  `category_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `category_title` varchar(255) NOT NULL DEFAULT '',
  `category_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 ;


#
# Table structure for table `faq_contents`
#

CREATE TABLE `xoopsfaq_contents` (
  `contents_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `contents_cid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `contents_title` varchar(255) NOT NULL DEFAULT '',
  `contents_contents` text NOT NULL,
  `contents_publish` int(11) unsigned NOT NULL DEFAULT '0',
  `contents_weight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `contents_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dohtml` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `doxcode` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dosmiley` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `doimage` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dobr` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`contents_id`),
  KEY `contents_title` (`contents_title`(40)),
  KEY `contents_visible_category_id` (`contents_active`,`contents_cid`)
) ENGINE=MyISAM AUTO_INCREMENT=0;
