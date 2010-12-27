<?php


// This MUST be done in two steps because of the "title"
$ftmp = fopen("dynamic/menu_normal_user$lang.tmp",'w');
fwrite($ftmp,'<?php
$f = fopen("dynamic/menu_normal_user_$lang.html","w");
fwrite($f,"');
$style='';//' class=versionlink';
fwrite($ftmp, addslashes(
linkbox_head($l_documentation, "navbox", "cat-doc")."<ul>
<li><a href=\"mediawiki/index.php/KDevelop_4/Documentation\">$l_user_manual</a></li>
<li>".create_link("tutorials.html", $l_tutorials, $style)."</li>
<li>".create_link("versions.html", $l_version_history, $style)."</li>
<li><a href=\"doc/technotes/index.html\">$l_technotes</a></li>
<li><a href=\"mediawiki/index.php/Main_Page\" title=\"KDevelop Wiki\">Wiki</a></li>
</ul>".linkbox_tail()."

".linkbox_head($l_development, "navbox", "cat-devel")."<ul>
<li><a href=\"mediawiki/index.php/KDevelop_4\">$l_devel</a></li>
<li>$l_sources<ul>
<li><a href=\"mediawiki/index.php/KDevelop_4/KDElibs_Compatibility\">$l_branches</a></li>
<li><a href=\"mediawiki/index.php/KDevelop_4/compiling\">$l_compiling</a></li></ul>
</ul>".linkbox_tail()."

".linkbox_head($l_community, "navbox", "cat-community")."<ul>
<li<a href=\"phorum5/index.php\" title=\"KDevelop Forum\">$l_forum</a></li>
<li>".create_link("mailinglist.html", $l_mailinglist, $style)."</li>
<li><a href=\"chat/\" title=\"KDevelop IRC chat channel\">$l_on_line_chat</a></li>
<li>".create_link("$lsv/kdevelop.html#Bugs", $l_bugs_wishes, $style)."</li>
<li>".create_link("$lsv/authors.html", $l_credits, $style)."</li>
</ul>".linkbox_tail()."

".linkbox_head($l_promotion,  "navbox", "cat-promotion")."<ul>
<li>".create_link("logos_banners.html", $l_logos_banners, $style)."</li>
<li>".create_link("awards.html", $l_awards, $style)."</li>
<li>".create_link("in_the_press.html", $l_in_the_press, $style)."</li>
<li>".create_link("splashscreens.html", $l_splashscreens, $style)."</li>
</ul>".linkbox_tail()."\n"));
fwrite($ftmp, '");
fclose($f); ?>');

fclose($ftmp);

// This is the second step because of the "title"
include("dynamic/menu_normal_user$lang.tmp");

// Delete the no longer necessary intermidiate file
unlink("dynamic/menu_normal_user$lang.tmp");

?>
