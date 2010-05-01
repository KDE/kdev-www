<?php
# begin 16 November 2004
# (c) 2004 -2007 Amilcar Lucas <amilcar@kdevelop.org>
# published under the GNU GPL - http://www.gnu.org/copyleft/gpl.html
#
# Process the apache server header:
#   - do redirections if necessary
#   - validate the parameters passed in the URI (filename and set_lang)
#   - connect to the MySQL database
#   - protect against bad e-mail collecting web robots
#   - collect visitors statistics
#   - set the language preference cookie
#
# The order in which these operations are performed is important
# and as far as I can tell the current implementation is optimal

function update_stats_pages($filename, $lang, $http_status_code) {

  $last_updated = '0000-00-00 00:00:00';
  if ($lang == 'en'){
    if (file_exists($filename))
      $last_updated = date('Y-m-d H:i:s',filemtime($filename));
  } else {
    if (file_exists("lang/$lang/$filename"))
      $last_updated = date('Y-m-d H:i:s',filemtime("lang/$lang/$filename"));
  }
/*
  // determine if the page already exists in the table
  $result = mysql_query("SELECT nr_visited FROM stats_pages WHERE lang = '$lang' AND filename = '$filename'");

  if (mysql_fetch_row($result) && $http_status_code == 200) {
    // Page already exists in the table, update it
    $sql = "UPDATE stats_pages SET last_updated = '$last_updated' , last_visited = NOW( '0000-00-00 00:00:00' ) ,  nr_visited = (nr_visited + 1)"
         . " WHERE lang = '$lang' AND filename = '$filename' LIMIT 1";
  } else {
    if($http_status_code == 200){
      // Page does not yet exist in the table, create it
      $sql = 'INSERT INTO stats_pages ( lang , filename , stats_since, last_updated , last_visited) '
         . " VALUES ( '$lang' , '$filename' , NOW( '0000-00-00 00:00:00' ), '$last_updated' , NOW( '0000-00-00 00:00:00' ) ) ";
    } else {
      // determine if the page already exists in the errors table
      $result = mysql_query("SELECT nr_visited FROM stats_page_error WHERE lang = '$lang' AND filename = '$filename'");

      $HTTP_REFERER     = isset($_SERVER['HTTP_REFERER'])?addslashes(trim($_SERVER['HTTP_REFERER'])):'';
      $HTTP_USER_AGENT  = isset($_SERVER['HTTP_USER_AGENT'])?addslashes(trim($_SERVER['HTTP_USER_AGENT'])):'';
      $HTTP_REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR'])?addslashes(trim($_SERVER['REMOTE_ADDR'])):'';

      if (mysql_fetch_row($result)) {
        // Page already exists in the error table, update it
        $sql = "UPDATE stats_page_error SET last_visited = NOW( '0000-00-00 00:00:00' ) , nr_visited = (nr_visited + 1), last_referer = '$HTTP_REFERER', last_useragent = '$HTTP_USER_AGENT' , status = '$http_status_code' , ip = '$HTTP_REMOTE_ADDR'" .
             "WHERE lang = '$lang' AND filename = '$filename' LIMIT 1";
      } else {
        // Page does not yet exist in the error table, create it
        $sql = 'INSERT INTO stats_page_error ( lang , filename , status , first_visited , last_visited, last_referer, last_useragent, ip) ' .
            " VALUES ( '$lang' , '$filename' , '$http_status_code' , NOW( '0000-00-00 00:00:00' ) , NOW( '0000-00-00 00:00:00' ) , '$HTTP_REFERER', '$HTTP_USER_AGENT','$HTTP_REMOTE_ADDR' ) ";
      }
    }
  }
  $result = mysql_query($sql);
*/
}


// This code is required for safe webservers that use "register globals off"
global $filename;
global $lang;
global $navmenu;

