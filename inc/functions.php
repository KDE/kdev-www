<?php

function protect_against_bad_robots($http_status_code, $timeout_minutes, $bad_hits_treshold, $trapped_treshold) {
/*
  // return if database connection failed
  if ($http_status_code == 503)
    return $http_status_code;

  $HTTP_USER_AGENT  = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
  $HTTP_REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';

  // determine if the robot already exists in the table
  $result = mysql_query("SELECT ip, e403, e404, e400, trapped, UNIX_TIMESTAMP(last_visited) as unix_last_visited FROM stats_bad_robots WHERE ip = '$HTTP_REMOTE_ADDR'");
  $row = mysql_fetch_object($result);

  // Kick out some known bad robots
  if( $http_status_code == 200 &&
    (strstr($HTTP_USER_AGENT, 'LWP::Simple') ||
     strstr($HTTP_USER_AGENT, 'Microsoft URL') ||
     strstr($HTTP_USER_AGENT, 'Wget') ||
     strstr($HTTP_USER_AGENT, 'Java/1.4') ||
     strstr($HTTP_USER_AGENT, 'Locator') ||
     strstr($HTTP_USER_AGENT, 'EMPAS_ROBOT') ||
     strstr($HTTP_USER_AGENT, 'EmailSiphon') ||
     strstr($HTTP_USER_AGENT, 'Shareware') ||
     strstr($HTTP_USER_AGENT, 'User-Agent') ||
     strstr($HTTP_USER_AGENT, 'fastsearch') ||
     strstr($HTTP_USER_AGENT, 'Harvest') ||
     strstr($HTTP_USER_AGENT, 'libwww'))) {
    $http_status_code = 403;
  }

  // Trap Googlebot impersonators
  if ($http_status_code == 200 &&
     !(strstr($HTTP_REMOTE_ADDR, '66.249.') or strstr($HTTP_REMOTE_ADDR, '64.233.') or strstr($HTTP_REMOTE_ADDR, '216.239.')) &&
     strstr($HTTP_USER_AGENT, 'Googlebot')) {
    $http_status_code = 403;
  }

  // Trap Yahoo Slurp impersonators
  if ($http_status_code == 200 && strstr($HTTP_USER_AGENT, 'Slurp')){
    // if "HostnameLookups" is set to "off" in apache configuration, we need to explicitly call gethostbyaddr()
    $HTTP_REMOTE_HOST = isset($_SERVER['REMOTE_HOST'])?$_SERVER['REMOTE_HOST']:gethostbyaddr($_SERVER['REMOTE_ADDR']);
    if (!strstr($HTTP_REMOTE_HOST, 'yahoo.net') && !strstr($HTTP_REMOTE_HOST, 'yahoo.com') )
      $http_status_code = 403;
  }

  // Not an error and the error timed out so delete it from the database
  if ($http_status_code == 200 && $row && ($row->unix_last_visited + ($timeout_minutes * 60)) < time() && $row->trapped == 0 && $row->e403 < $bad_hits_treshold){
    mysql_query("DELETE FROM stats_bad_robots WHERE ip = '$HTTP_REMOTE_ADDR'");
    unset($row);
  }

  // Do not forbid google robot crawlers
  if (isset($row) && $row && !strstr($HTTP_REMOTE_ADDR, '66.249.')) {
    // If previously was already forbiden, or misbehaved (404+400), or traped (bad spam robot)
    if (($row->e403 > 0) || ($row->e404 + $row->e400 >= $bad_hits_treshold ) || ($row->trapped >= $trapped_treshold)) {
      $http_status_code = 403;
    }
  }

  // If forbidden a lot of times, then trap it
  if (isset($row) && $row && $row->e403 >= 15 ) {
    chdir('inc');
    $trapped_again = 1;
    include('spam_trap_page.php');
    exit();
  }

  // When the user does not identify himself, we do not serve him
  if($http_status_code == 200 && empty($HTTP_USER_AGENT)){
    header('HTTP/1.1 412 Precondition Failed');
    include('errorEmptyUA.html');
    $http_status_code = 412;
  }

  // Log the result into the database
  if ($http_status_code == 403 || $http_status_code == 404 || $http_status_code == 412 || $http_status_code == 400){
    // if "HostnameLookups" is set to "off" in apache configuration, we need to explicitly call gethostbyaddr()
    if (!isset($HTTP_REMOTE_HOST))
      $HTTP_REMOTE_HOST = isset($_SERVER['REMOTE_HOST'])?$_SERVER['REMOTE_HOST']:gethostbyaddr($_SERVER['REMOTE_ADDR']);
    if (isset($row) && $row) {
      // Robot IP already exists in the table, update it
      $sql = "UPDATE stats_bad_robots SET e$http_status_code = (e$http_status_code + 1), last_host='$HTTP_REMOTE_HOST', last_useragent='$HTTP_USER_AGENT', last_visited=NOW('0000-00-00 00:00:00') WHERE ip='$HTTP_REMOTE_ADDR'";
    } else {
      // Robot IP does not yet exist in the table, create it
      $sql = "INSERT INTO stats_bad_robots ( ip, e$http_status_code, last_host, last_useragent, first_visited, last_visited ) VALUES ( '$HTTP_REMOTE_ADDR', '1', '$HTTP_REMOTE_HOST', '$HTTP_USER_AGENT', NOW('0000-00-00 00:00:00'), NOW('0000-00-00 00:00:00') )";
    }
    $result = mysql_query($sql);
  }
*/
  return $http_status_code;
}


