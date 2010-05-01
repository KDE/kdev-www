#!/usr/bin/php
<?php
#
# This script should be invoked by a user with write permissions on the
# dynamic/ subdirectory on the website using a cron job that runs after
# the cvs update of the website.
#
# The purpose of this script is to create a web page with information
# about the translation status of the KDevelop website and to create
# several .zip files with the contents of the website. All the files
# are created in the dynamic/ subdirectory of the website.
#
# begin 16 April 2004
# (c) 2004 Amilcar Lucas <amilcar@kdevelop.org>
# published under the GNU GPL


// for global $basedir and $lsv
include('website_globals.php');

include('zip.lib.php');

// to avoid a warning in the include("../translations.inc")
$filename='';

// for $languages
include('../translations.inc');

// get the MySQL global login and password
include ('../../access.inc');

// Establish a connection with the MySQL server
$mysql_link = @mysql_connect ('localhost',$k_login,$k_password);

// Exit if it canot get a connection to the MySQL database
if ($mysql_link == false)
  die('Could not connect to the database. Website translation status pages will not be updated.
');

mysql_select_db('kdevelop_db');

global $outputdir;

// Configuration variables
$outputdir = $basedir . "dynamic/";
$outputfilename = $outputdir .'website_translation_status.html';
$tables_width = "\"96%\"";

// Extent the zipfile class provided by MyPHPadmin
class translation_zipfile extends zipfile {
  var $ziped_filename;

  function translation_zipfile($zipedfilename){
    $this -> ziped_filename = $zipedfilename;
  }

  function add_localfile($localfilename, $filenameinzip){
    // needs to go down because it is called from the bin directory
    $relativefilename = '../' . $localfilename;
    // Read at most 100Kb of file data
    $filedata = fread (fopen ($relativefilename, 'rb'), 100000);
    $timestamp = filemtime($relativefilename);
    // add the binary data stored in the string 'filedata' to the 'zip_file'
    $this -> addFile($filedata, $filenameinzip, $timestamp);
  }

  function write_localzipfile() {
    $fd = fopen ($this -> ziped_filename, 'wb');
    $out = fwrite ($fd, $this -> file());
    fclose ($fd);
  }
}

function calculate_precentage_and_width($nr_files, $total_nr_files, $graph_width) {
/**
  // When PHP was configured with --enable-bcmath
  $per = bcdiv(bcmul($nr_files,100),$total_nr_files,2);
  $tw = bcdiv(bcmul($nr_files,$graph_width),$total_nr_files,0);
/**/
  // When PHP was not configured with --enable-bcmath
  $per = sprintf ('%02.02f', $nr_files * 100 / $total_nr_files);
  $tw = $nr_files * $graph_width / $total_nr_files;
  $tw = (int) $tw;
/**/
  return array ($nr_files, $per, $tw);
}


function update_tables_and_zipfiles($translatable_filenames, $optional, $tables_width){
  global $langcodes;
  global $languages;
  global $lsv;

  global $basedir;
  global $outputdir;

  $graph_width = 200;
  $graph_widthp = $graph_width + 10;
  $trans_msg = 'Is translated and uptodate';
  $outdt_msg = 'Outdated, needs update';
  $untrn_msg = 'Not translated yet';
  $trans_bar = "<img src=\"graphics/stats/bar0.gif\" height=\"15\" width=\"15\" alt=\"$trans_msg\">";
  $outdt_bar = "<img src=\"graphics/stats/bar4.gif\" height=\"15\" width=\"15\" alt=\"$outdt_msg\">";
  $untrn_bar = "<img src=\"graphics/stats/bar1.gif\" height=\"15\" width=\"15\" alt=\"$untrn_msg\">";
  $legend = "legend:<br>
&nbsp;&nbsp;$trans_bar - $trans_msg. A file is considered translated if it was modified after the English version.
That does not mean that the file is completely translated, it just means that the file is newer than the English file.<br>
&nbsp;&nbsp;$outdt_bar - $outdt_msg. A file is outdated if the English version was modified after it.<br>
&nbsp;&nbsp;$untrn_bar - $untrn_msg. A file is untranslated if the translated file does not exist.<br>";


  // Initialize some local variables
  $translatable_zipfile = new translation_zipfile(
    $outputdir . 'english' . $optional . '_translatable.zip');
  for($j=0;$j<count($langcodes);$j++){
    $translated_zipfiles[$j]   = new translation_zipfile(
      $outputdir . $langcodes[$j] . $optional . '_translated.zip');
    $untranslated_zipfiles[$j] = new translation_zipfile(
      $outputdir . $langcodes[$j] . $optional . '_untranslated.zip');
    $nr_translated_files[$j]   = 0;
    $nr_outdated_files[$j]     = 0;
    $nr_untranslated_files[$j] = 0;
  }

  // $details_table will contain the html code of
  // the detailed "File by file" table with the website translation status
  $details_table = "The files with a grey background should be translated first. Click on the red, green and blue icons to view the files.<br>
<table border=\"1\" width=$tables_width>
  <tr bgcolor=\"#e0e0e0\">
  <th>Filename</th>\n";

  reset($langcodes);
  for($j=0;$j<count($langcodes);$j++){
    $details_table .= "  <th>$langcodes[$j]</th>\n";
  }
  $details_table .= "</tr>\n";

  // Add the images of the flags
  $details_table .= "<tr bgcolor=\"#ffffff\">\n  <td align=\"center\"><img src=\"graphics/flags/en.gif\" alt=\"English flag\"></td>\n";
  $translatable_zipfile->add_localfile("graphics/flags/en.gif", "graphics/flags/en.gif");
  for($j=0;$j<count($langcodes);$j++){
    $translated_zipfiles[$j]->add_localfile("graphics/flags/" . $langcodes[$j] . ".gif", "graphics/flags/" . $langcodes[$j] . ".gif");
    $temp_lang_string = $languages[$langcodes[$j]];
    $details_table .= "  <td align=\"center\"><img src=\"graphics/flags/$langcodes[$j].gif\" alt=\"$temp_lang_string flag\"></td>\n";
  }
  $details_table .= "</tr>\n";

  reset($translatable_filenames);
  for($i=0;$i<count($translatable_filenames);$i++){
    $translatable_zipfile->add_localfile($translatable_filenames[$i], "en/". $translatable_filenames[$i]);

    // Should it place a html link to the file
    if (strstr($translatable_filenames[$i],'.ihtml') ||
        strstr($translatable_filenames[$i],'news/') ||
       (substr($translatable_filenames[$i],1,1) == '.' and substr($translatable_filenames[$i],0,3) > $lsv) ||
        strstr($translatable_filenames[$i],'.inc')) {
      $link_to_trans_file = false;
    } else {
      $link_to_trans_file = true;
    }

    // Mark the important filenames
    if (strstr($translatable_filenames[$i],'.ihtml') ||
        strstr($translatable_filenames[$i],'main.html') ||
        strstr($translatable_filenames[$i],'.inc') ||
        strstr($translatable_filenames[$i],"$lsv/"))
      $details_table .= "<tr bgcolor=\"#e0e0e0\">\n";
    else
      $details_table .= "<tr bgcolor=\"#ffffff\">\n";

    // print the file name and details
    if ($link_to_trans_file == true) {
      $details_table .= "  <td><a href=\"index.html?filename=$translatable_filenames[$i]\">$translatable_filenames[$i]</a> (";
    } else {
      $details_table .= "  <td>$translatable_filenames[$i] (";
    }
    $details_table .= sprintf ('%2.01f', filesize($basedir . $translatable_filenames[$i]) / 1024). "Kib)</td>\n";

    reset($langcodes);
    for($j=0;$j<count($langcodes);$j++){

      if (file_exists('../lang/' . $langcodes[$j] . '/' . $translatable_filenames[$i])){
        if (filemtime('../' . $translatable_filenames[$i]) >
            filemtime('../lang/' . $langcodes[$j] . '/' . $translatable_filenames[$i])) {
          if ($link_to_trans_file == true) {
            $details_table .= "  <td align=center><a href=\"index.html?filename=$translatable_filenames[$i]&amp;set_lang=$langcodes[$j]\" title=\"$outdt_msg\">$outdt_bar</a></td>\n";
          } else {
            $details_table .= "  <td align=center>$outdt_bar</td>\n";
          }
          $untranslated_zipfiles[$j]->add_localfile('lang/' . $langcodes[$j] . '/' . $translatable_filenames[$i], $langcodes[$j] . '/' . $translatable_filenames[$i]);
          $nr_outdated_files[$j]++;
        }
        else {
          if ($link_to_trans_file == true) {
            $details_table .= "  <td align=center><a href=\"index.html?filename=$translatable_filenames[$i]&amp;set_lang=$langcodes[$j]\" title=\"$trans_msg\">$trans_bar</a></td>\n";
          } else {
            $details_table .= "  <td align=center>$trans_bar</td>\n";
          }
          $translated_zipfiles[$j]->add_localfile('lang/' . $langcodes[$j] . '/' . $translatable_filenames[$i], $langcodes[$j] . '/' .$translatable_filenames[$i]);
          $nr_translated_files[$j]++;
        }
      }
      else {
        if ($link_to_trans_file == true) {
          $details_table .= "  <td align=center><a href=\"index.html?filename=$translatable_filenames[$i]\" title=\"$untrn_msg\">$untrn_bar</a></td>\n";
        } else {
          $details_table .= "  <td align=center>$untrn_bar</td>\n";
        }
        $untranslated_zipfiles[$j]->add_localfile($translatable_filenames[$i], $langcodes[$j] . "/" . $translatable_filenames[$i]);
        $nr_untranslated_files[$j]++;
      }
    }
    $details_table .= "</tr>\n";
  }

  // write out the files to the local disk
  $translatable_zipfile -> write_localzipfile();
  for($j=0;$j<count($langcodes);$j++){
    // if a 'translator_info.html' file exists, add it to the translated zipfile.
    if (file_exists("../lang/$langcodes[$j]/translator_info.html")) {
      $translated_zipfiles[$j]->add_localfile("lang/$langcodes[$j]/translator_info.html", "$langcodes[$j]/translator_info.html");
    }
    $translated_zipfiles[$j]->write_localzipfile();
    $untranslated_zipfiles[$j]->write_localzipfile();
  }
  $details_table .= "</table>
  $legend\n";


  // $download_table will contain the html code of
  // the file download table of the website translations
  $download_table = "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" bgcolor=\"#8b898b\" width=$tables_width>
  <tr bgcolor=\"#e0e0e0\">
    <th rowspan=2><font size=\"2\">language</font></th>
    <th colspan=3><font size=\"2\" color=\"#339933\">translated</font></th>
    <th colspan=2><font size=\"2\" color=\"#333399\">outdated</font></th>
    <th colspan=3><font size=\"2\" color=\"#dd3333\">untranslated</font></th>
    <th rowspan=2 width=\"$graph_widthp\"><font size=\"2\">graph</font></th>
  </tr>
  <tr bgcolor=\"#e0e0e0\">
    <th align=\"center\"><font size=\"2\" color=\"#339933\">zip file</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#339933\">files</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#339933\">%</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#333399\">files</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#333399\">%</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#dd3333\">zip file</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#dd3333\">files</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#dd3333\">%</font></th>
  </tr>
  <tr bgcolor=\"#ffffff\">
    <td>English (en)</td>
    <td><a href=\"dynamic/english" .  $optional . "_translatable.zip\">translatable</a> (" . sprintf ("%2.01f", filesize($translatable_zipfile->ziped_filename) / 1024). "Kib)</td>
    <td align=\"right\"><font size=\"2\">$i</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">100.00</font></td>
    <td align=\"right\"><font size=\"2\">0</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">00.00</font></td>
    <td>-</td>
    <td align=\"right\"><font size=\"2\">0</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">00.00</font></td>
    <td><img src=\"graphics/stats/bar0.gif\" height=\"15\" width=\"$graph_width\" alt=\"$trans_msg\"></td>
  </tr>\n";

  reset($langcodes);
  for($j=0;$j<count($langcodes);$j++){
    $download_table .= "<tr bgcolor=\"#ffffff\">
    <td><a href=\"index.html?filename=website_translation_status.html&amp;set_lang=" . $langcodes[$j] . "\">{$languages[$langcodes[$j]]} ($langcodes[$j])</a></td>
    <td><a href=\"dynamic/$langcodes[$j]" .  $optional . "_translated.zip\">translated</a> (" . sprintf ("%2.01f", filesize($translated_zipfiles[$j]->ziped_filename) / 1024). "Kib)</td>\n";
    list ($nr, $per, $tw) = calculate_precentage_and_width( (int) $nr_translated_files[$j], $i, $graph_width);
    $download_table .= "  <td align=\"right\"><font size=\"2\">$nr</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">$per</font></td>\n";

    list ($nr, $per, $ow) = calculate_precentage_and_width( (int) $nr_outdated_files[$j], $i, $graph_width);
    $download_table .= "  <td align=\"right\"><font size=\"2\">$nr</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">$per</font></td>\n";

    list ($nr, $per, $uw) = calculate_precentage_and_width( (int) $nr_untranslated_files[$j], $i, $graph_width);
    $download_table .= "  <td><a href=\"dynamic/$langcodes[$j]" .  $optional . "_untranslated.zip\">untranslated</a> (" . sprintf ("%2.01f", filesize($untranslated_zipfiles[$j]->ziped_filename) / 1024). "Kib)</td>
    <td align=\"right\"><font size=\"2\">$nr</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">$per</font></td>
    <td><img src=\"graphics/stats/bar0.gif\" height=\"15\" width=\"$tw\" alt=\"$trans_msg\"><img src=\"graphics/stats/bar4.gif\" height=\"15\" width=\"$ow\" alt=\"$outdt_msg\"><img src=\"graphics/stats/bar1.gif\" height=\"15\" width=\"$uw\" alt=\"$untrn_msg\"></td>
  </tr>\n";
  }
  $download_table .= "</table>
  $legend\n";
  return array ($download_table, $details_table);
}

function update_lang_tables($translatable_filenames, $optional, $tables_width, $langcode){
  global $languages;
  global $lsv;

  global $basedir;
  global $outputdir;

  $graph_width = 200;
  $graph_widthp = $graph_width + 10;
  $trans_msg = "Is translated and uptodate";
  $outdt_msg = "Outdated, needs update";
  $untrn_msg = "Not translated yet";
  $trans_bar = "<img src=\"graphics/stats/bar0.gif\" height=\"15\" width=\"15\" alt=\"$trans_msg\">";
  $outdt_bar = "<img src=\"graphics/stats/bar4.gif\" height=\"15\" width=\"15\" alt=\"$outdt_msg\">";
  $untrn_bar = "<img src=\"graphics/stats/bar1.gif\" height=\"15\" width=\"15\" alt=\"$untrn_msg\">";
  $legend = "legend:<br>
&nbsp;&nbsp;$trans_bar - $trans_msg. A file is considered translated if it was modified after the English version.
That does not mean that the file is completely translated, it just means that the file is newer than the English file.<br>
&nbsp;&nbsp;$outdt_bar - $outdt_msg. A file is outdated if the English version was modified after it.<br>
&nbsp;&nbsp;$untrn_bar - $untrn_msg. A file is untranslated if the translated file does not exist.<br>";


  // Initialize some local variables
  $nr_translated_files   = 0;
  $nr_outdated_files     = 0;
  $nr_untranslated_files = 0;

  // $details_table will contain the html code of
  // the detailed "File by file" table with the website translation status
  $details_table = "The files with a grey background should be translated first. Click on the red, green and blue icons to view the files.<br>
<table border=\"1\" width=$tables_width>
  <tr bgcolor=\"#e0e0e0\">
  <th>Filename</th><th>status</th><th>observations</th>\n";

  $details_table .= "</tr>\n";

  reset($translatable_filenames);
  for($i=0;$i<count($translatable_filenames);$i++){

    // Should it place a html link to the file
    if (strstr($translatable_filenames[$i],".ihtml") ||
        strstr($translatable_filenames[$i],"news/") ||
       (substr($translatable_filenames[$i],1,1) == '.' and substr($translatable_filenames[$i],0,3) > $lsv) ||
        strstr($translatable_filenames[$i],".inc")) {
      $link_to_trans_file = false;
    } else {
      $link_to_trans_file = true;
    }

    // Mark the important filenames
    if (strstr($translatable_filenames[$i],".ihtml") ||
        strstr($translatable_filenames[$i],"main.html") ||
        strstr($translatable_filenames[$i],".inc") ||
        strstr($translatable_filenames[$i],"$lsv/"))
      $details_table .= "<tr bgcolor=\"#e0e0e0\">\n";
    else
      $details_table .= "<tr bgcolor=\"#ffffff\">\n";

    // print the file name and details
    if ($link_to_trans_file == true) {
      $details_table .= "  <td><a href=\"index.html?filename=$translatable_filenames[$i]\">$translatable_filenames[$i]</a> (";
    } else {
      $details_table .= "  <td>$translatable_filenames[$i] (";
    }
    $details_table .= sprintf ("%2.01f", filesize($basedir . $translatable_filenames[$i]) / 1024). "Kib)</td>\n";


      if (file_exists("../lang/" . $langcode . "/" . $translatable_filenames[$i])){
        if (filemtime("../" . $translatable_filenames[$i]) >
            filemtime("../lang/" . $langcode . "/" . $translatable_filenames[$i])) {
          if ($link_to_trans_file == true) {
            $details_table .= "  <td align=center><a href=\"index.html?filename=$translatable_filenames[$i]&amp;set_lang=$langcode\" title=\"$outdt_msg\">$outdt_bar</a></td>";
          } else {
            $details_table .= "  <td align=center>$outdt_bar</td>";
          }
          $details_table .= "  <td>Outdated since " . date("j F Y H:i ", filemtime("../lang/" . $langcode . "/" . $translatable_filenames[$i]) ) . "CET</td>\n";
          $nr_outdated_files++;
        }
        else {
          if ($link_to_trans_file == true) {
            $details_table .= "  <td align=center><a href=\"index.html?filename=$translatable_filenames[$i]&amp;set_lang=$langcode\" title=\"$trans_msg\">$trans_bar</a></td>";
          } else {
            $details_table .= "  <td align=center>$trans_bar</td>";
          }
          $details_table .= "  <td>Seems to be up-to-date</td>\n";
          $nr_translated_files++;
        }
      }
      else {
        if ($link_to_trans_file == true) {
          $details_table .= "  <td align=center><a href=\"index.html?filename=$translatable_filenames[$i]\" title=\"$untrn_msg\">$untrn_bar</a></td>";
        } else {
          $details_table .= "  <td align=center>$untrn_bar</td>";
        }
        $details_table .= "  <td>Not yet translated</td>\n";
        $nr_untranslated_files++;
      }
    $details_table .= "</tr>\n";
  }

  $details_table .= "</table>\n";


  // $download_table will contain the html code of
  // the file download table of the website translations
  $download_table = "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" bgcolor=\"#8b898b\" width=$tables_width>
  <tr bgcolor=\"#e0e0e0\">
    <th rowspan=2><font size=\"2\">language</font></th>
    <th colspan=3><font size=\"2\" color=\"#339933\">translated</font></th>
    <th colspan=2><font size=\"2\" color=\"#333399\">outdated</font></th>
    <th colspan=3><font size=\"2\" color=\"#dd3333\">untranslated</font></th>
    <th rowspan=2 width=\"$graph_widthp\"><font size=\"2\">graph</font></th>
  </tr>
  <tr bgcolor=\"#e0e0e0\">
    <th align=\"center\"><font size=\"2\" color=\"#339933\">zip file</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#339933\">files</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#339933\">%</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#333399\">files</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#333399\">%</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#dd3333\">zip file</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#dd3333\">files</font></th>
    <th align=\"center\"><font size=\"2\" color=\"#dd3333\">%</font></th>
  </tr>
  <tr bgcolor=\"#ffffff\">
    <td>English (en)</td>
    <td><a href=\"dynamic/english" .  $optional . "_translatable.zip\">translatable</a> (" . sprintf ("%2.01f", filesize($outputdir . "english" . $optional . "_translatable.zip") / 1024). "Kib)</td>
    <td align=\"right\"><font size=\"2\">$i</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">100.00</font></td>
    <td align=\"right\"><font size=\"2\">0</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">00.00</font></td>
    <td>-</td>
    <td align=\"right\"><font size=\"2\">0</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">00.00</font></td>
    <td><img src=\"graphics/stats/bar0.gif\" height=\"15\" width=\"$graph_width\" alt=\"$trans_msg\"></td>
  </tr>\n";

    $download_table .= "<tr bgcolor=\"#ffffff\">
    <td>{$languages[$langcode]} ($langcode)</td>
    <td><a href=\"dynamic/$langcode" .  $optional . "_translated.zip\">translated</a> (" . sprintf ("%2.01f", filesize($outputdir . $langcode . $optional . "_translated.zip") / 1024). "Kib)</td>\n";
    list ($nr, $per, $tw) = calculate_precentage_and_width( (int) $nr_translated_files, $i, $graph_width);
    $download_table .= "  <td align=\"right\"><font size=\"2\">$nr</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">$per</font></td>\n";

    list ($nr, $per, $ow) = calculate_precentage_and_width( (int) $nr_outdated_files, $i, $graph_width);
    $download_table .= "  <td align=\"right\"><font size=\"2\">$nr</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">$per</font></td>\n";

    list ($nr, $per, $uw) = calculate_precentage_and_width( (int) $nr_untranslated_files, $i, $graph_width);
    $download_table .= "  <td><a href=\"dynamic/$langcode" .  $optional . "_untranslated.zip\">untranslated</a> (" . sprintf ("%2.01f", filesize($outputdir . $langcode . $optional . "_untranslated.zip") / 1024). "Kib)</td>
    <td align=\"right\"><font size=\"2\">$nr</font></td>
    <td align=\"right\" bgcolor=\"#f0f0f0\"><font size=\"2\">$per</font></td>
    <td><img src=\"graphics/stats/bar0.gif\" height=\"15\" width=\"$tw\" alt=\"$trans_msg\"><img src=\"graphics/stats/bar4.gif\" height=\"15\" width=\"$ow\" alt=\"$outdt_msg\"><img src=\"graphics/stats/bar1.gif\" height=\"15\" width=\"$uw\" alt=\"$untrn_msg\"></td>
  </tr>\n";
  $download_table .= "</table>
  $legend\n";
  return array ($download_table, $details_table);
}


$how_to_become_a_website_translator="<?php module_head(\"How to become a website translator\");?>

The KDevelop-Project is looking for volunteer translators for this website. If you are willing to do it,
<ul>
  <li> download the <a href=\"/dynamic/english_translatable.zip\">English translatable file</a> if you want to add a new language,
       or one of the <a href=\"#download\">untranslated files</a> (from the top of this page) if you want to complete an already existing language,
  <li> unzip it,
  <li> open the files one by one in a text editor and translate them,
  <li> compress the files you translated into a single file,
  <li> send it by <a href=\"mailto:webmaster@kdevelop.org?subject=Website translation\">mail to the webmaster</a>.
</ul>

<p>Even if you just translate a few files, but you want to see the results of your work on-line,
zip the files you translated and send them to the webmaster.
He will do some changes on the website to add your language, review your files, give you feedback and
he will put them on-line for you. You can see your files by clicking on the green or blue icons on the table above.</p>

<p>Start by translating the *.inc and *.ihtml files. Those will give the most functionality.
Then translate the files of the $lsv/ directory. These files are marked with a gray background in the file by file detailed table above.</p>

<p>Once that is done, we'll add your flag to the top of the website and give you a CVS account.
Then you can commit your changes to the website directly without the review of the webmaster.
After that is done, you can start translating all the other files.</p>

<p><b>Do not</b> use Microsoft Front Page, Netscape Composer, Mozilla Composer, Microsoft Word or OpenOffice.org Writer
to edit the files. Use a simple text editor: kwrite, kate, vi, emacs or any other editor of your choice.
And please keep the formating and indentation of the English version as much as possible,
because that will ease the process of finding differences and updating the translations.</p>

<p>To keep your files up to date with the English version you need to find the difference between the
English version and your version of the files.
You can use <a href=\"http://kdiff3.sourceforge.net/\">KDiff3</a> (distributed with kdeextragear-1)
to do that if you kept the same formating as the English version. KDiff3 highlights the differences
character by character while ignoring the HTML and PHP keywords (because those are language
independent) and this will help you to find exactly the places you need to update your translation.
Here is an example of the command line for the Portuguese language:</p>
<pre>
>unzip english_translatable.zip
>unzip pt_untranslated.zip
>kdiff3 en pt &
</pre>
<p>This will show you the differences in all outdated files. kdiff3 will also allow you to edit the files directly,
so you do not need to use an extra editor.</p>

<p>It is not a big problem if you do not translate all files, but it would be really nice if you would.
If one language already has a maintainer, but many files are still untranslated,
you can contact him/her and offer your help.</p>
<p>Once you finished translating and reviewing these files, if you have the time and want to translate more we also have a list of
<a href=\"index.html?filename=optional_files_translation_status.html\">optional translatable files</a> (not so important).</p>

<p>Thank you for volunteering :)</p>

