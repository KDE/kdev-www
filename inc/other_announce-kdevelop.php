<?php
if ($filename == 'in_the_press.html')
  module_head('KDevelop press releases');
else
  module_head($l_other_versions);

echo "<ul>";
if ($filename != "3.5/announce-kdevelop-3.5.html")
  printf("<li><a href=\"index.html?filename=3.5/announce-kdevelop-3.5.html\">KDevelop - $l_pt_x_x_announce_kdevelop</a></li>\n", "3.5");
else
  printf("<li>KDevelop - $l_pt_x_x_announce_kdevelop</li>\n", "3.5");

if ($filename != "3.4/announce-kdevelop-3.4.html")
  printf("<li><a href=\"index.html?filename=3.4/announce-kdevelop-3.4.html\">KDevelop - $l_pt_x_x_announce_kdevelop</a></li>\n", "3.4");
else
  printf("<li>KDevelop - $l_pt_x_x_announce_kdevelop</li>\n", "3.4");

if ($filename != "3.3/announce-kdevelop-3.3.html")
  printf("<li><a href=\"index.html?filename=3.3/announce-kdevelop-3.3.html\">KDevelop - $l_pt_x_x_announce_kdevelop</a></li>\n", "3.3");
else
  printf("<li>KDevelop - $l_pt_x_x_announce_kdevelop</li>\n", "3.3");

if ($filename != "3.2/announce-kdevelop-3.2.html")
  printf("<li><a href=\"index.html?filename=3.2/announce-kdevelop-3.2.html\">KDevelop - $l_pt_x_x_announce_kdevelop</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_announce_kdevelop</li>\n", "3.2");

if ($filename != "3.1/announce-kdevelop-3.1.html")
  printf("<li><a href=\"index.html?filename=3.1/announce-kdevelop-3.1.html\">KDevelop - $l_pt_x_x_announce_kdevelop</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_announce_kdevelop</li>\n", "3.1");

if ($filename != "3.0/announce-kdevelop-3.0.html")
  printf("<li><a href=\"index.html?filename=3.0/announce-kdevelop-3.0.html\">KDevelop - $l_pt_x_x_announce_kdevelop</a></li>\n", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_announce_kdevelop</li>\n", "3.0");

if ($filename != "2.1/announce-kdevelop-2.0.html")
  echo "<li><a href=\"index.html?filename=2.1/announce-kdevelop-2.0.html\">KDevelop - $l_pt_2_1_announce_kdevelop_2_0</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_2_1_announce_kdevelop_2_0</li>\n";

if ($filename != "1.3/announce-kdevelop-1.3.html")
  echo "<li><a href=\"index.html?filename=1.3/announce-kdevelop-1.3.html\">KDevelop - $l_pt_1_3_announce_kdevelop_1_3</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_1_3_announce_kdevelop_1_3</li>\n";

if ($filename != "1.3/pressrelease1.1.html")
  echo "<li><a href=\"index.html?filename=1.3/pressrelease1.1.html\">KDevelop - $l_pt_1_3_pressrelease1_1</a></li>\n";
else
  echo "<li>KDevelop - $l_pt_1_3_pressrelease1_1</li>\n";

if ($filename != "1.3/pressrelease1.0.html")
  echo "<li><a href=\"index.html?filename=1.3/pressrelease1.0.html\">KDevelop - $l_pt_1_3_pressrelease1_0</a></li>";
else
  echo "<li>KDevelop - $l_pt_1_3_pressrelease1_0</li>";

echo "</ul>";
module_tail();
