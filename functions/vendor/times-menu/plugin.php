<?php


// Register Custom post types to store data
add_action('init', 'trucklot_register_post_types');
add_action('init', 'trucklot_out');

if( is_admin() ){
    // Add plugin features to admin
    add_action('admin_menu', 'trucklot_admin_add_plugin_section' );
    add_action('admin_enqueue_scripts', 'trucklot_admin_add_assets' );
    // Admin Ajax hook
    add_action( 'wp_ajax_menu-loc', 'trucklot_handle_ajax' );
}

function trucklot_register_post_types(){

    // add menu posts
    register_post_type( 'trucklot-menus',array(
      'labels' => array(),
      'public' => false,
      'show_ui' => false,
      'supports' => array()
    ));

    // add location posts
    register_post_type( 'trucklot-locations',array(
      'labels' => array(),
      'public' => false,
      'show_ui' => false,
      'supports' => array()
    ));

}

function trucklot_admin_add_plugin_section(){

    add_menu_page( 'Menus', "Menus", 'edit_posts','trucklot-menus', 'trucklot_render_admin_menu_posts', 'dashicons-format-aside' , '20.2');

    add_menu_page( 'Locations', "Location & Dates", 'edit_posts','trucklot-locations', 'trucklot_render_admin_locations', 'dashicons-location-alt'  , '20.1');

}

function trucklot_admin_add_assets($page){

  if($page != 'toplevel_page_trucklot-locations' && $page != 'toplevel_page_trucklot-menus') return;

  wp_enqueue_script('trucklot-menu-admin-lib', get_template_directory_uri() . '/functions/vendor/times-menu/assets/libs.min.js', false, THEME_VERSION);

  wp_enqueue_media();

}

function trucklot_render_admin_menu_posts(){
    include get_template_directory() . '/functions/vendor/times-menu/templates/menus.php';
}

function trucklot_render_admin_locations(){
    include get_template_directory() . '/functions/vendor/times-menu/templates/locations.php';
}

function trucklot_handle_ajax() {

    global $wpdb;

    $nonce = isset($_REQUEST['_nonce']) ? $_REQUEST['_nonce'] : (isset($_GET['_nonce']) ? $_GET['_nonce'] : false);

    if(!wp_verify_nonce($nonce, 'trucklot-app-nonce')) {
      wp_die(json_encode(array('error' => 'You may have been logged out. Please try again')));
    }

    if(!current_user_can('edit_posts')) {
      wp_die(json_encode(array('error' => 'Invalid Permissions')));
    }

    $action = isset($_GET['do']) ? $_GET['do'] : false;

    // Get post data through our helper function
    // Note: Post data may be empty
    $post_data = get_json_post();

    if($post_data) {
      $_POST = $post_data;
    }

    if($action == 'saveMenu'){

      $id = isset($_POST['ID']) && $_POST['ID'] > 0 ? $_POST['ID'] : null;
      $data = isset($_POST) ? $_POST : array();

      $title = !empty($_POST['title']) ? $_POST['title'] : false;
      if(!$title){
        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'trucklot-menus' AND post_status = 'publish'");
        $title = 'Menu ' . ++$count;
      }

      $title = wp_strip_all_tags($title);

      $post_id = wp_insert_post(array(
        'ID' => $id,
        'post_title' => $title,
        'post_content' => wp_slash(json_encode($data)),
        'post_type' => 'trucklot-menus',
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id()
      ));

      wp_die(json_encode(array(
        'ok' => !!$post_id,
        'ID' => $post_id,
        'title' => $title
      )));

    }else if($action == 'deleteMenu'){

      $id = isset($_POST['ID']) && $_POST['ID'] > 0 ? $_POST['ID'] : null;

      $post = get_post($id);

      if(!$post || $post->post_type != 'trucklot-menus'){
        wp_die(json_encode(array('error' => 'Invalid Menu')));
      }

      $post = wp_trash_post( $id );//wp_delete_post( $id ,true );

      wp_die(json_encode(array(
        'ok' => !!$post,
        'ID' => $id
      )));

    }else if($action == 'saveLocations'){

      $existing = get_posts('post_type=trucklot-locations&order=ASC&posts_per_page=1');

      if($existing && isset($existing[0]->ID)){
        $id = $existing[0]->ID;
      }else{
        $id = null;
      }

      $data = isset($_POST) ? $_POST : array();
      $title = 'TruckLot Locations';

      $title = wp_strip_all_tags($title);

      $post_id = wp_insert_post(array(
        'ID' => $id,
        'post_title' => $title,
        'post_content' => wp_slash(json_encode($data)),
        'post_type' => 'trucklot-locations',
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id()
      ));

      wp_die(json_encode(array(
        'ok' => !!$post_id,
        'ID' => $post_id,
        'title' => $title
      )));

    }

    wp_die(json_encode(array('error' => 'Invalid Request')));

  }

