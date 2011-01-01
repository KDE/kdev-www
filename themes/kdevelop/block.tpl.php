<?php
// $Id: block.tpl.php,v 1.3 2007/08/07 08:39:36 goba Exp $

$custom_block_classes = array(
  'block-1' => 'cat-download',
  'menu-menu-documentation' => 'cat-doc',
  'menu-menu-development' => 'cat-devel',
  'menu-menu-community' => 'cat-community',
  'menu-menu-promotion' => 'cat-promotion',
);

$id = $block->module .'-'.$block->delta;
$custom_class = '';
if (isset($custom_block_classes[$id])) {
  $custom_class = $custom_block_classes[$id];
}
?>
<div id="block-<?php print $id; ?>" class="clear-block block block-<?php print $block->module.' '.$custom_class ?>">

  <div class="header">
  <?php if (!empty($block->subject)): ?>
    <span class="category-icon"></span>
    <h3><?php print $block->subject ?></h2>
  <?php endif;?>
  </div>

  <div class="content"><?php print $block->content ?></div>
  <div class="footer"></div>
</div>
