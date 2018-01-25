<?php


// Namespace FoodTruckTheme & FTT

define('FTT_TEXT_DOMAIN', 'food-truck-theme');

//require get_parent_theme_file_path( '/inc/custom-header.php' );
//get_parent_theme_file_uri( '/assets/images/header.jpg' )
//get_template_directory_uri() . '/assets/dist/css/site.css'

class FoodTruckTheme {
  function __construct() {
    // Hooks
    add_action('wp_enqueue_scripts', array($this, 'manage_frontend_assets'));
    add_action('widgets_init', array($this, 'widgets_init'));

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(array(
      'top-left'    => __('Main Menu: Left of Logo', FTT_TEXT_DOMAIN),
      'top-right'    => __('Main Menu: Right of Logo', FTT_TEXT_DOMAIN)
    ));

    //
    new FoodTruckThemeCustomization();
  }

  function manage_frontend_assets() {
    wp_enqueue_style(FTT_TEXT_DOMAIN, get_parent_theme_file_uri('/assets/dist/css/site.css'), null, wp_get_theme()->get( 'Version' ), 'all');
    wp_enqueue_script(FTT_TEXT_DOMAIN, get_parent_theme_file_uri('/assets/dist/js/site.js'), array('jquery'), wp_get_theme()->get( 'Version' ), true);

    if ( is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script( 'comment-reply' );
    }
  }

  function widgets_init() {
    register_sidebar( array(
      'name'          => __('Footer Widgets', FTT_TEXT_DOMAIN),
      'id'            => 'footer-widgets',
      'description'   => __('Add widgets here to appear at the bottom of all pages.', FTT_TEXT_DOMAIN),
      'before_widget' => '<section id="%1$s" class="widgets-layout_widget %2$s"><div class="widgets-layout_widget_contain"><div class="content">',
      'after_widget'  => '</section>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    ));
  }
}

new FoodTruckTheme();



//get_theme_starter_content()

class FoodTruckThemeCustomization {
  function __construct() {
    $this->manage_feature_support();

    // Possible future feature
    //$this->add_default_bg_support();

    add_action('customize_register', array($this, 'init_options'));

    // Populate initial Food Truck Data WP4.7+
    add_action('after_setup_theme', function () {
      // default starter content
      // https://developer.wordpress.org/reference/functions/get_theme_starter_content/
      // get_theme_starter_content()
      add_theme_support( 'starter-content', $this->get_starter_content());
    });
  }

  function manage_feature_support() {
    // Custom Logo
    add_theme_support('custom-logo', array(
      'height'      => 220,
      'width'       => 500,
      'flex-height' => true,
      'flex-width'  => true,
      'header-text' => array( 'site-title', FTT_TEXT_DOMAIN),
    ));

    /*
    * Let WordPress manage and provide the document title. WP 4.1+
    */
    add_theme_support('title-tag');

    // add html5 markup for the following features
    add_theme_support('html5', array('comment-list', 'comment-form'));

    // support thumbnails for post listing
    add_theme_support('post-thumbnails', array('post'));

    // Add feed support to site (needed for wordpress.org) WP3.0+
    add_theme_support('automatic-feed-links');
  }

  function add_default_bg_support() {
    $defaults = array(
      'default-image' => '',
      'default-preset' => 'default',
      'default-position-x' => 'left',
      'default-position-y' => 'top',
      'default-size' => 'auto',
      'default-repeat' => 'repeat',
      'default-attachment' => 'scroll',
      'default-color' => '#fff',
      'wp-head-callback' => '_custom_background_cb',
      'admin-head-callback' => '',
      'admin-preview-callback' => '',
    );

    add_theme_support( 'custom-background', $defaults );
  }

  function init_options($wp_customize) {
    // Section: default colors
    $this->add_colors_options($wp_customize);

    $wp_customize->add_panel( 'front_page_panel', array(
      'title' => 'Front Page Stuff',
      'description' => 'Stuff that you can change about the Front Page',
      'priority' => 10,
      'active_callback' => 'is_front_page',
    ));

    $wp_customize->add_section( 'themedemo_panel_settings', array(
      'title' => 'More Stuff',
      'priority' => 10,
      'panel' => 'front_page_panel',
    ));

    $wp_customize->add_setting( 'demo_radio_control', array(
      'default'        => 'a',
    ));

    $wp_customize->add_control( 'demo_radio_control', array(
      'label'      => 'radio_control',
      'section'    => 'themedemo_panel_settings',
      'settings'   => 'demo_radio_control',
      'type'       => 'radio',
      'choices'    => array(
      'a' => 'Choice A',
      'b' => 'Choice B',
      ),
    ));

  }

  function add_colors_options($wp_customize) {

    $wp_customize->add_control('colorscheme', array(
      'type'    => 'radio',
      'label'    => __( 'Color Scheme', 'twentyseventeen' ),
      'choices'  => array(
        'light'  => __( 'Light', 'twentyseventeen' ),
        'dark'   => __( 'Dark', 'twentyseventeen' ),
        'custom' => __( 'Custom', 'twentyseventeen' ),
      ),
      'section'  => 'colors',
      'priority' => 5,
    ));

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
      'label'      => __( 'Header Color', 'mytheme' ),
      'section'    => 'your_section_id',
      'settings'   => 'your_setting_id',
    )));
  }


  function _todo() {


  }

  function get_starter_content() {
    $content = array();

    $content['attachments'] = array(
        'featured-image-logo' => array(
          'post_title' => 'Featured Logo',
          'post_content' => 'Attachment Description',
          'post_excerpt' => 'Attachment Caption',
          'file' => 'assets/images/featured-logo.jpg',
        ),
      );

    $content['posts'] = array(
      'about' => array(
          // Use a page template with the predefined about page
          'template' => 'sample-page-template.php',
      ),
      'custom' => array(
          'post_type' => 'post',
          'post_title' => 'Custom Post',
          'thumbnail' => '{{featured-image-logo}}',
          'post_content' => 'About services'
      ),
    );

    $content['nav_menus'] = array(
      'top' => array(
        'name' => __( 'Top Menu', 'twentyseventeen' ),
        'items' => array(
          'page_home',
          'page_about',
          'page_blog',
          'page_contact',

          // Our Custom Services page
          'page_service' => array(
            'type' => 'post_type',
            'object' => 'page',
            'object_id' => '{{services}}',
          ),
        ),
      ),
      /*'social' => array(
        'name' => __( 'Social Links Menu', 'twentyseventeen' ),
        'items' => array(
          'link_yelp',
          'link_facebook',
          'link_twitter',
          'link_instagram',
          'link_email',
        ),
      ),*/
    );

    $content['theme_mods'] = array(
      'panel_1' => '{{homepage-section}}',
			'panel_2' => '{{about}}',
			'panel_3' => '{{blog}}',
      'panel_4' => '{{contact}}',
      'page_layout' => 'one-column'
    );

    $content['options'] = array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}', // file_get_contents(get_template_directory_uri() . 'content/pages/about.html')
			// Our Custom
      //'blogdescription' => 'My Awesome Blog'
      //permalink_structure
      //posts_per_page
		);

    $content['widgets'] = array(
      'footer' => array(
        'meta_custom' => array( 'meta', array(
          'title' => 'Pre-hydrated meta widget.',
        )),
      ),
    );

    /*
        // Plugin widget added using filters
    function myprefix_starter_content_add_widget( $content, $config ) {
      if ( isset( $content['widgets']['sidebar-1'] ) ) {
          $content['widgets']['sidebar-1']['a_custom_widget'] = array(
              'my_custom_widget', array(
                  'title' => 'A Special Plugin Widget',
              ),
          );
      }
      return $content;
    }
    add_filter( 'get_theme_starter_content', 'myprefix_starter_content_add_widget', 10, 2 );
    */

  }

}



