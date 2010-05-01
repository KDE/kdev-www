<?php
################################################################################
# GraphViz Site Map Generator
# Author: Michael Angeles (http://urlgreyhot.com/contact/)
#
# Adapted to www.kdevelop.org by Amilcar Lucas
#
# Copyright (c) 2002-2003, Michael Angeles
# Copyright (c) 2004, Amilcar Lucas
# http://urlgreyhot.com/graphviz/
#
# This script sends UNIX shell commands to graphviz in order to generate
# site maps from data contained in a MySQL database. To get this to work, you will need
# to have GraphViz (dot, neato) installed on your server. For details about
# AT&T Labs' GraphViz application, see http://graphviz.org
#
# I'd be happy to know if you are able to use this script somewhere. Send me
# email (michael@studioid.com) if you do. 
#
# GraphViz Site Map Generator comes with ABSOLUTELY NO WARRANTY.
# This is free software, and you are welcome to redistribute it
# under certain conditions. See http://urlgreyhot.com/graphviz/LICENSE 
# for details.
###############################################################################

################################################################################
# INSTALLATION
################################################################################

# 1. Download and install the GraphViz application. See: 
# http://www.research.att.com/sw/tools/graphviz/download.html
# 2. Install/download True Type fonts to be used by GraphViz.
# 3. Modify configuration values to reflect your directory set up.
# 4. Access the update_sitemap.php script from your server.


// Get the username of the owner of this file
$owner = posix_getpwuid(fileowner(__FILE__));
$owner_name = $owner['name'];

################################################################################
# CONFIGURATION
################################################################################

// for global $basedir and languages
include('website_globals.php');

// for $languages and page titles
include('../translations.inc');

// get the MySQL global login and password
include ('../../access.inc');

// use the get_title_variable() to convert a filename into its page title
include ('../inc/functions.php');

# 1. The 2 following variables refer to the SAME directory where your script
# is located/installed
# 1a. Server path (without trailing slash)
$base = "$basedir/dynamic";

# 1b. URL (without trailing slash)
// serve the links according to the file owner
if ($owner_name == 'smeier')
  $baseurl='http://www.kdevelop.org';
else
  $baseurl="http://localhost/~$owner_name/www";
$fileurl="$baseurl/dynamic";

# 2. Directory where the input files should be saved
$filedir = "$basedir/dynamic";

# 3. Location of dot executable
$dotbase = '/usr/bin';

# 4. Location of true type fonts
$fontbase = '/usr/X11R6/lib/X11/fonts/truetype';

################################################################################
# CONFIGURATION END
# You shouldn't need to modify anything below this line
################################################################################

$vers = '0.5.3b';
$self = $PHP_SELF;

// header and footer
function page_header() {
  echo '<html><body>
<h1>GraphViz Site Map Generator</h1>
<hr>
';
}

function page_footer() {
  global $vers;
  echo "<hr>
  <p><a href=\"http://urlgreyhot.com/graphviz/\">GraphViz Sitemap Generator</a> :: <a href=\"update_sitemap.php?q=vers\">Vers. $vers</a></p>
  </body></html>";
}

################################################################################
# do stuff
################################################################################

page_header();
  global $baseurl;
  global $fileurl;
  global $filedir;
  global $dotbase;

