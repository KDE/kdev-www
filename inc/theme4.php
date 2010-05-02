<?php

function module_head($subject, $class=''){
    echo "<div class=\"moduleentry $class\">\n";
    if (strpos($class, 'border') !== false) {
        echo "<div class=\"border-tl\"></div>\n";
        echo "<div class=\"border-tr\"></div>\n";
        echo "<div class=\"border-bl\"></div>\n";
        echo "<div class=\"border-br\"></div>\n";
        echo "<div class=\"border-l\"></div>\n";
        echo "<div class=\"border-r\"></div>\n";
    }
    echo "<div class=\"header\"><img class=\"category-icon\"/><h3>$subject</h3></div>\n";
    echo "<div class=\"content\">\n";
}

function module_tail(){
echo '</div>
  <div class="footer"></div>
</div>

';
}
