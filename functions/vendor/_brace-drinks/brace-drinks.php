<?php

if( !class_exists('BraceDrinks') ):

class BraceDrinks {

  static $instance = null;

  static function instance() {

    if(!self::$instance) {
      $cls = __CLASS__;
      self::$instance = new $cls();
    }

    return self::$instance;

  }

  function __construct() {

    $this->dir =  dirname(__FILE__);
    $this->uri = get_template_directory_uri() . '/functions/vendor/brace-drinks';
    $this->post_type = 'brace-drinks';
    $this->menu_slug = 'brace-drinks';
    $this->label = 'Beer Menu';
    $this->label_singular = 'Drink List';
    $this->version = THEME_VERSION;

    // Register Custom post types to store data
    add_action('init', array($this, 'register_post_types') );

    if( is_admin() ){
        // Add plugin features to admin
        add_action('admin_menu', array($this, 'admin_add_plugin_section' ) );
        add_action('admin_enqueue_scripts', array($this, 'admin_add_assets' ) );
        // Admin Ajax hook
        add_action( 'wp_ajax_' . $this->menu_slug, array($this, 'handle_ajax' ) );
    }

  }

  function admin_add_plugin_section(){
    add_menu_page( $this->label, $this->label, 'edit_posts', $this->menu_slug, array( $this, 'render_admin_page' ), 'dashicons-format-aside' , '20.2');
  }

  function register_post_types() {

    // add menu posts
    register_post_type( $this->post_type, array(
      'labels' => array(),
      'public' => false,
      'show_ui' => false,
      'supports' => array()
    ));

  }

  function admin_add_assets($page){

    if($page != 'toplevel_page_' . $this->menu_slug) return;

    wp_enqueue_script( $this->menu_slug . '-admin-lib', $this->uri . '/assets/libs.min.js', false, $this->version);

    wp_enqueue_media();

  }

  function render_admin_page(){
      include $this->dir . '/templates/admin.php';
  }

  function handle_ajax() {

    global $wpdb;

    $nonce = isset($_REQUEST['_nonce']) ? $_REQUEST['_nonce'] : (isset($_GET['_nonce']) ? $_GET['_nonce'] : false);

    if(!wp_verify_nonce($nonce, $this->menu_slug . '-app-nonce')) {
      wp_die(json_encode(array('error' => 'You may have been logged out. Please try again')));
    }

    if(!current_user_can('edit_posts')) {
      wp_die(json_encode(array('error' => 'Invalid Permissions')));
    }

    $action = isset($_GET['do']) ? $_GET['do'] : false;

    // Get post data through our helper function
    // Note: Post data may be empty
    $post_data = $this->get_json_post();

    if($post_data) {
      $_POST = $post_data;
    }

    if($action == 'save') {

      $id = isset($_POST['ID']) && $_POST['ID'] > 0 ? $_POST['ID'] : null;
      $data = isset($_POST) ? $_POST : array();

      $title = !empty($_POST['title']) ? $_POST['title'] : false;
      if(!$title){
        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = '$this->post_type' AND post_status = 'publish'");
        $title = $this->label_singular . ' ' . ++$count;
      }

      $title = wp_strip_all_tags($title);

      $post_id = wp_insert_post(array(
        'ID' => $id,
        'post_title' => $title,
        'post_content' => wp_slash(json_encode($data)),
        'post_type' => $this->post_type,
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id()
      ));

      wp_die(json_encode(array(
        'ok' => !!$post_id,
        'ID' => $post_id,
        'title' => $title
      )));

    }else if($action == 'delete') {

      $id = isset($_POST['ID']) && $_POST['ID'] > 0 ? $_POST['ID'] : null;

      $post = $this->get_post($id);

      if(!$post || $post->post_type != $this->post_type) {
        wp_die(json_encode(array('error' => 'Invalid ' . $this->label)));
      }

      $post = wp_trash_post( $id );//wp_delete_post( $id ,true );

      wp_die(json_encode(array(
        'ok' => !!$post,
        'ID' => $id
      )));

    }

    wp_die(json_encode(array('error' => 'Invalid Request')));

  }

  function parse_json_to_body($string){

    return json_encode($string, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);

  }

  function get_json_post(){
    $postdata = file_get_contents("php://input");
    return $this->parse_body_to_json($postdata);
  }

  function parse_body_to_json($body){

    $body = str_replace(array("\n", "\r"), ' ', trim((string) $body));

    $start = strpos($body, '{');
    $end = strrpos($body, '}');

    if($start === false || !$end) return array();

    $body = substr($body, $start,$end - $start + 1);

    $body = @json_decode($body, true);

    return $body ?: array();

  }

  function find_posts($post_type = false, $ids = false){

    if(!$post_type) $post_type = $this->post_type;

    $opts = array(
      'post_type' => $post_type,
      'post_per_page' => -1
    );

    if($ids !== false) $opts['post__in'] = (array) $ids;

    $posts = get_posts($opts);

    if(!$posts) return array();

    $out = array();

    foreach ($posts as $key => $post) {

      $out[$key] = $this->parse_body_to_json($post->post_content);
      $out[$key]['ID'] = $post->ID;
      $out[$key]['title'] = $post->post_title;
    }

    return $out;

  }

  function find_one($post_type, $id) {

    $posts = $this->posts_find($post_type, $id);

    if(!isset($posts[0])) return false;

    return $posts[0];

  }

  function get_nonce() {
    return wp_create_nonce($this->menu_slug . '-app-nonce');
  }

}

BraceDrinks::instance();

endif;
