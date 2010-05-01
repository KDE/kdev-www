#!/usr/bin/php
<?php
#
# This script should be invoked by a cron job.
#
# The purpose of this script is to update the "avr_daily_visits" row
# on the "stats_pages" table of the "kdevelop_db" MySQL database.
#
# begin 17 September 2004
# (c) 2004 Amilcar Lucas <amilcar@kdevelop.org>
# published under the GNU GPL


// get the MySQL global login and password
include ("../../access.inc");

// Establish a connection with the MySQL server
$mysql_link = @mysql_connect ("localhost",$k_login,$k_password);

// Exit if it canot get a connection to the MySQL database
if ($mysql_link == false)
  die('Could not connect to the database. Average daily visits statistics will not be computed.
');

mysql_select_db("kdevelop_db");

// Update the average daily page visits row on the stats_pages table
mysql_query("UPDATE stats_pages SET avr_daily_visits = nr_visited*86400/(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(stats_since))");
mysql_query("ALTER TABLE `stats_pages` ORDER BY filename, lang");

// Optimize these tables because they tend to grow a lot and crash MySQL
mysql_query("OPTIMIZE TABLE `stats_counterlog`");
mysql_query("OPTIMIZE TABLE `stats_userlog`");

// Close the mysql link
mysql_close($mysql_link);
?>