<?php
// $Id: page.tpl.php,v 1.18.2.1 2009/04/30 00:13:31 goba Exp $
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <?php print $head ?>
    <title><?php print $head_title ?></title>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
  </head>
  <body<?php print phptemplate_body_class($left, $right); ?>>
  <div id="layoutcontainer" class="threecols">
<!-- Layout -->

      <div id="maincontent">
          <?php print $breadcrumb; ?>
          <?php if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
          <?php if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h2>'; endif; ?>
          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
          <?php if ($show_messages && $messages): print $messages; endif; ?>
          <?php print $help; ?>
          <div class="clear-block">
            <?php print $content ?>
          </div>
          <?php print $feed_icons ?>
      </div><!-- /#maincontent -->
      <div id="footer"><?php print $footer_message . $footer ?></div>

      <?php if ($right): ?>
        <div id="sidebar-right" class="sidebar">
          <?php if (!$left && $search_box): ?><div class="block block-theme"><?php print $search_box ?></div><?php endif; ?>
          <?php print $right ?>
        </div>
      <?php endif; ?>

      <?php if ($left): ?>
        <div id="sidebar-left" class="sidebar">
          <?php print $left ?>
        </div>
      <?php endif; ?>

      <div id="head"></div>
      <div id="logo">
        <?php
            print '<a href="'. check_url($front_page) .'" title="'. $site_title .'">';
            print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo" />';
            print '</a>';
        ?>
        </div>
      </div> <!-- /header -->

      <!-- TODO: -->
      <div id="languageselector"></div>

      <?php if ($search_box): ?>
        <div id="sitesearch"><div id="searchbar-content"><?php print $search_box ?></div></div>
      <?php endif; ?>
      <?php if (isset($primary_links)) : ?>
      <div id="menu">
          <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>
      </div>
      <?php endif; ?>
<!-- /layout -->
  </div>

  <?php print $closure ?>
  </body>
</html>
