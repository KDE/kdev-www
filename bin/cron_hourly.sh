#!/bin/sh
#cd /home/smeier/
cd ~

cd public_html/www/bin

# Run MRTG (needs to be done at the beginning to minimize "jitter")
env LANG=C nice mrtg new_dl_per_hour.mrtg.cfg --logging=../admin/logs/new_dl_per_hour.mrtg.log

case $USER in
  amilcar)  # this is for the local webserver at amilcar's computer
    KDE_SVN_BASE_URL=svn+ssh://aclu@svn.kde.org/home/kde
    ;;
   *)       # this is for the kdevelop.org webserver
     KDE_SVN_BASE_URL=https://smeier@svn.kde.org/home/kde
esac
LAST_RELEASED_REV=1061557

./update_rss_news.php

# Get an XML change log file directly from the log messages of the individual
# SVN commits between the stable branchpoint and HEAD
SVN_BRANCH_PATH=trunk/extragear/sdk/kdevelop/
svn log $KDE_SVN_BASE_URL/$SVN_BRANCH_PATH --revision HEAD:$LAST_RELEASED_REV -v --xml > ../HEAD/ChangeLog.xml

# Test if the file was successfully created. This is necessary because sometimes the SVN command
# quits with a timeout leaving us with an empty HEAD/ChangeLog.xml or incomplete file
if [ $? -eq 0 ]; then
  # Create the HEAD/ChangeLog.html and HEAD/ChangeLog.rss files (using python)
  ./svn2log.py -o ../HEAD/ChangeLog.ihtml -s ../HEAD/ChangeLog2.rss -p 'branches/[^/]+|trunk/extragear/sdk/kdevelop/' -u ../admin/logs/accounts -w http://websvn.kde.org/$SVN_BRANCH_PATH ../HEAD/ChangeLog.xml &> ../admin/logs/HEAD_SVN_ChangeLog.log
  # Create the HEAD/ChangeLog.html file (using XSLT)
  #xsltproc --stringparam strip-prefix $SVN_BRANCH_PATH --stringparam ignore-filelist false --stringparam add-br true --stringparam websvn-url http://websvn.kde.org/$SVN_BRANCH_PATH svn2cl.xsl ../HEAD/ChangeLog.xml > ../HEAD/ChangeLog.html

  # Create the HEAD/ChangeLog.rss file (using XSLT)
  xsltproc --stringparam k_branch_path $SVN_BRANCH_PATH --stringparam k_base_version HEAD svnlog.xslt ../HEAD/ChangeLog.xml > ../HEAD/ChangeLog.rss

  # put the most recent 5 entries in the frontpage
  xsltproc --stringparam strip-prefix $SVN_BRANCH_PATH --stringparam ignore-filelist true --stringparam add-br true  --stringparam limit-logentries 5 svn2cl.xsl ../HEAD/ChangeLog.xml > ../dynamic/changelog_3_2.html

  # create and/or update a list of authors
  ./svn2log.py -o ../HEAD/authors.new.ihtml -a -u ../admin/logs/accounts -w http://websvn.kde.org/$SVN_BRANCH_PATH ../HEAD/ChangeLog.xml
  # create the list (only needed the first time a branch is created)
  #sort -u ../HEAD/authors.new.ihtml > ../HEAD/authors.ihtml
  # sort them, merge them with previous runs, and remove duplicates
  sort -u ../HEAD/authors.new.ihtml ../HEAD/authors.ihtml > ../HEAD/authors.ihtml.tmp
  mv ../HEAD/authors.ihtml.tmp ../HEAD/authors.ihtml
fi



# Get an XML change log file directly from the log messages of the individual
# SVN commits between the stable branchpoint and HEAD
svn log $KDE_SVN_BASE_URL/trunk/extragear/sdk/kdevplatform/ --revision HEAD:$LAST_RELEASED_REV -v --xml > ../HEAD/ChangeLog_kdevplatform.xml

