#!/bin/sh

retch=`~/public_html/www/bin/new_dl_per_hour.mrtg.php`

set $retch

echo $1
echo $2

retch=`uptime`

set $retch

echo $3 $4
echo "www.kdevelop.org"
