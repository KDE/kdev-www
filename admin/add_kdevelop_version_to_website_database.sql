-- phpMyAdmin SQL Dump
-- version 2.11.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 05, 2008 at 09:20 PM
-- Server version: 4.0.18
-- PHP Version: 5.0.5


--
-- Database: `kdevelop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` smallint(6) NOT NULL auto_increment,
  `prefix` enum('../index.html?filename=','../') NOT NULL default '../index.html?filename=',
  `filename` varchar(255) NOT NULL default '',
  `translatable` enum('yes','optional','no') NOT NULL default 'no',
  `sitemap` tinyint(1) NOT NULL default '1',
  `sitemap_group` enum('none','redirect','news','events','guestbook','1.3','2.1','3.0','3.1','3.2','3.3','3.4','3.5','HEAD') NOT NULL default 'none',
  `sitemap_parentnode` smallint(6) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `filename` (`filename`),
  KEY `translatable` (`translatable`)
) TYPE=MyISAM COMMENT='Page proprieties of all the pages';

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`prefix`, `filename`, `translatable`, `sitemap`, `sitemap_group`, `sitemap_parentnode`) VALUES
('../index.html?filename=', '3.5/kdevelop.html', 'yes', 1, '3.5', 1),
('../index.html?filename=', '3.5/announce-kdevelop-3.5.html', 'optional', 1, '3.5', 209),
('../index.html?filename=', '3.5/authors.html', 'optional', 1, '3.5', 209),
('../index.html?filename=', '3.5/branches_compiling.html', 'optional', 1, '3.5', 209),
('../index.html?filename=', '3.5/ChangeLog.html', 'no', 1, '3.5', 214),
('../index.html?filename=', '3.5/changes.html', 'optional', 1, '3.5', 209),
('../index.html?filename=', '3.5/compiling.ihtml', 'optional', 0, '3.5', 209),
('../index.html?filename=', '3.5/compiling_macos.ihtml', 'optional', 0, '3.5', 209),
('../index.html?filename=', '3.5/download.html', 'yes', 1, '3.5', 209),
('../index.html?filename=', '3.5/faq.html', 'optional', 1, '3.5', 209),
('../index.html?filename=', '3.5/features.html', 'yes', 1, '3.5', 209),
('../index.html?filename=', '3.5/requirements.html', 'optional', 1, '3.5', 209),
('../index.html?filename=', '3.5/screenshots1.html', 'optional', 1, '3.5', 209),
('../index.html?filename=', '3.5/screenshots2.html', 'optional', 1, '3.5', 173),
('../index.html?filename=', '3.5/screenshots3.html', 'optional', 1, '3.5', 173);