function isSpider ( $userAgent ) {
  if ( stristr($userAgent, 'Googlebot')    || /* Google */
    stristr($userAgent, 'Slurp')    || /* Inktomi/Y! */
    stristr($userAgent, 'MSNBOT')   || /* MSN */
    stristr($userAgent, 'teoma')    || /* Teoma */
    stristr($userAgent, 'ia_archiver')    || /* Alexa */
    stristr($userAgent, 'Scooter')    || /* Altavista */
    stristr($userAgent, 'Mercator')   || /* Altavista */
    stristr($userAgent, 'FAST')    || /* AllTheWeb */
    stristr($userAgent, 'MantraAgent')    || /* LookSmart */
    stristr($userAgent, 'Lycos')    || /* Lycos */
    stristr($userAgent, 'ZyBorg')    /* WISEnut */
  ) return TRUE;
  return FALSE;
}


function detectProxy(&$ar) {

  $gotcha=false;

  if (array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER) ||
      array_key_exists('HTTP_PROXY_CONNECTION',$_SERVER) ||
      array_key_exists('HTTP_VIA',$_SERVER)) {
    $gotcha=TRUE;
  }

  $gotcha = (stristr($_SERVER['REMOTE_HOST'],"proxy") !== FALSE ) ? TRUE : $gotcha;

  if($gotcha) {
    $ar['PORT']=     (array_key_exists('REMOTE_PORT',$_SERVER) ? $_SERVER['REMOTE_PORT'] : "unknown");
    $ar['HOST']=     (array_key_exists('REMOTE_HOST',$_SERVER) ? $_SERVER['REMOTE_HOST'] : "unknown");
    $ar['IP']=       (array_key_exists('REMOTE_ADDR',$_SERVER) ? $_SERVER['REMOTE_ADDR'] : "unknown");
    $ar['FORWARDED_FOR']=(array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : "unknown");
    $ar['INFO']=     (array_key_exists('HTTP_VIA',$_SERVER) ? $_SERVER['HTTP_VIA'] : "unknown");
  } else {
    $ar['PORT']=     (array_key_exists('REMOTE_PORT',$_SERVER) ? $_SERVER['REMOTE_PORT'] : "unknown");
    $ar['HOST']=     (array_key_exists('REMOTE_HOST',$_SERVER) ? $_SERVER['REMOTE_HOST'] : "unknown");
    $ar['IP'  ]=     (array_key_exists('REMOTE_ADDR',$_SERVER) ? $_SERVER['REMOTE_ADDR'] : "unknown");
  }

  return $gotcha;
}
/*
$a=array();
print "<pre>";
detectProxy($a) ? print_r($a) : print "Not using a proxy.<br>";
print "</pre>";
*/


