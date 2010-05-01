#!/usr/bin/php
<?php
#
# This script should be invoked by a cron job of an user with write permissions on the
# dynamic/ directory on the website
#
# The purpose of this script is to create a HTML or RSS version of the latest blogs about KDevelop
#
# Usage:
#    ./aggregate_rss_feeds.php [type] [items] > outputfile
#
#        type  - can be <html> or <rss> defaults to html
#        items - is a number and defaults to 5
#
# begin March 2009
# (c) 2009 Amilcar Lucas <amilcar@kdevelop.org>
# published under the GNU GPL

// load SimplePie Class
require_once '../inc/simplepie/simplepie.inc';

// Include a class to handle internationalized domain names.
require_once('../inc/simplepie/idn/idna_convert.class.php');

// extend SimplePie class
require_once '../inc/simplepie/filter/simplepie_filter.php';


// Initialize some feeds for use without filtering
//$feed = new SimplePie();

// Initialize some feeds for use with filtering
$feed = new SimplePie_Filter();
$feed->set_feed_url(array(
	'http://zwabel.wordpress.com/category/kde/kdevelop/',
	'http://vladimir_prus.blogspot.com/',
	'http://apaku.wordpress.com/category/kde/kdevelop/',
	'http://milianw.de/tag/kdevelop/feed',
	'http://www.proli.net/category/kdevelop/',
	'http://www.kdenews.org/rss.xml',
	'http://www.kdedevelopers.org/',
	'http://www.planetkde.org/rss20.xml',
	'http://liveblue.wordpress.com/feed/',
	'http://labs.trolltech.com/blogs/'
));

function shorten($string, $length)
{
    // By default, an ellipsis will be appended to the end of the text.
    $suffix = '...';
 
    // Convert 'smart' punctuation to 'dumb' punctuation, strip the HTML tags,
    // and convert all tabs and line-break characters to single spaces.
    $short_desc = trim(str_replace(array("\r","\n", "\t"), ' ', strip_tags($string)));
 
    // Cut the string to the requested length, and strip any extraneous spaces 
    // from the beginning and end.
    $desc = trim(substr($short_desc, 0, $length));
 
    // Find out what the last displayed character is in the shortened string
    $lastchar = substr($desc, -1, 1);
 
    // If the last character is a period, an exclamation point, or a question 
    // mark, clear out the appended text.
    if ($lastchar == '.' || $lastchar == '!' || $lastchar == '?') $suffix='';
 
    // Append the text.
    $desc .= $suffix;
 
    // Send the new description back to the page.
    return $desc;
}

if ($argc < 2){
  // default to html output
  $type = 'html';
} else {
  $type = $argv[1];
}
if ($type != 'html' && $type != 'rss'){
  echo '<p>ERROR: first argument must be html or rss</p>';
  die;
}

if ($argc < 3){
  // default to 5 items
  $total_items = 5;
} else {
  $total_items = $argv[2];
}
if (!is_numeric($total_items) || $total_items < 1){
  echo '<p>ERROR: second argument must be a positive number</p>';
  die;
}


// When we set these, we need to make sure that the handler_image.php file is also trying to read from the same cache directory that we are.
if ($type == 'html'){
  $feed->set_favicon_handler('../inc/simplepie/handler_image.php');
  $feed->set_image_handler('../inc/simplepie/handler_image.php');
}
// Make sure the page is being served with the UTF-8 headers.
$feed->handle_content_type();

// Use the phorum5 cache directory
$feed->set_cache_location('../phorum5/cache');

// set words to filter by
$words = 'kdevelop KDevelop';

// set filter mode (and/or)
$mode = 'or';

// set the filter words and mode
$feed->set_filter($words,$mode);

if ($type == 'html')
  $feed->set_output_encoding('iso-8859-1');

// Initialize the feed
$feed->init();

// Exit on error
if($feed->error())
{
	echo '<p>',$feed->error(),'</p>';
	die;
}

if ($type == 'rss')
  echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>Aggregated Feed of KDevelop Developer\'s blog posts</title>
<link>http://www.kdevelop.org/</link>
<atom:link href="http://www.kdevelop.org/dynamic/blogs_rss_feeds.rss" rel="self" type="application/rss+xml" />
<description>
Aggregated Feed of KDevelop Developer\'s blog posts.
</description>
<language>en-us</language>
';
else
  echo '<?php module_head("<a name=\"Latest_blog_posts\">$l_latest_blog_posts</a> <a href=\"dynamic/blogs_rss_feeds.rss\"><img src=\"graphics/rss.gif\" width=\"16\" height=\"16\" alt=\"RSS feed\"></a>"); ?>
';

// Filter them
$itemsFiltered = $feed->filter($feed->get_items());

// Let's loop through each of the first $total_items items in the feed
foreach(array_slice($itemsFiltered, 0, $total_items) as $item)
{
	// Let's give ourselves a reference to the parent $feed object for this particular item.
	$pfeed = $item->get_feed();
if ($type == 'rss'){
?>
<item>
  <title><?php echo $item->get_title(); ?></title>
  <link><?php echo $item->get_permalink(); ?></link>
  <guid<?php if (strstr ($item->get_id(), ' ') || substr ($item->get_id(), 0, 7) != 'http://') echo ' isPermaLink="false"'; ?>><?php echo $item->get_id(); ?></guid>
  <?php if ($author = $item->get_author())
    echo '<author>'. $author->get_name() ."</author>\n"; ?>
  <pubDate><?php echo $item->get_date('D, d M Y H:i:s O'); ?></pubDate>
  <description>
<?php       // make sure there are no &amp; otherwise they get converted into &amp;amp;
      $description = str_replace('&amp;', '&', $item->get_description());
      $description = str_replace('&', '&amp;', $description);
      $description = str_replace('<', '&lt;', $description);
      $description = str_replace('>', '&gt;', $description);
echo $description; ?>
  </description>
</item>
<?php } else {
?>
<div>
	<h4><a href="<?php echo $item->get_permalink(); ?>"><?php echo html_entity_decode($item->get_title(), ENT_QUOTES, 'UTF-8'); ?></a></h4>
	<?php echo shorten($item->get_description(), 200); ?>

	<p class="footnote">Source: <a href="<?php echo $pfeed->get_permalink(); ?>"><?php echo $pfeed->get_title(); ?></a> | <?php echo $item->get_date('j M Y | g:i a'); ?></p>
</div>
<?php
}
}
if ($type == 'rss'){
  echo '</channel>
</rss>
';
} else {
  echo '<?php ';
  if ($total_items < 10)
    echo 'echo "<p class=\"detailedchangelog\"><a href=\"index.html?filename=in_the_press.html#Latest_blog_posts\">$l_more_elipsis</a></p>"; ';
  echo 'module_tail(); ?>';
}
?>