// This extracts the "cookie" and "get" variables manually one by one (required for security reasons)
if (isset($_COOKIE['lang']) and strlen($_COOKIE['lang'])<7)
  $lang=addslashes(trim($_COOKIE['lang']));
if (isset($_COOKIE['navmenu']))
  $navmenu=addslashes(trim($_COOKIE['navmenu']));
if (isset($_GET['filename'])and strlen($_GET['filename'])<256)
  $filename=addslashes(trim($_GET['filename']));
if (isset($_GET['set_lang']) and strlen($_GET['set_lang'])<7)
  $set_lang=addslashes(trim($_GET['set_lang']));
if (isset($_GET['set_navmenu']))
  $set_navmenu=addslashes(trim($_GET['set_navmenu']));

// If the filename contains 'filename=' then it comes from a broken webspider robot.
// We do not like broken webspider robots :)
if (strpos($filename, 'filename=') !== FALSE){
  header('HTTP/1.0 404 Not Found');
  exit();
}

if (isset($_SERVER['HTTP_HOST'])){
// redirect http://fara.cs.uni-potsdam.de/~smeier/www requests to http://www.kdevelop.org
// we need this to make sure the robots.txt file gets read
if (strpos($_SERVER['HTTP_HOST'], 'fara.cs.uni-potsdam.de') !== FALSE ||
    strpos($_SERVER['HTTP_HOST'], 'burns.cs.uni-potsdam.de') !== FALSE ||
    strpos($_SERVER['HTTP_HOST'], '141.89.48.223') !== FALSE){
  header('HTTP/1.1 301 Moved Permanently');
  // remove the '~smeier/www'
  header('Location: http://www.kdevelop.org'.substr($_SERVER['REQUEST_URI'], 12));
  exit();
}

// Redirect the aliases, because it messes up the search engines scores
if (strpos($_SERVER['HTTP_HOST'], 'kdevelop.kde.org') !== FALSE ||
    strpos($_SERVER['HTTP_HOST'], 'kdevelop.de') !== FALSE ||
    strpos($_SERVER['HTTP_HOST'], 'kdevelop.org') === 0 ){  // the === is to redirect kdevelop.org to www.kdevelop.org
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: http://www.kdevelop.org'.$_SERVER['REQUEST_URI']);
  exit();
}
}
// Use the bad behavior mediawiki extension to protect the website (without DB support)
//require_once('mediawiki/extensions/bad-behavior2/bad-behavior-generic.php');

// default HTTP STATUS code
$http_status_code = 200;

require('bin/website_globals.php');

