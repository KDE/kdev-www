#!/usr/bin/php
<?php
#
# This script should be invoked by a cron job of an user with write permissions on the
# root directory on the website
#
# The purpose of this script is to create a XML sitemap of the KDevelop website for
# search-engine robots.
# The generated sitemap file follows the protocol form http://www.sitemaps.org/protocol.php
#
# begin January 2008
# (c) 2008 Amilcar Lucas <amilcar@kdevelop.org>
# published under the GNU GPL

$debug=0;

// for $lsv
include('website_globals.php');

// get the MySQL global login and password
include ('../../access.inc');

// Establish a connection with the MySQL server
$mysql_link = @mysql_connect ('localhost',$k_login,$k_password);
unset($k_login);
unset($k_password);

// Get the username of the owner of this file
$owner = posix_getpwuid(fileowner(__FILE__));
$owner_name = $owner['name'];
// serve the links according to the file owner
if ($owner_name == 'smeier')
  $server_url='http://www.kdevelop.org/';
else
  $server_url="http://localhost/~$owner_name/www/";
unset($owner, $owner_name);

// Exit if it can not get a connection to the MySQL database
if ($mysql_link == false)
    die('Could not connect to the database. Sitemap for robots will not be updated.
');

mysql_select_db('kdevelop_db');

$result = mysql_query("SELECT SUM(`avr_daily_visits`)/COUNT(`avr_daily_visits`) AS avr_daily_hits
FROM `stats_pages` WHERE
 `filename` != 'main.html' and
(`filename` != 'users.html' or `lang` = 'en') and
(`last_updated` != '0000-00-00 00:00:00' or
  (`filename` = 'main2007.html' or
   `filename` = 'sitemap.html' or
   `filename` = 'website_translation_status.html' or
   `filename` = 'optional_files_translation_status.html')
)");
while($row = mysql_fetch_object($result)) {
    $avr_non_mainhtml_visits=(double)$row->avr_daily_hits;
}

$result = mysql_fetch_array(mysql_query("show table status like 'user_progs'")); 
$user_progs_lastmod=strtotime($result['Update_time']);

$result = mysql_query('SELECT `lang`, `filename`, `avr_daily_visits` FROM `stats_pages` ORDER BY '.($debug==1?'`filename` ASC':'`avr_daily_visits` DESC').' LIMIT 0, 9000');
$today=time();
$pages=0;
chdir('..');

if ($debug==1){
  echo "<p>Average 'non-main.html' hits: $avr_non_mainhtml_visits</p>\n";
  echo "<table align=\"center\" width=\"80%\" cellspacing=\"2\" cellpadding=\"2\" border=\"1\">\n";
  echo "<tr><th>lang</th><th>filename</th><th>lastmod</th><th>changefreq</th><th>priority</th></tr>\n";
} else {
  $sitemap='<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';
}

$navmenu_lastmod = max(filemtime('inc/menu_normal_user.php'), filemtime('index.html'), filemtime('inc/quick_downloads.php'));

while($row = mysql_fetch_object($result)) {
  $filepath=($row->lang!='en'?"lang/$row->lang/$row->filename":$row->filename);
  $lastmod=false;
  $changefreq='';
  /* $changefreq can take one of the folowing values:
         always hourly daily weekly monthly yearly never
  */
  if (strpos($row->filename,'guestbook') !== FALSE)   // skip guestbook files
    continue;

  if (file_exists($filepath)){
    $lastmod=filemtime($filepath);
  }
  // needs to be out of the loop because the translated files do not exist
  if ($row->filename == 'main.html'){
    $lastmod=filemtime('news/main.ihtml');
    $changefreq='weekly';
  }
  // needs to be out of the loop because the translated files do not exist
  if ($row->filename == 'sitemap.html'){
    $filepath='dynamic/sitemap_'. $row->lang .'.png';
    if (file_exists($filepath))
      $lastmod=filemtime($filepath);
    $changefreq='monthly';
  }
  if ($row->filename == 'website_translation_status.html'){
    $lastmod=$today;
    $changefreq='monthly'; // be conservative, the translators only submit translations once a month
  }
  if ($row->filename == 'optional_files_translation_status.html'){
    if ($row->lang == 'en'){
      $lastmod=$today;
      $changefreq='monthly'; // be conservative, the translators only submit translations once a month
    } else
      continue;
  }
  if (ereg('^main([0-9]{4}).html',$row->filename,$regs)){
    // This is only valid for files after the year 2007
    if ($regs[1] >= 2007)
      $lastmod=filemtime($row->filename);
  }
  if ($lastmod==false)   // if the file does not exist (not translated) then skip it
    continue;
  if ($row->filename == 'bugs.html'){
    $lastmod=$today;
    $changefreq='weekly'; // be conservative, most times only a few bugs change per week
  }
  if ($row->filename == 'stats.html'){
    $lastmod=$today;
    $changefreq='weekly';
  }
  if ($row->filename == 'users.html'){
    if ($row->lang == 'en'){
      $lastmod=$user_progs_lastmod;
      $changefreq='monthly';
    } else
      continue;
  }
  if ($row->filename == 'versions.html'){
    $lastmod=filemtime('versions.ihtml');
    $changefreq='monthly';
  }
  if (ereg('^([0-9]\.[0-9])/download.html',$row->filename,$regs)){
    $lastmod=filemtime("$regs[1]/download_binaries.ihtml");
  }

  $priority = 0.5*$row->avr_daily_visits/$avr_non_mainhtml_visits;
  $priority = ($priority>1.0?1.0:$priority);  // acording to www.sitemaps.org saturate at 1.0

  // If "version" pages or "yearly news" pages
  if (ereg('^[0-9]\.[0-9]/',$row->filename) or ereg('^main([0-9]{4}).html',$row->filename)){
    $changefreq='never';
    if (!ereg('^'.quotemeta($lsv),$row->filename))
      $priority -= 0.1; // penalize old, non "latest stable version" ($lsv) files in -0.1 priority
  }
  // If "HEAD" or $lsv version pages
  if (ereg('^HEAD',$row->filename) or ereg('^'.quotemeta($lsv),$row->filename)){
    if (strpos($row->filename,'ChangeLog') !== FALSE){
      $lastmod=$today;
      $changefreq='weekly';
    }else
      $changefreq='yearly';
  }

  $loc=$server_url;
  if ($row->filename != 'main.html' or $row->lang != 'en')
    //$loc.="&#105;&#110;&#100;&#101;&#120;&#046;&#104;&#116;&#109;&#108;?filename=$row->filename";
    $loc.="index.html?filename=$row->filename";
  if ($row->lang != 'en')
    $loc.="&amp;set_lang=$row->lang";

  $priority = ($priority<0.01?0.01:$priority);
  $priority = sprintf ('%.02f', $priority);   // 2 significant digits

  $lastmod=date('Y-m-d', max($lastmod, $navmenu_lastmod));

  if ($debug==1)
    echo "<tr><td>$row->lang</td><td>$row->filename</td><td>$lastmod</td><td>$changefreq</td><td>$priority</td></tr>\n";
   else {
    $sitemap.="<url><loc>$loc</loc>";
    $sitemap.="<lastmod>$lastmod</lastmod>";
    if (!empty($changefreq)) $sitemap.="<changefreq>$changefreq</changefreq>";
    $sitemap.="<priority>$priority</priority></url>\n";
  }
  $pages++;
}

$result = mysql_query("SELECT * FROM `pages` WHERE prefix = '../' and sitemap = '1' ORDER BY `filename` DESC LIMIT 0, 9000");
while($row = mysql_fetch_object($result)) {
  $lastmod=false;
  $changefreq='never';
  $priority='';
  $filepath=$row->filename;
  if (file_exists($filepath)){
    $lastmod=filemtime($filepath);
  }
  if ($row->filename == 'phorum5/'){
    $row->filename='phorum5/index.php';  // this in the only URL allowed in the robots.txt file
    $lastmod=$today;
    $changefreq='monthly';
  }
  if ($row->filename == 'mediawiki/'){
    continue;  // the mediawiki links are described in an extra, separated sitemap file
    $lastmod=$today;
    $changefreq='monthly';
  }
  if ($row->filename == 'chat/'){
    $lastmod=filemtime('chat/index.html');
    $changefreq='never';
  }
  if (ereg('^HEAD/doc',$row->filename)){
    $row->filename .= 'html/index.html';  // add path to generated directory
    $lastmod=@filemtime($row->filename);
    //$changefreq='daily';
  }
  if ($lastmod > 0)
    $lastmod=date('Y-m-d', $lastmod);
  else
    unset($lastmod);
  if ($debug==1)
    echo "<tr><td> </td><td>$row->filename</td><td>$lastmod</td><td>$changefreq</td><td> </td></tr>\n";
   else {
    $sitemap.='<url><loc>'.$server_url.$row->filename.'</loc>';
    if (!empty($lastmod)) $sitemap.="<lastmod>$lastmod</lastmod>";
    if (!empty($changefreq)) $sitemap.="<changefreq>$changefreq</changefreq>";
    if (!empty($priority)) $sitemap.="<priority>$priority</priority>";
    $sitemap.="</url>\n";
  }
  $pages++;
}

if ($debug==1){
  echo "</table>\n";
  echo "<p>total pages: $pages</p>";
} else {
  $sitemap.='</urlset>';
  $fp = @gzopen('sitemap-translated.xml.gz', 'w9');
  @gzwrite($fp, $sitemap) or die ('Could not write sitemap-translated.xml.gz file');
  @gzclose($fp);
}

  // dynamically create the sitemap index file in order to have a lastmod field
  $fp = @fopen('sitemap.xml', 'w');
  fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
') or die ('Could not write sitemap.xml file');
  $mod_time=filemtime('sitemap-translated.xml.gz');
  if ($mod_time > 0)
    fwrite($fp, '   <sitemap>
      <loc>http://www.kdevelop.org/sitemap-translated.xml.gz</loc>
      <lastmod>'.gmdate('c',$mod_time)."</lastmod>
   </sitemap>\n");
  $mod_time=filemtime('sitemap-mediawiki.xml.gz');
  if ($mod_time > 0)
    fwrite($fp, '   <sitemap>
      <loc>http://www.kdevelop.org/sitemap-mediawiki.xml.gz</loc>
      <lastmod>'.gmdate('c',$mod_time)."</lastmod>
   </sitemap>\n");
  fwrite($fp, "</sitemapindex>\n");
  fclose($fp);

?>