function send_error_page($http_status_code){
  switch ($http_status_code) {
    case 301:  // Moved Permanently
    case 303:  // See other
      exit();
      break;

    case 404:  // Not found
      header('HTTP/1.0 404 Not Found');
      include('error404.html');
      exit();
      break;

    case 403:  // Forbidden
      header('HTTP/1.1 403 Forbidden');
      include('error403.html');
      exit();
      break;

    case 410:  // Gone
      header('HTTP/1.1 410 Gone');
      include('error410.html');
      exit();
      break;

    case 412:  // Precondition failed (bad behavior mediawiki extension)
      exit();
      break;

    case 400:  // Bad Request
      header('HTTP/1.1 400 Bad Request');
      include('error400.html');
      exit();
      break;

    case 503:  // Service Unavailable (could not connect to MySQL database)
      header('HTTP/1.1 503 Service Unavailable');
      include('error503.html');
      exit();
      break;
  }
}

function include_file($filename){
  global $lang;
  global $l_page_is_outdated;
  global $l_last_updated;
  global $K_VERSION;
  global $stable_KDE_BRANCH;
  global $lsv;

  // Determine the version strings to use (this only started for pages >= 3.2)
  $ver_index = substr($filename,0,3);
  if (($ver_index[0]>='3' and $ver_index[1]=='.') or $ver_index=='HEA') {
    $k_base_version             = $K_VERSION[$ver_index]['base_version'];
    $k_series_version           = $K_VERSION[$ver_index]['series_version'];
    $k_latest_version           = $K_VERSION[$ver_index]['latest_version'];
    $k_branch                   = $K_VERSION[$ver_index]['branch'];
    $k_branch_path              = $K_VERSION[$ver_index]['branch_path'];
    $k_kde_base_version         = $K_VERSION[$ver_index]['kde_base_version'];
    $k_i18n_stats_url           = $K_VERSION[$ver_index]['i18n_stats_url'];
    $k_min_required_kde_version = $K_VERSION[$ver_index]['min_required_kde_version'];
  }

  // if $filename is in the form of x.x/cvsbranches.ihtml then drop the x.x part because the path does not really exist
  // this allows the branches_compiling.html files to present a "versionidified" cvsbranches table.
  if (strstr($filename, 'cvsbranches.ihtml')) {
    $filename = 'cvsbranches.ihtml';
  }

  if (file_exists("lang/$lang/$filename")){
    if (filemtime($filename)> filemtime("lang/$lang/$filename") && !strstr($filename,'news/')){
      echo "<p class=\"outdatedtranslation\">$l_page_is_outdated</p>";
    }
    $retmtime = include("lang/$lang/$filename");
    if (!strstr($filename,'ihtml'))
      return $retmtime!=1?$retmtime:filemtime("lang/$lang/$filename");
  }
  else if(file_exists($filename)){
    // If the translated page does not exist and it falls back to the english page, mark it as english
    // main.html is a special case, because its just a smart place-holder, and is therefore not translated
    if ($lang != 'en' && !strstr($filename,'ihtml') && $filename != 'main.html') echo "<span lang=\"en\">\n";
    $retmtime = include($filename);
    if ($lang != 'en' && !strstr($filename,'ihtml') && $filename != 'main.html') echo "</span>\n";
    if (!strstr($filename,'ihtml'))
      return $retmtime!=1?$retmtime:filemtime($filename);
  }
  else {
    echo "<h2>Could not find file \"$filename\" !!!</h2>\n";
  }
}

