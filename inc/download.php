<?php
module_head('Download', 'quickbox cat-download');
global $lsv;
global $K_VERSION;
echo "<p>Latest release:</p>\n";
echo '<a href="http://download.kde.org/download.php?url=stable/kdevelop/', $K_VERSION[$lsv]['latest_version'] , '/" class="download">KDevelop ', $K_VERSION[$lsv]['latest_version'], '<br><span class="date">', strftime($l_dateformat, $K_VERSION[$lsv]['release_date']), "</span></a>\n";
echo '<a href="http://download.kde.org/download.php?url=stable/kdevelop/', $K_VERSION['3.5']['latest_version'] , '/" class="download download-legacy">KDevelop ', $K_VERSION['3.5']['latest_version'], '<br><span class="date">', strftime($l_dateformat, $K_VERSION['3.5']['release_date']), " (legacy release)</span></a>\n";
echo "<p>More:</p>\n";
echo "<ul>\n";
//echo "<li><a href=\"https://projects.kde.org/projects/extragear/kdevelop/\">Other releases, Plugins</a></li>\n";
echo "<li><a href=\"https://projects.kde.org/projects/extragear/kdevelop/\">Latest sources from Git</a></li>\n";
echo "</ul>\n";
module_tail();
?>