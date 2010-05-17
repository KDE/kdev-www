<?php

// BEGIN DEPRECATED
function module_head($subject, $class='moduleentry'){
    echo "<div class=\"$class\">\n";
    if (strpos($class, 'border') !== false) {
        echo "<div class=\"border-tl\"></div>\n";
        echo "<div class=\"border-tr\"></div>\n";
        echo "<div class=\"border-bl\"></div>\n";
        echo "<div class=\"border-br\"></div>\n";
        echo "<div class=\"border-l\"></div>\n";
        echo "<div class=\"border-r\"></div>\n";
    }
    echo "<div class=\"header\"><span class=\"category-icon\"></span><h3>$subject</h3></div>\n";
    echo "<div class=\"content\">\n";
}

function module_tail(){
echo '</div>
  <div class="footer"></div>
</div>

';
}
// END DEPRECATED

// Markup for Content Boxes (Fluid-width boxes that can have arbitrary content):
function box_head($class) {
    echo "<div class=\"$class\">\n";
    echo "<div class=\"border-tl\"></div>\n";
    echo "<div class=\"border-tr\"></div>\n";
    echo "<d
iv class=\"border-bl\"></div>\n";
    echo "<div class=\"border-br\"></div>\n";
    echo "<div class=\"border-l\"></div>\n";
    echo "<div class=\"border-r\"></div>\n";
}
function box_tail() {
    echo "</div>\n";
}

// Markup for Link Boxes (Fixed-width boxes that have a title and optionally a category icon):
function linkbox_head($title, $class, $category) {
    echo "<div class=\"$class";         // $class will be 'navbox' or 'quickbox'
    if (empty($category))
      echo "\"><div class=\"header\">";
    else
      echo " $category\"><div class=\"header\"><span class=\"category-icon\"/></span>";
    echo "<h3>$title</h3></div><div class=\"content\">\n";
    
}
function linkbox_tail() {
    echo "</div><div class=\"footer\"></div></div>\n";
}