<?php


// This MUST be done in two steps because of the "title"
$ftmp = fopen("dynamic/menu$lang.tmp",'w');
fwrite($ftmp,'<?php
$f = fopen("dynamic/menu_$lang.html","w");
fwrite($f,"<?php module_head(\"$l_website_navigation\"); ?>
');
fwrite($ftmp, addslashes("<table cellspacing=\"4\" cellpadding=\"2\">
<tbody><tr><th colspan=\"3\"><h2>$l_general</h2></th></tr>
<tr><td>$l_home</td><td class=\"versionlink\" colspan=\"2\"><a href=\".\" title=\"KDevelop - $l_pt_main\">$l_all_versions</a></td></tr>
<tr>".create_intern_menu_link("versions.html", $l_versions, " align=\"left\"").create_intern_menu_link("$lsv/kdevelop.html", "$lsv.x").create_intern_menu_link("HEAD/kdevelop.html", "HEAD")."</tr>
<tr><td>$l_features</td>".create_intern_menu_link("$lsv/features.html", "$lsv.x").create_intern_menu_link("HEAD/features.html", "HEAD")."</tr>
<tr><td>$l_requirements</td>".create_intern_menu_link("$lsv/requirements.html", "$lsv.x").create_intern_menu_link("HEAD/requirements.html", "HEAD")."</tr>
<tr><td>$l_download</td>".create_intern_menu_link("$lsv/download.html", "$lsv.x").create_intern_menu_link("HEAD/download.html", "HEAD")."</tr>
<tr><td>$l_cvs_branches_compiling</td>".create_intern_menu_link("$lsv/branches_compiling.html", "$lsv.x").create_intern_menu_link("HEAD/branches_compiling.html", "HEAD")."</tr>
<tr><td>$l_changes</td>".create_intern_menu_link("$lsv/changes.html", "$lsv.x").create_intern_menu_link("HEAD/changes.html", "HEAD")."</tr>
<tr><td>$l_bugs_wishes</td>".create_intern_menu_link("$lsv/kdevelop.html#Bugs", "$lsv.x").create_intern_menu_link("bugs.html", "HEAD")."</tr>
</tbody><tbody><tr><th colspan=\"3\"><h2>$l_documentation</h2></th></tr>
<tr><td>$l_user_manual</td><td class=\"versionlink\"><a href=\"$stable_user_manual_url\">$lsv.x</a></td><td class=\"versionlink\"><a href=\"$HEAD_user_manual_url\">HEAD</a></td></tr>
<tr><td>$l_api_documentation</td><td class=\"versionlink\">-</td><td class=\"versionlink\"><a href=\"HEAD/doc/api/html/index.html\">HEAD</a></td></tr>
<tr><td>$l_tutorials</td>".create_intern_menu_link2("tutorials.html", $l_all_versions)."</tr>
<tr><td>$l_faq</td>".create_intern_menu_link("$lsv/faq.html", "$lsv.x").create_intern_menu_link("HEAD/faq.html", 'HEAD')."</tr>
<tr><td>$l_technotes</td>".create_intern_menu_link2("doc/technotes/index.html", $l_all_versions)."</tr>
<tr><td>$l_mediawiki</td><td class=\"versionlink\" colspan=\"2\"><a href=\"mediawiki/index.php/Main_Page\">$l_all_versions</a></td></tr>
</tbody><tbody><tr><th colspan=\"3\"><h2>$l_presentations_graphics</h2></th></tr>
<tr><td>$l_screenshots</td>".create_intern_menu_link("$lsv/screenshots.html", "$lsv.x").create_intern_menu_link("HEAD/screenshots1.html", "HEAD")."</tr>
<tr><td>$l_in_the_press</td>".create_intern_menu_link2("in_the_press.html", $l_all_versions)."</tr>
<tr><td>$l_awards</td>".create_intern_menu_link2("awards.html", $l_all_versions)."</tr>
<tr><td>$l_logos_banners</td>".create_intern_menu_link2("logos_banners.html", $l_all_versions)."</tr>
<tr><td>$l_splashscreens</td>".create_intern_menu_link2("splashscreens.html", $l_all_versions)."</tr>
<tr><td>$l_events</td>".create_intern_menu_link2("events.html", $l_all_versions)."</tr>
</tbody><tbody><tr><th colspan=\"3\"><h2>$l_development</h2></th></tr>
<tr><td>$l_join_us</td>".create_intern_menu_link2("join-the-team.html", $l_all_versions)."</tr>
<tr><td>$l_current_work_todo_list</td>".create_intern_menu_link2("current_work.html", $l_all_versions)."</tr>
<tr><td>$l_latest_cvs_commits</td>".create_intern_menu_link("$lsv/ChangeLog.html", "$lsv.x").create_intern_menu_link("HEAD/ChangeLog.html", "HEAD")."</tr>
<tr><td>MARC</td><td class=\"versionlink\"><a href=\"http://lists.kde.org/?l=kde-commits&amp;w=2&amp;r=1&amp;s=branches+KDE+$stable_KDE_major_version+kdevelop&amp;q=b\">$lsv.x</a></td><td class=\"versionlink\"><a href=\"http://lists.kde.org/?l=kde-commits&amp;w=2&amp;r=1&amp;s=trunk+KDE+kdevelop&amp;q=b\">HEAD</a></td></tr>
<tr><td>$l_browse_code</td><td class=\"versionlink\"><a href=\"$stable_lxr_url\">$lsv.x</a></td><td class=\"versionlink\"><a href=\"http://lxr.kde.org/source/kdevelop/\">HEAD</a></td></tr>
<tr><td>$l_web_cvs</td><td class=\"versionlink\"><a href=\"http://websvn.kde.org/$stable_KDevelop_BRANCH\">$lsv.x</a></td><td class=\"versionlink\"><a href=\"http://websvn.kde.org/trunk/KDE/kdevelop/\">HEAD</a></td></tr>
<tr><td>$l_user_manual_translation</td><td class=\"versionlink\"><a href=\"http://l10n.kde.org/stats/doc/stable/kdevelop/\">$lsv.x</a></td><td class=\"versionlink\"><a href=\"http://l10n.kde.org/stats/doc/trunk/kdevelop/\">HEAD</a></td></tr>
<tr><td>$l_website_translation</td>".create_intern_menu_link2("website_translation_status.html", $l_all_versions)."</tr>
<tr><td>$l_upload</td>".create_intern_menu_link2("upload.html", $l_all_versions)."</tr>
</tbody><tbody><tr><th colspan=\"3\"><h2>$l_links</h2></th></tr>
<tr><td>$l_user_progs</td>".create_intern_menu_link2("users.html", $l_all_versions)."</tr>
<tr><td>$l_sponsors</td>".create_intern_menu_link2("sponsors.html", $l_all_versions)."</tr>
<tr><td>$l_development</td>".create_intern_menu_link2("links_development.html", $l_all_versions)."</tr>
<tr><td>$l_development_tools</td>".create_intern_menu_link2("links_tools.html", $l_all_versions)."</tr>
</tbody><tbody><tr><th colspan=\"3\"><h2>$l_contacts</h2></th></tr>
<tr><td>$l_on_line_chat</td><td class=\"versionlink\" colspan=\"2\"><a href=\"chat/\">$l_all_versions</a></td></tr>
<tr><td>$l_forum</td><td class=\"versionlink\" colspan=\"2\"><a href=\"phorum5/index.php\">$l_all_versions</a></td></tr>
<tr><td>$l_mailinglist</td>".create_intern_menu_link2("mailinglist.html", $l_all_versions)."</tr>
<tr><td>$l_bugs_wishes</td>".create_intern_menu_link2("bugs.html", $l_all_versions)."</tr>
<tr><td>$l_guestbook</td><td class=\"versionlink\" colspan=\"2\"><a href=\"phorum5/read.php?10,26111\">$l_all_versions</a></td></tr>
<tr><td>$l_credits</td>".create_intern_menu_link("$lsv/authors.html", "$lsv.x").create_intern_menu_link("HEAD/authors.html", "HEAD")."</tr>
</tbody><tbody><tr><th colspan=\"3\"><h2>$l_misc</h2></th></tr>
<tr><td>$l_sitemap</td>".create_intern_menu_link2("sitemap.html", $l_all_versions)."</tr>
<tr><td>$l_license</td>".create_intern_menu_link2("license.html", $l_all_versions)."</tr></tbody>
</table>\n"));
//<tr><td>$l_donation</td>".create_intern_menu_link2("donate.html", $l_all_versions)."</tr>
fwrite($ftmp,'<?php module_tail(); ?>");
fclose($f); ?>');

fclose($ftmp);

// This is the second step because of the "title"
include("dynamic/menu$lang.tmp");

// Delete the no longer necessary intermidiate file
unlink("dynamic/menu$lang.tmp");

?>