// Hack because both apache's mod_alias and mod_rewrite modules do not
// redirect the kind of URL's that we use on this site
// these things are here in the "most requested first" order (for speed reasons)
switch ($filename) {

  # Use main.html as default

  case '':
//    Serve protest_against_software_patents.html as entry page when no specific page was specified
//    include_once('protest_against_software_patents.html');
//    exit();
    $filename='main.html';
    break;

  case 'index.html': // prevent this file from including itself
    header('HTTP/1.1 303 See other');
    $http_status_code = 303;
    header('Location: .');
    break;

  # These are actually alias to the most stable version. They should be changed
  # to point to the newest stable version whenever a new stable version is released

  case 'download.html':
    $filename="$lsv/download.html";
    break;
  case 'features.html':
    $filename="$lsv/features.html";
    break;
  case 'requirements.html':
    $filename="$lsv/requirements.html";
    break;
  case 'screenshots.html':
  case 'screenshots1.html':
    $filename="$lsv/screenshots.html";
    break;
#  case 'faq.html':
#    $filename="$lsv/faq.html";
#    break;
  case 'faq.html':
    $filename='3.3/faq.html';
    break;
  case 'changes.html':
    $filename="$lsv/changes.html";
    break;
  case 'ChangeLog.html':
    $filename="$lsv/ChangeLog.html";
    break;
  case 'kdevelop.html':
    $filename="$lsv/kdevelop.html";
    break;
  case 'branches_compiling.html':
    $filename="$lsv/branches_compiling.html";
    break;
  case 'authors.html':
    $filename="$lsv/authors.html";
    break;
  case 'screenshots2.html':
    $filename="$lsv/screenshots.html";
    break;
  case 'screenshots3.html':
    $filename="$lsv/screenshots.html";
    break;

  // Temporally redirect HEAD pages to the wiki
  case 'HEAD/download.html':
  case 'HEAD/branches_compiling.html':
    header('HTTP/1.1 303 See other');
    header('Location: mediawiki/index.php/KDevelop_4/compiling');
    exit();
    break;
  case 'HEAD/features.html':
    header('HTTP/1.1 303 See other');
    header('Location: mediawiki/index.php/KDevelop_4/Feature_Plan');
    exit();
    break;
  case 'HEAD/requirements.html':
    header('HTTP/1.1 303 See other');
    header('Location: mediawiki/index.php/KDevelop_4/requirements');
    exit();
    break;
  case 'HEAD/screenshots1.html':
  case 'HEAD/screenshots2.html':
  case 'HEAD/screenshots3.html':
    header('HTTP/1.1 303 See other');
    header('Location: mediawiki/index.php/KDevelop_4#Screenshots');
    exit();
    break;
  case 'HEAD/changes.html':
  case 'HEAD/kdevelop.html':
    header('HTTP/1.1 303 See other');
    header('Location: mediawiki/index.php/KDevelop_4');
    exit();
    break;


  // This group is for the "wise" guys that try to be "smart arses"
  case 'HEAD':
  case 'HEAD/':
  case 'HEAD/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=HEAD/kdevelop.html');
    $http_status_code = 301;
    break;
  case '1.3':
  case '1.3/':
  case '1.3/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=1.3/kdevelop.html');
    $http_status_code = 301;
    break;
  case '2.1':
  case '2.1/':
  case '2.1/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/kdevelop.html');
    $http_status_code = 301;
    break;
  case '3.0':
  case '3.0/':
  case '3.0/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.0/kdevelop.html');
    $http_status_code = 301;
    break;
  case '3.1':
  case '3.1/':
  case '3.1/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.1/kdevelop.html');
    $http_status_code = 301;
    break;
  case '3.2':
  case '3.2/':
  case '3.2/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.2/kdevelop.html');
    $http_status_code = 301;
    break;
  case '3.3':
  case '3.3/':
  case '3.3/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.3/kdevelop.html');
    $http_status_code = 301;
    break;
  case '3.4':
  case '3.4/':
  case '3.4/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.4/kdevelop.html');
    $http_status_code = 301;
    break;
  case '3.5':
  case '3.5/':
  case '3.5/.':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.5/kdevelop.html');
    $http_status_code = 301;
    break;
}

// We also do not like hackers, but we at least tell them they're wrong and we log their attempts.
if ( ($http_status_code == 200) && (($filename[0]=='/') || strstr($filename,'./') || strstr($filename,'..') || strstr($filename,'//')) ) {
  $http_status_code = 404;
}

// The .ihtml pages are not to be served directly.
// They have to be included from other files, so tell that they are gone
if ( ($http_status_code == 200) && strpos($filename,'.ihtml') !== FALSE ){
  $http_status_code = 410;
}

// Warn if not .html file was requested (DO NOT SERVE .php file, because of the chat, wiki and forum)
if ( ($http_status_code == 200) && substr($filename, -5) != '.html' ){
  $http_status_code = 400;
}

// Warn if filename is chat/index.html
if ( ($http_status_code == 200) && ($filename == 'chat/index.html') ){
  $http_status_code = 404;
}

// Warn if filename contains admin
if ( ($http_status_code == 200) && strpos($filename,'admin') !== FALSE ){
  $http_status_code = 404;
}

