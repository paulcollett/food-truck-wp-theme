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
      $navItems = $('.mainnav_items_links .menu-item');
      //$movNavItemWrapper = $('.mainnav_mob_items');
      $mobNavContainer = $('.mainnav_mob_items');//$movNavItemWrapper.parent();
      //$movNavItemWrapper = $movNavItemWrapper.clone();

      $('.mainnav-toggle').on('click', toggleMobileNav);
    }
  })(window.jQuery);

  (function($) {
    var $body = $();
    var windowWidth = Infinity;
    var breakPoint = Infinity;

    var onResize = function() {
      windowWidth = window.innerWidth || $(window).width();

      $body.toggleClass('site--nav-mobile', windowWidth <= breakPoint);
    }

    exprt.mobileNavAt = function(newBreakpoint) {
      breakPoint = ~~newBreakpoint;
    }

    exprt.bodyReady = function() {
      $body = $('body');

      onResize();

      $(window).on('resize', onResize);
    }
  })(window.jQuery);

  return exprt;
})(this);
