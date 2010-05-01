<div id="latestchangelogs">
<?php //if ($navmenu=='developer'){
global $l_more_elipsis;
module_head("HEAD $l_change_log".' <a href="HEAD/ChangeLog.rss"><img src="graphics/rss.gif" width="16" height="16" alt="RSS feed"></a>');
if ($lang != "en") echo "    <span lang=\"en\" dir=\"ltr\">\n";
include("dynamic/changelog_3_2.html");
if ($lang != "en") echo "    </span>\n";
echo '<p class="detailedchangelog"><a href="index.html?filename=HEAD/ChangeLog.html">'.$l_more_elipsis.'</a></p>
';
module_tail();

module_head("KDevPlatform $l_change_log".' <a href="HEAD/ChangeLog_kdevplatform.rss"><img src="graphics/rss.gif" width="16" height="16" alt="RSS feed"></a>');
if ($lang != "en") echo "    <span lang=\"en\" dir=\"ltr\">\n";
include("dynamic/changelog_3_2_kdevplatform.html");
if ($lang != "en") echo "    </span>\n";
echo '<p class="detailedchangelog"><a href="index.html?filename=HEAD/ChangeLog_kdevplatform.html">'.$l_more_elipsis.'</a></p>
';
module_tail();
/*}
module_head("3.5 $l_change_log".' <a href="3.5/ChangeLog.rss"><img src="graphics/rss.gif" width="16" height="16" alt="RSS feed"></a>');
if ($lang != "en") echo "    <span lang=\"en\" dir=\"ltr\">\n";
include("dynamic/changelog_3_5.html");
if ($lang != "en") echo "    </span>\n";
echo '<p class="detailedchangelog"><a href="index.html?filename=3.5/ChangeLog.html">'.$l_more_elipsis.'</a></p>
';
module_tail();*/ ?>
</div>