Amilcar Lucas<br>
current webmaster<br>
April 2004<br>

<?php module_tail();?>\n";



// Try to open the html file that this script will produce
$of = fopen($outputfilename, "w");
if (!$of) {
  echo "<p>Unable to open local output file \"$outputfilename\".</p>\n";
  exit;
}


/*
 * Generate dynamic/website_translation_status.html page
 */
$result = mysql_query("SELECT filename FROM pages WHERE translatable = 'yes' ORDER BY filename ASC");
while($row = mysql_fetch_object($result)){
  $translatable_filenames[] .= $row->filename;
}
mysql_free_result($result);
list ($download_table, $details_table) = update_tables_and_zipfiles($translatable_filenames, "", $tables_width);

fwrite($of,"<?php module_head(\"Website translation status\");?>
<p>This page only exists in English because English is KDevelop's official language.
All the website translators are required to speak English because the translations
are always done from English to the other languages.<br>
Currently some translations are deactivated because there is no volunteer translator for them or because they do not have enough translated pages.</p>
<p>This page is automatically updated every hour, two minutes after the CVS update.</p>
<h3><a name=\"download\">Download</a></h3>
The outdated files are included in the untranslated zip files.
$download_table
<h3>File by file details</h3>
$details_table
<p>There is also a list of <a href=\"index.html?filename=optional_files_translation_status.html\">optional translatable files</a>.</p>
<?php module_tail();?>
$how_to_become_a_website_translator
");

fclose($of);



/*
 * Generate dynamic/optional_files_translation_status.html page
 */
$outputfilename = $outputdir . "optional_files_translation_status.html";
// Try to open the html file that this script will produce
$of = fopen($outputfilename, "w");
if (!$of) {
  echo "<p>Unable to open local output file \"$outputfilename\".</p>\n";
  exit;
}
$result = mysql_query("SELECT filename FROM pages WHERE translatable = 'optional' ORDER BY filename ASC");
while($row = mysql_fetch_object($result)){
  $optional_translatable_filenames[] .= $row->filename;
}
mysql_free_result($result);
list ($download_table, $details_table) = update_tables_and_zipfiles( $optional_translatable_filenames, "_optional", $tables_width);
fwrite($of,"<?php module_head(\"Optional website translationable files\");?>
<p>These files are optional. You should only translate them if you have already
translated the <a href=\"index.html?filename=website_translation_status.html\">important files</a> and you have the free time to also translate these.<p>
<h3>Download</h3>
The outdated files are included in the untranslated zip files.
$download_table
<h3>File by file details</h3>
$details_table
<p>There is also a list of <a href=\"index.html?filename=website_translation_status.html\">important files to translate</a>.</p>
<?php module_tail();?>\n");

fclose($of);




/*
 * Generate dynamic/translation_status_xx.html pages
 */
for($j=0;$j<count($langcodes);$j++){
  $outputfilename = $outputdir . "translation_status_" . $langcodes[$j] . ".html";
  // Try to open the html file that this script will produce
  $of = fopen($outputfilename, "w");
  if (!$of) {
    echo "<p>Unable to open local output file \"$outputfilename\".</p>\n";
    exit;
  }

  list ($download_table, $details_table) = update_lang_tables( $translatable_filenames, "", $tables_width, $langcodes[$j]);
  fwrite($of,
  "<span lang=\"en\" dir=\"ltr\">
   <?php module_head(\"Important website files - translation status ({$languages[$langcodes[$j]]})\"); ?>

<p>This page only exists in English because English is KDevelop's official language. All the website translators are required to speak English because the translations are always done from English to the other languages.</p>

<p>This page is automatically updated every hour, two minutes after the CVS update.</p>

<h3>Download</h3>
  These are the important files. The outdated files are included in the untranslated zip files.
  $download_table
  <h3>File by file details</h3>
  $details_table
  <?php module_tail();?>\n");

  list ($download_table, $details_table) = update_lang_tables( $optional_translatable_filenames, "_optional", $tables_width, $langcodes[$j]);
  fwrite($of,
  "<?php module_head(\"Optional website files - translation status ({$languages[$langcodes[$j]]})\"); ?>
  <h3>Download</h3>
  These are the optional files. The outdated files are included in the untranslated zip files.
  $download_table
  <h3>File by file details</h3>
  $details_table
  <?php module_tail();?>\n");

  //
  //  Most requested but outdated pages
  //
  $popular_pages ="<?php module_head('<a name=\"Most_requested_but_outdated\">Most requested but outdated pages ('.\"{$languages[$langcodes[$j]]})</a>\");?>
  <table border=\"1\" width=$tables_width>
    <tr bgcolor=\"#e0e0e0\"><th>filename</th><th>last attempted visit</th><th>outdated since</th><th>average attempted visits per day</th></tr>\n";

  // It uses this DB query to accelerate the process, but later on it will double check the results with the file system
  $result = mysql_query("SELECT translatedversion.filename, translatedversion.last_visited, translatedversion.last_updated, translatedversion.avr_daily_visits FROM `stats_pages` as englishversion, `stats_pages` as translatedversion WHERE englishversion.lang = 'en' and translatedversion.lang = '$langcodes[$j]' and translatedversion.last_updated >0 and englishversion.filename = translatedversion.filename and englishversion.last_updated > translatedversion.last_updated ORDER BY `avr_daily_visits` DESC LIMIT 0, 30");
  $nr_outdated_pages = 0;
  while($row = mysql_fetch_object($result)) {
    $av_attempted_visits = sprintf ("%.02f",$row->avr_daily_visits);
    // double check the results with the file system
    if ((in_array($row->filename, $translatable_filenames) || in_array($row->filename, $optional_translatable_filenames)) and
        (filemtime('../' . $row->filename) > filemtime('../lang/' . $langcodes[$j] . '/' . $row->filename))) {
      $popular_pages .= "<tr bgcolor=\"#ffffff\"><td>$row->filename</td><td>$row->last_updated</td><td>$row->last_visited</td><td>$av_attempted_visits</td></tr>\n";
      $nr_outdated_pages = $nr_outdated_pages + 1;
    }
  }
  $popular_pages .= "</table>
  <?php module_tail();?>";
  //mysql_free_result($result);

  if ($nr_outdated_pages > 0)
    fwrite($of,$popular_pages);
  else
    fwrite($of,'<p><a name="Most_requested_but_outdated">Congratulations, there are no outdated pages</a></p>');


  //
  //  Most requested but not translated yet pages
  //
  $popular_pages ="<?php module_head('<a name=\"Most_requested_but_not_translated_yet\">Most requested but not translated yet pages ('.\"{$languages[$langcodes[$j]]})</a>\");?>
  <table border=\"1\" width=$tables_width>
    <tr bgcolor=\"#e0e0e0\"><th>filename</th><th>last attempted visit</th><th>average attempted visits per day</th></tr>\n";

  $result = mysql_query("SELECT * FROM `stats_pages` WHERE `last_updated` = '0000-00-00 00:00:00' AND `lang` = '$langcodes[$j]' ORDER BY `avr_daily_visits` DESC");
  $nr_untranslated_pages = 0;
  while($row = mysql_fetch_object($result)) {
    $av_attempted_visits = sprintf ("%.02f",$row->avr_daily_visits);
    if ((in_array($row->filename, $translatable_filenames) || in_array($row->filename, $optional_translatable_filenames)) and
     (substr($row->filename,1,1) != '.' or substr($row->filename,0,3) == $lsv) and  // ignore old versions
     (substr($row->filename,0,5) != 'main1') and                                    // ignore main1xxx.html
     (substr($row->filename,0,5) != 'main2') and                                    // ignore main2xxx.html
     (substr($row->filename,0,7) != 'events/') ){                                   // ignore events/*
      $popular_pages .= "<tr bgcolor=\"#ffffff\"><td>$row->filename</td><td>$row->last_visited</td><td>$av_attempted_visits</td></tr>\n";
      $nr_untranslated_pages = $nr_untranslated_pages + 1;
    }
  }
  $popular_pages .= "</table>
  <?php module_tail();?>";
  //mysql_free_result($result);

  if ($nr_untranslated_pages > 0)
    fwrite($of,$popular_pages);
  else
    fwrite($of,'<p><a name="Most_requested_but_not_translated_yet">Congratulations, there are no recent untranslated pages</a></p>');

  fwrite($of,$how_to_become_a_website_translator);
  fwrite($of,'</span>');

  fclose($of);
}

// Close the mysql link
mysql_close($mysql_link);

?>