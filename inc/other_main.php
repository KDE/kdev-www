<?php module_head("$l_other_news");
echo "<ul>";
for ($y=date('Y') ; $y >= 1999 ; $y--)
  if ($filename != "main$y.html")
    printf("<li class=\"othershoriz\"><a href=\"index.html?filename=main$y.html\">KDevelop - $l_pt_main_year</a></li>\n", $y);
  else
    printf("<li class=\"othershoriz\">KDevelop - $l_pt_main_year</li>\n", $y);
echo "</ul>";
module_tail();
