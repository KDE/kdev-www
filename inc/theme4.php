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
    return "<div class=\"$class\">\n".
           "<div class=\"border-tl\"></div>\n".
           "<div class=\"border-tr\"></div>\n".
           "<div class=\"border-bl\"></div>\n".
           "<div class=\"border-br\"></div>\n".
           "<div class=\"border-l\"></div>\n".
           "<div class=\"border-r\"></div>\n";
}
function box_tail() {
    return "</div>\n";
}

// Markup for Link Boxes (Fixed-width boxes that have a title and optionally a category icon):
function linkbox_head($title, $class, $category) {
    $html = "<div class=\"$class";         // $class will be 'navbox' or 'quickbox'
    if (empty($category))
      $html .= "\"><div class=\"header\">";
    else
      $html .= " $category\"><div class=\"header\"><span class=\"category-icon\"/></span>";
    $html .= "<h3>$title</h3></div><div class=\"content\">\n";
    return $html;
}
function linkbox_tail() {
    return "</div><div class=\"footer\"></div></div>\n";
}
