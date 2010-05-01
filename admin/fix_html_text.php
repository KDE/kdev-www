<?php

// This function converts text to valid HTML
function htmlEncodeText ($string){
  $pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';
  preg_match_all ('/' . $pattern . '/', $string, $tagMatches, PREG_SET_ORDER);
  $textMatches = preg_split ('/' . $pattern . '/', $string);

  foreach ($textMatches as $key => $value) {
    // encode normal text
    $textMatches [$key] = htmlentities ($value);
  }

  foreach ($tagMatches as $key => $value) {
    // encode text inside html tags
    $tagMatches [$key] = str_replace('&amp;', '&', $tagMatches [$key]); // make sure there are no amps
    $tagMatches [$key] = str_replace('&', '&amp;', $tagMatches [$key]); // now _safely_ convert to amps
  }

  for ($i = 0; $i < count ($textMatches); $i ++) {
    $textMatches [$i] = $textMatches [$i] . $tagMatches [$i] [0];
  }

  return implode ($textMatches);
}

$fp = fopen('php://stdin','r');
$text = fread($fp, 100000);
fclose($fp);

//echo htmlEncodeText ( $text );
echo preg_replace('/\&(?![a-zA-Z]*;)/i', '&amp;', $text);

?>