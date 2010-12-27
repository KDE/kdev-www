<?php module_head("$l_other_versions");
echo "<ul>";
if ($filename != "HEAD/download.html")
  echo "<li><a href=\"index.html?filename=HEAD/download.html\">KDevelop - $l_pt_HEAD_download</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_HEAD_download</li>\n";

if ($filename != "3.5/download.html")
  printf("<li><a href=\"index.html?filename=3.5/download.html\">KDevelop - $l_pt_x_x_download</a></li>\n", "3.5");
else
  printf("<li>KDevelop - $l_pt_x_x_download</li>\n", "3.5");

if ($filename != "3.4/download.html")
  printf("<li><a href=\"index.html?filename=3.4/download.html\">KDevelop - $l_pt_x_x_download</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_download</li>\n", "3.4");

if ($filename != "3.3/download.html")
  printf("<li><a href=\"index.html?filename=3.3/download.html\">KDevelop - $l_pt_x_x_download</a></li>\n", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_download</li>\n", "3.3");

if ($filename != "3.2/download.html")
  printf("<li><a href=\"index.html?filename=3.2/download.html\">KDevelop - $l_pt_x_x_download</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_download</li>\n", "3.2");

if ($filename != "3.1/download.html")
  printf("<li><a href=\"index.html?filename=3.1/download.html\">KDevelop - $l_pt_x_x_download</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_download</li>\n", "3.1");

if ($filename != "3.0/download.html")
  printf("<li><a href=\"index.html?filename=3.0/download.html\">KDevelop - $l_pt_x_x_download</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_download</li>\n", "3.0");

if ($filename != "2.1/kdevelop.html")
  echo "<li><a href=\"index.html?filename=2.1/kdevelop.html#Downloads\">$l_download_section_at KDevelop - $l_pt_2_1_kdevelop</a></li>\n";
else
  echo "<li>$l_download_section_at KDevelop - $l_pt_2_1_kdevelop</li>\n";

if ($filename != "1.3/kdevelop.html")
  echo "<li><a href=\"index.html?filename=1.3/kdevelop.html#Downloads\">$l_download_section_at KDevelop - $l_pt_1_3_kdevelop</a></li>";
else
  echo "<li>$l_download_section_at KDevelop - $l_pt_1_3_kdevelop</li>";

echo "</ul>";
module_tail();
