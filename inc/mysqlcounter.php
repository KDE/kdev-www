<?php

$IP=$_SERVER['REMOTE_ADDR'];

$time= explode(  " ", microtime());

$userusec= (double)$time[0];
$usersec= (double)$time[1];

// 8 minutes timeout
$smaller=$usersec-(8*60);

mysql_select_db("kdevelop_db");

$deleteuser= mysql_query("delete from stats_userlog where DATE < $smaller");
$stats_userlog= mysql_num_rows(mysql_query("SELECT * FROM stats_userlog where IP like '$IP'"));

if($stats_userlog < 1)
{
$ok= @mysql_query("insert INTO stats_userlog (IP,DATE) VALUES('$IP','$usersec')");
}

$smaller=$usersec-1080;

$deleteuser= mysql_query("delete from stats_counterlog where DATE < $smaller");
$stats_userlog= mysql_num_rows(mysql_query("SELECT * FROM stats_counterlog where IP like '$IP'"));

if($stats_userlog < 1)
{
$ok= @mysql_query("insert INTO stats_counterlog (IP,DATE) VALUES('$IP','$usersec')");
mysql_query("UPDATE stats_counter SET visitors = (visitors + 1);");
}

?>
