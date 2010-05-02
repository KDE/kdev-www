<?php

function module_head($subject, $class=''){

echo '<div class="moduleentry '.$class.'">
  <div class="header"><img class="category-icon"/><h3>'.$subject.'</h3></div>
  <div class="content">
';
}

function module_tail(){
echo '</div>
  <div class="footer"></div>
</div>

';
}
