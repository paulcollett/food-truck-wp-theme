<?php


// Namespace FoodTruckTheme & FTT

define('FTT_TEXT_DOMAIN', 'food-truck-theme');

//require get_parent_theme_file_path( '/inc/custom-header.php' );
//get_parent_theme_file_uri( '/assets/images/header.jpg' )
//get_template_directory_uri() . '/assets/dist/css/site.css'


//RECOMMENDED: No reference to add_editor_style()

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

    $this->manage_theme_helpers();

    // Possible future feature
    //$this->add_default_bg_support();

    add_action('customize_register', array($this, 'init_options'));

    // Populate initial Food Truck Data WP4.7+
    add_action('after_setup_theme', function () {
      // default starter content
      // https://developer.wordpress.org/reference/functions/get_theme_starter_content/
      // get_theme_starter_content()
      //add_theme_support('starter-content', $this->get_starter_content());
    });

    add_action('wp_head', array($this, 'output_css'));
  }

  function manage_feature_support() {
    // Custom Logo
    add_theme_support('custom-logo', array(
      'height'      => 220,
      'width'       => 500,
      'flex-height' => true,
      'flex-width'  => true,
      'header-text' => array( 'site-title' ),
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
    // Clean up defaults
    $wp_customize->get_section('title_tagline')->title = 'Logo & Icons';
    $wp_customize->get_section('static_front_page')->title = 'Home & Blog Pages';
    $wp_customize->get_section('static_front_page')->priority = 20;
    $wp_customize->add_panel('ftt_panel_advanced', array(
      'title'    => __('Advanced', FTT_TEXT_DOMAIN),
      'description' => 'Stuff that you can change',
      'priority' => 200,
    ));
    $wp_customize->get_section('custom_css')->panel = 'ftt_panel_advanced';

    // Home Page Options
    $wp_customize->add_section('ftt_section_home', array(
      'title'    => __('Homepage Panels', FTT_TEXT_DOMAIN),
      'description' => 'Stuff that you can change',
      'priority' => 30,
    ));

    $this->add_home_options($wp_customize, 'ftt_section_home');

    // Add colors options
    $wp_customize->add_section('ftt_section_colors', array(
      'title'    => __('Colors', FTT_TEXT_DOMAIN),
      'description' => 'Stuff that you can change',
      'priority' => 30,
    ));

    $this->add_colors_options($wp_customize, 'ftt_section_colors');

    // Add font options
    $wp_customize->add_section('ftt_fonts', array(
      'title'    => __('Fonts', FTT_TEXT_DOMAIN),
      'description' => 'Stuff that you can change',
      'priority' => 30,
    ));

    $this->add_font_options($wp_customize, 'ftt_fonts');

    // Other Misc Options
    $wp_customize->add_section('ftt_section_footer', array(
      'title'    => __('Footer Text', FTT_TEXT_DOMAIN),
      'description' => 'Stuff that you can change',
      'priority' => 10,
      'panel' => 'ftt_panel_advanced'
    ));

    $this->add_footer_options($wp_customize, 'ftt_section_footer');

    $wp_customize->add_section('ftt_section_mobnav_bp', array(
      'title'    => __('Mobile Navigation Breakpoint', FTT_TEXT_DOMAIN),
      'description' => 'Show the mobile navigation when the browser width is how many pixels:',
      'priority' => 20,
      'panel' => 'ftt_panel_advanced'
    ));

    $this->add_breakpoint_options($wp_customize, 'ftt_section_mobnav_bp');
  }

  function add_colors_options($wp_customize, $section_id) {
    // OPTION
    $option_id = 'ftt_theme_mod_color_scheme';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => 'palm',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod', // Alt: 'option' get_option()
    ));
    $wp_customize->add_control($control_id, array(
      'type'    => 'radio',
      'label'    => __( 'Pre-Designed Color Schemes', FTT_TEXT_DOMAIN),
      'choices'  => array(
        'del-mar'  => __('Del Mar', FTT_TEXT_DOMAIN),
        'cupcake' => __('Cupcake', FTT_TEXT_DOMAIN),
        'fries' => __('Fries', FTT_TEXT_DOMAIN),
        'tokyo' => __('Tokyo', FTT_TEXT_DOMAIN),
        'health' => __('Health', FTT_TEXT_DOMAIN),
        'waves' => __('Waves', FTT_TEXT_DOMAIN),
        'modern'   => __('Modern', FTT_TEXT_DOMAIN),
        'grill' => __('Grill', FTT_TEXT_DOMAIN),
        'custom' => __('or, Custom', FTT_TEXT_DOMAIN),
      ),
      'section'  => $section_id,
      'settings'   => $option_id,
      'priority' => 5,
    ));

    $color_scheme_options_id = $option_id;

    $is_custom_scheme = function($control) use ($color_scheme_options_id) {
      return $control->manager->get_setting($color_scheme_options_id)->value() == 'custom';
    };

    // OPTION
    $option_id = 'ftt_theme_mod_brand_color';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => '#888888',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_id, array(
      'label'      => __( 'Brand Color', FTT_TEXT_DOMAIN),
      'section'    => $section_id,
      'settings'   => $option_id,
      'active_callback' => $is_custom_scheme
    )));

    // OPTION
    $option_id = 'ftt_theme_mod_text_color';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => '#444444',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_id, array(
      'label'      => __( 'Text Colour', FTT_TEXT_DOMAIN),
      'section'    => $section_id,
      'settings'   => $option_id,
      'active_callback' => $is_custom_scheme
    )));

    // OPTION
    $option_id = 'ftt_theme_mod_background_color';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => '#ffffff',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_id, array(
      'label'      => __( 'Background Color', FTT_TEXT_DOMAIN),
      'section'    => $section_id,
      'settings'   => $option_id,
      'active_callback' => $is_custom_scheme
    )));
  }

  function add_home_options($wp_customize, $section_id) {
    // Move built-in to new section
    $wp_customize->get_control('header_text')->section = $section_id;
    $wp_customize->get_control('header_text')->label = 'Display Tagline Banner on Homepage';
    $wp_customize->get_control('header_text')->priority = 0;

    $wp_customize->get_control('blogdescription')->section = $section_id;

    $option_id = 'ftt_theme_mod_tagline_text';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => 'Spreading the love for all things tasty, our sustainably run food truck strives to bring fresh, vibrant and carefully handpicked ingredients to your plate.',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
    ));
    $wp_customize->add_control($control_id, array(
      'type' => 'textarea',
      'label'      => __('About paragraph', FTT_TEXT_DOMAIN),
      'section'    => $section_id,
      'settings'   => $option_id,
    ));

    $option_id = 'ftt_theme_mod_tagline_image';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      //'default'        => 'bungee',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $control_id, array(
      'label'      => __( 'Background Image', 'theme_name' ),
      'section'    => $section_id,
      'settings'   => $option_id,
    )));

    $option_id = 'ftt_theme_mod_tagline_bgtint';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => true,
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
    ));
    $wp_customize->add_control($control_id, array(
      'type' => 'checkbox',
      'label'      => __( 'Tint Background Image', 'theme_name' ),
      'section'    => $section_id,
      'settings'   => $option_id,
    ));
  }

  function add_font_options($wp_customize, $section_id) {
    // OPTION
    $option_id = 'ftt_theme_mod_font_scheme';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => 'bungee',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
    ));
    $wp_customize->add_control($control_id, array(
      'type'    => 'radio',
      'label'    => __( 'Pre-Designed Font Pairings', FTT_TEXT_DOMAIN),
      'choices'  => array(
        'bungee' => __('Bungee', FTT_TEXT_DOMAIN),
        'la-mex' => __('La Mex', FTT_TEXT_DOMAIN),
        'impact' => __('Impact', FTT_TEXT_DOMAIN),
        'samari' => __('Samari', FTT_TEXT_DOMAIN),
        'leaf' => __('Leaf', FTT_TEXT_DOMAIN),
        'modern' => __('Modern', FTT_TEXT_DOMAIN),
        'icing' => __('Icing', FTT_TEXT_DOMAIN),
        'black-ops' => __('Black Ops', FTT_TEXT_DOMAIN),
        'diner' => __('Diner', FTT_TEXT_DOMAIN),
        'comic' => __('Comic', FTT_TEXT_DOMAIN),
      ),
      'section'  => $section_id,
      'settings'   => $option_id,
      'priority' => 5,
    ));
  }

  function add_footer_options($wp_customize, $section_id) {
    // OPTION
    $option_id = 'ftt_theme_mod_footer_text';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => '[copyright] [year] [name]',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod'
    ));
    $wp_customize->add_control($control_id, array(
        'label'      => __('Footer Text', FTT_TEXT_DOMAIN),
        'section'    => $section_id,
        'settings'   => $option_id,
    ));
  }

  function add_breakpoint_options($wp_customize, $section_id) {
    // OPTION
    $option_id = 'ftt_theme_mod_nav_breakpoint';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => 1200,
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod'
    ));
    $wp_customize->add_control($control_id, array(
        'type'    => 'number',
        'label'      => __('Breakpoint in pixels', FTT_TEXT_DOMAIN),
        'section'    => $section_id,
        'settings'   => $option_id,
    ));
  }

  function output_css() {
    $css_rule = function($selector, $rules = array()) {
      $rules = array_map(function($value, $rule) {
        return $rule . ': ' . $value;
      }, array_values($rules), array_keys($rules));

      return sprintf('%s { %s }', $selector, implode("; ", $rules));
    };

    $color_scheme_name = get_theme_mod('ftt_theme_mod_color_scheme', 'tropical');
    $font_scheme_name = get_theme_mod('ftt_theme_mod_font_scheme', 'tropical');

    $color_schemes_available = array(
      'del-mar' => array('#599977', '#7d3b41', '#FFF9DA'),
      'cupcake' => array('#ea45a7', '#6f336e', '#f4bfca'),
      'modern' => array('#777', '#333', '#fff'),
      'waves' => array('#00A2FF', '#fff', '#001866'),
      'fries' => array('#F2772D', '#45371A', '#fff'),
      'health' => array('#aad24a', '#675c4b', '#fff'),
      'tokyo' => array('#EB3030', '#ddd', '#222'),
      'grill' => array('#c58b44', '#FFE8B8', '#382C12'),
      'custom' => array(
        get_theme_mod('ftt_theme_mod_brand_color', '#999'),
        get_theme_mod('ftt_theme_mod_text_color', '#999'),
        get_theme_mod('ftt_theme_mod_background_color', '#fff')
      )
    );
    $font_schemes_available = array(
      'bungee' => array('Bungee', 'Overpass:300'),
      'la-mex' => array('Sancreek', 'Lato'),
      'impact' => array('Anton', 'Barlow Semi Condensed'),//Tinos
      'samari' => array('Shojumaru', 'PT Serif'),//Vollkorn
      'leaf' => array('Vesper Libre:900', 'Zilla Slab'),//Karla
      'modern' => array('Overpass Mono', 'Overpass Mono'),//Montserrat:300
      'icing' => array('Leckerli One', 'ABeeZee'),//Pacifico //Dosis
      'black-ops' => array('Black Ops One', 'Titillium Web'),//Monda
      'diner' => array('Fontdiner Swanky', 'Josefin Sans'),//Fira Sans:300
      'comic' => array('Bangers', 'Inconsolata'),
    );


    $color_scheme_name = isset($color_schemes_available[$color_scheme_name]) ? $color_scheme_name : 'tropical';
    $font_scheme_name = isset($font_schemes_available[$font_scheme_name]) ? $font_scheme_name : 'strong';
    $color_scheme = $color_schemes_available[$color_scheme_name];
    $font_scheme_query = str_replace(' ', '+', implode('|', $font_schemes_available[$font_scheme_name]));
    $font_scheme_stack = array_map(function($v) {
      return '"' . (explode(':', $v)[0]) . '", sans-serif';
    }, $font_schemes_available[$font_scheme_name]);

    echo implode("\n", array(
      sprintf('<!-- Customized Food Truck Theme CSS (%s, %s) --> ', $color_scheme_name, $font_scheme_name),
      '<link href="https://fonts.googleapis.com/css?family=' . $font_scheme_query . '" rel="stylesheet">',
      '<style>',
      $css_rule('body', array(
        'color' => $color_scheme[1],
        'background-color' => $color_scheme[2],
        'font-family' => $font_scheme_stack[1]
      )),
      $css_rule('.mainnav, .ftt-banner-tagline-text_line', array(
        'color' => $color_scheme[0],
        'background-color' => $color_scheme[2]
      )),
      $css_rule('.mainnav_mob, .site--nav-open .mainnav-toggle', array(
        'color' => $color_scheme[2]
      )),
      $css_rule('html, .mainnav_mob', array(
        'background-color' => $color_scheme[0]
      )),
      $css_rule('.ftt-banner-tagline', array(
        'color' => $color_scheme[0],
      )),
      get_theme_mod('ftt_theme_mod_tagline_image', '') ? $css_rule('.ftt-banner-tagline', array(
        'background-image' => sprintf('url(%s)', get_theme_mod('ftt_theme_mod_tagline_image', ''))
      )) : '',
      $css_rule('.mainnav, .sub-pages, h1, h2, h3, h4, h5, .btn, .button, button, *[type=submit], .submit', array(
        'font-family' => $font_scheme_stack[0]
      )),
      $css_rule('.logo_text, .btn, .button, button, *[type=submit], .submit', array(
        'color' => $color_scheme[2],
        'background-color' => $color_scheme[0]
      )),
      $css_rule('.widgets-layout_widget', array(
        'color' => $color_scheme[1],
        'background-color' => $color_scheme[0],
      )),
      $css_rule('.widgets-layout_widget:nth-child(4n + 2), .widgets-layout_widget:nth-child(4n + 3)', array(
        'color' => $color_scheme[0].'!important',
        'background-color' => $color_scheme[1].'!important',
      )),
      $css_rule('.widgets-layout_widget .btn, .widgets-layout_widget .button, .widgets-layout_widget button, .widgets-layout_widget *[type=submit], .widgets-layout_widget .submit', array(
        'color' => $color_scheme[1] .'!important',
        'background-color' => $color_scheme[2].'!important'
      )),
      '</style>'
    ));
  }

  function manage_theme_helpers() {
    // Helpers to allow adjusting the theme content container on a per text basis
    if(!shortcode_exists('no_container')) {
      add_shortcode('no_container', function($a = array(), $c = '') {
        return sprintf('</div></section><div class="content">%s</div><section class="contain contain--md ptmd mbmd"><div class="content">', do_shortcode($c));
      });
    }

    if(!shortcode_exists('large_container')) {
      add_shortcode('large_container', function($a = array(), $c = '') {
        return sprintf('</div></section><section class="contain contain--lg ptmd mbmd"><div class="content">%s</div></section><section class="contain contain--md ptmd mbmd"><div class="content">', do_shortcode($c));
      });
    }
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
/*
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
*/
/*
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
    );*/
/*
    $content['theme_mods'] = array(
      'panel_1' => '{{homepage-section}}',
			'panel_2' => '{{about}}',
			'panel_3' => '{{blog}}',
      'panel_4' => '{{contact}}',
      'page_layout' => 'one-column'
    );*/

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
        'theme_initial_widget_1' => array('text', array(
          'title' => 'Join the mailing list',
          'text' => 'This is a good sport for a sign up form. Try a Mailchimp plugin'
        ))
        //'theme_initial_widget_1' => array('text', array(
        //  'title' => 'Join the mailing list',
        //  'text' => 'This is a good sport for a sign up form. Try a Mailchimp plugin'
        //)),
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

  function get_pages_of_root_parent() {
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

    $current_page_id = isset($GLOBALS['wp_the_query']->query['page_id'])
      ? $GLOBALS['wp_the_query']->query['page_id']
      : get_the_ID();

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
