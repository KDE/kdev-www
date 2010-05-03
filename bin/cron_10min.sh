#!/bin/sh
#
# PLEASE do not edit this file, edit manual_maintenance.sh insted
#

#cd /home/smeier/
cd ~

# Update the on-line version of the website
# http://fara.cs.uni-potsdam.de/~smeier/www/
# AKA http://www.kdevelop.org
# using the repository copy located at
# :pserver:xxxxx@barney.cs.uni-potsdam.de:/home/cvs/kdevelop_www
cd public_html/www/
cvs update -dP > admin/logs/website_cvs_up.log 2> admin/logs/website_cvs_up.err.log

# Update change log module on the front page (got outdated, now we use the real SVN log messages)
#cd ../../kdevelop/
#svn update -N . > ../public_html/www/admin/logs/kdevelop_svn_10min_up.log 2> ../public_html/www/admin/logs/kdevelop_svn_10min_up.err.log
#cd ../public_html/www/

# Sometimes the permissions get restricted by the CVS update operation
# so open them up, let the webserver overwrite the .ihtml files
chmod o+w news/*.ihtml
chmod o+w main201*.html

# Execute manual maintenance tasks
./bin/manual_maintenance.sh

# Run MRTG to update the "online visitors" statistics graphs
cd bin/
env LANG=C nice mrtg online.mrtg.cfg --logging=../admin/logs/online.mrtg.log