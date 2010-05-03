#!/bin/sh
#cd /home/smeier/
cd ~

cd public_html/www/

year=`date +%Y`
month=`date +%-m`
day=`date +%d`
: $(cal $month $year); last_day_of_the_month=$_

# Create Webalizer http statistics for www.kdevelop.org
if [ "$day" -eq "$last_day_of_the_month" ]; then
mkdir -p admin/webalizer_stats
# -K 120 Specify how many months should be displayed in the main index (yearly summary) table
# -k 18  Specify how many months should be displayed in the main index (yearly summary) graph
nice webalizer -A 100 -M 0 -C 100 -o admin/webalizer_stats/ -n www.kdevelop.org -r kdevelop.org /var/log/httpd/kdevelop.httpd.access_log > admin/logs/webalizer.log 2> admin/logs/webalizer.err.log
fi

# if for example main2010.html file does not exist, create it with correct permissions
if [ ! -f main$year.html ]; then
   touch main$year.html
   chmod 666 main$year.html
   # This should only be done on the www.kdevelop.org server, NOT on your local copy
   if [ `whoami` = "smeier" ]; then
      cvs -q add -m "Includes news from year $year" main$year.html
      cvs -q commit -m "Another year has passed" main$year.html
   fi
fi

# Add news items to CVS using smeier account, only new items get added
# This should only be done on the www.kdevelop.org server, NOT on your local copy
if [ `whoami` = "smeier" ]; then
  cd news
  cvs -q add *.html
  cvs -q add *.ihtml
  cvs -q commit -m "Added news item"
  cd ..
  cvs -q commit -m "Added news item" main*.html
fi

cd bin
./inform_translators.php
./remove_outdated_stats_rows.php
chmod 755 update_robots_sitemap.php
./update_robots_sitemap.php
./aggregate_rss_feeds.php html 5 > ../dynamic/blogs_rss_feeds.ihtml
./aggregate_rss_feeds.php html 50 > ../dynamic/blogs_rss_feeds_long.ihtml
./aggregate_rss_feeds.php rss 50 > ../dynamic/blogs_rss_feeds.rss
./rundoxygen.sh
cd ..

# Make the bugs.kde.org server cache the graphs for kdevplatform
wget -q "http://bugs.kde.org/reports.cgi?product=kdevplatform&output=show_chart&datasets=FIXED&banner=1" -O /dev/null
wget -q http://bugs.kde.org/graphs/kdevplatform_FIXED.png -O dynamic/kdevplatform_FIXED.png
wget -q "http://bugs.kde.org/reports.cgi?product=kdevplatform&output=show_chart&datasets=NEW%3A&datasets=REOPENED%3A&datasets=UNCONFIRMED%3A&banner=1" -O /dev/null
wget -q http://bugs.kde.org/graphs/kdevplatform_NEW_REOPENED_UNCONFIRMED.png -O dynamic/kdevplatform_NEW_REOPENED_UNCONFIRMED.png

# Make the bugs.kde.org server cache the graphs for kdevelop
wget -q "http://bugs.kde.org/reports.cgi?product=kdevelop&output=show_chart&datasets=FIXED&banner=1" -O /dev/null
wget -q http://bugs.kde.org/graphs/kdevelop_FIXED.png -O dynamic/kdevelop_FIXED.png
wget -q "http://bugs.kde.org/reports.cgi?product=kdevelop&output=show_chart&datasets=NEW%3A&datasets=REOPENED%3A&datasets=UNCONFIRMED%3A&banner=1" -O /dev/null
wget -q http://bugs.kde.org/graphs/kdevelop_NEW_REOPENED_UNCONFIRMED.png -O dynamic/kdevelop_NEW_REOPENED_UNCONFIRMED.png
wget -q "http://bugs.kde.org/weekly-bug-summary.cgi?tops=100&days=7" -O dynamic/weekly-bug-summary.htm

# can only be called after the previous wget
cd bin
chmod 755 update_bug_graphs.php
./update_bug_graphs.php
cd ..

# Get the latest "Spam Blacklists" from mediawiki.org and chongqed.org
./mediawiki/extensions/SpamBlacklist/update_blacklists.sh

# Delete phorum captcha files older than 2 days
#DAYSOLD="2"
#find phorum5/cache/captcha/captcha* -maxdepth 1 -type f -mtime +$DAYSOLD -exec rm {} \;

# Update the CVS commits statistics of the website files
mkdir -p admin/www_changelog
nice ./bin/cvschangelogbuilder.pl -output=buildhtmlreport -dir=admin/www_changelog -debug=1 -ignore=mediawiki/,phorum5/,chat/ > admin/logs/cvschangelogbuilder.log 2> admin/logs/cvschangelogbuilder.err.log