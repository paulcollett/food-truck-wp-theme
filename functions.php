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
    wp_enqueue_style(FTT_TEXT_DOMAIN, get_parent_theme_file_uri('/assets/dist/css/site.css'), null, null, 'all');
    wp_enqueue_script(FTT_TEXT_DOMAIN, get_parent_theme_file_uri('/assets/dist/js/site.js'), array('jquery'), null, true);

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
    add_action( 'customize_register', array($this, 'init_options') );
  }

  function theme_support() {
    // Custom Logo
    add_theme_support( 'custom-logo', array(
      'height'      => 220,
      'width'       => 500,
      'flex-height' => true,
      'flex-width'  => true,
      'header-text' => array( 'site-title', FTT_TEXT_DOMAIN ),
    ));

    /*
    * Let WordPress manage and provide the document title. WP 4.1+
    */
    add_theme_support( 'title-tag' );

    // add html5 markup for the following features
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

    // support thumbnails for post listing
    add_theme_support( 'post-thumbnails', array( 'post' ) );

    // Add feed support to site WP3.0+
    add_theme_support( 'automatic-feed-links' );

    // Custom Widgets WP4.7+
    add_action('after_setup_theme', function () {
      // default starter content
      //get_theme_starter_content() // https://developer.wordpress.org/reference/functions/get_theme_starter_content/
      add_theme_support( 'starter-content', $this->get_starter_content());
    });

    $defaults = array(
      //'default-image' => '',
      //'default-preset' => 'default',
      //'default-position-x' => 'left',
      //'default-position-y' => 'top',
      //'default-size' => 'auto',
      //'default-repeat' => 'repeat',
      //'default-attachment' => 'scroll',
      'default-color' => '#fff',
      //'wp-head-callback' => '_custom_background_cb',
      //'admin-head-callback' => '',
      //'admin-preview-callback' => '',
    );
    add_theme_support( 'custom-background', $defaults );
  }

  function init_options($wp_customize) {

    $wp_customize->add_control( 'colorscheme', array(
      'type'    => 'radio',
      'label'    => __( 'Color Scheme', 'twentyseventeen' ),
      'choices'  => array(
        'light'  => __( 'Light', 'twentyseventeen' ),
        'dark'   => __( 'Dark', 'twentyseventeen' ),
        'custom' => __( 'Custom', 'twentyseventeen' ),
      ),
      'section'  => 'colors',
      'priority' => 5,
    ) );

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