# Test if the file was successfully created. This is necessary because sometimes the SVN command
# quits with a timeout leaving us with an empty HEAD/ChangeLog_kdevplatform.xml or incomplete file
if [ $? -eq 0 ]; then
  # Create the HEAD/ChangeLog_kdevplatform.html and HEAD/ChangeLog2_kdevplatform.rss files (using python)
  ./svn2log.py -o ../HEAD/ChangeLog_kdevplatform.ihtml -s ../HEAD/ChangeLog2_kdevplatform.rss -p '/trunk/extragear/sdk/kdevplatform/' -u ../admin/logs/accounts -w http://websvn.kde.org/trunk/extragear/sdk/kdevplatform/ ../HEAD/ChangeLog_kdevplatform.xml &> ../admin/logs/HEAD_SVN_ChangeLog_kdevplatform.log
  # Create the HEAD/ChangeLog_kdevplatform.html file (using XSLT)
  #xsltproc --stringparam strip-prefix trunk/extragear/sdk/kdevplatform/ --stringparam ignore-filelist false --stringparam add-br true --stringparam websvn-url http://websvn.kde.org/trunk/extragear/sdk/kdevplatform/ svn2cl.xsl ../HEAD/ChangeLog_kdevplatform.xml > ../HEAD/ChangeLog_kdevplatform.ihtml

  # Create the HEAD/ChangeLog_kdevplatform.rss file (using XSLT)
  xsltproc --stringparam k_branch_path trunk/extragear/sdk/kdevplatform --stringparam k_base_version HEAD svnlog.xslt ../HEAD/ChangeLog_kdevplatform.xml > ../HEAD/ChangeLog_kdevplatform.rss

  # put the most recent 5 entries in the frontpage
  xsltproc --stringparam strip-prefix trunk/extragear/sdk/kdevplatform/ --stringparam ignore-filelist true --stringparam add-br true --stringparam limit-logentries 5 svn2cl.xsl ../HEAD/ChangeLog_kdevplatform.xml > ../dynamic/changelog_3_2_kdevplatform.html

  # create and/or update a list of authors
  #./svn2log.py -o ../HEAD/authors.new.ihtml -a -u ../admin/logs/accounts -w http://websvn.kde.org/trunk/extragear/sdk/kdevplatform/ ../HEAD/ChangeLog.xml
  # create the list (only needed the first time a branch is created)
  #sort -u ../HEAD/authors.new.ihtml > ../HEAD/authors.ihtml
  # sort them, merge them with previous runs, and remove duplicates
  #sort -u ../HEAD/authors.new.ihtml ../HEAD/authors.ihtml > ../HEAD/authors.ihtml.tmp
  #mv ../HEAD/authors.ihtml.tmp ../HEAD/authors.ihtml
fi


# Get an XML change log file directly from the log messages of the individual
# SVN commits between the stable branchpoint and HEAD
SVN_BRANCH_PATH=trunk/extragear/sdk/kdevelop/
svn log $KDE_SVN_BASE_URL/$SVN_BRANCH_PATH --revision HEAD:$LAST_RELEASED_REV -v --xml > ../4.0/ChangeLog.xml

# Test if the file was successfully created. This is necessary because sometimes the SVN command
# quits with a timeout leaving us with an empty 4.0/ChangeLog.xml or incomplete file
if [ $? -eq 0 ]; then
  # Create the 4.0/ChangeLog.html and 4.0/ChangeLog.rss files (using python)
  ./svn2log.py -o ../4.0/ChangeLog.ihtml -s ../4.0/ChangeLog2.rss -p 'branches/[^/]+|trunk/extragear/sdk/kdevelop/' -u ../admin/logs/accounts -w http://websvn.kde.org/$SVN_BRANCH_PATH ../4.0/ChangeLog.xml &> ../admin/logs/4.0_SVN_ChangeLog.log
  # Create the 4.0/ChangeLog.html file (using XSLT)
  #xsltproc --stringparam strip-prefix $SVN_BRANCH_PATH --stringparam ignore-filelist false --stringparam add-br true --stringparam websvn-url http://websvn.kde.org/$SVN_BRANCH_PATH svn2cl.xsl ../4.0/ChangeLog.xml > ../4.0/ChangeLog.html

  # Create the 4.0/ChangeLog.rss file (using XSLT)
  xsltproc --stringparam k_branch_path $SVN_BRANCH_PATH --stringparam k_base_version HEAD svnlog.xslt ../4.0/ChangeLog.xml > ../4.0/ChangeLog.rss

  # put the most recent 5 entries in the frontpage
#  xsltproc --stringparam strip-prefix $SVN_BRANCH_PATH --stringparam ignore-filelist true --stringparam add-br true --stringparam websvn-url http://websvn.kde.org/$SVN_BRANCH_PATH --stringparam limit-logentries 5 svn2cl.xsl ../4.0/ChangeLog.xml > ../dynamic/changelog_3_2.html

  # create and/or update a list of authors
  ./svn2log.py -o ../4.0/authors.new.ihtml -a -u ../admin/logs/accounts -w http://websvn.kde.org/$SVN_BRANCH_PATH ../4.0/ChangeLog.xml
  # create the list (only needed the first time a branch is created)
  #sort -u ../4.0/authors.new.ihtml > ../4.0/authors.ihtml
  # sort them, merge them with previous runs, and remove duplicates
  sort -u ../4.0/authors.new.ihtml ../4.0/authors.ihtml > ../4.0/authors.ihtml.tmp
  mv ../4.0/authors.ihtml.tmp ../4.0/authors.ihtml
fi



# Get an XML change log file directly from the log messages of the individual
# SVN commits between the stable branchpoint and 4.0
svn log $KDE_SVN_BASE_URL/trunk/extragear/sdk/kdevplatform/ --revision HEAD:$LAST_RELEASED_REV -v --xml > ../4.0/ChangeLog_kdevplatform.xml

