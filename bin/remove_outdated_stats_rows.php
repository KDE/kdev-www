#!/usr/bin/php
<?php
# Remove some outdated rows from stats_* tables so that the
# kdevelop_db does not grow to infinite :)
# It also sends an e-mail to the webmaster with info about the removed rows
# (w) 31.10.2004
# (c) by Amilcar Lucas, licensed under the GPL

// get the MySQL global login and password
include ('../../access.inc');

// Establish a connection with the MySQL server
$mysql_link = @mysql_connect ('localhost',$k_login,$k_password);

// Exit if it can not get a connection to the MySQL database
if ($mysql_link == false){
  die('Could not connect to the database. Outdated statistics will not be removed.
');
}

// Exit if it can not select to the MySQL database
if (@mysql_select_db("kdevelop_db") == false){
  echo "Could not select the database. Outdated statistics will not be removed.\n";
  exit();
}

// BEGIN CONFIGURATION
$subject = 'Automated database management system (bin/remove_outdated_stats_rows.php script)';
$email_header = '<html><head>
<title>Automated database management system</title>
<meta http-equiv="Content-Type" content="text/html;
charset=iso-8859-1">
</head><body>
<p>Hi, I`m the bin/remove_outdated_stats_rows.php script.</p>
';
$email_body = '';
$email_footer = '</body></html>';
$short_timeout = 40 * 24 * 60 * 60;     // 40 days
$long_timeout  = 4 * 31 * 24 * 60 * 60; // 4 months
// END CONFIGURATION

$current_timestamp=time();
$result = mysql_query("SELECT * FROM `stats_bad_robots` WHERE (e404 + e400 <= '2') AND ($current_timestamp - UNIX_TIMESTAMP(last_visited) >= $short_timeout ) AND (trapped = '0')");
if (mysql_num_rows($result)) {
  $email_body .= '<h2>Bad behaved robots that turned out to be not that bad after all. I deleted them.</h2>
<table align=center width="80%" cellspacing=1 cellpadding=1 border=1>
  <tr><th>ip</th><th>403 errors</th><th>404 errors</th><th>412 errors</th><th>400 errors</th><th>trapped</th><th>last agent</th><th>last visited</th></tr>
';
  while($row = mysql_fetch_object($result)) {
    $email_body .= "  <tr><td>$row->ip</td><td>$row->e403</td><td>$row->e404</td><td>$row->e412</td><td>$row->e400</td><td>$row->trapped</td><td>".htmlspecialchars($row->last_useragent)."</td><td>$row->last_visited</td></tr>\n";
  }
  $email_body .= '</table>
';
  mysql_query("DELETE FROM `stats_bad_robots` WHERE (e404 + e400 <= '2') AND ($current_timestamp - UNIX_TIMESTAMP(last_visited) >= $short_timeout ) AND (trapped = '0')");
  mysql_query("OPTIMIZE TABLE `stats_bad_robots`");
  }
mysql_free_result($result);


$result = mysql_query("SELECT * FROM `stats_bad_robots` WHERE ($current_timestamp - UNIX_TIMESTAMP(last_visited) > $long_timeout )");
if (mysql_num_rows($result)) {
  $email_body .= '<h2>Bad behaved robots. I deleted them because they where not visited for a long time.</h2>
<table align=center width="80%" cellspacing=1 cellpadding=1 border=1>
  <tr><th>ip</th><th>403 errors</th><th>404 errors</th><th>412 errors</th><th>400 errors</th><th>trapped</th><th>last agent</th><th>last visited</th></tr>
';
  while($row = mysql_fetch_object($result)) {
    $email_body .= "  <tr><td>$row->ip</td><td>$row->e403</td><td>$row->e404</td><td>$row->e412</td><td>$row->e400</td><td>$row->trapped</td><td>".htmlspecialchars($row->last_useragent)."</td><td>$row->last_visited</td></tr>\n";
  }
  $email_body .= '</table>
';
  mysql_query("DELETE FROM `stats_bad_robots` WHERE ($current_timestamp - UNIX_TIMESTAMP(last_visited) > $long_timeout )");
  mysql_query("OPTIMIZE TABLE `stats_bad_robots`");
  }
mysql_free_result($result);


$result = mysql_query("SELECT * FROM `stats_page_error` WHERE filename LIKE '%.css' OR filename LIKE '%.gif' OR filename LIKE '%.png' OR `status` = '403' OR ((nr_visited <= '2') AND ($current_timestamp - UNIX_TIMESTAMP(last_visited) >= $short_timeout )) ORDER BY last_visited");
if (mysql_num_rows($result)) {
  $email_body .= '<h2>Pages visited by error that I deleted because they where not important.</h2>
<table align=center width="80%" cellspacing=1 cellpadding=1 border=0>
  <tr><th>lang</th><th>filename</th><th>first visited</th><th>last visited</th><th>status</th><th>nr visits</th><th>ip</th><th>last agent</th><th>last referrer</th></tr>
';
  while($row = mysql_fetch_object($result)) {
    $color = (dechex($row->status) . dechex($row->status));
    $email_body .= "  <tr bgcolor=\"#$color\"><td>".htmlspecialchars($row->lang)."</td><td>".htmlspecialchars($row->filename)."</td><td>$row->first_visited</td><td>$row->last_visited</td><td>$row->status</td><td>$row->nr_visited</td><td>$row->ip</td><td>".htmlspecialchars($row->last_useragent)."</td><td>".htmlspecialchars($row->last_referer)."</td></tr>\n";
  }
  $email_body .= '</table>
';
  mysql_query("DELETE FROM `stats_page_error` WHERE filename LIKE '%.css' OR filename LIKE '%.gif' OR filename LIKE '%.png' OR `status` = '403' OR ((nr_visited <= '2') AND ($current_timestamp - UNIX_TIMESTAMP(last_visited) >= $short_timeout ))");
  mysql_query('OPTIMIZE TABLE `stats_page_error`');
  }
mysql_free_result($result);


$result = mysql_query("SELECT * FROM `stats_page_error` WHERE ($current_timestamp - UNIX_TIMESTAMP(last_visited) > $long_timeout )");
if (mysql_num_rows($result)) {
  $email_body .= '<h2>Pages visited by error that I deleted because they where not visited for a long time.</h2>
<table align=center width="80%" cellspacing=1 cellpadding=1 border=0>
  <tr><th>lang</th><th>filename</th><th>first visited</th><th>last visited</th><th>status</th><th>nr visits</th><th>ip</th><th>last agent</th><th>last referrer</th></tr>
';
  while($row = mysql_fetch_object($result)) {
    $color = (dechex($row->status) . dechex($row->status));
    $email_body .= "  <tr bgcolor=\"#$color\"><td>".htmlspecialchars($row->lang)."</td><td>".htmlspecialchars($row->filename)."</td><td>$row->first_visited</td><td>$row->last_visited</td><td>$row->status</td><td>$row->nr_visited</td><td>$row->ip</td><td>".htmlspecialchars($row->last_useragent)."</td><td>".htmlspecialchars($row->last_referer)."</td></tr>\n";
  }
  $email_body .= '</table>
';
  mysql_query("DELETE FROM `stats_page_error` WHERE ($current_timestamp - UNIX_TIMESTAMP(last_visited) > $long_timeout )");
  mysql_query('OPTIMIZE TABLE `stats_page_error`');
}
mysql_free_result($result);


if (!empty($email_body)) {
  // Build the emails
  $email_body = "$email_header$email_body$email_footer";

  // echo ("\nSubject: $subject\n\nBody:\n$email_body\n"); 
  //mail("webmaster@kdevelop.org",$subject,$email_body, "From: KDevelop Webmaster <webmaster@kdevelop.org>\nContent-Type: text/html; charset=\"iso-8859-1\"\nX-Mailer: PHP/" . phpversion());
}

// Order the pages table in a nice way
mysql_query('ALTER TABLE `pages` ORDER BY `prefix` DESC , `sitemap_group`, `filename`');

// Close the mysql link
mysql_close($mysql_link);

?>