// This needs to be here because the file exists.
//if (($http_status_code == 200) && ($filename == "$lsv/faq.html" || $filename == 'HEAD/faq.html')){
if (($http_status_code == 200) && ($filename == 'HEAD/faq.html')){
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: mediawiki/index.php/FAQ');
  $http_status_code = 301;
}

// This needs to be here because the file exists.
if (($http_status_code == 200) && ($filename == 'dynamic/website_translation_status.html')){
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: index.html?filename=website_translation_status.html');
  $http_status_code = 301;
}

// This needs to be here because the file exists.
if (($http_status_code == 200) && ($filename == 'dynamic/optional_files_translation_status.html')){
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: index.html?filename=optional_files_translation_status.html');
  $http_status_code = 301;
}

// Prevent attempts to access a language file directly
// This needs to be here because the file (potentially) exists.
if (($http_status_code == 200) && strpos($filename,'lang/') !== FALSE){
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: .');
  $http_status_code = 301;
}

// If file does not exist warn the user in an elegant way
if ( ($http_status_code == 200) && (!file_exists($filename))) {

  // Hack because both apache's mod_alias and mod_rewrite modules do not
  // redirect the kind of URL's that we use on this site
  // these things are here in the "most requested first" order (for speed reasons) 
  switch ($filename) {

  # These are the permanent redirects for pages that where permanently moved

  case 'HEAD/kdevelop_po_status.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://l10n.kde.org/stats/gui/trunk-kde4/package/kdevelop/');
    exit();
    break;
  case '3.5/screenshots1.html':
  case '3.5/screenshots2.html':
  case '3.5/screenshots3.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.5/screenshots.html');
    exit();
    break;
  case '3.4/faq.html':   # fallback to 3.3 faq, it has been updated up-to 3.5
  case '3.5/faq.html':   # fallback to 3.3 faq, it has been updated up-to 3.5
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.3/faq.html');
    exit();
    break;

  case '3.0/tutorials.html':
  case '3.1/tutorials.html':
  case '3.2/tutorials.html':
  case '3.3/tutorials.html':
  case '3.4/tutorials.html':
  case '3.5/tutorials.html':
  case 'HEAD/tutorials.html':
  case 'books.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=tutorials.html');
    exit();
    break;
  case 'announce-kdevelop-3.0.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.0/announce-kdevelop-3.0.html');
    exit();
    break;
  case 'announce-kdevelop-2.0.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/announce-kdevelop-2.0.html');
    exit();
    break;
  case 'tutorial_autoconf.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=3.0/doc/tutorial_autoconf.html');
    exit();
    break;
  case '2.x/kdevelop.html':
  case 'kdevelop-2.x.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/kdevelop.html');
    exit();
    break;
  case 'kdev2prev.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/features.html');
    exit();
    break;
  case 'changelog-2.0beta1.html':
  case 'changelog-2.0.html':
  case 'changelog-2.0.1.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/changes.html');
    exit();
    break;
  case 'community.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=events/linuxtag2000.html');
    exit();
    break;
  case 'linuxtag2001.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=events/linuxtag2001.html');
    exit();
    break;
  case 'kastle2003.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=events/kastle2003.html');
    exit();
    break;
  case 'pressrelease1.0.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=1.3/pressrelease1.0.html');
    exit();
    break;
  case 'pressrelease1.1.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=1.3/pressrelease1.1.html');
    exit();
    break;
  case 'guestbook/eintragen.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: phorum5/read.php?10,26111');
    exit();
    break;
  case 'todo-kdev2.0-5june2001.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=current_work.html');
    exit();
    break;
  case 'features-2.x.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/features.html');
    exit();
    break;
  case 'changes-2.x.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/changes.html');
    exit();
    break;
  case 'screenshots1-2.x.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/screenshots1.html');
    exit();
    break;
  case 'screenshots2-2.x.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/screenshots2.html');
    exit();
    break;
  case 'screenshots3-2.x.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=2.1/screenshots3.html');
    exit();
    break;
  case 'dynamic/sitemap.html':
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: index.html?filename=sitemap.html');
    break;

  # These are the 'see other' redirects for pages that have related content

  case 'i18n.html':
  case 'i18n-howto.html':
  case 'doc-howto.html':
    header('HTTP/1.1 303 See other');
    header('Location: http://l10n.kde.org/docs/translation-howto/');
    $http_status_code = 303;
    break;
  case 'kdk.html':
  case 'plugins_patches.html':
    header('HTTP/1.1 303 See other');
    header('Location: index.html?filename=HEAD/download.html');
    $http_status_code = 303;
    break;

  # These are gone and will never come back

  case 'forum_picture_corner.ihtml':
  case 'quick_downloads.ihtml':
  case 'menu.ihtml':
  case '3.3/kdevelop_po_status.html':
    $http_status_code = 410;
    break;

  # If we got to this point means that the file does not exist now and never did exist in the past

  default:
    $http_status_code = 404;
  }
}