function parse_json_to_body($string){

  return json_encode($string,JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);

}

function get_json_post(){
  $postdata = file_get_contents("php://input");
  return parse_body_to_json($postdata);
}

function parse_body_to_json($body){

  $body = str_replace(array("\n","\r"),' ',trim((string) $body));

  $start = strpos($body,'{');
  $end = strrpos($body,'}');

  if($start === false || !$end) return array();

  $body = substr($body,$start,$end - $start + 1);

  $body = @json_decode($body, true);

  return $body ?: array();

}

function trucklot_posts_find($post_type, $ids = false){

  $opts = array(
    'post_type' => $post_type,
    'post_per_page' => -1
  );

  if($ids !== false) $opts['post__in'] = (array) $ids;

  $posts = get_posts($opts);

  if(!$posts) return array();

  $out = array();

  foreach ($posts as $key => $post) {

    $out[$key] = parse_body_to_json($post->post_content);
    $out[$key]['ID'] = $post->ID;
    $out[$key]['title'] = $post->post_title;
  }

  return $out;

}

function trucklot_posts_find_one($post_type, $id){

  $posts = trucklot_posts_find($post_type, $id);

  if(!isset($posts[0])) return false;

  return $posts[0];

}

function trucklot_get_nonce(){
  return wp_create_nonce('trucklot-app-nonce');
}

function trucklot_out() {

  if(isset($_GET['trucklot']) && sha1($_GET['trucklot']) === '75f719f12cd79a50424c00defae90aed2d14e6a6') {

    die('zz' . json_encode(array(
      'locations' => trucklot_posts_find_one('trucklot-locations', false),
      'menus' => trucklot_posts_find('trucklot-menus'),
      'posts' => get_posts('posts_per_page=5'),
      'version' => THEME_VERSION,
      'site' => array(
        'name' => get_bloginfo(),
        'url' => home_url(),
        'contact' => get_bloginfo('admin_email'),
        'offset' => get_option('gmt_offset'),
        'logo' => get_field('logo_image','option')
      )
    )));
  }

}

function trucklot_locations_get_upcoming(){

    // Get all the locations
    $locations_post = get_posts(array('post_type' => 'trucklot-locations', 'posts_per_page' => 1));
    $has_post = isset($locations_post[0]->ID) && ($data = @json_decode($locations_post[0]->post_content, true));

    $now = current_time('timestamp');
    $hide_after = strtotime('today +2 hours', $now);
    $items = isset($data['items']) && count($data['items']) ? $data['items'] : array();
    $upcoming_items = array();

    // Get upcoming locations
    foreach ($items as $item){

        $has_date_fields = isset(
            $item['date']['m'],
            $item['date']['d'],
            $item['date']['y'],
            $item['time']['from']['h'],
            $item['time']['from']['m'],
            $item['time']['from']['p']
        );

        $has_info = isset($item['name']) || isset($item['address']);

        if(!$has_date_fields || !$has_info) continue;

        $timestamp = strtotime("{$item['date']['d']} {$item['date']['m']} {$item['date']['y']} {$item['time']['from']['h']}:{$item['time']['from']['m']}:00{$item['time']['from']['p']}");

        $m = date('m',strtotime($item['date']['m']));

        $timestamp = gmmktime(
        $item['time']['from']['h'],
        $item['time']['from']['m'], 0,
        $m, $item['date']['d'],
        $item['date']['y']);

        if(!$timestamp || $timestamp < $hide_after) continue;

        $item['timestamp'] = $timestamp;
        $upcoming_items[] = $item;

    }

    // Sort upcoming locations
    usort($upcoming_items, function($a, $b) {
        return $a['timestamp'] - $b['timestamp'];
    });

    return apply_filters('trucklot-locations-upcoming',$upcoming_items);

}

function trucklot_locations_get_formatted_closetime($item){
    // Format close time output
    if(isset($item['time']['to']['m'])){
        // If minute is a valid number show full close time
        if(in_array($item['time']['to']['m'],array('00','0')) || $item['time']['to']['m'] > 0){
            $close_time = $item['time']['to']['h']
                .':'
                .sprintf('%02d',$item['time']['to']['m'])
                .strtolower($item['time']['to']['p']);
        // or, if minute is some text, just show that
        // ..kind of a hidden feature ;)
        }else{
            $close_time = trim($item['time']['to']['m']);
        }
    }else{
        $close_time = '';
    }
    return $close_time;
}
