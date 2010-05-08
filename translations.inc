<?php
// kate: encoding iso-8859-1
// Do not translate the comments.
// Translate only the strings.

// The prefered encoding is UTF8, but you can use other ones
$l_charset_encoding= 'iso-8859-1';

// The php locale, LanguageCode_CountryCode
$l_locale= 'en_US';

$l_graphic_mode = "Graphic Mode";
$l_text_mode = "Text Mode";

// Logo
$l_back_kdevelop_home="back to KDevelop home";

// Named languages associative array (for the flags, translated languages, etc)
global $languages;
$languages = array("en" => "English",
                   "ar" => "Arabic",
                   "cs" => "Czech",
                   "de" => "German",
                   "es" => "Spanish",
                   "fr" => "French",
                   "hu" => "Hungarian",
                   "id" => "Indonesian",
                   "it" => "Italian",
                   "nl" => "Dutch",
                   "pt" => "Portuguese",
                   "pt_BR" => "Brazilian Portuguese",
                   "pl" => "Polish",
                   "ro" => "Romanian",
                   "ru" => "Russian",
                   "tr" => "Turkish",
                   "uk" => "Ukrainian",
                   "zh_CN" => "Simplified Chinese");

$l_more_elipsis = 'more ...';

$l_what_is_KDevelop = 'What is KDevelop ?';
$l_def_KDevelop = 'is a free, open source IDE (Integrated Development Environment) for MS Windows, Mac OS X, Linux, Solaris and FreeBSD.
It is a feature-full, plugin extensible IDE for C/C++ and other programming languages.
It is based on KDevPlatform, and the KDE and Qt libraries and is under development since 1998.';
$l_def_KDevPlatform = 'is a free, open source set of libraries that can be used as a foundation for IDE-like programs.
It is programing-language independent, and is planned to be used by programs like: KDevelop, Quanta, Kile, KTechLab ... etc.';
$l_def_KDevelop4 = 'A major rewrite based on Qt4/KDE4 with a powerful language architecture. Under heavy development, recommended for C++ or PHP development with CMake or custom Makefiles as their build system. Recommended for Linux (<b>all other OS might work</b>, but are not tested yet).';
$l_def_KDevelop3 = 'Very stable and mature IDE based on Qt3/KDE3 with support for many programing languages and build systems. No longer under development.';

// cvsbranches.ihtml page
$l_unstable_development = 'unstable development for %s';
$l_stable_version = 'stable for %s';
$l_entirely_new_code = 'entirely new code base, rewritten from scratch';
$l_was_released_with = 'has been released with';
$l_bugfix_versions = 'versions';
$l_scheduled_for_versions = 'scheduled for version';
$l_scheduled_versions = '%s bugfix %s will be released together with %s';
$l_release_schedule = '%s release schedule';

// Google module
$l_search="Search";

// Quick download module
$l_quick_downloads = "Quick download";
$l_current_stable_version = "Current stable version";
$l_current_development_version = "Current development version";

// Menu module
$l_website_navigation="Website navigation";
$l_all_versions="all&nbsp;versions";
$l_general="General";
$l_home="Home";
$l_versions="Versions";
$l_features="Features";
$l_requirements="Requirements";
$l_download="Download";
$l_cvs_branches_compiling="SVN Branches &amp; Compiling";
$l_changes="Changes";
$l_bugs_wishes="Bugs &amp; wishes";

$l_documentation="Documentation";
$l_user_manual="User Manual";
$l_api_documentation="API documentation";
$l_platform_api_documentation="Platform API";
$l_tutorials="Tutorials";
$l_faq="F.A.Q.";
$l_technotes="TechNotes";
$l_mediawiki="Wiki";

$l_presentations_graphics="Presentations and Graphics";
$l_screenshots="Screenshots";
$l_in_the_press="In the Press";
$l_awards="Awards";
$l_logos_banners="Logos &amp; Banners";
$l_splashscreens="Splashscreens";
$l_events="Events";

$l_development="Development";
$l_join_us="Join us!";
$l_current_work_todo_list="Current Work, TODO list";
$l_latest_cvs_commits="Latest SVN commits";
$l_browse_code="Browse the code";
$l_web_cvs="Web SVN";
$l_gui_translation="GUI translation";
$l_user_manual_translation="User Manual translation";
$l_website_translation="Website translation";
$l_upload="Upload";

