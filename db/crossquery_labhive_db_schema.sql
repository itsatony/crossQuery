/*
SQLyog Community- MySQL GUI v8.21 RC 
MySQL - 5.0.67-log : Database - labhive
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`labhive` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `labhive`;

/*Table structure for table `cq_datasets` */

DROP TABLE IF EXISTS `cq_datasets`;

CREATE TABLE `cq_datasets` (
  `tome_id` varchar(20) NOT NULL,
  `tome_name` varchar(50) NOT NULL,
  `tome_species` varchar(40) NOT NULL,
  `tome_tablename` varchar(60) NOT NULL,
  `tome_description` varchar(1024) NOT NULL,
  `tome_id_link` varchar(2048) NOT NULL,
  PRIMARY KEY  (`tome_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `cq_datasets_cols` */

DROP TABLE IF EXISTS `cq_datasets_cols`;

CREATE TABLE `cq_datasets_cols` (
  `field_index` bigint(20) NOT NULL auto_increment,
  `field_name` varchar(30) NOT NULL,
  `field_type` varchar(10) NOT NULL COMMENT 'NUMERIC, TEXT, LINK, GO',
  `dataset_id` varchar(20) NOT NULL,
  `field_desc` tinytext NOT NULL,
  PRIMARY KEY  (`field_index`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

/*Table structure for table `cq_huvec_v1` */

DROP TABLE IF EXISTS `cq_huvec_v1`;

CREATE TABLE `cq_huvec_v1` (
  `pid` varchar(20) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `acc` varchar(20) NOT NULL,
  `gfp` decimal(10,2) NOT NULL default '0.00',
  `notch1` decimal(10,2) NOT NULL default '0.00',
  `icap1` decimal(10,2) NOT NULL default '0.00',
  `chromosome` varchar(2) NOT NULL default '0',
  `illumina_probe_id` bigint(20) NOT NULL,
  `pubmed` varchar(1024) NOT NULL,
  `goterms` varchar(4096) NOT NULL,
  `pathway` varchar(4096) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `cq_saved_queries` */

DROP TABLE IF EXISTS `cq_saved_queries`;

CREATE TABLE `cq_saved_queries` (
  `query_index` bigint(20) NOT NULL auto_increment,
  `query_filter` text NOT NULL,
  `query_sorter` text NOT NULL,
  `query_orientation` tinytext NOT NULL COMMENT 'asc or desc',
  `query_limit` bigint(20) NOT NULL,
  `query_name` tinytext NOT NULL,
  `query_owner_id` bigint(20) NOT NULL,
  `query_dataset_id` varchar(60) NOT NULL,
  PRIMARY KEY  (`query_index`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Table structure for table `cq_tome_medaka_v8` */

DROP TABLE IF EXISTS `cq_tome_medaka_v8`;

CREATE TABLE `cq_tome_medaka_v8` (
  `tid` varchar(40) character set latin1 NOT NULL default '',
  `gid` varchar(40) NOT NULL default '',
  `exo` char(8) character set latin1 NOT NULL default '',
  `eye` char(9) character set latin1 NOT NULL default '',
  `hyper` char(9) character set latin1 NOT NULL default '',
  `invasive` char(9) character set latin1 NOT NULL default '',
  `mes1` char(9) NOT NULL default '',
  `sg3` char(9) NOT NULL default '',
  `description` text NOT NULL,
  `goterms` text NOT NULL,
  `UniProt_xref_id` varchar(10) NOT NULL default '',
  `dmrt1binding` char(10) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `lh_info` */

DROP TABLE IF EXISTS `lh_info`;

CREATE TABLE `lh_info` (
  `index` bigint(20) NOT NULL auto_increment,
  `associated_table` bigint(4) default NULL COMMENT 'reference to the associated table in int format: 0=users, 1=sessions',
  `association_id` bigint(20) NOT NULL COMMENT 'for joining with other tables',
  `thing` varchar(40) NOT NULL COMMENT 'what is this here',
  `content` varchar(512) NOT NULL COMMENT 'and the value?',
  PRIMARY KEY  (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `lh_sessions` */

DROP TABLE IF EXISTS `lh_sessions`;

CREATE TABLE `lh_sessions` (
  `sid` bigint(20) unsigned NOT NULL auto_increment,
  `session_id` varchar(64) default NULL COMMENT 'PHPsessid',
  `uid` bigint(20) unsigned default NULL,
  `created_date` datetime default NULL,
  `created_ip` varchar(39) default NULL,
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

/*Table structure for table `lh_users` */

DROP TABLE IF EXISTS `lh_users`;

CREATE TABLE `lh_users` (
  `user_id` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `created_date` datetime default NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
