#!/bin/sh
###################################################################
#
# These are maintenance tasks that can be performed manually on the server.
# To do so uncomment one of the tasks, or add you own task to this file
#
# This file gets updated and executed every 10 minutes because it's
# called from the cron_10min.sh
# This needs to be in a separated file so that it gets updated from
# CVS and then executed. This fixes the problem with the tasks on the
# cron_10min.sh file only being executed 20 minutes after the file
# got changed.
#
# (c) by Amilcar Lucas, licensed under the GPL
#
###################################################################

# Let mediawiki configure itself
#chmod 777 mediawiki/config

# Install a new doxygen (do this once every two months)
#cd dynamic
#cp doxygen-1.5.1.bz2 doxygen.bz2
#bunzip2 doxygen.bz2
#mv doxygen ../../../bin/doxygen
#chmod 755 ../../../bin/doxygen
#cd ..

# Cleanup the local KDevelop source code working copy, and check out a new fresh copy 
#cd ~
#rm -Rf kdevelop
#svn co https://smeier@svn.kde.org/home/kde/trunk/KDE/kdevelop/ kdevelop > public_html/www/admin/logs/kdevelop_svn_up.log 2> public_html/www/admin/logs/kdevelop_svn_up.err.log

# Update the phpMyAdmin installation
###################################

# uncomment this line to automatically install a new phpMyAdmin version
#PHPMYADMIN=phpMyAdmin-2.11.9.1-english

# uncomment this line to backup the old phpMyAdmin installation and move the new into production
#PHPMYADMIN_BACKUP=phpMyAdmin-2.11.9-english

# Install a parallel version for testing purposes (if $PHPMYADMIN is defined)
if [ -n "$PHPMYADMIN" ]; then
  cd ..
  wget -q http://switch.dl.sourceforge.net/sourceforge/phpmyadmin/$PHPMYADMIN.tar.gz # download it
  if [ $? -ne 0 ]; then # try another mirror
    wget -q http://mesh.dl.sourceforge.net/sourceforge/phpmyadmin/$PHPMYADMIN.tar.gz # download it
  fi
  if [ $? -eq 0 ]; then
    tar -xzvf $PHPMYADMIN.tar.gz
    if [ $? -eq 0 ]; then                # if tar file extraction was successful
      echo "All $PHPMYADMIN files extracted successfully"
      rm -Rf $PHPMYADMIN.tar.gz
      cd $PHPMYADMIN
      cp ../phpMyAdmin/.htpasswd .       # the encrypted password
      if [ $? -eq 0 ]; then echo "File .htpasswd (the encrypted password) copied successfully"; fi
      cp ../phpMyAdmin/.htaccess .       # to restrict the access to this directory
      if [ $? -eq 0 ]; then echo "File .htaccess (to restrict the access to this directory) copied successfully"; fi
      cp ../phpMyAdmin/config.inc.php .  # to configure the phpMyAdmin installation
      if [ $? -eq 0 ]; then echo "File config.inc.php (phpMyAdmin configuration) copied successfully"; fi
      patch libraries/common.inc.php < ../www/bin/phpMyAdmin_urlstring.patch  # to avoid regressions in the administrative links of the website
      if [ $? -eq 0 ]; then echo "libraries/common.lib.php patched successfully"; fi
      cd ..
    fi
  else
    echo "The wget of the new phpMyAdmin version was not successfull. I will now not move the old (but still working) version"
    # the wget was not successfull, a new version could not be installed,
    # so do not move the current version out of production
    unset $PHPMYADMIN_BACKUP
  fi
  cd www
fi

# Backup the old and move the new version into "production" (if $PHPMYADMIN_BACKUP is defined)
if [ -n "$PHPMYADMIN_BACKUP" ]; then
  if [ -n "$PHPMYADMIN" ]; then
    cd ..
    mv phpMyAdmin $PHPMYADMIN_BACKUP
    if [ $? -eq 0 ]; then
      echo "Backed up old phpMyAdmin/ into $PHPMYADMIN_BACKUP/ directory. This is a backup only, you can not login into it because the .htaccess will not allow it"
    fi
    mv $PHPMYADMIN phpMyAdmin
    if [ $? -eq 0 ]; then
      echo "Moved new $PHPMYADMIN into phpMyAdmin/ directory"
    fi
    cd www
  fi
fi

# Remove very old backups
#rm -Rf ../phpMyAdmin-2.11.*


# Download a test version of MediaWiki
########################################

# uncomment this line to automatically install a test version of MediaWiki
#MEDIAWIKI=mediawiki-1.9.0

