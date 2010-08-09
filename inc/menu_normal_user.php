<?php


// This MUST be done in two steps because of the "title"
$ftmp = fopen("dynamic/menu_normal_user$lang.tmp",'w');
fwrite($ftmp,'<?php
$f = fopen("dynamic/menu_normal_user_$lang.html","w");
fwrite($f,"');
$style='';//' class=versionlink';
fwrite($ftmp, addslashes(
linkbox_head($l_documentation, "navbox", "cat-doc")."<ul>
<li>".create_link("about.html", $l_about, $style)."</li>
<li>".create_link("$lsv/manual.html", $l_user_manual)."</li>
<li>".create_link("$lsv/tutorials.html", $l_tutorials)."</li>
<li>".create_link("versions.html", $l_version_history)."</li>
<li><a href=\"mediawiki/index.php/Main_Page\" title=\"KDevelop Wiki\">Wiki</a></li>
</ul>".linkbox_tail()."

".linkbox_head($l_development, "navbox", "cat-devel")."<ul>
<li>".create_link("$lsv/devel.html", $l_devel)."</li>
<li>$l_sources<ul>
<li>".create_link("$lsv/branches.html", $l_branches)."</li>
<li>".create_link("$lsv/compiling.html", $l_compiling)."</li></ul>
</ul>".linkbox_tail()."

".linkbox_head($l_community, "navbox", "cat-community")."<ul>
<li<a href=\"phorum5/index.php\" title=\"KDevelop Forum\">$l_forum</a></li>
<li>".create_link("mailinglist.html", $l_mailinglist)."</li>
<li><a href=\"chat/\">$l_on_line_chat</a></li>
<li>".create_link("$lsv/kdevelop.html#Bugs", $l_bugs_wishes, $style)."</li>
<li>".create_link("contribute.html", $l_contribute)."</li>
</ul>".linkbox_tail()."

<div class=\"navbox cat-promotion\"><div class=\"header\"><span class=\"category-icon\"/></span><h3>$l_promotion</h3></div><div class=\"content\"><ul>
<li>".create_link("logos_banners.html", $l_logos_banners, $style)."</li>
<li>".create_link("awards.html", $l_awards, $style)."</li>
<li>".create_link("in_the_press.html", $l_in_the_press, $style)."</li>
</ul>".linkbox_tail()."\n"));
fwrite($ftmp, '");
fclose($f); ?>');

fclose($ftmp);

// This is the second step because of the "title"
include("dynamic/menu_normal_user$lang.tmp");

// Delete the no longer necessary intermidiate file
unlink("dynamic/menu_normal_user$lang.tmp");

?>
