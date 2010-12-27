<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/screenshots3.html")
  echo "<li><a href=\"index.html?filename=HEAD/screenshots3.html\">KDevelop - $l_pt_HEAD_screenshots3</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_screenshots3</li>\n";

if ($filename != "3.4/screenshots3.html")
  printf("<li><a href=\"index.html?filename=3.4/screenshots3.html\">KDevelop - $l_pt_x_x_screenshots3</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots3</li>\n", "3.4");

if ($filename != "3.3/screenshots3.html")
  printf("<li><a href=\"index.html?filename=3.3/screenshots3.html\">KDevelop - $l_pt_x_x_screenshots3</a></li>\n", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots3</li>\n", "3.3");

if ($filename != "3.2/screenshots3.html")
  printf("<li><a href=\"index.html?filename=3.2/screenshots3.html\">KDevelop - $l_pt_x_x_screenshots3</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots3</li>\n", "3.2");

if ($filename != "3.1/screenshots3.html")
  printf("<li><a href=\"index.html?filename=3.1/screenshots3.html\">KDevelop - $l_pt_x_x_screenshots3</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots3</li>\n", "3.1");

if ($filename != "3.0/screenshots3.html")
  printf("<li><a href=\"index.html?filename=3.0/screenshots3.html\">KDevelop - $l_pt_x_x_screenshots3</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots3</li>\n", "3.0");

if ($filename != "2.1/screenshots3.html")
  echo "<li><a href=\"index.html?filename=2.1/screenshots3.html\">KDevelop - $l_pt_2_1_screenshots3</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_2_1_screenshots3</li>\n";

if ($filename != "1.3/screenshots3.html")
  echo "<li><a href=\"index.html?filename=1.3/screenshots3.html\">KDevelop - $l_pt_1_3_screenshots3</a></li>";
else
  echo "<li>KDevelop - $l_pt_1_3_screenshots3</li>";

echo "</ul>";
module_tail();
