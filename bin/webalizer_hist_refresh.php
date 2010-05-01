#!/usr/bin/php
<?php

// configuration start
$currentyear = 2008;
$currentmonth = 7;
// configuration end

function getFirstDayOfMonth($month, $year){
  return idate('d', mktime(0, 0, 0, $month, 1, $year));
}

function getLastDayOfMonth($month, $year){
  return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
}

$year = $currentyear;
$yearmonth = $currentmonth;
/*
 Generate the webalizer.hist file acording to these fields:

Month# Year# Hits Files Sites KBytes Fdom Ldom Pages Visits
(Fdom=First Ldom=Last day of month processed)
*/


// Month varies from 1 to 120
for ($monthn = 1 ; $monthn <= 120 ; $monthn++){
  $usagefilename=sprintf("usage_%d%02d.html",$year,$yearmonth);
  $fp=@fopen($usagefilename, 'r');
  if (!$fp){
    //echo("Could not open file $usagefilename\n");
    $hits=0;
    $files=0;
    $sites=0;
    $kbytes=0;
    $pages=0;
    $visits=0;
  }else{
  do {
    $line = fgets($fp, 1024);
    // Finds the Hits using regular expressions
  } while (!feof($fp) && !eregi('Total Hits', $line, $out));
  $line = fgets($fp, 1024);
  eregi('<B>([0-9]*)</B>', $line, $out);
  $hits = $out[1];
  do {
    $line = fgets($fp, 1024);
    // Finds the Files using regular expressions
  } while (!feof($fp) && !eregi('Total Files', $line, $out));
  $line = fgets($fp, 1024);
  eregi('<B>([0-9]*)</B>', $line, $out);
  $files = $out[1];
  do {
    $line = fgets($fp, 1024);
    // Finds the Pages using regular expressions
  } while (!feof($fp) && !eregi('Total Pages', $line, $out));
  $line = fgets($fp, 1024);
  eregi('<B>([0-9]*)</B>', $line, $out);
  $pages = $out[1];
  do {
    $line = fgets($fp, 1024);
    // Finds the Visits using regular expressions
  } while (!feof($fp) && !eregi('Total Visits', $line, $out));
  $line = fgets($fp, 1024);
  eregi('<B>([0-9]*)</B>', $line, $out);
  $visits = $out[1];
  do {
    $line = fgets($fp, 1024);
    // Finds the KBytes using regular expressions
  } while (!feof($fp) && !eregi('Total KBytes', $line, $out));
  $line = fgets($fp, 1024);
  eregi('<B>([0-9]*)</B>', $line, $out);
  $kbytes = $out[1];
  do {
    $line = fgets($fp, 1024);
    // Finds the Sites using regular expressions
  } while (!feof($fp) && !eregi('Unique Sites', $line, $out));
  $line = fgets($fp, 1024);
  eregi('<B>([0-9]*)</B>', $line, $out);
  $sites = $out[1];
  fclose($fp);
  }
  echo "$monthn $year $hits $files $sites $kbytes ".getFirstDayOfMonth($yearmonth, $year).' '.getLastDayOfMonth($yearmonth, $year)." $pages $visits\n";
  //echo "$monthn $year\n";
  $yearmonth--;
  if ($yearmonth == 0){
    $yearmonth = 12;
    $year--;
  }
  // Year goes backwards until 2000
  if ($year == 2000) break;
}
