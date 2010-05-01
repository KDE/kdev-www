#!/usr/bin/php
<?php
# Inform the translators that an english page has changed in the last 24H
# (w) 13.6.2000
# (c) by Sandy Meier, licensed under the GPL
# (c) by Amilcar Lucas, licensed under the GPL


$basedir = '../';

// get the MySQL global login and password
include ('../../access.inc');

// Establish a connection with the MySQL server
$mysql_link = @mysql_connect ('localhost',$k_login,$k_password);

// Exit if it can not get a connection to the MySQL database
if ($mysql_link == false){
  die('Could not connect to the database. Translators will not be informed tonight.
');
}

// Exit if it can not select to the MySQL database
if (@mysql_select_db("kdevelop_db") == false){
  echo "Could not select the database. Translators will not be informed tonight.\n";
  exit();
}

// Automatically reorder the table to maintain consistency and ease the manangement
$result = mysql_query("ALTER TABLE `transl_translators` ORDER BY `languages` , `versions` , `name`");

// Get the configuration from the DB
$result = mysql_query('SELECT * FROM transl_inform');
$row = mysql_fetch_object($result);
$send_emails  = $row->send_emails;
$subject      = $row->subject;
$email_header = $row->email_header;
$email_footer = $row->email_footer;
mysql_free_result($result);
$mod_files = '';
$optional_mod_files = '';

if ($send_emails) {

  $min_time = time() - (24*60*60-1); // all message that are older than 24 H
  $cvsdiff = "cd $basedir && cvs diff -N -l -u -D ".date('"Y-m-d H:i:s"',$min_time);

  // Translatable files message
  $result = mysql_query("SELECT filename FROM pages WHERE translatable = 'yes'");
  while($row = mysql_fetch_object($result)) {
    $file_name = $basedir . $row->filename;
    if (file_exists($file_name) && filemtime($file_name) > $min_time)
      $mod_files .= str_replace('/home/cvs/kdevelop_www/www/','', `$cvsdiff $row->filename` )."===================================================================\n\n";
  }
  mysql_free_result($result);
  if (!empty($mod_files))
    $mod_files = "\nThe following important file(s) were changed:\n" . 
                 $mod_files .
                 "\nFor more info about the translation visit:\n" .
                 "http://www.kdevelop.org/index.html?filename=website_translation_status.html\n";

  // Optional translatable files message
  $result = mysql_query("SELECT filename FROM pages WHERE translatable = 'optional'");
  while($row = mysql_fetch_object($result)) {
    $file_name = $basedir . $row->filename;
    if (file_exists($file_name) && filemtime($file_name) > $min_time)
      $optional_mod_files .= str_replace('/home/cvs/kdevelop_www/www/','', `$cvsdiff $row->filename` )."===================================================================\n\n";
  }
  mysql_free_result($result);
  if (!empty($optional_mod_files))
    $optional_mod_files = "\nThe following optional (not so important) file(s) were changed:\n" .
                          $optional_mod_files .
                          "\nFor more info about state of the optional files translation visit:\n" .
                          "http://www.kdevelop.org/index.html?filename=optional_files_translation_status.html\n";

  if (!empty($mod_files) or !empty($optional_mod_files)){

    // Build the emails
    $email_body = "$email_header$mod_files$optional_mod_files\n$email_footer";

    echo "E-mails sent to:\n"; 
    $result = mysql_query("SELECT name , email FROM transl_translators WHERE versions LIKE '%HEAD%'");
    while($row = mysql_fetch_object($result)) {
      mail("$row->name <$row->email>",$subject,$email_body, "From: KDevelop Webmaster <webmaster@kdevelop.org>\nX-Mailer: PHP/" . phpversion());
      echo "$row->name <$row->email>\n";
    }
    echo ("\nSubject: $subject\n\nBody:\n$email_body\n"); 
  }
} else {
  echo "Inform translators is disabled in the database\n";
}

// Close the mysql link
mysql_close($mysql_link);

?>