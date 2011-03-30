/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


CREATE DATABASE /*!32312 IF NOT EXISTS*/`startersuite` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `startersuite`;

/* create user */

GRANT INSERT,UPDATE,DELETE,SELECT ON startersuite.* TO 'startersuite'@'localhost' IDENTIFIED BY 'startersuite';

/*Table structure for table `btv_channel_peer` */

DROP TABLE IF EXISTS `btv_channel_peer`;

CREATE TABLE `btv_channel_peer` (
  `channel_hash_id` varchar(255) NOT NULL,
  `peer_hash_id` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `port` int(10) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '3',
  `subtype` tinyint(4) NOT NULL DEFAULT '1',
  `abi` int(10) unsigned NOT NULL DEFAULT '0',
  `opened_port` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `downloaded_bytes` bigint(10) unsigned NOT NULL DEFAULT '0',
  `download_rate` int(10) unsigned NOT NULL DEFAULT '0',
  `uploaded_bytes` bigint(10) unsigned NOT NULL DEFAULT '0',
  `upload_rate` int(10) unsigned NOT NULL DEFAULT '0',
  `qoe` double(10,9) NOT NULL DEFAULT '0.000000000',
  `regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_report` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`channel_hash_id`,`peer_hash_id`),
  KEY `channel_hash_index` (`channel_hash_id`),
  KEY `type_index` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


/*Table structure for table `hosted_channel` */

DROP TABLE IF EXISTS `hosted_channel`;

CREATE TABLE `hosted_channel` (
  `hosted_channel_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `hosted_channel_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `hosted_channel_tracker_url` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `hosted_channel_bitrate` int(11) NOT NULL,
  `hosted_channel_chunk_size` int(11) NOT NULL,
  `hosted_channel_broadcaster_ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `hosted_channel_thumb` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hosted_channel_del_token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`hosted_channel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `news_id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `news_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `news_language` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'english',
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TABLE IF EXISTS `user_session`;

CREATE TABLE `user_session` (
  `userid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `useragent` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `lastactivity` datetime NOT NULL,
  PRIMARY KEY (`userid`,`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `user_type_permission` */

DROP TABLE IF EXISTS `user_type_permission`;

CREATE TABLE `user_type_permission` (
  `user_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `operation` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_type`,`controller`,`operation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user_type_permission` */

insert  into `user_type_permission`(`user_type`,`controller`,`operation`) values ('guest','announce',''),('guest','channel_list','get_goalbit_file'),('guest','channel_list','get_html'),('guest','channel_list','get_js'),('guest','channel_list','register_goalbit_file'),('guest','login','index'),('guest','login','log_me_in'),('guest','login','not_allowed'),('guest','stats',''),('limited','broadcaster','index'),('limited','broadcaster','start'),('limited','channel_list','channel_details'),('limited','channel_list','channel_list_refresh'),('limited','channel_list','embeb_code'),('limited','channel_list','get_goalbit_file'),('limited','channel_list','get_html'),('limited','channel_list','get_js'),('limited','channel_list','iframe_code'),('limited','channel_list','index'),('limited','channel_list','news'),('limited','channel_list','scan_port'),('limited','channel_list','viewers_refresh'),('limited','channels','index'),('limited','index','index'),('limited','login','index'),('limited','login','log_me_out'),('limited','login','not_allowed'),('limited','start_broadcast','index'),('limited','stop_broadcast ','index'),('limited','viewers','index');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `userid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'english',
  `news_closed` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'limited',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`userid`,`password`,`language`,`news_closed`, `type`) values ('admin','dd94709528bb1c83d08f3088d4043f4742891f4f','english',0, 'admin'),('guest','35675e68f4b5af7b995d9205ad0fc43842f16450','english',0,'limited');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
