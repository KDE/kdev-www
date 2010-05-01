<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/faq.html")
  echo "<li><a href=\"index.html?filename=HEAD/faq.html\">KDevelop - $l_pt_HEAD_faq</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_faq</li>\n";

if ($filename != "3.3/faq.html")
  printf("<li><a href=\"index.html?filename=3.3/faq.html\">KDevelop - $l_pt_x_x_faq</a></li>\n", '3.3, 3.4, 3.5');
else
  printf("<li>KDevelop - $l_pt_x_x_faq</li>\n", '3.3, 3.4, 3.5');

if ($filename != "3.2/faq.html")
  printf("<li><a href=\"index.html?filename=3.2/faq.html\">KDevelop - $l_pt_x_x_faq</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_faq</li>\n", "3.2");

if ($filename != "3.1/faq.html")
  printf("<li><a href=\"index.html?filename=3.1/faq.html\">KDevelop - $l_pt_x_x_faq</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_faq</li>\n", "3.1");

if ($filename != "3.0/faq.html")
  printf("<li><a href=\"index.html?filename=3.0/faq.html\">KDevelop - $l_pt_x_x_faq</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_faq</li>\n", "3.0");

if ($filename != "2.1/faq.html")
  echo "<li><a href=\"index.html?filename=2.1/faq.html\">KDevelop - $l_pt_2_1_faq</a></li>";
else
  echo "<li>KDevelop - $l_pt_2_1_faq</li>";

echo "</ul>";
module_tail();
