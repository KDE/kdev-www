<?php

function menuleft($text,$content){
global $design;

$menucontent=preg_split("/,/",$content);

echo "
<table cellpadding=\"3\" border=\"0\" width=\"100%\"><tr><td align=center>
<table cellpadding=\"0\" border=\"0\" width=\"100%\" cellspacing=\"0\" bgcolor=\"#e6f0f9\">
  <tr bgcolor=\"#3e91eb\">
    <th width=\"18\" height=\"32\" background=\"graphics/theme1/menutopleft.gif\">
      <img src=\"graphics/theme1/menutopleft.gif\" border=\"0\" /></th>
    <th class=\"menuheader\" align=\"center\" width=\"100%\" background=\"graphics/theme1/menutop.gif\" />$text</th>
    <th width=\"18\" height=\"32\" background=\"graphics/theme1/menutopright.gif\">
      <img src=\"graphics/theme1/menutopright.gif\" border=0></th>
  </tr>
  <tr>
    <td width=\"18\" background=\"graphics/theme1/menuleft.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=\"0\" /></td>
    <td class=menuentry align=center valign=middle background=\"graphics/theme1/grid.gif\">
";

$size=sizeof($menucontent)-1;
for ($x=0;$x<=$size;$x=$x+2) {
  $y=$x+1;
  echo "<a href=\"$menucontent[$y]\">$menucontent[$x]</a><br>";
}

echo "
    </td> 
    <td width=\"18\" background=\"graphics/theme1/menuright.gif\" >
      <img src=\"graphics/theme1/fill.gif\" border=\"0\" /></td>
  </tr>
  <tr>
    <td class=fill height=\"12\" background=\"graphics/theme1/menubottomleft.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=\"0\" /></td>
    <td class=fill background=\"graphics/theme1/menubottom.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=\"0\" /></td>
    <td class=fill height=\"12\" background=\"graphics/theme1/menubottomright.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=\"0\" /></td>
  </tr>
</table>
</td></tr></table>
";

}

function module_head($subject){
global $design;

echo "
<table cellpadding=3 border=0 width=\"100%\"><tr><td>
<table cellspacing=0 cellpadding=0 border=0 width=\"100%\" bgcolor=\"#e6f0f9\">
  <tr bgcolor=\"#3e91eb\">
    <th width=\"18\" height=\"32\" background=\"graphics/theme1/menutopleft.gif\">
      <img src=\"graphics/theme1/menutopleft.gif\" alt=\"\"></th>
    <th class=moduleheader align=center width=\"100%\" background=\"graphics/theme1/menutop.gif\">
      $subject</th>
    <th width=\"18\" height=\"32\">
      <img src=\"graphics/theme1/menutopright.gif\" alt=\"\"></th>
  </tr>
  <tr>
    <td width=\"18\" background=\"graphics/theme1/menuleft.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=0></td>
    <td align=center valign=middle background=\"graphics/theme1/grid.gif\">
      <table width=\"100%\" border=0 cellspacing=0 cellpadding=0>
        <tr><td class=fill>&nbsp;</td></tr>
        <tr><td align=left valign=middle>
";

}

function module_tail(){
global $design;

echo "
        </td></tr>
        <tr><td class=fill>&nbsp</td></tr>
      </table>
    </td>
    <td width=\"18\" background=\"graphics/theme1/menuright.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=0></td>
  </tr>
  <tr>
    <td  class=fill height=\"12\" background=\"graphics/theme1/menubottomleft.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=0></td>
    <td  class=fill width=\"100%\" background=\"graphics/theme1/menubottom.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=0></td>
    <td  class=fill height=\"12\" background=\"graphics/theme1/menubottomright.gif\">
      <img src=\"graphics/theme1/fill.gif\" border=0></td>
  </tr>
</table>
</td></tr></table>
";

}

?>