$l_links="Links";
$l_user_progs="User Programs";
$l_sponsors="Sponsors";
$l_donation="Donation";
$l_development_tools="Dev. Tools";

$l_contacts="Contacts";
$l_on_line_chat="On-line chat";
$l_forum="Forum";
$l_mailinglist="Mailinglist";
$l_guestbook="Guestbook";
$l_credits="Credits";

$l_misc="Misc";
$l_license="License";
$l_sitemap="Site map";

// Stats module
$l_user_online = "User online";
$l_visitors_since = "Visitors since 09/22/98";

// Forum module
$l_forum_description="Discussion <a href=\"phorum5/\">board</a> for KDevelop, <a href=\"http://www.kde.org/\">KDE</a> and <a href=\"http://www.trolltech.com\">Qt</a> related stuff.";

// IRC module
$l_irc_description="You can meet other users and developers on the <a href=\"chat/\"><em>#kdevelop</em> channel at <em>irc.kde.org</em></a>.";

// Picture Corner module
$l_picture_corner="Picture Corner";
$l_picture_corner_description="KDevelop 4.0 screenshot";

// User Programs module
$l_total = "total:";
$l_newest_entries = "newest <b>%s</b> entries";
$l_by = "by";

// Downloads Per Hour module
$l_downloads_per_hour = "Downloads per hour";

// ChangeLog module
$l_change_log = "ChangeLog";

// Content module
$l_page_is_outdated = "<b>This page is outdated</b>, a newer <a href=\"index.html?filename=$filename&amp;set_lang=en\" hreflang=\"en\">english version</a> exists";
$l_translation_by = "English translation maintained by ";
$l_last_updated = "This page was last updated on: ";
$l_website_bugs = "If there are any problems with the website, please contact the ";
$l_latest_blog_posts = "Latest blog posts from KDevelop developers";

// "Other versions" links
$l_other_versions="Other versions";
$l_other_news="Other news";
$l_branches_compiling_section_at="Compiling section at";
$l_bugs_section_at="Bugs &amp; wishes section at";
$l_download_section_at="Download section at";
$l_requirements_section_at="Requirements section at";
$l_tutorials_section_at="Tutorials section at";