// Set language if $set_lang was passed in the URI, this will overwrite the value possibly set by the cookie
if (($http_status_code == 200) && !empty($set_lang)){
  // if set_lang=en was passed in the URI, then place a cookie to remember it and redirect to the URI without
  // the set_lang. This is to avoid duplicated english content (URIs with set_lang=en and URIs without set_lang)
  // It is specially designed for search-engine crawl robots as it improves our ranking and reduces bandwidth
  // It is done here to avoid the statistics queries
  if ($set_lang == 'en') {
    // This setCookie() should only be done after sending all the header() commands, but in THIS case it has to be done before
    setCookie('lang',$set_lang,time()+31536000,'/','',0);
    header('HTTP/1.1 301 Moved Permanently');
    if ($filename != 'main.html')
      header("Location: index.html?filename=$filename");
    else
      header("Location: ."); // this prevents a double redirect in www.kdevelop.org/index.html?filename=main.html&set_lang=en
    exit();
  }
  $lang=$set_lang;
}

// If language was not defined in the cookie or URI, try to use the language predefined in the user's browser
if (($http_status_code == 200) && empty($lang) && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
  $accepted_languages=explode(',', addslashes($_SERVER['HTTP_ACCEPT_LANGUAGE']));
  for ($j=0;$j<count($accepted_languages);$j++){
    if (!empty($accepted_languages[$j])) {

      $accepted_languages[$j]=strtolower(strtr(substr(trim($accepted_languages[$j]),0,5),'-','_'));

      // try to match the first 5 characters
      for ($i=0;$i<count($activelanguages);$i++){
        if (strstr(strtolower($activelanguages[$i]), $accepted_languages[$j])){
          $lang=$activelanguages[$i];
          break;
        }
      }

      // get out of the for loop as soon as a match is found
      if (!empty($lang)) break;

      // try to match the first 2 characters
      for ($i=0;$i<count($activelanguages);$i++){
        if (strstr(strtolower($activelanguages[$i]), substr($accepted_languages[$j],0,2))){
          $lang=$activelanguages[$i];
          break;
        }
      }

      // get out of the for loop as soon as a match is found
      if (!empty($lang)) break;
    }
  }
  unset($accepted_languages,$i,$j);
}

// If language was not defined, fall back to english
if (empty($lang)){
  $lang='en';
}
// if all is ok, but the URI == www.kdevelop.org/index.html?filename=main.html and the language is English,
// redirect to www.kdevelop.org/
// this is only done for the English language, all others use the explicit URI
if ( $http_status_code == 200 && isset($_GET['filename']) && $filename == 'main.html' && $lang == 'en' ) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: .');
    exit();
}
// Big hack for Portuguese from Brazil backwords compatability :(
if ( ($http_status_code == 200) && ($lang == 'br') ){
    header('HTTP/1.1 301 Moved Permanently');
    $http_status_code = 301;
    header("Location: index.html?filename=$filename&set_lang=pt_BR");
}
// Big hack for Czech backwords compatability :(
if ( ($http_status_code == 200) && ($lang == 'cz') ){
    header('HTTP/1.1 301 Moved Permanently');
    $http_status_code = 301;
    header("Location: index.html?filename=$filename&set_lang=cs");
}
// Do not allow trailing '/' characters
if ( ($http_status_code == 200) && strlen($lang)>2 && ($lang[2] == '/') ){
    header('HTTP/1.1 301 Moved Permanently');
    $http_status_code = 301;
    header("Location: index.html?filename=$filename&set_lang=".substr($lang,0,2));
}

