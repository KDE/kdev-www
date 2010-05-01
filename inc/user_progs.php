<?php module_head($l_user_progs);

// Number of entries to display
$nr_entries = 5;
/*
// a special MySQL query that calculates the number of rows, but only returns a small subset of them
$result = mysql_query("SELECT SQL_CALC_FOUND_ROWS link,name,author FROM user_progs ORDER BY id DESC LIMIT $nr_entries");
$row = mysql_fetch_row(mysql_query('SELECT FOUND_ROWS()'));
$number = $row[0];

$text = sprintf($l_newest_entries, $nr_entries);
echo "<p id=\"userprograms\"><a href=\"index.html?filename=users.html\">$l_total</a> <b>$number</b>, $text</p>";

while($row = mysql_fetch_object($result)){
  echo "<a href=\"$row->link\">$row->name</a> $l_by $row->author<br>";
}
mysql_free_result($result);
*/
module_tail();
