<?php

global $almostdone;
global $complete;

/* Get timestamp from a CVS branch about a translation file from a language code */
function get_trans_timestamp($cvsbranch, $pofile, $langcode){
  $remotefilename = "http://i18n.kde.org/stats/gui/$cvsbranch/kdevelop/$langcode/index.php";
  $file = fopen($remotefilename, "r");
  if (!$file) {
    echo "<p>Unable to open remote file \"$remotefilename\" for read.</p>\n";
    exit;
  }
  while (!feof($file)) {
    $line = fgets($file, 1024);
    /* Finds the last update time using regular expressions */
    if (ereg("last update: <b>(.*)</b>", $line, $out)) {
      /* Prints the last update time */
      return $out[1];
      break;
    }
  }

}

/* Get information from a CVS branch about a translation file from a language code */
function get_lang_trans_status($cvsbranch, $pofile, $langcode, $outputfile){
  global $almostdone;
  global $complete;

  $remotefilename = "http://i18n.kde.org/stats/gui/$cvsbranch/kdevelop/$langcode/index.php";
  $file = fopen($remotefilename, "r");
  if (!$file) {
    echo "<p>Unable to open remote file \"$remotefilename\" for read.</p>\n";
    exit;
  }
  while (!feof($file)) {
    $line = fgets($file, 1024);
    /* Finds a "/ xxxx team " or a "/ xxxx xxxx team " string */
    if (ereg("/ ([A-z]*( [A-z]*){0,1}) team ", $line, $out)) {
      $languagestring = $out[1];
      fputs($outputfile, "<tr bgcolor=\"#ffffff\">\n");
      /* Prints the language name and code with a link to the translation team. */
      fputs($outputfile, "  <td><font size=\"2\"><nobr><a href=\"http://l10n.kde.org/team-infos.php?teamcode=$langcode\">$languagestring</a> ($langcode)</font></nobr></td>\n");
      break;
    }
  }
  while (!feof($file)) {
    $line = fgets($file, 1024);
    /* Finds the $pofile using regular expressions */
    if (ereg($pofile, $line, $out)) {
      /* Prints a link to the web CVS repository of the file. */
      fputs($outputfile, $line);
      /* This should get the rest of the data */
      fputs($outputfile, fgets($file, 1024));
      $line = fgets($file, 1024);
      fputs($outputfile, $line);
      ereg("[0-9]{1,3}\.[0-9]{1,2}", $line, $out);
      if ((float)$out[0] >= 100)
        $complete = $complete . $languagestring . ", ";
      else if ((float)$out[0] >= 80)
        $almostdone = $almostdone . $languagestring . ", ";
      fputs($outputfile, fgets($file, 1024));
      fputs($outputfile, fgets($file, 1024));
      fputs($outputfile, fgets($file, 1024));
      fputs($outputfile, fgets($file, 1024));
      fputs($outputfile, fgets($file, 1024));
      $line = fgets($file, 1024);
      /* Replaces the relative link with an absolute link*/
      fputs($outputfile, str_replace("/stats/gui/img", "graphics/stats", $line));
      fputs($outputfile, "</tr>\n");
      break;
    }
  }
  fclose($file);
}

function write_to_file($filename, $string){
    $outputfile = fopen($filename, "w");
    if (!$outputfile) {
      echo "<p>Unable to open local output file file \"$filename\" for write.</p>\n";
      exit;
    }
    fputs($outputfile, $string);
    fclose($outputfile);
}