// execute modules
if (isset($_GET['q']) and $_GET['q']=='vers') {
  vers();
} else {
  // Establish a connection with the MySQL server
  $mysql_link = @mysql_connect ('localhost',$k_login,$k_password);
  unset($k_login);
  unset($k_password);

  // Exit if it canot get a connection to the MySQL database
  if ($mysql_link == false)
    die('Could not connect to the database. Sitemap will not be updated.
');

  mysql_select_db('kdevelop_db');

  // print dot version
  passthru("$dotbase/dot -V 2>&1");

  $charmap=array ('&amp;'    => '&',
                  '&#039;'   => '\'',
                  '&atilde;' => 'ã',
                  '&aacute;' => 'á',
                  '&#259;'   => 'ă',
                  '&Aacute;' => 'Á',
                  '&eacute;' => 'é',
                  '&Eacute;' => 'É',
                  '&ecirc;'  => 'ê',
                  '&iacute;' => 'í',
                  '&Iacute;' => 'Í',
                  '&Icirc;'  => 'Î',
                  '&otilde;' => 'õ',
                  '&oacute;' => 'ó',
                  '&Oacute;' => 'Ó',
                  '&ccedil;' => 'ç',
                  '&Ccedil;' => 'Ç',
                  '&#x15E;'  => 'ş',
                  '&#x163;'  => 'ţ',
                  '&uuml;'   => 'ü',
                  '&uacute;' => 'ú',
                  '&Uacute;' => 'Ú');

  $graph_colors=array(
    '1.3' => 'purple',
    '2.1' => 'violet',
    '3.0' => 'pink',
    '3.1' => 'moccasin',
    '3.2' => 'cadetblue1',
    '3.3' => 'deepskyblue3',
    '3.4' => 'darkolivegreen3',
    '3.5' => 'brown3',
    '4.0' => 'chartreuse1',
    '4.1' => 'darkslateblue',
    '4.2' => 'mediumslateblue',
    '4.3' => 'olivedrab1',
    '4.4' => 'palegreen3',
    '5.0' => 'burlywood3');

  $result = mysql_query('SELECT * FROM `pages` WHERE sitemap = 1 ORDER BY sitemap_group DESC, filename ASC');

  $all_langcodes = array_keys($languages);
  echo '<p>Generating image maps for: </p>';
  $filename='';   // to avoid undefined variable in the include('*/translations.inc'); lines bellow
  for($j=0;$j<count($all_langcodes);$j++){
    $lang = $all_langcodes[$j];

    // Reload the english translation just in case there are non translated
    // strings in the lang/$lang/translated.inc file
    include('../translations.inc');
    echo "$languages[$lang] ...";
    flush();
    if (file_exists('../lang/' . $lang . '/translations.inc')){
      include('../lang/' . $lang . '/translations.inc');
    }

    # 5. Name of the input file
    $GSMG_infilename = "sitemap_$lang";

    if ($l_charset_encoding != 'utf-8'){
      # convert the utf-8 charmap to the local encoding, so that the string conversion back to utf-8 works
      unset($local_charmap);
      foreach ($charmap as $htmlchar => $value){
        $local_charmap[] = array($htmlchar => @iconv('utf-8', $l_charset_encoding, $value));
      }
    }

    unset($graph_input);
    unset($graph_input_at_bottom);

    while($row = mysql_fetch_object($result)) {
      if ($row->filename == 'main.html')
        $page_title = 'Homepage';
      else
        if (substr($row->filename,strlen($row->filename)-5, strlen($row->filename)) == '.html')
          $page_title = get_title_variable($row->filename);
        else
          $page_title = strtr(${'l_pt_' . strtr($row->filename, '-/.', '___')} , $local_charmap);
      $graph_line = "$row->id\t$row->sitemap_parentnode\t$page_title\t$row->prefix$row->filename";

      // Change the character encoding to UTF-8 if dot does not support it.
      // dot does not understand 'iso-8859-9' nor 'koi8-r', so convert them to utf-8
      if ($l_charset_encoding == 'iso-8859-9' || $l_charset_encoding == 'koi8-r') {
        $graph_line=iconv($l_charset_encoding, 'utf-8', $graph_line); // works fine on my computer
        //$graph_line=recode_string($graph_line, "$l_charset_encoding..utf-8"); // buggy
        //$graph_line=mb_convert_encoding($graph_line, 'utf-8', $l_charset_encoding); // seams not to be installed on the server
      }

      // take a look at http://www.graphviz.org/pub/scm/graphviz2/doc/info/colors.html for colors names
      switch ($row->sitemap_group) {
        case 'news':
          $graph_input[] .= "$graph_line\tgreen\t\tfilled";
          break;
        case 'events':
          $graph_input_at_bottom[] .= "$graph_line\tblue\twhite\tfilled";
          break;
        case 'guestbook':
          $graph_input[] .= "$graph_line\tblack\twhite\tfilled";
          break;
        case $lsv:
          $graph_input[] .= "$graph_line\torange\t\tfilled";
          break;
        case 'HEAD':
          $graph_input[] .= "$graph_line\tred\t\tfilled";
          break;
        default:
          // add older versions at the bottom of the graph
          if (ereg('^[0-9]\.[0-9]',$row->sitemap_group)){
            $graph_color=$graph_colors[$row->sitemap_group];
            $graph_input_at_bottom[] .= "$graph_line\t$graph_color\t\tfilled";
          } else
            // by default, add using gray color
            $graph_input[] .= "$graph_line\tgray\t\tfilled";
      }
    }

    // Add the older pages at the bottom of the graph
    $graph_input = array_merge($graph_input, $graph_input_at_bottom);

    // Do not touch this !
    // dot does not understand 'iso-8859-9' nor 'koi8-r', so they were converted to utf-8
    if ($l_charset_encoding == 'iso-8859-9' || $l_charset_encoding == 'koi8-r') {
      $l_charset_encoding = 'utf-8';
    }

    // now create the dot file
    dot_create_dot_file($GSMG_infilename, $baseurl, 'box', '', $graph_input, $l_charset_encoding);

    // create image and image map
    dot_create_images($GSMG_infilename, 'hierarchical'); 

    echo ' <code style="color:green">done</code><br />';
    flush();

    // rewind the MySQL results to start a new sitemap
    if (!mysql_data_seek ($result, 0)) {
      printf ("Cannot seek to row 0\n");
      break;
    }
  }

mysql_free_result($result);

// Close the mysql link
mysql_close($mysql_link);

  echo '<ul>
  <li><a href="../dynamic/">See generated files</a></li>
  <li><a href="../index.html?filename=sitemap.html">See sitemap in action</a></li>
</ul>
';

}
page_footer();

################################################################################
# modules
################################################################################

// invoke module $name with optional arguments:
function module_invoke($name, $a1 = NULL, $a2 = NULL, $a3 = NULL, $a4 = NULL, $a5 = NULL, $a6 = NULL) {
  $function = $name;
  if (function_exists($function)) {
    return $function($a1, $a2, $a3, $a4, $a5, $a6);
  } else {
    error('Sorry. You got here by mistake.');
  }
}

// create dot files in ./input/
function dot_create_dot_file($a1 = NULL, $a2 = NULL, $a3 = NULL, $a4 = NULL, $a5 = NULL, $a6 = NULL) {
  global $base;
  global $fontbase;
  // get arguments, assign var values
//  $infilename = "$base/input/$a1";
  $outfilename = "$base/$a1.dot";
  // get arguments, set defaults
  $defaulturl = $a2;
  $shape = ($a3 == 'box') ? 'box' : 'circle';
  $edge = $a4;

  // read uploaded file
//  $fhin = fopen($infilename, "r");
//    $file_contents = fread($fhin, filesize($infilename));
//    $line = explode("\n", $file_contents);
//  fclose($fhin);
  $line = $a5;
  $charset = $a6;

  // convert lines to dot format
  $i = 0;
  $size = sizeof($line) - 1;
  $dotlines = '';
  while($i <= $size) {
    # split lines and assign values to fields
    $dat = explode("\t", $line[$i]);
    $datid = rtrim($dat[0]);
    $datparent = rtrim($dat[1]);
    ($dat[2] == '') ? $datlabel = addslashes(rtrim($dat[3])) : $datlabel = addslashes(rtrim($dat[2]));
    $datlabel = eregi_replace ('<nl>', "\\n ", $datlabel);
    $daturl = addslashes(rtrim($dat[3]));
    $datcolor = rtrim($dat[4]);
    $datfontcolor = rtrim($dat[5]);
    $datstyle = rtrim($dat[6]);
    # output the nav links
    if ($datid != ''){
      $dotlines .= "  $datid [label=\"$datlabel\", URL=\"$daturl\", fillcolor=\"$datcolor\", fontcolor=\"$datfontcolor\", style=\"$datstyle\"];\n";
    }
    if ($datid != $datparent){
      $dotlines .= "  $datparent -> $datid;\n";
    }
    $i++;
  }

  // prepare dot options
  $dotfile = "digraph G {\n";
  //$dotfile .= "  URL=\"$defaulturl\";\n"; // for some reason mod_imagemap does not like this line
  $dotfile .= "  fontpath=\"$fontbase\";\n";
  $dotfile .= "  charset=\"$charset\";\n";
  $dotfile .= "  rankdir=\"LR\";\n";
  $dotfile .= "  ranksep=\"0.2\";\n";
  $dotfile .= "  nodesep=\"0.1\";\n";
  $dotfile .= "  overlap=\"false\";\n";
  $dotfile .= "  node [shape=\"$shape\",color=\"#cccccc\",fontname=\"FreeSans\", fontsize=\"8\",fontcolor=\"#000000\" margin=\"0.04\" height=\"0.25\"];\n";
  $dotfile .= "  edge [color=\"#cccccc\",arrowhead=\"none\"];\n";
  $dotfile .= $dotlines;
  $dotfile .= "}";

  // write everything to the dot input file
  $fhout = fopen($outfilename, 'w');
  fwrite($fhout, $dotfile);
  fclose($fhout);

  //chmod ($infilename, 0775);
  chmod ($outfilename, 0775);
}


// create the dot files in ./output/
function dot_create_images($a1 = NULL, $a2 = NULL) {
  global $base;
  global $dotbase;
  ($a2 == 'hierarchical') ? $layout = 'dot -v' : $layout = 'twopi';
/*
  // create gif image
  passthru("touch $base/$a1.gif");          // create file
  passthru("$dotbase/$layout -o $base/$a1.gif -Tgif $base/$a1.dot 2>&1");
  chmod ("$base/$a1.gif", 0775);            //change permissions on file
*/
  // create png image
  passthru("touch $base/$a1.png");          // create file
  passthru("$dotbase/$layout -o $base/$a1.png -Tpng $base/$a1.dot 2>&1");
  chmod ("$base/$a1.png", 0775);            //change permissions on file

  // create image map
  passthru("touch $base/$a1.map");
  passthru("$dotbase/$layout -o $base/$a1.map -Timap $base/$a1.dot");
  // passthru("$dotbase/$layout -Tcmap $base/input/$a1.dot > $base/output/$a1.cmap");
  chmod ("$base/$a1.map", 0775);
/*
  // create svg
  passthru("$dotbase/$layout -o $base/$a1.svg -Tsvg $base/$a1.dot");
  // open file, remove comment and rewrite file
  modify_svg($a1);
  chmod ("$base/$a1.svg", 0775);
*/
}

function view($a1) {
  global $fileurl;
  echo "<h1><a href=\"$fileurl\">GraphViz sitemap generator</a> / $a1</h1>
  <a href=\"$fileurl/$a1.map\"><img src=\"$fileurl/$a1.png\" ismap=\"ismap\" border=\"0\" ></a><br /><br />
  <small>
  Uploaded file: <a href=\"$fileurl/$a1\">$fileurl/$a1</a><br />
  GIF: <a href=\"$fileurl/$a1.gif\">$fileurl/$a1.gif</a><br />
  PNG: <a href=\"$fileurl/$a1.png\">$fileurl/$a1.png</a><br />
  SVG: <a href=\"$fileurl/$a1.svg\">$fileurl/$a1.svg</a><br />
  Image map: <a href=\"$fileurl/map.php?map=$a1\">$fileurl/map.php?map=$a1</a><br />
  Plain site map (without navigation links): <a href=\"$fileurl/?q=viewsitemap&amp;a1=$a1\">$fileurl/?q=viewsitemap&amp;a1=$a1</a><br />
  </small>";
}

function viewsitemap($a1) {
  global $fileurl;
  echo "<a href=\"$fileurl/$a1.map\"><img src=\"$fileurl/$a1.png\" ismap=\"ismap\" border=\"0\" ></a><br /><br />";
}

// modify svg files in ./output/
function modify_svg($a1 = NULL) {
  global $base;
  // get arguments, assign var values
  $infilename = "$base/$a1.svg";
  $outfilename = $infilename;

  // read uploaded file
  $fhin = fopen($infilename, 'r');
    $file_contents = fread($fhin, filesize($infilename));
     $outfile = preg_replace('/<!--(.|\s)*?-->/', '', $file_contents); 
  fclose($fhin);

  // write everything to the dot input file
  $fhout = fopen($outfilename, 'w');
  fwrite($fhout, $outfile);
  fclose($fhout);

}


// version info
function vers() {
  echo "
  <h1>Version info</h1>
  <b>0.1</b><br />
  First version released with minimal configuration options.<br />
  <br />
  <b>0.1.1</b><br />
  Fixed bug caused by single and double quotes in upload files.<br />
  <br />
  <b>0.2</b><br />
  Added layout, shape and style attributes.<br />
  <br />
  <b>0.3</b><br />
  Fixed bug that was producing unusable SVG output. SVG files should now work, but font size is rather large.<br />
  <br />
  <b>0.4</b><br />
  Added minor feature to break up labels by using a newline marker <nl> in tab delimmited file.<br />
  <br />
  <b>0.5</b><br />
  Added error recovery messages.<br />
  <br />
  <b>0.5.1</b><br />
  Added direct links to view a) image map, b) gif and c) sitemap without navigation links. Set SVG files with smaller fonts to make more readable. Released under GPL.<br />
  <br />
  <b>0.5.2</b><br />
  Added some installation documentation and import_request_variables('GPC'); per Gilbert Grosdidier's suggestion.<br />
  <br />
  <b>0.5.3</b><br />
  Fixed bug when using tab character instead of \t in replacement string.<br />
  <br />
  <b>0.5.3a</b><br />
  Adapted the input methods to read from a MySQL database insted of from uploaded file. (Amilcar Lucas)<br />
  <br />
  <b>0.5.3b</b><br />
  Use .png images instead of .gif images. (Amilcar Lucas)<br />
  <br />
  ";
}

// error messages
function error($msg) {
  echo "<h1>Error</h1><p>$msg</p>";
}

################################################################################

?>
