<?php
#
# SpamBot Trap
# Copyright Tom Chance, 2002
#
# SpamBot Trap tries to trap Internet robots looking
# for e-mail and snail-mail addresses in an infinite
# loop. If the mailing addresses start getting lots of
# spam, and this counts lots of hits from non-browser
# HTTP User-Agents, then we can conclude it worked :)
# We use a load of silly names to confirm the spambot.
#
# The script process involves:
# - Declaring some lists of words to be used in random
#   email addresses and links
# - Making the links and email addresses from these
# - Retrieving environment variables to check the nature of
#   the agent requesting the page, and update robot counts
#   and logs accordingly
# - Getting previous data and printing it out along with the
#   random email addresses, links and the postal address
#
#
######################################################################
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Library General Public License for more details.
#
# http://www.gnu.org/copyleft/gpl.html
######################################################################

// get the MySQL global login and password
include ("../../access.inc");

// Establish a connection with the MySQL server
$mysql_link = mysql_connect ("localhost",$k_login,$k_password);

mysql_select_db("kdevelop_db");

# Database tables (1=hits, 2=logs)
$dbtable1 = "spam_browsers";
$dbtable2 = "spam_robots";

# Generate lots of random e-mail addresses?
# (0=no, 1=yes)
$gen_emails = 1;

# The maximum size of the block of addresses
$block_size = "30";

# Protect legitimate web crawlers from page?
# (0=no, 1=yes)
$protect_crawlers = 1;

## END CONFIG SECTION ###
##########################

global $names;
global $firstnames;
global $lastnames;
global $domains;
global $topdomains;
global $countries;
global $range_names;
global $range_firstnames;
global $range_lastnames;
global $range_domains;
global $range_topdomains;
global $range_countries;

# Declare list of names for the snail-mail address
$names = array('Karl Marx', 'Peter Brough', 'Lao Tzu', 'Gengis Khan', 'Aldous Huxley', 'Steve Jobs',
'Evelyn Waugh', 'Joan of Arc', 'Julius Caeser', 'Michael Gorbachev', 'Tony Blair',
'Abraham Lincoln', 'Malcolm X', 'Winston Churchill', 'Jimmy Carter', 'Aristotle');

$firstnames = array('jim','dave','roger','andy','william','geoff','terry','darcy','sally','anna','susan','james','peter','craig','andrew','dennis');

$lastnames = array('smith','jones','brough','richards','white','woodworth','davies','ludwigs','brown','fowler','bell','ryan','parkbach',
'manning','eadie','waghorn','williams','beale','giles','fleming');

# Declare some lists of words to make other fun random e-mail addresses
# (I dunno, Bob wanted it in...)
$domains = array('physics','switch','change','online','wonder','random','bogus','domain','undo','cat','flake','apple','family',
'refreshing','straight','selected','plan','decant','slice','plant','forever','prisoner','camera','bulbous',
'grass','egypt','ethiopia','bulgaria','austria','india','japan','equador','fuji','phillipines','germany',
'mexico','brazil','cuba','zambia','mongolia','china','korea','israel','morocco','crop','technology','rinse',
'blythe','short','existential','employer','recommended','planning','ancient','optional','clap','days','raise','gospel','friend','lake');

$topdomains = array('com','edu','org','gov','de','fr','pt','tv','se','uk','es');

$countries = array('egypt','ethiopia','bulgaria','austria','india','japan','equador','fuji','phillipines','germany',
'mexico','brazil','cuba','zambia','mongolia','china','korea','israel','morocco');


# calculate the range for the rand() calls
$range_names = count($names) -1;
$range_firstnames = count($firstnames) -1;
$range_lastnames = count($lastnames) -1;
$range_domains = count($domains) -1;
$range_topdomains = count($topdomains) -1;
$range_countries = count($countries) -1;


/**
 *  Generate a random e-mail
 */
function randomemail(){
  global $names;
  global $firstnames;
  global $lastnames;
  global $domains;
  global $topdomains;
  global $countries;
  global $range_names;
  global $range_firstnames;
  global $range_lastnames;
  global $range_domains;
  global $range_topdomains;
  global $range_countries;

  $rand1 = (integer)rand(0,$range_domains);
  $rand2 = (integer)rand(0,$range_firstnames);
  $rand3 = (integer)rand(0,$range_lastnames);
  $rand4 = (integer)rand(0,$range_domains);
  $rand5 = (integer)rand(0,$range_topdomains);
  return $firstnames[$rand2] . $lastnames[$rand3] . '@' . $domains[$rand1] . $domains[$rand4] . '.' . $topdomains[$rand5];
}


/**
 *  Generate a random e-mail in the <A HREF="mailto:$email">$email</A> format
 */
function randomf1email(){
  $email = randomemail();
  return "<A HREF=\"mailto:$email\">$email</A>";
}


/**
 *  Generate a random e-mail in the <A HREF="mailto:$email">firstname lastname</A> format
 */
