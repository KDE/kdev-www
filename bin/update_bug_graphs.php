#!/usr/bin/php
<?php
#
# This script should be invoked by a user with write permissions on the
# dynamic/ subdirectory on the website and using a cron job
#
# The purpose of this script is to create a webpage with local copies of bug counts
# on the KDevelop website. All the files
# are created in the dynamic/ subdirectory of the website.
#
# begin 3 December 2007
# (c) 2007 Amilcar Lucas <amilcar@kdevelop.org>
# published under the GNU GPL

$inputfilename ='../dynamic/weekly-bug-summary.htm';
$resultfilename='../dynamic/weekly-bug-summary.html';

// Open the input file copied from http://bugs.kde.org/weekly-bug-summary.cgi via wget
$inputfile = @fopen($inputfilename, 'r');
if (!$inputfile) {
  die("<p>Unable to open remote file \"$inputfilename\".</p>\n");
}

// output file
$resultfile = @fopen($resultfilename, 'w');
if (!$resultfile) {
  die("<p>Unable to open local file \"$resultfilename\".</p>\n");
}

$bug_summary = '<?php module_head($l_bom); ?>
  <center>
<h2>KDevPlatform</h2>
';
while (!feof($inputfile)) {
  $line = fgets($inputfile, 1024);
  /* Finds "kdevplatform " string */
  if (ereg("product=kdevplatform", $line, $out)) {
    $bug_summary .= "    <br>\n";
    $bug_summary .= "    <table border=0 cellspacing=2 cellpadding=5>\n";
    $bug_summary .= "      <tr>\n";
    $bug_summary .= "<?php echo \"        <th>\$l_ob</th><th>\$l_ol7d</th><th>\$l_cl7d</th><th>\$l_c</th><th>\$l_ow</th><th>\$l_ol7d</th><th>\$l_cl7d</th><th>\$l_c</th>\n\"; ?>";
    $bug_summary .= "      </tr>\n";
    $bug_summary .= "      <tr>\n";
    $line = fgets($inputfile, 1024);
    /* Replaces the relative link with an absolute link*/
    $bug_summary .= str_replace('buglist.cgi', 'http://bugs.kde.org/buglist.cgi', $line);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= fgets($inputfile, 1024);
    $line = fgets($inputfile, 1024);
    /* Replaces the relative link with an absolute link*/
    $bug_summary .= str_replace('buglist.cgi', 'http://bugs.kde.org/buglist.cgi', $line);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= "      </tr>\n";
    $bug_summary .= "    </table>\n";
    $bug_summary .= "    <br>\n";
    // get the cached graphic from bugs.kde.org server
    $bug_summary .= "    <img src=\"dynamic/kdevplatform_FIXED.png\" alt=\"<?php echo \$l_rbg; ?>\"><br>
    <br>\n";
    // get the cached graphic from bugs.kde.org server
    $bug_summary .= "    <img src=\"dynamic/kdevplatform_NEW_REOPENED_UNCONFIRMED.png\" alt=\"<?php echo \$l_obwg; ?>\"><br>
    <br>
<h2>KDevelop</h2>
";
    break;
  }
}

rewind($inputfile);

while (!feof($inputfile)) {
  $line = fgets($inputfile, 1024);
  /* Finds "kdevelop " string */
  if (ereg("product=kdevelop", $line, $out)) {
    $bug_summary .= "    <br>\n";
    $bug_summary .= "    <table border=0 cellspacing=2 cellpadding=5>\n";
    $bug_summary .= "      <tr>\n";
    $bug_summary .= "<?php echo \"        <th>\$l_ob</th><th>\$l_ol7d</th><th>\$l_cl7d</th><th>\$l_c</th><th>\$l_ow</th><th>\$l_ol7d</th><th>\$l_cl7d</th><th>\$l_c</th>\n\"; ?>";
    $bug_summary .= "      </tr>\n";
    $bug_summary .= "      <tr>\n";
    $line = fgets($inputfile, 1024);
    /* Replaces the relative link with an absolute link*/
    $bug_summary .= str_replace('buglist.cgi', 'http://bugs.kde.org/buglist.cgi', $line);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= fgets($inputfile, 1024);
    $line = fgets($inputfile, 1024);
    /* Replaces the relative link with an absolute link*/
    $bug_summary .= str_replace('buglist.cgi', 'http://bugs.kde.org/buglist.cgi', $line);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= fgets($inputfile, 1024);
    $bug_summary .= "      </tr>\n";
    $bug_summary .= "    </table>\n";
    $bug_summary .= "    <br>\n";
    // get the cached graphic from bugs.kde.org server
    $bug_summary .= "    <img src=\"dynamic/kdevelop_FIXED.png\" alt=\"<?php echo \$l_rbg; ?>\"><br>
    <br>\n";
    // get the cached graphic from bugs.kde.org server
    $bug_summary .= "    <img src=\"dynamic/kdevelop_NEW_REOPENED_UNCONFIRMED.png\" alt=\"<?php echo \$l_obwg; ?>\"><br>
    <br>
";
    break;
  }
}
fclose($inputfile);

// return the timestamp when the file was updated
$bug_summary .= '  </center>
<?php module_tail();
return '.time().'; ?>';

@fwrite($resultfile, $bug_summary);
fclose($resultfile);

?>