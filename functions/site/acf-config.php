<?php

// customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');
 
function my_acf_settings_path( $path ) {
 
    // update path
    $path = get_stylesheet_directory() . '/functions/vendor/acf/';
    
    // return
    return $path;
    
}
 

// customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');
 
function my_acf_settings_dir( $dir ) {
 
    // update path
    $dir = get_stylesheet_directory_uri() . '/functions/vendor/acf/';
    
    // return
    return $dir;
    
}

// Hide ACF field group menu item
if($_SERVER['HTTP_HOST'] !== 'server') {
  add_filter('acf/settings/show_admin', '__return_false');
}

// Save JSON here

add_filter('acf/settings/save_json', 'my_acf_json_save_point');
 
function my_acf_json_save_point( $path ) {
    
    // update path
    $path = get_stylesheet_directory() . '/functions/site/acf-json';
    
    
    // return
    return $path;
    
}


// Load JSON here, too

add_filter('acf/settings/load_json', 'my_acf_json_load_point');

function my_acf_json_load_point( $paths ) {
    
    // append path
    $paths[] = get_stylesheet_directory() . '/functions/site/acf-json';
    
    
    // return
    return $paths;
    
}

// Auto assign Featured image from 'post_image'
function acf_set_featured_image( $value, $post_id, $field  ){
  update_post_meta($post_id, '_thumbnail_id', (int) $value);
  return $value;
}

add_filter('acf/update_value/name=post_featured_image', 'acf_set_featured_image', 10, 3);

add_filter('acf/settings/google_api_key', 'theme_set_acf_gmaps_api_key');

function theme_set_acf_gmaps_api_key() {
    return 'AIzaSyC_yHZEpaGyDxKwONsVY8yL74dRAdVx7Fw';
}

// 
function acf_parse_field_settings($data) {

    $file = $data[1];
    $fields = $data[0];

    if($file == 'group_57f1bfc948a67.json' && isset($fields['fields'][1]['layouts'])) {
        $insert_data = apply_filters('acf/page/layouts/add', array());
        if($insert_data) foreach ($insert_data as $value) {
            if(!isset($value['index'], $value['data'])) continue;
            array_splice($fields['fields'][1]['layouts'], $value['index'], 0, $value['data']);
        }
    }

    return $fields;
}

// custom brace filter in the core acf (acf/core/json.php)
add_filter('acf/field-settings', 'acf_parse_field_settings');


// Include ACF
include_once( get_stylesheet_directory() . '/functions/vendor/acf/acf.php' );


// Add ACF options Page
if( function_exists('acf_add_options_page') ) {
 
    acf_add_options_page(array(
        'page_title'    => 'Food Truck Theme. Sitewide Look & Settings',
        'menu_title'    => 'Food Truck Theme',
        'menu_slug'     => 'truck-framework',
        'capability'    => 'edit_posts',
        'redirect'  => false
    ));
}

// load google font plugin

define('ACFGFS_API_KEY', 'AIzaSyC_yHZEpaGyDxKwONsVY8yL74dRAdVx7Fw');

include get_stylesheet_directory() . "/functions/vendor/acf-plugins/acf-google-font-selector-field/acf-google_font_selector.php";

function acf_gfont_plugin_update_paths(){
    //wp_deregister_style('acf-input-google_font_selector');
    //wp_deregister_script('acf-input-google_font_selector');
    wp_enqueue_script( 'acf-input-google_font_selector', get_stylesheet_directory_uri() . "/functions/vendor/acf-plugins/acf-google-font-selector-field/js/input.js" );
    wp_enqueue_style( 'acf-input-google_font_selector', get_stylesheet_directory_uri() . "/functions/vendor/acf-plugins/acf-google-font-selector-field/css/input.css" );
}

add_action('admin_enqueue_scripts', 'acf_gfont_plugin_update_paths' , 99999);

// custom styles

add_action('admin_footer', 'admin_fields_add_assets' );

function admin_fields_add_assets($page){

  site_include('functions/site/_fields-html.php');

}
