<?php


// This MUST be done in two steps because of the "title"
$ftmp = fopen("dynamic/menu_normal_user$lang.tmp",'w');
fwrite($ftmp,'<?php
$f = fopen("dynamic/menu_normal_user_$lang.html","w");
fwrite($f,"');
$style='';//' class=versionlink';
fwrite($ftmp, addslashes("<div class=\"navbox cat-doc\"><div class=\"header\"><img class=\"category-icon\"/>
<h3>$l_documentation</h3></div><div class=\"content\"><ul>
".create_intern_menu_item("versions.html", $l_versions, $style)."
<li$style><a href=\"$stable_user_manual_url\">$l_user_manual</a></li>
".create_intern_menu_item("doc/technotes/index.html", $l_technotes, $style)."
<li$style><a href=\"mediawiki/index.php/Main_Page\">$l_mediawiki</a></li>
</ul></div><div class=\"footer\"></div></div>

<div class=\"navbox cat-devel\"><div class=\"header\"><img class=\"category-icon\"/><h3>$l_development</h3></div><div class=\"content\"><ul>
".create_intern_menu_item("$lsv/changes.html", $l_changes, $style)."
".create_intern_menu_item("$lsv/branches_compiling.html", $l_cvs_branches_compiling, $style)."
".create_intern_menu_item('join-the-team.html', $l_join_us, $style)."
".create_intern_menu_item('website_translation_status.html', $l_website_translation, $style)."
</ul></div><div class=\"footer\"></div></div>

<div class=\"navbox\"><div class=\"header\"><img class=\"category-icon\"/><h3>$l_links</h3></div><div class=\"content\"><ul>
".create_intern_menu_item("users.html", $l_user_progs, $style)."
".create_intern_menu_item("sponsors.html", $l_sponsors, $style)."
".create_intern_menu_item("links_tools.html", $l_development_tools, $style)."
</ul></div><div class=\"footer\"></div></div>

<div class=\"navbox cat-promotion\"><div class=\"header\"><img class=\"category-icon\"/><h3>$l_presentations_graphics</h3></div><div class=\"content\"><ul>
".create_intern_menu_item("in_the_press.html", $l_in_the_press, $style)."
".create_intern_menu_item("awards.html", $l_awards, $style)."
".create_intern_menu_item("logos_banners.html", $l_logos_banners, $style)."
".create_intern_menu_item("splashscreens.html", $l_splashscreens, $style)."
</ul></div><div class=\"footer\"></div></div>

<div class=\"navbox cat-community\"><div class=\"header\"><img class=\"category-icon\"/><h3>$l_contacts</h3></div><div class=\"content\"><ul>
".create_intern_menu_item("$lsv/kdevelop.html#Bugs", $l_bugs_wishes, $style)."
<li$style><a href=\"chat/\">$l_on_line_chat</a></li>
<li$style><a href=\"phorum5/index.php\">$l_forum</a></li>
".create_intern_menu_item("mailinglist.html", $l_mailinglist, $style)."
<li$style><a href=\"phorum5/read.php?10,26111\">$l_guestbook</a></li>
".create_intern_menu_item("$lsv/authors.html", $l_credits, $style)."
</ul></div><div class=\"footer\"></div></div>\n"));
//".create_intern_menu_item("bugs.html", $l_bugs_wishes, $style)."
//".create_intern_menu_item("donate.html", $l_donation)."
fwrite($ftmp, '");
fclose($f); ?>');

fclose($ftmp);

// This is the second step because of the "title"
include("dynamic/menu_normal_user$lang.tmp");

// Delete the no longer necessary intermidiate file
unlink("dynamic/menu_normal_user$lang.tmp");

?>