// Page titles
$l_pt_main="Integrated Development Environment - Homepage";
$l_pt_main_year="News of %d";
$l_pt_versions="Released Versions";
$l_pt_in_the_press="In the Press";
$l_pt_license="License";
$l_pt_donate="Donation and Sponsoring";
$l_pt_website_translation_status="Website translation status";
$l_pt_optional_files_translation_status="Website translation status (optional files)";
$l_pt_sitemap="Website map (sitemap)";
$l_pt_upload="Upload";
$l_pt_current_work="Current Work";
$l_pt_join_the_team="Join Us!";
$l_pt_awards="Awards";
$l_pt_logos_banners="Logos &amp; Banners";
$l_pt_pic_corner="Picture Corner Archive";
$l_pt_splashscreens="Splashscreens";
$l_pt_users="User Programs";
$l_pt_sponsors="Sponsors";
$l_pt_links_development="Development Links";
$l_pt_links_tools="Development Tools";
$l_pt_mailinglist="Mailinglist";
$l_pt_bugs="Bugs &amp; Wishes";
$l_pt_stats="Website statistics";
$l_pt_doc_technotes_index="TechNotes Index";
$l_pt_mediawiki_=$l_mediawiki;
$l_pt_chat_=$l_on_line_chat;
$l_pt_phorum5_=$l_forum;
$l_pt_guestbook_guestbook="Guestbook Archives";
$l_pt_guestbook_guestbook_010203_1999="Guestbook Jan/Feb/Mar 1999";
$l_pt_guestbook_guestbook_040506_1999="Guestbook Apr/May/Jun 1999";
$l_pt_guestbook_guestbook_0708_1999="Guestbook Jul/Aug 1999";
$l_pt_guestbook_guestbook_091011_1999="Guestbook Sep/Oct/Nov 1999";
$l_pt_guestbook_guestbook_12_03_1999_2000="Guestbook Dec/Jan/Feb/Mar 1999/2000";
$l_pt_guestbook_guestbook_2000_2004="Guestbook Apr 2000 / Jul 2004";
$l_pt_events="events";
$l_pt_events_linuxtag2000="Linuxtag 2000";
$l_pt_events_linuxtag2001="Linuxtag 2001";
$l_pt_events_kastle2003="Kastle 2003";
$l_pt_events_akademy2004="Akademy 2004";
$l_pt_events_akademy2006="Akademy 2006";
$l_pt_1_3_kdevelop="1.x main info";
$l_pt_1_3_announce_kdevelop_1_3="1.3 release announcement";
$l_pt_1_3_authors="1.x authors";
$l_pt_1_3_changes="1.x changes";
$l_pt_1_3_features="1.x features";
$l_pt_1_3_pressrelease1_0="1.0 press release";
$l_pt_1_3_pressrelease1_1="1.1 press release";
$l_pt_1_3_screenshots1="1.x screenshots section 1";
$l_pt_1_3_screenshots2="1.x screenshots section 2";
$l_pt_1_3_screenshots3="1.x screenshots section 3";
$l_pt_2_1_kdevelop="2.x main info";
$l_pt_2_1_announce_kdevelop_2_0="2.0 release announcement";
$l_pt_2_1_authors="2.x authors";
$l_pt_2_1_changes="2.x changes";
$l_pt_2_1_faq="2.x FAQ";
$l_pt_2_1_features="2.x features";
$l_pt_2_1_screenshots1="2.x screenshots section 1";
$l_pt_2_1_screenshots2="2.x screenshots section 2";
$l_pt_2_1_screenshots3="2.x screenshots section 3";
$l_pt_x_x_kdevelop="%s.x main info";
$l_pt_x_x_announce_kdevelop="%s release announcement";
$l_pt_x_x_authors="%s.x authors";
$l_pt_x_x_branches_compiling="%s.x SVN Branches &amp; Compiling";
$l_pt_x_x_changes="%s.x changes";
$l_pt_x_x_faq="%s.x FAQ";
$l_pt_x_x_download="%s.x download";
$l_pt_x_x_features="%s.x features";
$l_pt_x_x_kdevelop_po_status="%s.x GUI translation status";
$l_pt_x_x_requirements="%s.x requirements";
$l_pt_x_x_screenshots="%s.x screenshots";
$l_pt_x_x_screenshots1="%s.x screenshots section 1";
$l_pt_x_x_screenshots2="%s.x screenshots section 2";
$l_pt_x_x_screenshots3="%s.x screenshots section 3";
$l_pt_x_x_tutorials="%s.x tutorials";
$l_pt_x_x_doc_tutorial_autoconf="%s.x Tutorial for Autoconf/Automake"; 
$l_pt_x_x_doc_kmditutorial_index="%s.x KMDI tutorial";
$l_pt_x_x_doc_tutorial_qtopia_index="%s.x Qtopia tutorial";
$l_pt_x_x_doc_tutorial_settings_index="%s.x \"User defined settings\" Tutorial";
$l_pt_x_x_ChangeLog="%s.x individual SVN commit logs";
$l_pt_HEAD_kdevelop="HEAD main info";
$l_pt_HEAD_authors="HEAD authors";
$l_pt_HEAD_branches_compiling="HEAD SVN Branches &amp; Compiling";
$l_pt_HEAD_changes="HEAD changes";
$l_pt_HEAD_ChangeLog="HEAD individual SVN commit logs";
$l_pt_HEAD_faq="HEAD FAQ";
$l_pt_HEAD_download="HEAD download";
$l_pt_HEAD_features="HEAD features";
$l_pt_HEAD_kdevelop_po_status="HEAD GUI translation status";
$l_pt_HEAD_requirements="HEAD requirements";
$l_pt_HEAD_screenshots="HEAD screenshots";
$l_pt_HEAD_screenshots1="HEAD screenshots section 1";
$l_pt_HEAD_screenshots2="HEAD screenshots section 2";
$l_pt_HEAD_screenshots3="HEAD screenshots section 3";
$l_pt_tutorials="tutorials";
$l_pt_HEAD_doc_api_=$l_api_documentation;
$l_pt_HEAD_doc_platform_=$l_platform_api_documentation;
$HEAD_user_manual_url='http://docs.kde.org/development/en/kdevelop/kdevelop/';
