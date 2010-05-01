#!/usr/bin/php
<?php
# Backup the MySQL kdevelop_db database and the MRTG databases
# (w) 22.11.2004
# (c) by Amilcar Lucas, licensed under the GPL

// get the MySQL global login and password
include ('../../access.inc');

// Configure here
$host= 'localhost';
// This will hopefully work in all situations (apache served or command line invoked)
// It allows the entire website to adapt itself to it's location on the filesystem
// The dirname is invoked twice to remove the last subdir (/bin) of the path
$backupdir = dirname(dirname(__FILE__)) . '/admin/backups';
$mysqldump ='mysqldump';

// Compute day, month, year, hour and min.
$today = getdate();
$day = $today['mday'];
if ($day < 10) {
  $day = "0$day";
}
$month = $today['mon'];
if ($month < 10) {
  $month = "0$month";
}
$year = $today['year'];
$hour = $today['hours'];
$min = $today['minutes'];
$sec = "00";
echo "$year/$month/$day - $hour:$min\n";

// Enter your MySQL access data
$user= $k_login;
$pass= $k_password;
$db=  'kdevelop_db';

// Execute mysqldump command.
// It will produce a file named $db-$year$month$day-$hour$min.gz
// under $backupdir
echo system("$mysqldump --opt -h $host -u $user -p$pass $db | gzip > $backupdir/$db-$year$month$day-$hour$min.sql.gz");
echo "MySQL database $db DONE\n";


// Backup the MRTG databases, mediawiki uploaded images,
// website translation status
chdir('..');
$db='other_files';
echo system("tar cvzf $backupdir/$db-$year$month$day-$hour$min.tar.gz --exclude=CVS --exclude=README mediawiki/images/* mediawiki/extensions/SpamBlacklist/*_blacklist */ChangeLog.xml */ChangeLog.ihtml */ChangeLog.rss dynamic/new_dl_per_hour.log dynamic/online.log dynamic/translation_status*.html dynamic/*_translation_status.html sitemap.xml sitemap-* admin/webalizer_stats/*");
echo "\n$db DONE\n";

?>