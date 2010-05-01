#!/bin/sh
#cd /home/smeier/
cd ~

cd public_html/www/bin/

# Automated MySQL and MRTG databases backups
./backup_mysql_db.php > ../admin/logs/backup_mysql_db.log

cd ..

# Allow users to upload images to the wiki
chmod 777 mediawiki/images

# Allow the phorum to work (needed for captchas and cache stuff)
chmod 777 phorum5/cache
chmod 777 phorum5/cache/captcha

# Make sure all the flags graphics are readable
chmod 644 graphics/flags/*.gif

# Allow the webserver to write in the news/ directory
mkdir -p news
chmod 777 news

# Allow the webserver to write in the dynamic/ directory
chmod 777 dynamic

# fix a timestamp
touch -m -t 200408161953 3.1/compiling.ihtml

# Add/update authors information to/in CVS using smeier account
# This should only be done on the www.kdevelop.org server, NOT on your local copy
if [ `whoami` = "smeier" ]; then
  cvs -q add */authors.ihtml
  cvs -q commit -m "Updated authors" */authors.ihtml
fi

# Update the local copy of the KDE developers accounts information
# This file is used for generating the SVN ChangeLog
cd admin/logs/
wget http://websvn.kde.org/*checkout*/trunk/kde-common/accounts &> wget_accounts.log
mv accounts.1 accounts
cd ../..

cd mediawiki/
/usr/bin/php maintenance/generateSitemap.php --server=http://www.kdevelop.org --compress=yes
mv sitemap-kdevelop_db-mw_-NS_0-0.xml.gz ../sitemap-mediawiki.xml.gz
rm -f sitemap-index-kdevelop_db-mw_.xml
rm -f *.xml.gz
cd -

# Delete backup files older than 5 months
DAYSOLD="150"
find admin/backups/*.* -maxdepth 1 -type f -mtime +$DAYSOLD -exec rm {} \;

# Delete the doxygen API (It will be regenerated in the next 15 minutes)
#rm -Rf HEAD/doc/api/html
#rm -Rf HEAD/doc/platform/html

# Delete these logs weekly to avoid them to grow to infinite :)
rm admin/logs/online.mrtg.log admin/logs/new_dl_per_hour.mrtg.log
