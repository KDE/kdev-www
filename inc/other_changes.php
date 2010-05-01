<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/changes.html")
  echo "<li><a href=\"index.html?filename=HEAD/changes.html\">KDevelop - $l_pt_HEAD_changes</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_changes</li>\n";

if ($filename != "3.5/changes.html")
  printf("<li><a href=\"index.html?filename=3.5/changes.html\">KDevelop - $l_pt_x_x_changes</a></li>\n", "3.5");
else
  printf("<li>KDevelop - $l_pt_x_x_changes</li>\n", "3.5");

if ($filename != "3.4/changes.html")
  printf("<li><a href=\"index.html?filename=3.4/changes.html\">KDevelop - $l_pt_x_x_changes</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_changes</li>\n", "3.4");

if ($filename != "3.3/changes.html")
  printf("<li><a href=\"index.html?filename=3.3/changes.html\">KDevelop - $l_pt_x_x_changes</a></li>\n", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_changes</li>\n", "3.3");

if ($filename != "3.2/changes.html")
  printf("<li><a href=\"index.html?filename=3.2/changes.html\">KDevelop - $l_pt_x_x_changes</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_changes</li>\n", "3.2");

if ($filename != "3.1/changes.html")
  printf("<li><a href=\"index.html?filename=3.1/changes.html\">KDevelop - $l_pt_x_x_changes</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_changes</li>\n", "3.1");

if ($filename != "3.0/changes.html")
  printf("<li><a href=\"index.html?filename=3.0/changes.html\">KDevelop - $l_pt_x_x_changes</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_changes</li>\n", "3.0");

if ($filename != "2.1/changes.html")
  echo "<li><a href=\"index.html?filename=2.1/changes.html\">KDevelop - $l_pt_2_1_changes</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_2_1_changes</li>\n";

if ($filename != "1.3/changes.html")
  echo "<li><a href=\"index.html?filename=1.3/changes.html\">KDevelop - $l_pt_1_3_changes</a></li>";
else
  echo "<li>KDevelop - $l_pt_1_3_changes</li>";

echo "</ul>";
module_tail();
