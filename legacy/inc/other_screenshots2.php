<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/screenshots2.html")
  echo "<li><a href=\"index.html?filename=HEAD/screenshots2.html\">KDevelop - $l_pt_HEAD_screenshots2</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_screenshots2</li>\n";

if ($filename != "3.4/screenshots2.html")
  printf("<li><a href=\"index.html?filename=3.4/screenshots2.html\">KDevelop - $l_pt_x_x_screenshots2</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots2</li>\n", "3.4");

if ($filename != "3.3/screenshots2.html")
  printf("<li><a href=\"index.html?filename=3.3/screenshots2.html\">KDevelop - $l_pt_x_x_screenshots2</a></li>\n", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots2</li>\n", "3.3");

if ($filename != "3.2/screenshots2.html")
  printf("<li><a href=\"index.html?filename=3.2/screenshots2.html\">KDevelop - $l_pt_x_x_screenshots2</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots2</li>\n", "3.2");

if ($filename != "3.1/screenshots2.html")
  printf("<li><a href=\"index.html?filename=3.1/screenshots2.html\">KDevelop - $l_pt_x_x_screenshots2</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots2</li>\n", "3.1");

if ($filename != "3.0/screenshots2.html")
  printf("<li><a href=\"index.html?filename=3.0/screenshots2.html\">KDevelop - $l_pt_x_x_screenshots2</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_screenshots2</li>\n", "3.0");

if ($filename != "2.1/screenshots2.html")
  echo "<li><a href=\"index.html?filename=2.1/screenshots2.html\">KDevelop - $l_pt_2_1_screenshots2</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_2_1_screenshots2</li>\n";

if ($filename != "1.3/screenshots2.html")
  echo "<li><a href=\"index.html?filename=1.3/screenshots2.html\">KDevelop - $l_pt_1_3_screenshots2</a></li>";
else
  echo "<li>KDevelop - $l_pt_1_3_screenshots2</li>";

echo "</ul>";
module_tail();