function get_gui_trans_status(
              $cvsbranch,
              $searchpattern,
              $lastupdatedstring,
              $almostdonestring,
              $completestring
          ){
  global $stable_KDE_BRANCH;
  global $almostdone;
  global $complete;

  $local_prefix = strtr($cvsbranch, '/.', '__');
  if ($searchpattern == "desktop_kdevelop.po"){
    $status_filename     = "dynamic/" . $local_prefix . "_dsk_po_status.ihtml";
    $lastupdate_filename = "dynamic/" . $local_prefix . "_dsk_po_status_lastupdate.ihtml";
    $almostdone_filename = "dynamic/" . $local_prefix . "_dsk_po_status_almostdone.ihtml";
    $complete_filename   = "dynamic/" . $local_prefix . "_dsk_po_status_complete.ihtml";
  }else{
    $status_filename     = "dynamic/" . $local_prefix . "_po_status.ihtml";
    $lastupdate_filename = "dynamic/" . $local_prefix . "_po_status_lastupdate.ihtml";
    $almostdone_filename = "dynamic/" . $local_prefix . "_po_status_almostdone.ihtml";
    $complete_filename   = "dynamic/" . $local_prefix . "_po_status_complete.ihtml";
  }

  // If the file does not exist or if it is older than 24H
  $time = time();
  $min_time = $time - (24*60*59);
  if ((!file_exists($status_filename) || filemtime($status_filename) <= $min_time )
     // Do not overwrite files from versions that i18n.kde.org no longer provides us with info
     && ($cvsbranch == $stable_KDE_BRANCH || $cvsbranch == 'HEAD')){
    $outputfile = fopen($status_filename, 'w');
    if (!$outputfile) {
      echo "<p>Unable to open local output file file \"$status_filename\" for write.</p>\n";
      exit;
    }
    // Init these strings
    $almostdone = '';
    $complete = '';

    switch ($cvsbranch){
     case $stable_KDE_BRANCH:
       $cvsbranch = 'stable';
       break;
     case 'HEAD':
       $cvsbranch = 'trunk';
       break;
    }

    get_lang_trans_status($cvsbranch, $searchpattern, "af", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ar", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "az", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "be", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "bg", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "bn", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "bo", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "br", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "bs", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ca", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "cs", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "cy", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "da", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "de", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "el", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "en_GB", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "eo", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "es", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "et", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "eu", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "fa", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "fi", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "fo", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "fr", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "fy", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ga", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "gl", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "gu", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "he", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "hi", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "hr", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "hu", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "id", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "is", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "it", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ja", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ko", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "ku", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "ky", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "lo", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "lt", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "lv", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "mi", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "mk", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "mn", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ms", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "mt", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "nb", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "nds", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "nl", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "nn", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "nso", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "oc", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "pl", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "pt", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "pt_BR", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ro", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ru", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "se", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "sk", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "sl", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "sq", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "sr", $outputfile);
  //  get_lang_trans_status($cvsbranch, $searchpattern, "ss", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "sv", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ta", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "tg", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "th", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "tr", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "uk", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "uz", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "ven", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "vi", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "wa", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "xh", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "zh_CN", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "zh_TW", $outputfile);
    get_lang_trans_status($cvsbranch, $searchpattern, "zu", $outputfile);
    fclose($outputfile);

    write_to_file($lastupdate_filename, get_trans_timestamp($cvsbranch, $searchpattern, "af"));

    if ($almostdone != "") // If not empty replace the trailing ',' with a '.'
      $almostdone[strrpos($almostdone, ",")] = ".";
    write_to_file($almostdone_filename, $almostdone);

    if ($complete != "")   // If not empty replace the trailing ',' with a '.'
      $complete[strrpos($complete, ",")] = ".";
    write_to_file($complete_filename, $complete);
  }


  include($status_filename);
  echo "<tr><td align=\"left\" colspan=10><font size=\"2\">$lastupdatedstring";
  include($lastupdate_filename);
  echo "</td></tr>\n";
  echo "<tr><td align=\"left\" colspan=10><font size=\"2\">$almostdonestring";
  include($almostdone_filename);
  echo "</td></tr>\n";
  echo "<tr><td align=\"left\" colspan=10><font size=\"2\">$completestring";
  include($complete_filename);
  echo "</td></tr>\n";
}
?>