function randomf2email(){
  global $names;
  global $firstnames;
  global $lastnames;
  global $domains;
  global $topdomains;
  global $countries;
  global $range_names;
  global $range_firstnames;
  global $range_lastnames;
  global $range_domains;
  global $range_topdomains;
  global $range_countries;

  $rand1 = (integer)rand(0,$range_domains);
  $rand2 = (integer)rand(0,$range_firstnames);
  $rand3 = (integer)rand(0,$range_lastnames);
  $rand4 = (integer)rand(0,$range_domains);
  $rand5 = (integer)rand(0,$range_topdomains);
  return "<A HREF=\"mailto:$firstnames[$rand2]$lastnames[$rand3]@$domains[$rand1]$domains[$rand4].$topdomains[$rand5]\">$firstnames[$rand2] $lastnames[$rand3]</A>";
}


/**
 *  Generate a random snail-mail
 */
function randomsmail(){
  global $names;
  global $domains;
  global $countries;
  global $range_names;
  global $range_domains;
  global $range_countries;

  $rand1 = (integer)rand(0,$range_names);
  $rand2 = (integer)rand(0,$range_domains);
  $rand3 = (integer)rand(0,$range_countries);
  return $names[$rand1].'<BR>'. $rand2.' '.ucfirst($domains[$rand2]).' Avenue<BR>Chicago<BR>'.ucfirst($countries[$rand3]).'<BR>';
}


if ($gen_emails)
	{
	# Put some emails @localhost to annoy the spammers themselves
	$random_data = "<A HREF=\"mailto:admin@localhost\">admin@localhost.com</A> \n";
	$random_data .= "<A HREF=\"mailto:postmaster@localhost\">postmaster@localhost.com</A> \n";
	$random_data .= "<A HREF=\"mailto:abuse@localhost\">abuse@localhost.com</A> \n";
	$random_data .= "<A HREF=\"mailto:webmaster@localhost\">webmaster@localhost.com</A> \n";

	# Generate a block of random data and e-mail addresses
        $length = (integer)rand(0,$block_size);
	for ($i=0;$i < $length;$i++)
		{
		$random_data .= ' ' . randomf1email();
		}
	}


# Retrieve all the necessary HTTP HEADER information
global $HTTP_SERVER_VARS;
$HTTP_USER_AGENT  = isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])?$HTTP_SERVER_VARS['HTTP_USER_AGENT']:'';
$HTTP_REQUEST_URI = isset($HTTP_SERVER_VARS['REQUEST_URI'])?$HTTP_SERVER_VARS['REQUEST_URI']:'';
$HTTP_REMOTE_ADDR = isset($HTTP_SERVER_VARS['REMOTE_ADDR'])?$HTTP_SERVER_VARS['REMOTE_ADDR']:'';
$HTTP_REMOTE_HOST = isset($HTTP_SERVER_VARS['REMOTE_HOST'])?$HTTP_SERVER_VARS['REMOTE_HOST']:'';
$HTTP_REMOTE_IDENT= isset($HTTP_SERVER_VARS['REMOTE_IDENT'])?$HTTP_SERVER_VARS['REMOTE_IDENT']:'';
$HTTP_REFERER     = isset($HTTP_SERVER_VARS['HTTP_REFERER'])?$HTTP_SERVER_VARS['HTTP_REFERER']:'';
$HTTP_QUERY_STRING= isset($HTTP_SERVER_VARS['QUERY_STRING'])?$HTTP_SERVER_VARS['QUERY_STRING']:'';
$HTTP_PATH_INFO   = isset($HTTP_SERVER_VARS['PATH_INFO'])?$HTTP_SERVER_VARS['PATH_INFO']:'';
$HTTP_QUERY_PATH  = empty($HTTP_QUERY_STRING)?$HTTP_PATH_INFO:$HTTP_QUERY_STRING;

# Generate some random speil to tag onto the query string
# so the URL is not the same, just in case the spambot wont
# follow self-referrers like that
$chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
               'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$pos = strpos ($HTTP_REQUEST_URI, ".php");
if ($pos > 0) {
  $HTTP_REQUEST_URI = substr($HTTP_REQUEST_URI, 0, $pos+4);
}
$pos = strpos ($HTTP_REQUEST_URI, ".html");
if ($pos > 0) {
  $HTTP_REQUEST_URI = substr($HTTP_REQUEST_URI, 0, $pos+5);
}
$query_string = $HTTP_REQUEST_URI . '?op=' . (integer)rand(0,300);// . '&id=' . join('',$chars[map{ rand $chars} (1..8)]);
$query_string1 = $HTTP_REQUEST_URI . '/new/' . $domains[(integer)rand(0,$range_domains)] . $domains[(integer)rand(0,$range_domains)] . $domains[(integer)rand(0,$range_domains)] . '.html';
$query_string2 = '/new/' . $domains[(integer)rand(0,$range_domains)] . $domains[(integer)rand(0,$range_domains)] . $domains[(integer)rand(0,$range_domains)] . '.html';

