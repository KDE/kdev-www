<?php $color = (isset($HTTP_GET_VARS['color'])) ? $HTTP_GET_VARS['color'] : "E5E5E5"; ?>
	body
		{ margin-top: 5px; }
	input, select
		{ font-weight: bold; }
	.input
		{ width: 140px; }
	.back
		{ background-image: url(img/background.gif); }
	.border
		{ background-image: url(img/background.gif); border: 1px solid #000000; }

	/* Fonts */
	.tall
		{ font-family: Arial, Tahoma, Helvetica, sans-serif; font-size: 110%; }
	.medium
		{ font-family: Arial, Tahoma, Helvetica, sans-serif; font-size: 95%; font-weight: bold; }
	.small
		{ font-family: Arial, Tahoma, Helvetica, sans-serif; font-size: 80%; }

	/* Bitmap smiley background */
	.color
		{ background-color: #<?php print $color; ?>; }