# Test if the file was successfully created. This is necessary because sometimes the SVN command
# quits with a timeout leaving us with an empty 4.0/ChangeLog_kdevplatform.xml or incomplete file
if [ $? -eq 0 ]; then
  # Create the 4.0/ChangeLog_kdevplatform.html and 4.0/ChangeLog2_kdevplatform.rss files (using python)
  ./svn2log.py -o ../4.0/ChangeLog_kdevplatform.ihtml -s ../4.0/ChangeLog2_kdevplatform.rss -p '/trunk/extragear/sdk/kdevplatform/' -u ../admin/logs/accounts -w http://websvn.kde.org/trunk/extragear/sdk/kdevplatform/ ../4.0/ChangeLog_kdevplatform.xml &> ../admin/logs/4.0_SVN_ChangeLog_kdevplatform.log
  # Create the 4.0/ChangeLog_kdevplatform.html file (using XSLT)
  #xsltproc --stringparam strip-prefix trunk/extragear/sdk/kdevplatform/ --stringparam ignore-filelist false --stringparam add-br true --stringparam websvn-url http://websvn.kde.org/trunk/extragear/sdk/kdevplatform/ svn2cl.xsl ../4.0/ChangeLog_kdevplatform.xml > ../4.0/ChangeLog_kdevplatform.ihtml

  # Create the 4.0/ChangeLog_kdevplatform.rss file (using XSLT)
  xsltproc --stringparam k_branch_path trunk/extragear/sdk/kdevplatform --stringparam k_base_version HEAD svnlog.xslt ../4.0/ChangeLog_kdevplatform.xml > ../4.0/ChangeLog_kdevplatform.rss

  # put the most recent 5 entries in the frontpage
#  xsltproc --stringparam strip-prefix trunk/extragear/sdk/kdevplatform/ --stringparam ignore-filelist true --stringparam add-br true --stringparam websvn-url http://websvn.kde.org/trunk/extragear/sdk/kdevplatform/ --stringparam limit-logentries 5 svn2cl.xsl ../4.0/ChangeLog_kdevplatform.xml > ../dynamic/changelog_3_2_kdevplatform.html

  # create and/or update a list of authors
  #./svn2log.py -o ../4.0/authors.new.ihtml -a -u ../admin/logs/accounts -w http://websvn.kde.org/trunk/extragear/sdk/kdevplatform/ ../4.0/ChangeLog.xml
  # create the list (only needed the first time a branch is created)
  #sort -u ../4.0/authors.new.ihtml > ../4.0/authors.ihtml
  # sort them, merge them with previous runs, and remove duplicates
  #sort -u ../4.0/authors.new.ihtml ../4.0/authors.ihtml > ../4.0/authors.ihtml.tmp
  #mv ../4.0/authors.ihtml.tmp ../4.0/authors.ihtml
fi



# Get an XML change log file directly from the log messages of the individual
# SVN commits between the branchpoint and HEAD of the stable "KDE/3.5" branch
svn log $KDE_SVN_BASE_URL/branches/KDE/3.5/kdevelop/ --revision HEAD:1010222 -v --xml > ../3.5/ChangeLog.xml

# Test if the file was successfully created. This is necessary because sometimes the SVN command
# quits with a timeout leaving us with an empty 3.5/ChangeLog.xml file
if [ $? -eq 0 ]; then
  # Create the 3.5/ChangeLog.ihtml file (using python)
  ./svn2log.py -o ../3.5/ChangeLog.ihtml -p 'branches/[^/]+|branches/KDE/3.5/kdevelop/' -u ../admin/logs/accounts -w http://websvn.kde.org/branches/KDE/3.5/kdevelop/ ../3.5/ChangeLog.xml &> ../admin/logs/kdevelop_3_5_SVN_ChangeLog.log

  # Create the 3.5/ChangeLog.rss file (using XSLT)
  xsltproc --stringparam k_branch_path branches/KDE/3.5/kdevelop/ --stringparam k_base_version 3.5 svnlog.xslt ../3.5/ChangeLog.xml > ../3.5/ChangeLog.rss

  # put the most recent 5 entries in the frontpage
  xsltproc --stringparam strip-prefix branches/KDE/3.5/kdevelop/ --stringparam ignore-filelist true --stringparam add-br true --stringparam websvn-url http://websvn.kde.org/branches/KDE/3.5/kdevelop/ --stringparam limit-logentries 5 svn2cl.xsl ../3.5/ChangeLog.xml > ../dynamic/changelog_3_5.html

  # create and/or update a list of authors
  ./svn2log.py -o ../3.5/authors.new.ihtml -a -u ../admin/logs/accounts -w http://websvn.kde.org/branches/KDE/3.5/kdevelop/ ../3.5/ChangeLog.xml
  # create the list (only needed the first time a branch is created)
  #sort -u ../3.5/authors.new.ihtml > ../3.5/authors.ihtml
  # sort them, merge them with previous runs, and remove duplicates
  sort -u ../3.5/authors.new.ihtml ../3.5/authors.ihtml > ../3.5/authors.ihtml.tmp
  mv ../3.5/authors.ihtml.tmp ../3.5/authors.ihtml
fi


#./make_ch_log_2_0
./update_web_trans_status.php
chmod 755 ./update_avr_daily_visits.php
./update_avr_daily_visits.php