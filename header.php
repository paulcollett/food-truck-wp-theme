<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="assets/dist/css/site.css">

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
<script>FoodTruckTheme.mobileNavAt(window.FoodTruckTheme_MobNavAt || 1200); FoodTruckTheme.bodyReady();</script>

<?php //if ( has_nav_menu( 'top' ) ) { ?>

<header class="navigation" role="navigation">
  <nav class="navigation_items">

    <ul class="navigation_items_links menu-top-left-container">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'top-left',
          'menu_id'        => 'top-menu-left',
          'depth' => 1,
          'container' => false,//'div',
          //'container_class' => 'navigation_items_links menu-{menu slug}-container',
          //'walker' => new WPDocs_Walker_Nav_Menu(), // <div class="navigation_items_links_item"><a class="nav-link" href="" data-dummy="1,3"></a></div>
          'items_wrap' => '%3$s',//'<div class="navigation_items_links_item"><a class="nav-link" href=""'
          'fallback_cb' => false, //'wp_page_menu'
        ));
      ?>
    </ul>

    <!--
      <div class="navigation_items_links_item"><a class="nav-link nav-link--active" href="" data-dummy="1,2"></a></div>
      <div class="navigation_items_links_item"><a class="nav-link" href="" data-dummy="1,2"></a></div>
    -->

    <div class="navigation_items_logo">
      <div class="logo">
        <img src="" data-dummy="100,300x100,300" />
      </div>
    </div>

    <ul class="navigation_items_links menu-top-right-container">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'top-right',
          'menu_id'        => 'top-menu-right',
          'depth' => 1,
          'container' => false,//'div',
          //'container_class' => 'navigation_items_links menu-{menu slug}-container',
          //'walker' => new WPDocs_Walker_Nav_Menu(), // <div class="navigation_items_links_item"><a class="nav-link" href="" data-dummy="1,3"></a></div>
          'items_wrap' => '%3$s',//'<div class="navigation_items_links_item"><a class="nav-link" href=""'
          'fallback_cb' => false, //'wp_page_menu'
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
