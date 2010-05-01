<?php
$i=0;
$pic_file[$i]='3.1/add_slot.png';$i=$i+1;
$pic_file[$i]='3.1/ctags.png';$i=$i+1;
$pic_file[$i]='3.1/editor_filetree.png';$i=$i+1;
$pic_file[$i]='3.1/new_class_dialog.png';$i=$i+1;
$pic_file[$i]='3.1/project_packaging_publishing.png';$i=$i+1;
$pic_file[$i]='3.1/class_diagram.png';$i=$i+1;
$pic_file[$i]='3.1/editor.png';$i=$i+1;
$pic_file[$i]='3.1/project_options_doxygen.png';$i=$i+1;
$pic_file[$i]='3.1/snippet_dialog.png';$i=$i+1;
$pic_file[$i]='3.1/context_menu_goto.png';$i=$i+1;
$pic_file[$i]='3.1/documentation.png';$i=$i+1;
$pic_file[$i]='3.1/kdevdesigner.png';$i=$i+1;
$pic_file[$i]='3.1/project_options_runoptions.png';$i=$i+1;
$pic_file[$i]='3.1/subproject_options.png';$i=$i+1;
$pic_file[$i]='3.2/ctags.png';$i=$i+1;
$pic_file[$i]='3.2/cvs-integration.png';$i=$i+1;
$pic_file[$i]='3.2/fun4.png';$i=$i+1;
$pic_file[$i]='3.2/kdevelop1.png';$i=$i+1;
$pic_file[$i]='3.2/newfiletree.png';$i=$i+1;
$pic_file[$i]='3.2/ruby-debuger.png';$i=$i+1;
$pic_file[$i]='3.2/yzis.jpg';$i=$i+1;
$pic_file[$i]='3.3/main_popup.png';$i=$i+1;
$pic_file[$i]='3.3/tracing1.png';$i=$i+1;
$pic_file[$i]='3.3/tracing_output.png';$i=$i+1;
$pic_file[$i]='3.3/variables_recent.png';$i=$i+1;
$pic_file[$i]='3.3/editing_variable.png';$i=$i+1;
$pic_file[$i]='3.3/tracing2.png';$i=$i+1;
$pic_file[$i]='3.3/variables_popup.png';$i=$i+1;
$pic_file[$i]='3.3/variables_window.png';$i=$i+1;
$pic_file[$i]='3.4/codecomplbases.png';$i=$i+1;
$pic_file[$i]='3.4/qm.png';$i=$i+1;
$pic_file[$i]='3.4/qmreload.png';$i=$i+1;
$pic_file[$i]='3.4/codecompl.png';$i=$i+1;
$pic_file[$i]='3.4/qmctxt1.png';$i=$i+1;
$pic_file[$i]='3.4/qmctxt2.png';$i=$i+1;
$pic_file[$i]='3.4/qtopts.png';$i=$i+1;
$pic_file[$i]='3.4/qmopts.png';$i=$i+1;
$pic_file[$i]='3.4/qmqt4conf.png';$i=$i+1;
$pic_file[$i]='3.4/qt4uis.png';$i=$i+1;
$pic_file[$i]='3.5/automakesubprojectoptions.png';$i=$i+1;
$pic_file[$i]='3.5/codecompl.png';$i=$i+1;
$pic_file[$i]='3.5/codecomplbases.png';$i=$i+1;
$pic_file[$i]='3.5/codeformatingoptions.png';$i=$i+1;
$pic_file[$i]='3.5/doxygenoptions.png';$i=$i+1;
$pic_file[$i]='3.5/editorcontextmenu.png';$i=$i+1;
$pic_file[$i]='3.5/newprojectwizard.png';$i=$i+1;
$pic_file[$i]='3.5/qmakemanangeroptions.png';$i=$i+1;
$pic_file[$i]='3.5/qtoptions.png';$i=$i+1;
$pic_file[$i]='3.5/runoptions.png';$i=$i+1;

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