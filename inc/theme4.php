<?php

function module_head($subject){

echo '<div class="moduleentry">
  <div class="header"><h3>'.$subject.'</h3></div>
  <div class="content">
';
}

function module_tail(){
echo '</div>
  <div class="footer"></div>
</div>

';
}
