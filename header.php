<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- wp_head -->
<?php wp_head(); ?>
<!-- / wp_head -->

<script>
window.FoodTruckTheme = (function(global) {
  var exprt = {};

  (function($) {
    var mobileNavOpen = false;
    var $body = $();
    var $navItems = $();
    //var $movNavItemWrapper = $();
    var $mobNavContainer = $();

    function cloneNavItemsToMobileContainer() {
      var $container = $mobNavContainer.clone();
      var $items = $navItems.clone();

      $container.empty().append($items);
      //$items.wrap($movNavItemWrapper);

      $mobNavContainer.replaceWith($container);

      $mobNavContainer = $container;
    }

    function toggleMobileNav() {
      mobileNavOpen || cloneNavItemsToMobileContainer();

      mobileNavOpen = !mobileNavOpen;

      $body.toggleClass('site--nav-open', mobileNavOpen);
    }

    exprt.navReady = function() {
      $body = $('body');
      $navItems = $('.navigation_items_links .menu-item');
      //$movNavItemWrapper = $('.navigation_mob_items');
      $mobNavContainer = $('.navigation_mob_items');//$movNavItemWrapper.parent();
      //$movNavItemWrapper = $movNavItemWrapper.clone();

      $('.navigation-toggle').on('click', toggleMobileNav);
    }
  })(window.jQuery);

  (function($) {
    var $body = $();
    var windowWidth = Infinity;
    var breakPoint = Infinity;

    var onResize = function() {
      windowWidth = window.innerWidth || $(window).width();

      $body.toggleClass('site--nav-mobile', windowWidth <= breakPoint);

      console.log('resize', windowWidth);
    }

    exprt.mobileNavAt = function(newBreakpoint) {
      breakPoint = ~~newBreakpoint;
      console.log('updated breakpoint', newBreakpoint);
    }

    exprt.bodyReady = function() {
      $body = $('body');

      onResize();

      $(window).on('resize', onResize);
    }
  })(window.jQuery);

  return exprt;
})(this);


</script>

</head>
<body <?php body_class(); ?>>
<script>FoodTruckTheme.mobileNavAt(<?php echo absint(get_theme_mod('ftt_theme_mod_nav_breakpoint')) ?: 1200; ?>); FoodTruckTheme.bodyReady();</script>

<header class="navigation" role="navigation">
  <nav class="navigation_items">
    <ul class="navigation_items_links menu-top-left-container">
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
    <div class="navigation_items_logo">
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
          <div class="logo_text"><?php echo get_bloginfo('name', 'display'); ?></div>
        <?php endif; ?>
      </a>
    </div>
    <ul class="navigation_items_links menu-top-right-container">
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
      <li class="navigation_items_links_toggle">
        <div class="navigation-toggle"><i></i></div>
      </li>
    </ul>
  </nav>
  <nav class="navigation_mob">
    <ul class="navigation_mob_items menu-top-mobile-container">
      <li class="navigation_mob_items_item"></li>
    </ul>
  </nav>
</header>
<script>FoodTruckTheme.navReady();</script>

<!-- start main. contains page specific content -->
<main role="main">
