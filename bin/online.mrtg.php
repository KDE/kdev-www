#!/usr/bin/php
<?php
// Get access to the MySQL database
include ('../../access.inc');

// Login
$mysql_link = @mysql_connect ("localhost",$k_login,$k_password);

// Exit if it can not get a connection to the MySQL database
if ($mysql_link == false){
  echo "Could not connect to the database. Online users statistics will not be computed.\n";
  exit();
}

// Exit if it can not select to the MySQL database
if (@mysql_select_db("kdevelop_db") == false){
  echo "Could not select the database. Online users statistics will not be computed.\n";
  exit();
}

$result=mysql_query("SELECT ip FROM stats_userlog;");

// Nr of on-line users
echo mysql_num_rows($result);
echo "\n";

// Nr of on-line users
echo mysql_num_rows($result);
echo "\n";
/*
// Uptime
$ut = explode(' ', exec('uptime'));
$ut = "$ut[5] $ut[6] $ut[8]";
echo substr($ut, 0, strlen($ut)-1);
echo "\n";

// Host
echo "www.kdevelop.org\n";
*/
// Close MySQL connection
mysql_close();
?>