class FoodTruckThemeNavigation {
  static $instance = null;

  static function instance() {
    if(!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  function get_pages_of_root_parent(){
    $page_id = get_the_ID();

    $return_parent = true;

    if($this->is_using_wp_menus_for_nav()) {
      return array_merge(
        $this->get_pages_of_root_parent_in_a_wp_menu($page_id, 'top-left', $return_parent) ?: array(),
        $this->get_pages_of_root_parent_in_a_wp_menu($page_id, 'top-right', $return_parent) ?: array()
      );
    } else {
      return $this->get_pages_of_root_parent_in_post_pages($page_id, $return_parent);
    }
  }

  function is_using_wp_menus_for_nav() {
    /*
    if(isset($_POST['customized'])){
      $show_custom_menu = @array_filter(json_decode(wp_unslash($_POST['customized']), true));
    } else {
      $theme_menu_locations = get_nav_menu_locations();
      $show_custom_menu = isset($theme_menu_locations['main']) && get_objects_in_term( $theme_menu_locations['main'], 'nav_menu' );
    }*/
    return has_nav_menu('top-right') || has_nav_menu('top-left');
  }

  function get_pages_of_root_parent_in_a_wp_menu($page_id, $menu_id, $include_parent = false) {
    $theme_menu_locations = get_nav_menu_locations();

    if(!isset($theme_menu_locations[$menu_id])) {
      return false;
    }

    //Retrieves all menu items of a navigation menu
    $menu_items = wp_get_nav_menu_items($theme_menu_locations[$menu_id], array(
      'update_post_term_cache' => false
    ));

    // If theres no menu items, $menu_items will be false
    $menu_items = $menu_items ?: array();

    $menu_items_by_children = array();
    $menu_items_by_id = array();
    $current_page_nav = false;

    foreach($menu_items as $menu_item) {
      if($menu_item->object_id == $page_id) {
        $current_page_nav = $menu_item;
      }

      $menu_items_by_id[$menu_item->ID] = $menu_item;

      if(!isset($menu_items_with_children[ $menu_item->menu_item_parent ])) {
        $menu_items_with_children[ $menu_item->menu_item_parent ] = array();
      }

      $menu_items_with_children[ $menu_item->menu_item_parent ][$menu_item->menu_order] = $menu_item;
    }

    if(!$current_page_nav) {
      return false;
    }

    $found_root_item = false;
    $current_loop_item = $current_page_nav;

    while (!$found_root_item) {
      if($current_loop_item->menu_item_parent && isset($menu_items_by_id[ $current_loop_item->menu_item_parent ])) {
        // following line should not be needed, but it's just a failsafe
        if($current_loop_item->ID == $menu_items_by_id[ $current_loop_item->menu_item_parent ]->ID) $found_root_item = true;
        $current_loop_item = $menu_items_by_id[ $current_loop_item->menu_item_parent ];
      } else {
        $found_root_item = true;
      }
    }

    $sub_pages = $include_parent ? array($current_loop_item) : array();
    $found_child_item = false;

    $this->_walk_children_of_custom_menu($current_loop_item, $menu_items_with_children, $sub_pages);

    $children = array();

    foreach ($sub_pages as $item) {
      $url = in_array($item->object, array('page','post')) ? get_permalink($item->object_id) : $item->url;

      $children[] = array(
        'id' => $item->ID,
        'active' => $current_page_nav->ID == $item->ID,
        'title' => $item->title,
        'url' => $url
      );
    }

    return $children;
  }

  function _walk_children_of_custom_menu($current_menu_item, $menu_items_by_parent, &$sub_pages) {
    if(isset($menu_items_by_parent[$current_menu_item->ID]) && $menu_items_by_parent[$current_menu_item->ID]) {
      ksort($menu_items_by_parent[$current_menu_item->ID], SORT_NUMERIC);

      foreach ($menu_items_by_parent[$current_menu_item->ID] as $item) {
        $sub_pages[] = $item;
        $this->_walk_children_of_custom_menu($item, $menu_items_by_parent, $sub_pages);
      }
    }
  }

  function get_pages_of_root_parent_in_post_pages($page_id, $include_parent = false) {
    $root_page = array_slice(get_ancestors( $page_id, 'page' ), -1, 1);
    $root_page = isset($root_page[0]) ? (int) $root_page[0] : $page_id;

    $sub_pages = get_pages(array(
      'child_of' => $root_page,
      'orderby' => 'menu_order'
    ));

    if($include_parent) {
      array_unshift($sub_pages, get_page($root_page));
    }

    $children = array();

    foreach ($sub_pages as $item) {
      $children[] = array(
        'id' => $item->ID,
        'active' => $page_id == $item->ID,
        'title' => $item->post_title,
        'url' => get_permalink($item->ID)
      );
    }

    return $children;
  }

  function output_wp_menu_fallback($menu_id) {
    if($this->is_using_wp_menus_for_nav()) {
      return;
    }

    $current_page_id = get_the_ID();

    $pages = get_pages(array(
      'parent' => 0,
      'orderby' => 'menu_order'
    )) ?: array();

    if($menu_id === 'top-menu-left') {
      $pages = array_slice($pages, 0, ceil(count($pages) / 2));
    } else if($menu_id === 'top-menu-right') {
      $pages = array_slice($pages, ceil(count($pages) / 2));
    }

    echo implode("\n", array_map(function($page) use ($current_page_id) {
      $class_list = sprintf('menu-item menu-item-type-post_type menu-item-object-page page_item page-item-%1$s menu-item-%1$s', $page->ID);

      // Note, wont display as current when on child pages
      if($current_page_id == $page->ID) {
        $class_list .= ' current-menu-item current_page_item';
      }

      return sprintf('<li id="menu-item-%s" class="%s"><a href="%s" target="_self">%s</a></li>',
        $page->ID,
        $class_list,
        get_permalink($page->ID),
        esc_html($page->post_title)
      );
    }, $pages));
  }
}

function ftt_get_pages_of_root_parent() {
  return FoodTruckThemeNavigation::instance()->get_pages_of_root_parent();
}

function ftt_no_wp_top_menu_fallback($args) {
  echo FoodTruckThemeNavigation::instance()->output_wp_menu_fallback($args['menu_id']);
}

