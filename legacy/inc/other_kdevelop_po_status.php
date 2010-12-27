<?php module_head($l_other_versions);
echo '<ul>';
// Automaticaly build a link the the HEAD version
echo "<li><a href=\"http://l10n.kde.org/stats/gui/trunk-kde4/package/kdevelop/\">KDevelop - $l_pt_HEAD_kdevelop_po_status</a></li>\n";

// Automaticaly build a link the the stable version
printf("<li><a href=\"http://l10n.kde.org/stats/gui/stable/package/kdevelop/\">KDevelop - $l_pt_x_x_kdevelop_po_status</a></li>\n", $lsv);

// After KDevelop 3.3 no more local pages exist !!

if ($filename != "3.2/kdevelop_po_status.html")
  printf("<li><a href=\"index.html?filename=3.2/kdevelop_po_status.html\">KDevelop - $l_pt_x_x_kdevelop_po_status</a></li>\n", "3.2");
else
  printf("<li>KDevelop - $l_pt_x_x_kdevelop_po_status</li>\n", "3.2");

if ($filename != "3.1/kdevelop_po_status.html")
  printf("<li><a href=\"index.html?filename=3.1/kdevelop_po_status.html\">KDevelop - $l_pt_x_x_kdevelop_po_status</a></li>\n", "3.1");
else
  printf("<li>KDevelop - $l_pt_x_x_kdevelop_po_status</li>\n", "3.1");

if ($filename != "3.0/kdevelop_po_status.html")
  printf("<li><a href=\"index.html?filename=3.0/kdevelop_po_status.html\">KDevelop - $l_pt_x_x_kdevelop_po_status</a></li>", "3.0");
else
  printf("<li>KDevelop - $l_pt_x_x_kdevelop_po_status</li>", "3.0");

echo '</ul>';
module_tail();
