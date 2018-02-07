<?php

class FoodTruckThemeCustomization {
  function __construct() {
    // Register features supported by this theme
    $this->manage_feature_support();

    // Make theme available for translation
    load_theme_textdomain('food-truck');

    // Setup Customizer with theme options
    add_action('customize_register', array($this, 'init_options'));

    // Suggest suitable starter content typical of Food Truck websites (WP4.7+)
    // Note: WP Bug? Conflicts with other themes content when theme-switching in Customizer
    $starter_content = apply_filters('ftt_starter_content', $this->get_starter_content());

    if($starter_content) {
      add_action('after_setup_theme', function() {
        add_theme_support('starter-content', $starter_content);
      });
    }

    // Output customization css
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

    // Let WordPress manage and provide the document title. WP 4.1+
    add_theme_support('title-tag');

    // add html5 markup for the following features
    add_theme_support('html5', array('comment-list', 'comment-form'));

    // support thumbnails for post listing
    add_theme_support('post-thumbnails', array('post'));

    // Add feed support to site (needed for wordpress.org) WP3.0+
    add_theme_support('automatic-feed-links');

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ));

    // Possible future feature
    //$this->add_default_bg_support();
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
      'title'    => __('Advanced', 'food-truck'),
      //'description' => '',
      'priority' => 200,
    ));
    $wp_customize->get_section('custom_css')->panel = 'ftt_panel_advanced';

    // Home Page Options
    $wp_customize->add_section('ftt_section_home', array(
      'title'    => __('Homepage Panels', 'food-truck'),
      //'description' => '',
      'priority' => 30,
    ));

    $this->add_home_options($wp_customize, 'ftt_section_home');

    // Add colors options
    $wp_customize->add_section('ftt_section_colors', array(
      'title'    => __('Colors', 'food-truck'),
      //'description' => '',
      'priority' => 30,
    ));

    $this->add_colors_options($wp_customize, 'ftt_section_colors');

    // Add font options
    $wp_customize->add_section('ftt_fonts', array(
      'title'    => __('Fonts', 'food-truck'),
      //'description' => '',
      'priority' => 30,
    ));

    $this->add_font_options($wp_customize, 'ftt_fonts');

    // Other Misc Options
    $wp_customize->add_section('ftt_section_footer', array(
      'title'    => __('Footer Text', 'food-truck'),
      //'description' => '',
      'priority' => 10,
      'panel' => 'ftt_panel_advanced'
    ));

    $this->add_footer_options($wp_customize, 'ftt_section_footer');

    $wp_customize->add_section('ftt_section_mobnav_bp', array(
      'title'    => __('Mobile Navigation Breakpoint', 'food-truck'),
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
      'default'        => 'del-mar',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod', // Alt: 'option' get_option()
      'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control($control_id, array(
      'type'    => 'radio',
      'label'    => __( 'Pre-Designed Color Schemes', 'food-truck'),
      'choices'  => array(
        'del-mar'  => __('Del Mar', 'food-truck'),
        'cupcake' => __('Cupcake', 'food-truck'),
        'fries' => __('Fries', 'food-truck'),
        'tokyo' => __('Tokyo', 'food-truck'),
        'health' => __('Health', 'food-truck'),
        'waves' => __('Waves', 'food-truck'),
        'modern'   => __('Modern', 'food-truck'),
        'grill' => __('Grill', 'food-truck'),
        'custom' => __('or, Custom', 'food-truck'),
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
      'sanitize_callback' => 'sanitize_hex_color'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_id, array(
      'label'      => __( 'Brand Color', 'food-truck'),
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
      'sanitize_callback' => 'sanitize_hex_color'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_id, array(
      'label'      => __( 'Text Colour', 'food-truck'),
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
      'sanitize_callback' => 'sanitize_hex_color'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_id, array(
      'label'      => __( 'Background Color', 'food-truck'),
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
      'sanitize_callback' => 'wp_kses_post'
    ));
    $wp_customize->add_control($control_id, array(
      'type' => 'textarea',
      'label'      => __('About paragraph', 'food-truck'),
      'section'    => $section_id,
      'settings'   => $option_id,
    ));

    $option_id = 'ftt_theme_mod_tagline_image';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      //'default'        => 'bungee',
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
      'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $control_id, array(
      'label'      => __( 'Background Image', 'food-truck' ),
      'section'    => $section_id,
      'settings'   => $option_id,
    )));

    $option_id = 'ftt_theme_mod_tagline_bgtint';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => true,
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
      'sanitize_callback' => 'wp_validate_boolean'
    ));
    $wp_customize->add_control($control_id, array(
      'type' => 'checkbox',
      'label'      => __( 'Tint Background Image', 'food-truck' ),
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
      'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control($control_id, array(
      'type'    => 'radio',
      'label'    => __( 'Pre-Designed Font Pairings', 'food-truck'),
      'choices'  => array(
        'bungee' => __('Bungee', 'food-truck'),
        'la-mex' => __('La Mex', 'food-truck'),
        'impact' => __('Impact', 'food-truck'),
        'samari' => __('Samari', 'food-truck'),
        'leaf' => __('Leaf', 'food-truck'),
        'modern' => __('Modern', 'food-truck'),
        'icing' => __('Icing', 'food-truck'),
        'black-ops' => __('Black Ops', 'food-truck'),
        'diner' => __('Diner', 'food-truck'),
        'comic' => __('Comic', 'food-truck'),
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
      'type'           => 'theme_mod',
      'sanitize_callback' => 'wp_kses_post' // alt. wp_filter_nohtml_kses
    ));
    $wp_customize->add_control($control_id, array(
      'label'      => __('Footer Text', 'food-truck'),
      'section'    => $section_id,
      'settings'   => $option_id
    ));
  }

  function add_breakpoint_options($wp_customize, $section_id) {
    // OPTION
    $option_id = 'ftt_theme_mod_nav_breakpoint';
    $control_id = $option_id;
    $wp_customize->add_setting($option_id, array(
      'default'        => 1100,
      'capability'     => 'edit_theme_options',
      'type'           => 'theme_mod',
      'sanitize_callback' => 'absint'
    ));
    $wp_customize->add_control($control_id, array(
      'type'    => 'number',
      'label'      => __('Breakpoint in pixels', 'food-truck'),
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
        get_theme_mod('ftt_theme_mod_text_color', '#444'),
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

    $color_scheme_name = get_theme_mod('ftt_theme_mod_color_scheme', false);
    $font_scheme_name = get_theme_mod('ftt_theme_mod_font_scheme', false);

    $color_scheme_name = isset($color_schemes_available[$color_scheme_name]) ? $color_scheme_name : key($color_schemes_available);
    $font_scheme_name = isset($font_schemes_available[$font_scheme_name]) ? $font_scheme_name : key($font_schemes_available);
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
      $css_rule('.logo_text, .btn, .button, button, *[type=submit], .submit, .elementor-button', array(
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

  function get_starter_content() {
    $content = array();

    // Possible Future addition
    // Provide food truck related imagery
    // Can be added to posts/pages thumbnail with {{food-truck-footer}}
    /*
      $content['attachments'] = array(
        'food-truck-footer' => array(
          'post_title' => 'Food Truck Example Image',
          'file' => 'inc/starter-content/widget-image.jpg',
        ),
      );
    */

    $starter_home = '<p>Welcome to your site! This is your homepage. You can change the content</p>'
      . '<h2>Upcoming Locations</h2><p>Try the <a href="' . admin_url('plugin-install.php?s=food-truck&tab=search&type=tag') . '">Food Truck Plugin</a> to show a list of locations &amp; times with this shortcode:</p>[foodtruck display="list" count="3"]'
      . '<h3>Customize Theme</h3><p>You can customize this theme\'s look and feel in the admin <a href="' . admin_url('customize.php') . '">Appearance &gt; Customize</a></p>';

    $starter_locations = '<h1>Upcoming Locations</h1>'
      . '<p>Try the <a href="' . admin_url('plugin-install.php?s=food-truck&tab=search&type=tag') . '">Food Truck Plugin</a> to show a list of locations &amp; times with this shortcode:</p> [foodtruck display="list" separator="bg" count="30"]';

    $content['posts'] = array(
      'home' => array(
        'post_type' => 'page',
        'post_title' => _x('Home', 'Starter Content', 'food-truck'),
        'post_content' => $starter_home,
      ),
      'locations' => array(
        'post_type' => 'page',
        'post_title' => _x('Locations', 'Starter Content', 'food-truck'),
        'post_content' => $starter_locations,
      ),
      'menu' => array(
        'post_type' => 'page',
        'post_title' => _x('Menu', 'Starter Content', 'food-truck'),
        'post_content' => _x('<h1>Menu</h1> <p>Place your menu items on this page. You might also try a menu plugin for different layouts.</p>', 'Starter Content', 'food-truck')
      ),
      'about' => array(
        'post_type' => 'page',
        'post_title' => _x('About', 'Starter Content', 'food-truck'),
        'post_content' => _x('<h3>Our Story</h3> <p>Introduce your food truck here</p> <h3>Our Mission</h3> <p>What makes your food truck special</p>', 'Starter Content', 'food-truck' ),
      ),
      'blog' => array(
        'post_type' => 'page',
        'post_title' => _x('News', 'Starter Content', 'food-truck'),
      ),
      'catering' => array(
        'post_type' => 'page',
        'post_title' => _x('Catering', 'Starter Content', 'food-truck'),
        'post_content' => _x('<h1>Catering</h1> <p>This is a page with some basic contact information, such as an address and phone number. You might also try a plugin to add a contact form.</p>', 'Starter Content', 'food-truck' ),
      )
    );

    $content['nav_menus'] = array(
      'top-left' => array(
        'name' => __('My Top Menu Left', 'food-truck'),
        'items' => array(
          'link_home',
          'page_locations' => array(
            'type' => 'post_type',
            'object' => 'page',
            'object_id' => '{{locations}}',
          ),
          'page_menu' => array(
            'type' => 'post_type',
            'object' => 'page',
            'object_id' => '{{menu}}',
          )
        ),
      ),
      'top-right' => array(
        'name' => __('My Top Menu Right', 'food-truck'),
        'auto_add' => true,
        'items' => array(
          'page_about',
          'page_news' => array(
            'type' => 'post_type',
            'object' => 'page',
            'object_id' => '{{blog}}',
          ),
          'page_catering' => array(
            'type' => 'post_type',
            'object' => 'page',
            'object_id' => '{{catering}}',
          )
        ),
      )
    );

    $content['options'] = array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
      'blogdescription' => 'Satisfaction To-go'
      // Additional Future Options: posts_per_page
    );

    $content['theme_mods'] = array(
      'header_text' => true,
      'ftt_theme_mod_tagline_bgtint' => true
    );

    $content['widgets'] = array(
      'footer-widgets' => array(
        'theme_initial_widget_1' => array('text', array(
          'title' => 'Join the mailing list',
          'text' => 'This is a good spot for a sign up form! Try the Mailchimp plugin'
        )),
        'theme_initial_widget_2' => array('text', array(
          'title' => 'Follow Us',
          'text' => 'Great spot for Social Media buttons. Try a Social Button plugin or add some links'
        )),
        'theme_initial_widget_3' => array('text', array(
          'title' => 'Upcoming Locations',
          'text' => '<p>This is a good spot for a location listing. Try the <a href="' . admin_url('plugin-install.php?s=food-truck&tab=search&type=tag') . '">"Food Truck" plugin</a> to convert this shortcode":</p>[foodtruck display="summary" count="2"]'
        )),
        'theme_initial_widget_4' => array('text', array(
          'title' => '',
          // Note: Can't add attachments to media_image widget ..yet?
          // provide in text widget for now:
          'text' => '<img src=" ' . get_parent_theme_file_uri('inc/starter-content/widget-image.jpg') . '" />'
        )),
      ),
    );

    return $content;
  }
}
