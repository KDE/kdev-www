<?php module_head($contenttitle);

foreach($thumbs as $thumb){
  echo '<div class="screenshotthumb">
 <a href="graphics/screenshots/4.0/'.$thumb->filename.'" title="'.$biggerimage .'"><img src="graphics/screenshots/4.0/thumbs/'.$thumb->filename.'" width="'.$thumb->width.'" height="'.$thumb->height.'" alt="'.$l_screenshot.'"></a>
 <div class="screenshotcaption">'.$thumb->caption.'</div>
</div>
';
}
?>

<br style="clear:both;">

<?php module_tail();?>
