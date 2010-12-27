<?php
module_head($l_top_lang_visited_pages);

$result = mysql_query("SELECT * FROM `stats_pages` WHERE `lang` = '$lang' ORDER BY `avr_daily_visits` DESC LIMIT 0, 30");

echo "<table width=\"80%\" cellspacing=\"2\" cellpadding=\"2\" border=\"1\">
  <tr><th>$l_page</th><th>$l_last_update</th><th>$l_last_visited</th><th>$l_average_daily_visits</th></tr>\n";
while($row = mysql_fetch_object($result)) {
    $av_visits = sprintf ("%.02f",$row->avr_daily_visits);
    echo "  <tr><td><a href=\"index.html?filename=$row->filename\">$row->filename</a></td><td>$row->last_updated</td><td>$row->last_visited</td><td>$av_visits</td></tr>\n";
}

echo "</table>\n";
module_tail();


module_head($l_global_website_statistics);

// Total served pages per day
$result = mysql_query("SELECT SUM(`avr_daily_visits`) AS daily_served_pages FROM `stats_pages`");
$row = mysql_fetch_object($result);
echo "<p>" . sprintf ($l_served_pages, $row->daily_served_pages) . "</p>\n";
mysql_free_result($result);

// Total served pages per day excluding "main.html" files
$result = mysql_query("SELECT SUM(`avr_daily_visits`) AS daily_served_pages FROM `stats_pages` WHERE filename != 'main.html'");
$row = mysql_fetch_object($result);
$daily_served_pages_ex_main=$row->daily_served_pages;

$result = mysql_query("SELECT lang , SUM(`avr_daily_visits`) AS daily_served_pages FROM `stats_pages` WHERE filename != 'main.html' GROUP BY `lang` ORDER BY daily_served_pages DESC");

echo "<table width=\"80%\" cellspacing=\"2\" cellpadding=\"2\" border=\"1\">
  <tr><th>$l_language</th><th>$l_average_daily_visits</th><th>$l_percentage</th></tr>\n";
global $languages;
while($row = mysql_fetch_object($result)) {
  $av_visits = sprintf ("%.02f",$row->daily_served_pages);
  $percentage_visits = sprintf ("%02.02f",100*$row->daily_served_pages/$daily_served_pages_ex_main);
  echo "  <tr><td><a href=\"index.html?filename=website_translation_status.html&amp;set_lang=$row->lang\"> {$languages[$row->lang]} ($row->lang)</a></td><td>$av_visits</td><td>$percentage_visits %</td></tr>\n";
}
mysql_free_result($result);
echo "</table>\n";
?>
<hr>
<table width="100%" cellspacing="2" cellpadding="2" border="0">
<tr>
  <th align="center"><?php echo $l_stat_graphics; ?></th>
</tr>
<tr>
  <td align="center"><?php echo $l_day_graphic; ?><br>
  <img src="dynamic/online-day.png" alt="<?php echo $l_day_graphic; ?>"><br><hr></td>
</tr>
<tr>
  <td align="center"><?php echo $l_week_graphic; ?><br>
  <img src="dynamic/online-week.png" alt="<?php echo $l_week_graphic; ?>"><br><hr></td>
</tr>
<tr>
  <td align="center"><?php echo $l_month_graphic; ?><br>
  <img src="dynamic/online-month.png" alt="<?php echo $l_month_graphic; ?>"><br><hr></td>
</tr>
<tr>
  <td align="center"><?php echo $l_year_grahic; ?><br>
  <img src="dynamic/online-year.png" alt="<?php echo $l_year_grahic; ?>"></td>
</tr>
</table>

<?php
module_tail();
return time();
?>