# Install a parallel version for testing purposes (if $MEDIAWIKI is defined)
if [ -n "$MEDIAWIKI" ]; then
  cd ..
  wget -q http://kent.dl.sourceforge.net/sourceforge/wikipedia/$MEDIAWIKI.tar.gz # download it
  if [ $? -ne 0 ]; then # try another mirror
    wget -q http://mesh.dl.sourceforge.net/sourceforge/wikipedia/$MEDIAWIKI.tar.gz # download it
  fi
  if [ $? -eq 0 ]; then
    tar -xzvf $MEDIAWIKI.tar.gz
    if [ $? -eq 0 ]; then                # if tar file extraction was successful
      echo "All $MEDIAWIKI files extracted successfully"
      rm -Rf $MEDIAWIKI.tar.gz
      chmod a+w $MEDIAWIKI/config        # Let mediawiki configure itself
    fi
  else
    echo "The wget of the new MediaWiki version was not successfull."
  fi
  cd www
fi

#rm -Rf news
#cd ..
#cvs -d :pserver:smeier@barney.cs.uni-potsdam.de:/home/cvs/kdevelop_www co www/news
#cd www

cd news
#touch -t 0701031200 2007-01-03.html
#touch -t 0701251200 2007-01-25.html
#touch -t 0703081200 2007-03-08.html
#touch -t 0703251200 2007-03-25.html
#touch -t 0703261200 2007-03-26.html
#touch -t 0704031200 2007-04-03.html
#touch -t 0704281200 2007-04-28.html
#touch -t 0705031200 2007-05-03.html
#touch -t 0705161200 2007-05-16.html
#touch -t 0705221200 2007-05-22.html
#touch -t 0706131200 2007-06-13.html
#touch -t 0706141200 2007-06-14.html
#touch -t 0709111200 2007-09-11.html
#touch -t 0709291200 2007-09-29.html
#touch -t 0710161200 2007-10-16.html
#touch -t 0710241200 2007-10-24.html
#touch -t 0711271200 2007-11-27.html
#touch -t 0712041200 2007-12-04.html
#touch -t 0801151200 2008-01-15.html
#touch -t 0801181200 2008-01-18.html
#touch -t 0801291200 2008-01-29.html
#touch -t 0802081200 2008-02-08.html
#touch -t 0802111200 2008-02-11.html
#touch -t 0802191200 2008-02-19.html
#touch -t 0802281200 2008-02-28.html
#touch -t 0803091200 2008-03-09.html
#touch -t 0803271200 2008-03-27.html
#touch -t 0803301200 2008-03-30.html
#chmod g+w 2008*
#chmod o+w *.ihtml
cd ..

# Remove the test installation version of mediawiki
#rm -Rf ../mediawiki-1.9.0

# Restore a backup copy of the on-line mrtg database
#cp -a admin/backups/online.log-20070302-2355 dynamic/online.old
#cp -a admin/backups/online.log-20070302-2355 dynamic/online.log
#touch --reference=dynamic/online.log bin/online.mrtg.ok

#cd bin
#./backup_mysql_db.php

#rm -f news/2007-03-26.html
#touch ../google2eebbb98630b941d.html

# Create a static version of the FAQ from the dynamic wiki FAQ page
#wget -q --output-document=wiki_faq.html --user-agent="Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.0 (like Gecko)" http://www.kdevelop.org/mediawiki/index.php?title=FAQ&printable=yes
#fg        # to make sure the transaction finishes before we start awk
#awk -f bin/wiki_to_staticFAQ.awk < wiki_faq.html > 3.5/faq.html
#rm wiki_faq.html

#Replace a string containing single quotes in multiple files
#sed -i "s/_replace(.*branch/tr(\$cvsbranch, \'\/.\', \'__\'/g" */*/3.3/kdevelop_po_status.html
#sed -r -i 's/announcements\/announce-(.{1,8})\.html/announcements\/announce-\1\.php/g' lang/*/*/*.html

#Replace a string containing double quotes in multiple files
#sed -i 's/_replace(.*branch/tr(\$cvsbranch, \"\/.\", \"__\"/g' */*/3.3/kdevelop_po_status.html

#rm -Rf dynamic/*cz*

#cd bin/
#./update_robots_sitemap.php
#cd ..

#mv admin/new.hist admin/webalizer_stats/webalizer.hist
#rm -Rf HEAD/doc/platform

#cd bin/
#./aggregate_rss_feeds.php html 5 > ../dynamic/blogs_rss_feeds.ihtml
#./aggregate_rss_feeds.php html 50 > ../dynamic/blogs_rss_feeds_long.ihtml
#./aggregate_rss_feeds.php rss 50 > ../dynamic/blogs_rss_feeds.rss
#./cron_weekly.sh
#./rundoxygen.sh
