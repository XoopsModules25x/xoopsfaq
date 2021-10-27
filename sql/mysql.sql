#
# Table structure for table `faq_categories`
#

CREATE TABLE `xoopsfaq_categories` (
  `category_id`    TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_title` VARCHAR(255)        NOT NULL DEFAULT '',
  `category_order` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 0;

#
# Table structure for table `faq_contents`
#

CREATE TABLE `xoopsfaq_contents` (
  `contents_id`       SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contents_cid`      TINYINT(3) UNSIGNED  NOT NULL DEFAULT '0',
  `contents_title`    VARCHAR(255)         NOT NULL DEFAULT '',
  `contents_contents` TEXT                 NOT NULL,
  `contents_publish`  INT(11) UNSIGNED     NOT NULL DEFAULT '0',
  `contents_weight`   SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `contents_active`   TINYINT(1) UNSIGNED  NOT NULL DEFAULT '1',
  `dohtml`            TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
  `doxcode`           TINYINT(1) UNSIGNED  NOT NULL DEFAULT '1',
  `dosmiley`          TINYINT(1) UNSIGNED  NOT NULL DEFAULT '1',
  `doimage`           TINYINT(1) UNSIGNED  NOT NULL DEFAULT '1',
  `dobr`              TINYINT(1) UNSIGNED  NOT NULL DEFAULT '1',
  PRIMARY KEY (`contents_id`),
  KEY `contents_title` (`contents_title`(40)),
  KEY `contents_visible_category_id` (`contents_active`, `contents_cid`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 0;
