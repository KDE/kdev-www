<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/ChangeLog.html")
  echo "<li><a href=\"index.html?filename=HEAD/ChangeLog.html\">KDevelop - $l_pt_HEAD_ChangeLog</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_ChangeLog</li>\n";

if ($filename != "3.5/ChangeLog.html")
  printf("<li><a href=\"index.html?filename=3.5/ChangeLog.html\">KDevelop - $l_pt_x_x_ChangeLog</a></li>\n", "3.5");
else
  printf("<li>KDevelop - $l_pt_x_x_ChangeLog</li>\n", "3.5");

if ($filename != "3.4/ChangeLog.html")
  printf("<li><a href=\"index.html?filename=3.4/ChangeLog.html\">KDevelop - $l_pt_x_x_ChangeLog</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_ChangeLog</li>\n", "3.4");

if ($filename != "3.3/ChangeLog.html")
  printf("<li><a href=\"index.html?filename=3.3/ChangeLog.html\">KDevelop - $l_pt_x_x_ChangeLog</a></li>", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_ChangeLog</li>", "3.3");

echo "</ul>";
module_tail();