function get_title_variable($filename) {
  global $l_pt_main_year;
  global $l_pt_x_x_announce_kdevelop;
  if ( substr($filename, 0, 4) == 'main' && $filename != 'main.html' ){  // mainXXXX.html files
    return sprintf($l_pt_main_year, substr($filename, 4, 4));  // the year is contained in the characters 4 to 8 of the filename
  } else if ($filename[0] != '1' && $filename[0] != '2' && $filename[1] == '.' && $filename[3] == '/') { // X.X/ files
    if (substr($filename,4,8) == 'announce') {
      // the x.x/announce_xxxxxx.html pages
      return sprintf($l_pt_x_x_announce_kdevelop, substr($filename,0,3));
    } else if (substr($filename,17,5) == '#Bugs'){
      // the x.x/kdevelop.html#Bugs pages
      return $GLOBALS['l_bugs_section_at'].' KDevelop - '. sprintf($GLOBALS['l_pt_x_x_kdevelop'], substr($filename,0,3));
    } else {
      // the rest of the x.x/xxxx.html pages
      return sprintf($GLOBALS['l_pt_x_x_' . strtr(substr($filename,4,strlen($filename)-9), '-/.', '___')], substr($filename,0,3));
    }
  } else { // all other files
    // this is just a temporary workaround.
    if ($filename=='HEAD/ChangeLog_kdevplatform.html')
      return 'KDev platform HEAD ChangeLog';
    return $GLOBALS['l_pt_' . strtr(substr($filename,0,strlen($filename)-5), '-/.', '___')];
  }
}


function create_intern_menu_item($filename, $text, $style=' class="versionlink"') {
  return "<li$style><a href=\"index.html?filename=$filename\" title=\"".get_title_variable($filename)."\">$text</a></li>";
}

function create_intern_menu_link($filename, $text, $style=' class="versionlink"') {
  return "<td$style><a href=\"index.html?filename=$filename\" title=\"".get_title_variable($filename)."\">$text</a></td>";
}

function create_intern_menu_link2($filename, $text, $style=' class="versionlink" colspan="2"') {
  return "<td$style><a href=\"index.html?filename=$filename\" title=\"".get_title_variable($filename)."\">$text</a></td>";
}


function get_translatorname_translatoremail($version, $lang_code) {

  mysql_select_db("kdevelop_db");
  $result = mysql_query("SELECT name , email FROM transl_translators WHERE languages = '$lang_code' AND versions LIKE '%$version%'");

  $returnstring= "";
  if (mysql_num_rows($result) == 0)
    return $returnstring;

  while($translator = mysql_fetch_object($result)){
    $name = $translator->name;
    $email = strtr($translator->email, "@.", "  "); // remove the @ and . in the email address
    if ($returnstring != "")
      $returnstring .= ", ";
    $returnstring .= $name . " &#060;<a href=\"mailto:$name &#060;$email&#062;?subject=KDevelop+website+translation\">" . $email ."</a>&#062;";
  }
  mysql_free_result($result);
  return $returnstring;
}

function get_translator_name_with_email($version, $lang_code) {

  mysql_select_db("kdevelop_db");
  $result = mysql_query("SELECT name , email FROM transl_translators WHERE languages = '$lang_code' AND versions LIKE '%$version%'");

  $returnstring= "";
  if (mysql_num_rows($result) == 0)
    return $returnstring;

  while($translator = mysql_fetch_object($result)){
    $name = $translator->name;
    $email = strtr($translator->email, "@.", "  "); // remove the @ and . in the email address
    if ($returnstring != "")
      $returnstring .= ", ";
    $returnstring .= "<a href=\"mailto:$name &#060;$email&#062;?subject=KDevelop+website+translation\">" . $name ."</a>";
  }
  mysql_free_result($result);
  return $returnstring;
}

function  get_website_translation_authors($version) {
  global $langcodes;
  global $languages;

  foreach($langcodes as $language){
    $trans_string = get_translatorname_translatoremail($version, $language);
    if ($trans_string != "")
      echo "$languages[$language] - $trans_string<br>";
  }
}

class screenshot_thumb{
  var $filename; //!< image filename
  var $width;    //!< image width [pixels]
  var $height;   //!< image height [pixels]
  var $caption;  //!< image description caption

  //constructor
  function screenshot_thumb($filename, $width, $height, $caption){
    $this->filename = $filename;
    $this->width = $width;
    $this->height = $height;
    $this->caption = $caption;
  }
}