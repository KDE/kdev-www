#!/bin/bash
#
# This script builds the KDevelop API documentation using doxygen
#
# (c) 2004-2009 Amilcar Lucas

# The directory where the kdevelop sources are
#cd /home/smeier/
cd ~

cd kdevelop/

# Update the sources
#svn switch https://smeier@svn.kde.org/home/kde/trunk/extragear/sdk/kdevelop/
svn update > ../public_html/www/admin/logs/kdevelop_svn_up.log 2> ../public_html/www/admin/logs/kdevelop_svn_up.err.log

# get the kdelibs and qt tag files from developer.kde.org and redirect it's stdout and stderr to a file
#wget http://api.kde.org/4.x-api/kdelibs-apidocs/dcop/dcop.tag > ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
#wget http://api.kde.org/4.x-api/kdelibs-apidocs/interfaces/interfaces.tag > ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/kdecore/kdecore.tag > ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
#wget http://api.kde.org/4.x-api/kdelibs-apidocs/kdefx/kdefx.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/kdeui/kdeui.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/khtml/khtml.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/kio/kio.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/kjs/kjs.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/kparts/kparts.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/knewstuff/knewstuff.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/kutils/kutils.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/interfaces/ktexteditor/ktexteditor.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
#wget http://api.kde.org/4.x-api/kdevplatform-apidocs/editor/editor.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdevplatform-apidocs/interfaces/interfaces.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdevplatform-apidocs/language/language.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdevplatform-apidocs/outputview/outputview.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdevplatform-apidocs/project/project.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdevplatform-apidocs/shell/shell.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdevplatform-apidocs/sublime/sublime.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdevplatform-apidocs/util/util.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdevplatform-apidocs/vcs/vcs.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1
wget http://api.kde.org/4.x-api/kdelibs-apidocs/qt/qt.tag >> ../public_html/www/HEAD/doc/api/doxytag.log 2>&1

# Overwrite the old files with the new ones (only if new ones exist)
#mv dcop.tag.1 dcop.tag
#mv interfaces.tag.1 interfaces.tag
mv kdecore.tag.1 kdecore.tag
#mv kdefx.tag.1 kdefx.tag
mv kdeui.tag.1 kdeui.tag
mv khtml.tag.1 khtml.tag
mv kio.tag.1 kio.tag
mv kjs.tag.1 kjs.tag
mv kparts.tag.1 kparts.tag
mv knewstuff.tag.1 knewstuff.tag
mv kutils.tag.1 kutils.tag
mv ktexteditor.tag.1 ktexteditor.tag
#mv editor.tag.1 editor.tag
mv interfaces.tag.1 interfaces.tag
mv language.tag.1 language.tag
mv outputview.tag.1 outputview.tag
mv project.tag.1 project.tag
mv shell.tag.1 shell.tag
mv sublime.tag.1 sublime.tag
mv util.tag.1 util.tag
mv vcs.tag.1 vcs.tag
mv qt.tag.1 qt.tag

# run doxygen with nice and redirect it's stdout and stderr to a file
nice ../bin/doxygen -d ExtCmd ../public_html/www/HEAD/doc/api/Doxyfile.cfg &> ../public_html/www/HEAD/doc/api/doxygen.log

# Change to the API directory do that the file gets created on a webserver "visible" directory
cd ../public_html/www/HEAD/doc/api/

# remove the /home/smeier/kdevelop/ string from the error/warning file because
# it's repeated all over the file and gives no useful information
# besides that it removes some warnings that are not important
# this way the file size reduces to 14Kb instead of 1.6Mb
sed 's:^/home/smeier/kdevelop/::g;/^<tagfile>:1 Warning: Internal inconsistency: member/d;/^QGDict::hashAsciiKey: Invalid null key/d' doxygen.log > doxygen.mail.log

# mail it to the kdevelop-devel mailing list
#mail -s "[fara] Doxygen errors on KDevelop API `date +%F`" kdevelop-devel@kdevelop.org < doxygen.mail.log

#########################################
#  Now for the platform API (Adymo's API)
#########################################

# Change to the API directory do that the file gets created on a webserver "visible" directory
#cd ../platform/

# This will create a Doxyfile.log file with the created configuration files concatenated together
# and a doxygen.log with the doxygen error output
#make &> doxygen.log

# remove the /home/smeier/kdevelop/ string from the error/warning file because
# it's repeated all over the file and gives no useful information
# besides that it removes some warnings that are not important
# this way the file size reduces to 14Kb instead of 1.6Mb
#sed 's:^/home/smeier/kdevelop/::g;/^<tagfile>:1 Warning: Internal inconsistency: member/d;/^QGDict::hashAsciiKey: Invalid null key/d' doxygen.log > doxygen.mail.log

# mail it to the kdevelop-devel mailing list
#mail -s "[fara] Doxygen errors on KDevelop platform API `date +%F`" kdevelop-devel@kdevelop.org < doxygen.mail.log
