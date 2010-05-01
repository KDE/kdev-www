<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/authors.html")
  echo "<li><a href=\"index.html?filename=HEAD/authors.html\">KDevelop - $l_pt_HEAD_authors</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_authors</li>\n";

if ($filename != "3.5/authors.html")
  printf("<li><a href=\"index.html?filename=3.5/authors.html\">KDevelop - $l_pt_x_x_authors</a></li>\n", "3.5");
else
  printf("<li>KDevelop - $l_pt_x_x_authors</li>\n", "3.5");

if ($filename != "3.4/authors.html")
  printf("<li><a href=\"index.html?filename=3.4/authors.html\">KDevelop - $l_pt_x_x_authors</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_authors</li>\n", "3.4");

if ($filename != "3.3/authors.html")
  printf("<li><a href=\"index.html?filename=3.3/authors.html\">KDevelop - $l_pt_x_x_authors</a></li>\n", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_authors</li>\n", "3.3");

if ($filename != "3.2/authors.html")
  printf("<li><a href=\"index.html?filename=3.2/authors.html\">KDevelop - $l_pt_x_x_authors</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_authors</li>\n", "3.2");

if ($filename != "3.1/authors.html")
  printf("<li><a href=\"index.html?filename=3.1/authors.html\">KDevelop - $l_pt_x_x_authors</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_authors</li>\n", "3.1");

if ($filename != "3.0/authors.html")
  printf("<li><a href=\"index.html?filename=3.0/authors.html\">KDevelop - $l_pt_x_x_authors</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_authors</li>\n", "3.0");

if ($filename != "2.1/authors.html")
  echo "<li><a href=\"index.html?filename=2.1/authors.html\">KDevelop - $l_pt_2_1_authors</a></li>";
else
  echo "<li>KDevelop - $l_pt_2_1_authors</li>";

if ($filename != "1.3/authors.html")
  echo "<li><a href=\"index.html?filename=1.3/authors.html\">KDevelop - $l_pt_1_3_authors</a></li>";
else
  echo "<li>KDevelop - $l_pt_1_3_authors</li>";

echo "</ul>";
module_tail();
