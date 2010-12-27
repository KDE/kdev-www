<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/branches_compiling.html")
  echo "<li><a href=\"index.html?filename=HEAD/branches_compiling.html\">KDevelop - $l_pt_HEAD_branches_compiling</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_branches_compiling</li>\n";

if ($filename != "3.5/branches_compiling.html")
  printf("<li><a href=\"index.html?filename=3.5/branches_compiling.html\">KDevelop - $l_pt_x_x_branches_compiling</a></li>\n", "3.5");
else
  printf("<li>KDevelop - $l_pt_x_x_branches_compiling</li>\n", "3.5");

if ($filename != "3.4/branches_compiling.html")
  printf("<li><a href=\"index.html?filename=3.4/branches_compiling.html\">KDevelop - $l_pt_x_x_branches_compiling</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_branches_compiling</li>\n", "3.4");

if ($filename != "3.3/branches_compiling.html")
  printf("<li><a href=\"index.html?filename=3.3/branches_compiling.html\">KDevelop - $l_pt_x_x_branches_compiling</a></li>\n", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_branches_compiling</li>\n", "3.3");

if ($filename != "3.2/branches_compiling.html")
  printf("<li><a href=\"index.html?filename=3.2/branches_compiling.html\">KDevelop - $l_pt_x_x_branches_compiling</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_branches_compiling</li>\n", "3.2");

if ($filename != "3.1/branches_compiling.html")
  printf("<li><a href=\"index.html?filename=3.1/branches_compiling.html\">KDevelop - $l_pt_x_x_branches_compiling</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_branches_compiling</li>\n", "3.1");

if ($filename != "3.0/branches_compiling.html")
  printf("<li><a href=\"index.html?filename=3.0/branches_compiling.html\">KDevelop - $l_pt_x_x_branches_compiling</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_branches_compiling</li>\n", "3.0");

if ($filename != "2.1/kdevelop.html")
  echo "<li><a href=\"index.html?filename=2.1/kdevelop.html#Compiling\">$l_branches_compiling_section_at KDevelop - $l_pt_2_1_kdevelop</a></li>\n";
else
  echo "<li>$l_branches_compiling_section_at KDevelop - $l_pt_2_1_kdevelop</li>\n";

if ($filename != "1.3/kdevelop.html")
  echo "<li><a href=\"index.html?filename=1.3/kdevelop.html#Compiling\">$l_branches_compiling_section_at KDevelop - $l_pt_1_3_kdevelop</a></li>";
else
  echo "<li>$l_branches_compiling_section_at KDevelop - $l_pt_1_3_kdevelop</li>";

echo "</ul>";
module_tail();