// if language does not exist redirect to english
if ( ($http_status_code == 200) && ($lang != 'en') && (!is_dir("lang/$lang/")) ) {
    header('HTTP/1.1 303 See other');
    $http_status_code = 303;
    header("Location: index.html?filename=$filename&set_lang=en");
}

// avoid URLs like http://www.kdevelop.org/?filename=    (the index.html is missing)
// Redirect them to the the "index.html?filename=" version because otherwise it messes up the search engines scores
// todo THIS DOES NOT WORK YET FOR localhost/~amilcar/www/ URLs
if ($http_status_code == 200 && ((substr($_SERVER['REQUEST_URI'],0,11)!='/index.html' && strlen($_SERVER['REQUEST_URI']) > 1) ||
   // also redirect if "filename" or "set_lang" parameter is missing or if too many parameters where supplied
   (!isset($_GET['filename']) && count($_GET)>0) || (!isset($_GET['set_lang']) && count($_GET)>1) || count($_GET)>2)){
  if (!isset($_GET['set_navmenu'])){
  header('HTTP/1.1 301 Moved Permanently');
  if ($filename == 'main.html' && $lang == 'en')
    header("Location: .");
  else
    if (!isset($_GET['set_lang']))
      header("Location: index.html?filename=$filename");
    else
      header("Location: index.html?filename=$filename&set_lang=$lang");
  exit();
  }
}

// get the MySQL global login and password
require('../access.inc');

// Establish a permanent connection with the MySQL server
$mysql_link = @mysql_pconnect ('localhost', $k_login, $k_password);

// Exit if it can not get a connection to the MySQL database
if ($mysql_link == false){
  $http_status_code = 503;
} elseif (mysql_select_db('kdevelop_db') == false) {
  $http_status_code = 503;
} else {

  // delete the login and password variables for safety reasons
  unset($k_login);
  unset($k_password);

  // Use the bad behavior mediawiki extension to protect the website (with DB support)
  //require_once('mediawiki/extensions/bad-behavior2/bad-behavior-kdevelop-with-db.php');

  // Count the hits of bad behaved robots
  // and forbid them to do many requests on a row
  $http_status_code = protect_against_bad_robots($http_status_code, $timeout_minutes, $bad_hits_treshold, $trapped_treshold);

  // Update the website statistics of the visited pages
  update_stats_pages($filename,$lang,$http_status_code);
}

// Send an error page if required
send_error_page($http_status_code);

// Set a language cookie if $set_lang was passed in the URI this will
// allow the server to remember which language the user choose for future visits.
// This should only be done after sending all the header() commands
if (!empty($set_lang)){
  setCookie('lang',$set_lang,time()+31536000,'/','',0);
}


// Set navmenu if $set_navmenu was passed in the URI
if (!empty($set_navmenu)){
  // This has to be done this way to make sure that $navmenu can only be 'user' or 'developer'
  if ($set_navmenu=='user')
    $navmenu='user';
  else
    $navmenu='developer';
}

// If navmenu was not defined, fall back to normal user
if (empty($navmenu)){
  $navmenu='user';
}

// Set the cookie
if (!empty($set_navmenu)){
  setCookie('navmenu',$navmenu,time()+31536000,'/','',0);
}

unset($http_status_code);
