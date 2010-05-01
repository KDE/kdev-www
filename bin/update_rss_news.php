#!/usr/bin/php
<?php
#
# This script should be invoked by a user with write permissions on the
# dynamic/ subdirectory on the website and using a cron job
#
# The purpose of this script is to create a rss feeds from the news of
# the KDevelop website, one feed per translated language. All the files
# are created in the dynamic/ subdirectory of the website.
#
# begin 1 December 2007
# (c) 2007 Amilcar Lucas <amilcar@kdevelop.org>
# published under the GNU GPL

// make rss_dir user writable, 0777
$path_to_root='../';
$news_dir = 'news/';
$rss_dir = 'dynamic/';
$site='http://www.kdevelop.org/';

// get the MySQL global login and password
include ("../../access.inc");

// for global $active_languages
include("website_globals.php");


function get_news($news_dir){

  $news_items = array();  // news items

  // Get the existing pages, and store relevant info in $news_items array
  $result = mysql_query("SELECT filename FROM `pages` WHERE `prefix` = '../' AND `filename` LIKE '$news_dir%' ORDER BY filename DESC");
  while($row = mysql_fetch_object($result)){
    // remove the "news/"
    $news_post_date=ereg_replace($news_dir, '', $row->filename);
    // remove the ".html"
    $news_post_date=ereg_replace('\.html', '', $news_post_date);
    // extract the $year, $month, $day
    list( $year, $month, $day ) = explode ('-', $news_post_date);
    // build the unix timestamp
    $news_items[] = array(
      'filename'       => $row->filename,
      'unix_timestamp' => mktime(0,0,0,$month,$day,$year)
    );
  };
  mysql_free_result($result);

  return $news_items;
}

function create_rss_feed($channel, $items)
{
    if(empty($items)){
        return;
    }

    /* $data ="<?php header('Content-type: application/rss+xml'); ?>\n"; */
    $data ="<?xml version=\"1.0\" encoding=\"$channel[encoding]\"?>\n";
    $data.="<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
    $data.="  <channel>\n";
    $data.="    <title>".htmlspecialchars(strip_tags($channel["name"]))."</title>\n";
    $data.="    <link>" . htmlspecialchars($channel["url"]) . "</link>\n";
    $data.="    <description>$channel[description]</description>\n";
    $data.="    <language>$channel[language]</language>\n";

    $data.="    <atom:link href=\"$channel[self_url]\" rel=\"self\" type=\"application/rss+xml\" />\n";
    $data.="    <copyright>1998-".date('Y').", The KDevelop team</copyright>\n";
    $data.="    <pubDate>$channel[pub_date]</pubDate>\n";
    $data.="    <lastBuildDate>".date('r')."</lastBuildDate>\n";
    $data.="    <category>".htmlspecialchars(strip_tags($channel["name"]))."</category>\n";
    $data.="    <webMaster>webmaster@k_d_e_v_e_l_o_p.org (KDevelop webmaster)</webMaster>\n";

    $data.="    <ttl>360</ttl>\n";

    foreach($items as $item){
        $data.="    <item>\n";
        $data.="      <title>".htmlspecialchars($item['headline'])."</title>\n";
        $data.="      <link>$item[url]</link>\n";
        //$data.="      <author>".htmlspecialchars($item["author"])."</author>\n";
        $data.="      <description>".htmlspecialchars($item['description'])."</description>\n";
        $data.="      <guid".($item['isPermaLink']==true?"isPermaLink=\"true\">":'>').htmlspecialchars($item["guid"])."</guid>\n";
        $data.="      <pubDate>$item[pub_date]</pubDate>\n";
        $data.="    </item>\n";
    }

    $data.="  </channel>\n";
    $data.="</rss>\n";

    return $data;
}


function build_rss_feed($site, $rss_dir, $lang, $news_items){

  global $path_to_root;

  $rss_items = array();   // RSS feed items
  $pub_date = 0;          // RSS feed publication date
  foreach($news_items as $news_item){

    $english_news_filename    = $path_to_root.$news_item['filename'];
    $translated_news_filename = $path_to_root."lang/$lang/".$news_item['filename'];

    // Read the translated news file
    if(file_exists($translated_news_filename)){
      $fp = @fopen($translated_news_filename, "r");
      $news_full = @fread($fp, filesize($translated_news_filename)) or die ("File $translated_news_filename not readable");
      @fclose($fp);
    } else {
      // Fallback to english if translation is not available
      if(file_exists($english_news_filename)){
        $fp = @fopen($english_news_filename, "r");
        $news_full = @fread($fp, filesize($english_news_filename)) or die ("File $english_news_filename not readable");
        @fclose($fp);
      } else {
        die ("File ".$news_item['filename']." does not exist");
      }
    }

    $n=strpos($news_full, '</b><br>');
    if ($n === false) { // note: three equal signs
        echo 'Error, on file '. $news_item['filename'] ."from $lang ";
        die('News title separator not found'); // not found...
    }

    // transform the relative paths into absolute
    //$description = ereg_replace('href="(?!http)', "href=\"$site",  substr($news_full, $n+9));
    $description = preg_replace('/href="(?!http)/i', "href=\"$site", substr($news_full, $n+9));

    $rss_items[]=array(
      "pub_date" => date("r", $news_item['unix_timestamp']),
      "url"  => $site,
      "guid" => $site.'index.html?filename=main.html#'.date('Y_m_d', $news_item['unix_timestamp']),
      "isPermaLink" => false,
      // make sure there are no &amp; otherwise they get converted into &amp;amp;
      "headline" => str_replace('&amp;', '&', substr($news_full, 3, $n-3)),
      "description" => str_replace('&amp;', '&', $description),
      "author" => "KDevelop webmaster",
      "category" => "KDevelop news"
    );
    $pub_date = max($news_item['unix_timestamp'], $pub_date);
  }

  // to avoid a warning in the include("... translations.inc")
  $filename='';

  // for $languages
  include($path_to_root."translations.inc");               // always include english as a fallback
  if ($lang != 'en')
    include($path_to_root."lang/$lang/translations.inc");  // the other languages

  $channel = array(
    'encoding' => $l_charset_encoding,
    'name' => 'KDevelop news',
    'url' => $site,
    'self_url' => $site.$rss_dir."kdevelop_news_$lang.rss",
    'description' => "KDevelop development news ($languages[$lang])",
    'language' => $lang,
    'pub_date' => date("r", $pub_date)
  );

  $fp = @fopen($path_to_root.$rss_dir."kdevelop_news_$lang.rss", "w");
  @fwrite($fp, create_rss_feed($channel, $rss_items)) or die ("File $news_filename not writeable");
  @fclose($fp);

}



// Establish a connection with the MySQL server
$mysql_link = @mysql_connect ("localhost", $k_login, $k_password);

// Exit if it canot get a connection to the MySQL database
if ($mysql_link == false)
  die('Could not connect to the database. Will not be able to create rss news feeds.
');

mysql_select_db("kdevelop_db");

// Make sure the news directory exists
if (!file_exists($path_to_root.$news_dir)){
  die("The dir <b>\"$path_to_root$news_dir\"</b> does not exist, please create it\n");
}

// Make sure the rss directory exists
if (!file_exists($path_to_root.$rss_dir)){
  die("The dir <b>\"$path_to_root$rss_dir\"</b> does not exist, please create it\n");
}

// get news
$news_items = get_news($news_dir);

// build one RSS news feed for each language
foreach($activelanguages as $lang)
  build_rss_feed($site, $rss_dir, $lang, $news_items);

?>