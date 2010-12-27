<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/features.html")
  echo "<li><a href=\"index.html?filename=HEAD/features.html\">KDevelop - $l_pt_HEAD_features</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_features</li>\n";

if ($filename != "3.5/features.html")
  printf("<li><a href=\"index.html?filename=3.5/features.html\">KDevelop - $l_pt_x_x_features</a></li>\n", "3.5");
else
  printf("<li>KDevelop - $l_pt_x_x_features</li>\n", "3.5");

if ($filename != "3.4/features.html")
  printf("<li><a href=\"index.html?filename=3.4/features.html\">KDevelop - $l_pt_x_x_features</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_features</li>\n", "3.4");

if ($filename != "3.3/features.html")
  printf("<li><a href=\"index.html?filename=3.3/features.html\">KDevelop - $l_pt_x_x_features</a></li>\n", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_features</li>\n", "3.3");

if ($filename != "3.2/features.html")
  printf("<li><a href=\"index.html?filename=3.2/features.html\">KDevelop - $l_pt_x_x_features</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_features</li>\n", "3.2");

if ($filename != "3.1/features.html")
  printf("<li><a href=\"index.html?filename=3.1/features.html\">KDevelop - $l_pt_x_x_features</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_features</li>\n", "3.1");

if ($filename != "3.0/features.html")
  printf("<li><a href=\"index.html?filename=3.0/features.html\">KDevelop - $l_pt_x_x_features</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_features</li>\n", "3.0");

if ($filename != "2.1/features.html")
  echo "<li><a href=\"index.html?filename=2.1/features.html\">KDevelop - $l_pt_2_1_features</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_2_1_features</li>\n";

if ($filename != "1.3/features.html")
  echo "<li><a href=\"index.html?filename=1.3/features.html\">KDevelop - $l_pt_1_3_features</a></li>";
else
  echo "<li>KDevelop - $l_pt_1_3_features</li>";

echo "</ul>";
module_tail();
