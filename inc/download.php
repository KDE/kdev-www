<?php
module_head('Download', 'quickbox cat-download');
global $lsv;
global $K_VERSION;
echo "<p>Latest release:</p>\n";
echo '<a href="http://download.kde.org/download.php?url=stable/kdevelop/', $K_VERSION[$lsv]['latest_version'] , '/" class="download">KDevelop ', $K_VERSION[$lsv]['latest_version'], '<br /><span class="date">', strftime($l_dateformat, $K_VERSION[$lsv]['release_date']), "</span></a>\n";
//echo "<a href=\"http://download.kde.org/download.php?url=stable/kdevelop/3.5.5/\" class=\"download download-legacy\">KDevelop 3.5.5<br /><span class=\"date\">Aug 11, 2009 (legacy release)</span></a>\n";
echo "<p>More:</p>\n";
echo "<ul>\n";
//echo "<li><a href=\"https://projects.kde.org/projects/extragear/kdevelop/\">Other releases, Plugins</a></li>\n";
echo "<li><a href=\"https://projects.kde.org/projects/extragear/kdevelop/\">Latest sources from Git</a></li>\n";
echo "</ul>\n";
module_tail();
?>