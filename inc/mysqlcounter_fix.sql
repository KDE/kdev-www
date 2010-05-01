# phpMyAdmin MySQL-Dump
# version 2.2.4
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: localhost
# Generation Time: Oct 08, 2004 at 06:08 PM
# Server version: 3.23.58
# PHP Version: 4.0.6
# Database : `kdevelop_db`
#
# Import this file into the database if one of the counter
# auxiliary tables stops working properly.
# It will delete the auxiliary tables and recreate them.
# There is no loss of important data because they only
# contain temporary auxiliary data 
#
# Amilcar Lucas
#
# --------------------------------------------------------

#
# Table structure for table `stats_counterlog`
#

DROP TABLE IF EXISTS `stats_counterlog`;
CREATE TABLE `stats_counterlog` (
  `date` int(11) default NULL,
  `ip` varchar(255) default NULL,
  KEY `date` (`date`),
  KEY `ip` (`ip`)
) TYPE=MyISAM PACK_KEYS=1 COMMENT='Auxiliary table for the website counter';
# --------------------------------------------------------


#
# Table structure for table `stats_userlog`
#

DROP TABLE IF EXISTS `stats_userlog`;
CREATE TABLE `stats_userlog` (
  `date` int(11) default NULL,
  `ip` varchar(255) default NULL,
  `kennummer` int(11) default NULL,
  `id` int(11) default NULL,
  `name` varchar(255) default NULL,
  KEY `date` (`date`),
  KEY `ip` (`ip`)
) TYPE=MyISAM PACK_KEYS=1 COMMENT='Auxiliary table for the website counter';
# --------------------------------------------------------
