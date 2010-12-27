<?php

function menuleft($text,$content){
global $design;

$menucontent=preg_split("/,/",$content);

echo "<table cellpadding=\"3\" border=\"0\" width=\"100%\"><tr><td>
<table cellpadding=\"3\" border=\"0\" width=\"100%\" cellspacing=\"0\">
  <tr>
    <th class=\"menuheader\">$text</th>
  </tr>
  <tr>
    <td class=\"menuentry\">
";

$size=sizeof($menucontent)-1;
for ($x=0;$x<=$size;$x=$x+2) {
  $y=$x+1;
  echo "<a href=\"$menucontent[$y]\">$menucontent[$x]</a><br />";
}

echo "
    </td>
  </tr>
</table>
</td></tr></table>
";

}

function module_head($subject){
global $design;

echo "\n<!-- $subject module -->
<table cellpadding=\"3\" border=\"0\" width=\"100%\"><tr><td>
<table cellpadding=\"3\" border=\"0\" width=\"100%\" cellspacing=\"0\">
  <tr>
    <th class=\"moduleheader\">$subject</th>
  </tr>
  <tr>
    <td class=\"moduleentry\">
";

}

function module_tail(){
global $design;

echo "    </td>
  </tr>
</table>
</td></tr></table>
\n";

}

?>