/*
# Get hostname the hard way if needs be
if($host != '') { ## Okay, we'll do it the hard way!
  $all = `/usr/bin/nslookup -sil $addr`;
//  for $x (@all) {
//    if ($x =~ /^Name: (.*)/) {
//      $host =$1;
//    }
//  }
}
*/

// determine if the page already exists in the table
$result = mysql_query("SELECT ip FROM stats_bad_robots WHERE ip = '$HTTP_REMOTE_ADDR'");
$row = mysql_fetch_object($result);

if ($row) {
  // Robot IP already exists in the table, update it
  $sql = "UPDATE stats_bad_robots SET trapped = (trapped + 1), last_useragent='$HTTP_USER_AGENT', last_visited=NOW('0000-00-00 00:00:00') WHERE ip='$HTTP_REMOTE_ADDR'";
} else {
  // Robot IP does not yet exist in the table, create it
  $sql = "INSERT INTO stats_bad_robots ( ip, trapped, last_useragent, first_visited, last_visited ) VALUES ( '$HTTP_REMOTE_ADDR', '1', '$HTTP_USER_AGENT', NOW('0000-00-00 00:00:00'), NOW('0000-00-00 00:00:00') )";
}
$result = mysql_query($sql);


# Print the page
print "<H1>Welcome :-)</H1>\n";
print "<p>You have been warned to behave properly, but you choose to ignore the warning. Now you are <b>b l o c k e d</b> from accessing this site.</p><p>This page is a <b>t r a p</b> for <b>s p a m - m a i l - r o b o t s</b>, so the addresses and data in the bottom section are random and <b>f a k e</b> :)</p>\n";
print "<p>Here you can find the <A href=\"http://www.tomchance.uklinux.net/spambot.txt\">source code</a>, for those interested, which sort of explains what this page does as well as show how.</p>\n";
print "<h2>Why are you a bad robot?</h2>
<p> This might have been caused by different things, such as any of the following:</p>
<ol>
<li> You tried to brute force attack KDevelop.org by inventing non existing or invalid URLs.</li>
<li> You did not follow the <CODE>robots.txt</CODE> rules.</li>
<li> You did not follow the <CODE>&#060;meta name=\"robots\" content=\"...\"&#062;</CODE> rules.</li>
<li> You did not identify yourself as being a robot. You impersonated Mozilla, IE or firefox although you are a robot.</li>
<li> Someone else impersonated you and did one of the things described above.</li>
</ol>\n";

sleep(2);

print "<p>In case you are a <b>legitimate human user interested in KDevelop</b> using a <b>normal browser</b> then we apologize for
this inconvenience, but like you, we enjoy our privacy, so we have a right to block automated e-mail harvesters, unpolite
mirroring tools and automated guestbook posters.
To be removed from the <em>bad robots blacklist</em> you can send an e-mail to this
<a href=\"http://spamassassin.apache.org/\">
spamassassin protected</a> e-mail address <em>webmaster&nbsp;&nbsp;kdevelop.org</em> with your IP address
(";
echo $HTTP_SERVER_VARS['REMOTE_ADDR'];
print ") explaining why you find KDevelop such a great tool ;) .</p>\n";

print "<P>By the way, you seem to be <em>$HTTP_REMOTE_HOST</em> ($HTTP_REMOTE_ADDR) ";
if ($HTTP_USER_AGENT)
  print "running on <em>$HTTP_USER_AGENT</em> ";
if ($HTTP_REMOTE_IDENT)
  print "with identity <em>$HTTP_REMOTE_IDENT</em> ";
if ($HTTP_REFERER)
  print "comming from <em>$HTTP_REFERER</em> ";
if ($HTTP_QUERY_PATH)
  print "by using the query <em>$HTTP_QUERY_PATH</em>\n";


print "<P>Some useful resources on <b>s p a m b o t s</b>: <A HREF=\"http://www.robotstxt.org/wc/active/html/index.html\">web robots db</A>, <A HREF=\"http://4webhelp.com/spiders/spidersa.shtml\">a db with lots more robots</A>, and google always tells you about the more odd bots\n<HR>\n";
/*
print "\n<h4>Several e-mail formats are supported:</h4>";
print '<p>E-Mail me at '. randomf1email() . ' or ' . randomf2email();

print "\n<h4>Snail mail is also supported:</h4>";
print '<p>Write to me at:<br> ' . randomsmail();

print "\n<h4>Self referring random links (for infinite looping) are also supported:</h4>";
*/
if (!isset($trapped_again))
print "<p>I have some more contact details <A HREF=\"$query_string\">over here</A> and also <A HREF=\"$query_string1\">here</A>";
else
print "<p>I have some more contact details <A HREF=\"/graphics/botjam.php$query_string\">over here</A> and also <A HREF=\"/graphics/botjam.php$query_string2\">here</A>";

print "\n<h4>Random data block (to further randomize size and content of the page, and to provide s p a m b o t s with more f a k e emails than they can handle... ):</h4>\n";
print "<p>$random_data\n\n";
?>