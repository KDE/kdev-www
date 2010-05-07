<?php
$posts_in_main_page = 4;
// make news_dir world writable, 0777 and main.ihtml page to 0666
$path_to_root='../';
$news_dir = 'news';
$news_page = 'add_news.php';

// get the MySQL global login and password
include ('../../access.inc');

// Establish a connection with the MySQL server
$mysql_link = @mysql_connect ('localhost', $k_login, $k_password);

// Exit if it canot get a connection to the MySQL database
if ($mysql_link == false)
  die('Could not connect to the database. Will not be able to add/edit/remove news.
');

mysql_select_db('kdevelop_db');

// Make sure the news directory exists and is writable by the webserver
if (!file_exists($path_to_root.$news_dir)){
  echo "The dir <b>\"$path_to_root$news_dir\"</b> does not exist, please create it and chmod it to 0777";
  exit;
}

function file_exists_and_is_writeable($filepath){
  if (file_exists($filepath) != true){
    // try to create the file
    if (touch($filepath)) {
      echo "<p>File $filepath created successfuly</p>";
    } else {
      die("<p>Sorry, could not create file $filepath automatically<br>
Please create it manually using the bin/manual_maintenance.sh script</p>");
    }
  }

  if (is_writable($filepath) != true){
    if (chmod($filepath, 0666) != true){ // octal; correct value of mode, need zero at start
      die("<p>Sorry, could not make file $filepath writeable automatically<br>
Please do it manually using the bin/manual_maintenance.sh script</p>");
    }
  }
  return 0;
}

if (is_writable($path_to_root.$news_dir) != true)
  die("Directory $path_to_root$news_dir is not writable");

file_exists_and_is_writeable("$path_to_root$news_dir/main.ihtml");
file_exists_and_is_writeable("$path_to_root$news_dir/edit.ihtml");

$current_year=date('Y');
file_exists_and_is_writeable($path_to_root."main$current_year.html");

// This function converts text to valid HTML
function htmlEncodeText ($string){
  $pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';
  preg_match_all ('/' . $pattern . '/', $string, $tagMatches, PREG_SET_ORDER);
  $textMatches = preg_split ('/' . $pattern . '/', $string);

  foreach ($textMatches as $key => $value) {
    // encode normal text
    $textMatches [$key] = htmlentities ($value);
  }

  foreach ($tagMatches as $key => $value) {
    // encode text inside html tags
    $tagMatches [$key] = str_replace('&amp;', '&', $tagMatches [$key]); // make sure there are no amps
    $tagMatches [$key] = str_replace('&', '&amp;', $tagMatches [$key]); // now _safely_ convert to amps
  }

  for ($i = 0; $i < count ($textMatches); $i ++) {
    $textMatches [$i] = $textMatches [$i] . $tagMatches [$i] [0];
  }

  return implode ($textMatches);
}

function add_news_post($news_filename, $news){
  global $subject, $news_post_date, $summaryp, $news_post_id, $news_body;
/*
		if(file_exists($news_filename)){
			$fp = @fopen($news_filename, "r");
			$old_news = @fread($fp, filesize($news_filename)) or die ("Directory or File $news_filename not readable");
			@fclose($fp); 
		}
*/
  $fp = @fopen($news_filename, 'w');
  @fwrite($fp, "$news\n") or die ("Directory or File $news_filename not writeable");
  @fclose($fp);
  // Make it writable by user (wwwrun) and group (www) and readable for all
  chmod($news_filename, 0666);
}


function rebuild_news_index_files($path_to_root, $news_dir, $rebuild_year){

  global $posts_in_main_page;

  // Get the existing pages
  $result = mysql_query("SELECT filename FROM `pages` WHERE `prefix` = '../' AND `filename` LIKE '$news_dir%' ORDER BY filename DESC");

  // build pages that include the most uptodate version of all the news pages (one for view, one for admin edits)
  $news_file = "<?php\n";
  $news_year_file = "<?php
global \$l_pt_main_year;
\$module_title=sprintf(\$l_pt_main_year, $rebuild_year);
module_head(\$module_title, 'news-list');\n";
  $news_edit_file = "<table border=\"0\">\n";

  $posts = 0;
  while($row = mysql_fetch_object($result)){
    // remove the "news/"
    $news_post_date=ereg_replace("$news_dir/", '', $row->filename);
    // remove the ".html"
    $news_post_date=ereg_replace('\.html', '', $news_post_date);
    // extract the $year, $month, $day
    list( $year, $month, $day ) = explode ('-', $news_post_date);
    // build the unix timestamp
    $news_unix_timestamp=mktime(0,0,0,$month,$day,$year);

    // Only add $posts_in_main_page to the main.html file
    if ($posts < $posts_in_main_page) {
      // this is served to the user
      $news_file .= "echo '<p class=\"newsDate\">'.date(\"M j, Y\", $news_unix_timestamp).\"</p>\\n\";\n";
      $news_file .= 'include_file("'.ereg_replace('\.\./', '', $row->filename)."\");\n";
      $news_file .= "echo \"\\n\";\n";
      $posts++;
    }

    // Only rebuild the year that got a new "news post" or that got a "news post" deleted
    if ($year == $rebuild_year) {
      // this is served to the user
      $news_year_file .= "echo '<p class=\"newsDate\">'.date(\"M j, Y\", $news_unix_timestamp).\"</p>\\n\";\n";
      $news_year_file .= 'include_file("'.ereg_replace('\.\./', '', $row->filename)."\");\n";
      $news_year_file .= "echo \"\\n\";\n";
    }

    // this is for the admin
    $news_edit_file .= "<tr><td><b>$news_post_date</b> - <?php include(\"$path_to_root".$row->filename."\");?></td>\n";
    // $_POST does not like dots (.) so replace them with underscores (_)
    $news_edit_file .= '<td><input type=submit name="'.ereg_replace('\.','_',$row->filename).'" value=Remove';
    /*if ($year < $current_year)
      $news_edit_file .= ' disabled';*/
    $news_edit_file .= "></td></tr>\n";
  }

  $news_file .= '?>';
  $news_year_file .= 'module_tail();
return ' . mktime() . ';
';
  $news_year_file .= '?>';
  $news_edit_file .= '</table>';
  mysql_free_result($result);
  add_news_post("$path_to_root$news_dir/main.ihtml", $news_file);
  add_news_post($path_to_root."main$rebuild_year.html", $news_year_file);
  add_news_post("$path_to_root$news_dir/edit.ihtml", $news_edit_file);
}


if (isset($_POST) and in_array ('Remove', array_values($_POST))){
  // First of all, check the password
  if ($_POST['passwd'] != $k_password){
    echo '<p><b>Password is wrong</b></p>';
    return;
  }

  // get the remove element
  while (list($key, $val) = each($_POST))
    if ($val == 'Remove') break;

  // Replace the underscores (_) with dots (.) because $_POST did not like them
  $filename= ereg_replace('_', '.', $key);

  $year=substr($filename, strlen($news_dir)+1, 4);
  // Verify if the year file is writable, this is necessary for the situation where the $year != $current_year
  if (is_writable($path_to_root."main$year.html") != true)
    die("File $path_to_root"."main$year.html does not seam to exist or is not writable");

  if (is_writable("../$filename") != true)
    die("File ../$filename does not seam to exist or is not deleteable (no permissions)");

  // Delete the page from the pages table in the database
  if (!mysql_query("DELETE FROM `pages` WHERE `pages`.`filename` = '$filename' LIMIT 1;")){
    echo "$filename could not be removed from the pages table in the database<br>\n";
    return;
  }

  // Delete the news body page file
  if (!unlink("../$filename")){
    echo "$filename file could not be deleted, something is wrong<br>\n";
    return;
  }

  // substr($filename,5,4) is the year of the post that just got deleted
  rebuild_news_index_files($path_to_root, $news_dir, $year);

  echo "$filename successfully removed<br>\n";
}

if (isset($_POST['Add'])){

  // First of all, check the password
  if ($_POST['passwd'] != $k_password){
    echo "<p><b>Password is wrong</b></p>";
    return;
  }

  // Extract and validate the date
  $news_post_date = $_POST['post_date'];
  list( $year, $month, $day ) = explode (' ', $news_post_date);
  if (!checkdate($month, $day, $year)){
    echo '<p><b>Invalid date, or date format</b></p>';
    return;
  }

  // The name of the file where this news post is going to be saved
  $news_post_url = "$path_to_root$news_dir/$news_post_date.html";
  $news_post_url = ereg_replace(' ', '-', $news_post_url); // replace whitespaces
  $news_post_url = ereg_replace('\.\./', '', $news_post_url); // remove ../

  // The subject
  $subject = htmlEncodeText(stripslashes($_POST['subject']));

  // The news body
  $news_body = preg_replace("/\\\'/","'",$_POST['news_body']);
  $bs = "\\";
  $f = "/$bs$bs$bs$bs/";
  $r = $bs;
  $news_body = htmlEncodeText(stripslashes(preg_replace($f,$r,$news_body)));

  if($subject == '' or $news_post_date == '' or $news_body == ''){
    echo '<p><b>Please fill all the <i>Date:</i>,  <i>Subject:</i> and <i>News:</i> fields</b></p>';
    return;
  }

  // build the contents of the news post file (place it all together)
  $news_post_body = "<h2>$subject</h2>\n<p>\n$news_body\n</p>";

  // Verify if the year file is writable, this is necessary for the situation where the $year != $current_year
  if (is_writable($path_to_root."main$year.html") != true)
    die("File $path_to_root"."main$year.html does not seam to exist or is not writable.");

  // Verify if a file for this day already exists
  if (file_exists("$path_to_root$news_post_url"))
    die("A news item already exists for this day, can not add another one.");

  // Insert page into the pages table in the database
  if (!mysql_query("INSERT INTO `pages` (`id`, `prefix`, `filename`, `translatable`, `sitemap`, `sitemap_group`, `sitemap_parentnode`) VALUES (NULL, '../', '$news_post_url', 'optional', '0', 'news', '1');")){
    echo "<p><b>It seams a news post already exists for this date. Use another date.</p>";
    return;
  }
  // create the news_post_body file
  add_news_post("$path_to_root$news_post_url", $news_post_body);

  // rebuild the index files
  rebuild_news_index_files($path_to_root, $news_dir, $year);

  //print "<html><head><META http-equiv='refresh' content='0;URL=".$_SERVER['REQUEST_URI']."'></head><body></body></html>";
  print "<html><head><META http-equiv='refresh' content='0;URL=../index.html?filename=main.html&set_lang=en'></head><body></body></html>";
}

?>
<center>
<h1>Add News</h1>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<table border="0">
<tr><td>Date:</td><td> <input type=text name="post_date" size="40" value="<?php echo date('Y m d'); ?>"> Please do NOT change the date format [year month day] (all numeric)</td></tr>
<tr><td>Subject:</td><td> <input type=text name="subject" size="112"> </td></tr>
<tr><td>News:</td><td> <textarea name="news_body" rows=15 cols="90"></textarea> </td></tr>
<tr><td>Password:</td><td> <input type=text name="passwd" size="20"> <input type=submit name=Add value=Add> Click only once! Do not resend information.</td></tr>
</table>
<p>You cannot post two news for the same day.</p>
<p>All URLs are relative to the website's root (http://www.kdevelop.org/)</p>
<p>News can be added out of order, the system will do the ordering automatically.</p>
</center>
<h1>Older News</h1>
<?php
// Include the already existing news
include("$path_to_root$news_dir/edit.ihtml");
echo "</form>\n";

?>
