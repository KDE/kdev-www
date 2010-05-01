#!/usr/bin/php
<?php
// Get access to the MySQL database
include ('../../access.inc');

// Login
$mysql_link = @mysql_connect ("localhost",$k_login,$k_password);

// Exit if it can not get a connection to the MySQL database
if ($mysql_link == false){
  echo "Could not connect to the database. Download statistics will not be computed.\n";
  exit();
}

// Exit if it can not select to the MySQL database
if (@mysql_select_db("kdevelop_db") == false){
  echo "Could not select the database. Download statistics will not be computed.\n";
  exit();
}

// Get the total number of downloads
$result=mysql_query("SELECT SUM(`nr_visited`) AS nr_visits FROM `stats_pages` WHERE filename LIKE '%download.html' GROUP BY `filename`");
$total_downloads=0;
while($row = mysql_fetch_object($result)) {
  $total_downloads = $total_downloads + $row->nr_visits;
}

echo $total_downloads;
echo "\n";

echo $total_downloads;
echo "\n";

// Close MySQL connection
mysql_close();
?>
