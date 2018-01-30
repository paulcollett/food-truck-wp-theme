<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- wp_head -->
<?php wp_head(); ?>
<!-- / wp_head -->

</head>
<body <?php body_class(); ?>>
<script>FoodTruckTheme.mobileNavAt(<?php echo absint(get_theme_mod('ftt_theme_mod_nav_breakpoint')) ?: (!empty($content_width) ? $content_width : 0) ?: 1200; ?>); FoodTruckTheme.bodyReady();</script>

<header class="mainnav" role="mainnav">
  <nav class="mainnav_items">
    <ul class="mainnav_items_links menu-top-left-container">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'top-left',
          'menu_id' => 'top-menu-left',
          'depth' => 1,
          'container' => false,
          'items_wrap' => '%3$s',
          'fallback_cb' => 'ftt_no_wp_top_menu_fallback',
        ));
      ?>
    </ul>
    <div class="mainnav_items_logo">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" rel="home" itemprop="url">
        <?php if(has_custom_logo()): ?>
          <?php
            // Note: the_custom_logo() outputs a wrapping a <a />, so we'll bypass:
            echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, array(
              'itemprop' => 'logo',
              'alt' => get_bloginfo( 'name', 'display' )
            ));
          ?>
        <?php else: ?>
          <div class="logo_text"><?php echo esc_html(get_bloginfo('name', 'display')); ?></div>
        <?php endif; ?>
      </a>
    </div>
    <ul class="mainnav_items_links menu-top-right-container">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'top-right',
          'menu_id' => 'top-menu-right',
          'depth' => 1,
          'container' => false,
          'items_wrap' => '%3$s',
          'fallback_cb' => 'ftt_no_wp_top_menu_fallback',
        ));
      ?>
      <li class="mainnav_items_links_toggle">
        <div class="mainnav-toggle"><i></i></div>
      </li>
    </ul>
  </nav>
  <nav class="mainnav_mob">
    <ul class="mainnav_mob_items menu-top-mobile-container">
      <li class="mainnav_mob_items_item"></li>
    </ul>
  </nav>
</header>
<script>FoodTruckTheme.navReady();</script>

<!-- start main. contains page specific content -->
<main role="main">
