
<?php

class FoodTruckTheme
{
  function __construct() {
    // Hooks
    add_action('wp_enqueue_scripts', array($this, 'manage_frontend_assets'));
    add_action('widgets_init', array($this, 'widgets_init'));
    add_editor_style(array('assets/editor-style.css'));

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(array(
      'top-left'    => __('Main Menu: Left of Logo', 'food-truck'),
      'top-right'    => __('Main Menu: Right of Logo', 'food-truck')
    ));

    // Load Customization Options for Theme
    require get_parent_theme_file_path( '/inc/customization.php' );

    // Load Navigation Helpers
    require get_parent_theme_file_path( '/inc/navigation-helpers.php' );

    // Load Functions used in the templates
    require get_parent_theme_file_path( '/inc/template-tags.php' );

    // Initiate Customization Options
    new FoodTruckThemeCustomization();
  }

  function manage_frontend_assets() {
    $theme_version = wp_get_theme()->get('Version');

    // Add assets to wp_head
    wp_enqueue_style('food-truck-theme', get_parent_theme_file_uri('style.css'), null, $theme_version, 'all');
    wp_enqueue_script('food-truck-theme', get_parent_theme_file_uri('/assets/site.js'), array('jquery'), $theme_version, false);

    // Add comments reply script
    if ( is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script( 'comment-reply' );
    }
  }

  function widgets_init() {
    register_sidebar( array(
      'name'          => __('Footer Widgets', 'food-truck'),
      'id'            => 'footer-widgets',
      'description'   => __('Add widgets here to appear at the bottom of all pages.', 'food-truck'),
      'before_widget' => '<section id="%1$s" class="widgets-layout_widget %2$s"><div class="widgets-layout_widget_contain"><div class="content">',
      'after_widget'  => '</section>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    ));
  }
}

new FoodTruckTheme();
