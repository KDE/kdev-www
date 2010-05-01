#!/usr/bin/php
<?php

/*
$files = `find .. -name translations.inc`;
$files = explode ("\n", $files);
foreach ($files as $filename){
  if ($filename == '') break;
  echo $filename;
  `sed -i 's/l_pt_3_0_/l_pt_x_x_/g' $filename`;
  `sed -i 's/announce_kdevelop_3_0/announce_kdevelop/g' $filename`;
  `sed -i 's/l_pt_3_4_ChangeLog/l_pt_x_x_ChangeLog/g' $filename`;
  `sed -i '/l_pt_x_x/s/3\.0/%s/g' $filename`;
  `sed -i 's/3\.4/%s/g' $filename`;
  `sed -i 's/main2002/main_year/g' $filename`;
  `sed -i 's/2002/%d/g' $filename`;
  `sed -i '/l_pt_3_/d' $filename`;
  `sed -i '/l_pt_main2/d' $filename`;
  `sed -i '/l_pt_main1/d' $filename`;
  `sed -i 's/lang=en/lang=en\\\" hreflang=\\\"en/g' $filename`;
  `sed -i 's/\?filename=/index\.html\?filename=/g' $filename`;
  echo ' ...done
';
}
*/
$files = `find ../inc -name other_*.php`;
$files = explode ("\n", $files);
foreach ($files as $filename){
//$filename= '../inc/other_ChangeLog.php';
//$filename= '../inc/other_announce-kdevelop.php';
  if ($filename == '') break;
  echo $filename;
  `sed -i '/l_pt_3_/s/echo /printf(/g' $filename`;
  `sed -i '/3_5/s/<\/dd>"/<\/dd>", "3\.5")/g' $filename`;
  `sed -i 's/3_5/x_x/g' $filename`;
  `sed -i '/3_4/s/<\/dd>"/<\/dd>", "3\.4")/g' $filename`;
  `sed -i 's/3_4/x_x/g' $filename`;
  `sed -i '/3_3/s/<\/dd>"/<\/dd>", "3\.3")/g' $filename`;
  `sed -i 's/3_3/x_x/g' $filename`;
  `sed -i '/3_2/s/<\/dd>"/<\/dd>", "3\.2")/g' $filename`;
  `sed -i 's/3_2/x_x/g' $filename`;
  `sed -i '/3_1/s/<\/dd>"/<\/dd>", "3\.1")/g' $filename`;
  `sed -i 's/3_1/x_x/g' $filename`;
  `sed -i '/3_0/s/<\/dd>"/<\/dd>", "3\.0")/g' $filename`;
  `sed -i 's/3_0/x_x/g' $filename`;
  `sed -i '/announce_kdevelop_x/s/announce_kdevelop_x_x/announce_kdevelop/g' $filename`;
  `sed -i '/l_pt_main/s/echo /printf(/g' $filename`;
  `sed -i '/2007/s/<\/dd>"/<\/dd>", "2007")/g' $filename`;
  `sed -i 's/_main2007/_main_year/g' $filename`;
  `sed -i '/2006/s/<\/dd>"/<\/dd>", "2006")/g' $filename`;
  `sed -i 's/_main2006/_main_year/g' $filename`;
  `sed -i '/2005/s/<\/dd>"/<\/dd>", "2005")/g' $filename`;
  `sed -i 's/_main2005/_main_year/g' $filename`;
  `sed -i '/2004/s/<\/dd>"/<\/dd>", "2004")/g' $filename`;
  `sed -i 's/_main2004/_main_year/g' $filename`;
  `sed -i '/2003/s/<\/dd>"/<\/dd>", "2003")/g' $filename`;
  `sed -i 's/_main2003/_main_year/g' $filename`;
  `sed -i '/2002/s/<\/dd>"/<\/dd>", "2002")/g' $filename`;
  `sed -i 's/_main2002/_main_year/g' $filename`;
  `sed -i '/2001/s/<\/dd>"/<\/dd>", "2001")/g' $filename`;
  `sed -i 's/_main2001/_main_year/g' $filename`;
  `sed -i '/2000/s/<\/dd>"/<\/dd>", "2000")/g' $filename`;
  `sed -i 's/_main2000/_main_year/g' $filename`;
  `sed -i '/1999/s/<\/dd>"/<\/dd>", "1999")/g' $filename`;
  `sed -i 's/_main1999/_main_year/g' $filename`;
  echo ' ...done
';
}

?>