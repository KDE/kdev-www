<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "bugs.html")
  echo "<li><a href=\"index.html?filename=bugs.html\">KDevelop - HEAD $l_pt_bugs</a></li>\n";
else
  echo "<li>KDevelop - HEAD $l_pt_bugs</li>\n";

if ($filename != '3.5/kdevelop.html#Bugs')
  printf("<li><a href=\"index.html?filename=3.5/kdevelop.html#Bugs\">$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</a></li>\n", '3.5');
else
  printf("<li>$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</li>\n", '3.5');

if ($filename != "3.4/kdevelop.html#Bugs")
  printf("<li><a href=\"index.html?filename=3.4/kdevelop.html#Bugs\">$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</a></li>\n", "3.4");
else
  printf("<li>$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</li>\n", "3.4");

if ($filename != "3.3/kdevelop.html#Bugs")
  printf("<li><a href=\"index.html?filename=3.3/kdevelop.html#Bugs\">$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</a></li>\n", "3.3");
else
  printf("<li>$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</li>\n", "3.3");

if ($filename != "3.2/kdevelop.html#Bugs")
  printf("<li><a href=\"index.html?filename=3.2/kdevelop.html#Bugs\">$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</a></li>\n", "3.2");
else
  printf("<li>$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</li>\n", "3.2");

if ($filename != "3.1/kdevelop.html#Bugs")
  printf("<li><a href=\"index.html?filename=3.1/kdevelop.html#Bugs\">$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</a></li>\n", "3.1");
else
  printf("<li>$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</li>\n", "3.1");

if ($filename != "3.0/kdevelop.html#Bugs")
  printf("<li><a href=\"index.html?filename=3.0/kdevelop.html#Bugs\">$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</a></li>\n", "3.0");
else
  printf("<li>$l_bugs_section_at KDevelop - $l_pt_x_x_kdevelop</li>\n", "3.0");

if ($filename != "2.1/kdevelop.html#Bugs")
  echo "<li><a href=\"index.html?filename=2.1/kdevelop.html#Bugs\">$l_bugs_section_at KDevelop - $l_pt_2_1_kdevelop</a></li>\n";
else
  echo "<li>$l_bugs_section_at KDevelop - $l_pt_2_1_kdevelop</li>\n";

if ($filename != "1.3/kdevelop.html#Bugs")
  echo "<li><a href=\"index.html?filename=1.3/kdevelop.html#Bugs\">$l_bugs_section_at KDevelop - $l_pt_1_3_kdevelop</a></li>";
else
  echo "<li>$l_bugs_section_at KDevelop - $l_pt_1_3_kdevelop</li>";

echo "</ul>";
module_tail();
