<?php
$i=0;
$pic_file[$i]='4.0/Kdevelop_cpp_codetooltip.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_fullcompletion.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_documentation.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_debug_tooltipvalue.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_includehelper.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_implementationhelper.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_assistantupdatedeclaration.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_assistant.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_workingset.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_svnmenu.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_svnhistorydifftoprevious.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_svnhistory.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_svnannotate.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_standard.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_splitview.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_slotcompletion_newslot.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_slotcompletion.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_signalcompletion.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_reviewinlinediff.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_reviewarea.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_quickoutline.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_minicompletion.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_grepview_openitem.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_grepview.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_debug_stopped.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_createclass_override.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_cmake_docs_navigationtooltip.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop_builderrors.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop-php8.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop-php7.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop-php6.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop-php5.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop-php4.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop-php3.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop-php2.png';$i=$i+1;
$pic_file[$i]='4.0/Kdevelop-php1.png';$i=$i+1;

$j = rand(0, $i-1);
$image_link='graphics/screenshots/'.$pic_file[$j];
$image_file='graphics/screenshots/'.str_replace('/','/thumbs/',$pic_file[$j]);

module_head($l_picture_corner);
echo "  <div id=\"picturecorner\">
  <a href=\"$image_link\">
    <img src=\"$image_file\" alt=\"preview\" width=\"205\" height=\"148\">
  </a>
  <!-- <p> $l_picture_corner_description </p> -->
  </div>\n";
module_tail();
?>