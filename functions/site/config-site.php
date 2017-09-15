<?php

class FoodSiteFramework
{
    
    function __construct() {
        define('THEME_VERSION', wp_get_theme()->get( 'Version' ));

        // needed for wordpress.org
        add_theme_support('automatic-feed-links');

        $this->register_wp_hooks();
        $path = get_template_directory();

        add_action( 'after_setup_theme', array( &$this, 'after_setup_theme') );

        include $path . '/functions/site/template-utils.php';
        include $path . '/functions/site/acf-config.php';
        include $path . '/functions/vendor/times-menu/plugin.php';

        $this->process_form_requests();

        add_action( 'wp_enqueue_scripts', array(&$this, 'add_assets_to_site') );

        // Add custom set seo, if yoast doesn't exist
        if (!defined('WPSEO_VERSION')) {
            add_filter( 'pre_get_document_title', array( &$this, 'set_default_home_page_seo_title' ) );
            add_filter( 'wp_head', array( &$this, 'set_default_home_page_seo_desc' ), 2 );
        }
    }

    function set_default_home_page_seo_title($title) {

        if(is_front_page() && get_field('custom_title','options')) {
            return esc_html(get_field('custom_title','options'));
        }

        return $title;

    }

    function set_default_home_page_seo_desc() {

        if(is_front_page() && get_field('custom_description','options')) {
            echo "<!-- meta description: set via theme settings for the home page -->\n";
            echo '<meta name="description"  content="' . esc_attr(get_field('custom_description','options')) . '" />';
        }

    }

    function register_wp_hooks() {

        register_nav_menus(array(
            'main' => 'Main Website Menu',
        ));

    }

    function after_setup_theme() {

        add_theme_support( 'post-thumbnails', array( 'post' ) );

        add_theme_support( 'woocommerce' );

        add_theme_support( 'title-tag' );

    }

    function add_assets_to_site() {

        wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/dist/js/main.js', array ( 'jquery' ), null, false);

    }

    function process_form_requests() {

        $this->process_form_request_subscribe();
        $this->process_form_request_generic_contact();

    }

    function process_form_request_subscribe() {


        if(isset($_GET['trucklot_email_sub'])) {

            // check request
            if(!isset($_POST['_p'],$_POST['_c1'],$_POST['_c2'],$_POST['ajax'],$_POST['_wpm'])) die(json_encode(array('error' => 'Request Error')));

            // get account details
            $account_api_key = @trim(get_metadata('post', (int) $_POST['_p'], site_safe_dec((string) $_POST['_c1']), true));
            $account_list_id = @trim(get_metadata('post', (int) $_POST['_p'], site_safe_dec((string) $_POST['_c2']), true));
            $account_region = explode('-', $account_api_key);
            $account_region = isset($account_region[1]) ? $account_region[1] : false;
            if(!$account_api_key || !$account_list_id || !$account_region) die(json_encode(array('error' => 'Website setup error (API & LIST ID are required/invalid)')));

            // verify form
            if(!wp_verify_nonce($_POST['_wpm'],'themelot-subscribe-form')) die(json_encode(array('error' => 'Form timeout. Sorry, but please reload and try again')));

            // check for email
            if(!isset($_POST['_e']) || ($email = trim((string) $_POST['_e'])) == '' || strpos($email,'@') < 1) die(json_encode(array('error' => 'Invalid email please try again')));

            $post = array(
                'apikey' => $account_api_key,
                'id' => $account_list_id,
                'email' => array(
                    'email' => $email,
                ),
                'double_optin' => false,
                'update_existing' => true
            );

            $ch = site_safe_remote('init');
            curl_setopt_array($ch,array(
                CURLOPT_URL => 'https://' . $account_region . '.api.mailchimp.com/2.0/lists/subscribe.json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($post)
            ));
            $output = @json_decode(site_safe_remote(false, $ch),true);
            site_safe_remote('close', $ch);

            if(isset($output['email'])) {
                die(json_encode(array('ok' => true)));
            } else if(isset($output['error'])) {
                $msg = $output['name'] == 'ValidationError' ? $output['error'] : "Website setup error ({$output['error']})";
                die(json_encode(array('error' => $msg)));
            } else {
                die(json_encode(array('error' => 'Website error (Unknown response from Mailchimp)')));
            }
            
        }

    }

    function process_form_request_generic_contact() {

        if(isset($_GET['trucklot_generic_contact'])){
            if(!isset($_POST['_wpm'], $_POST['ajax']) || !empty($_POST['_s'])) die(json_encode(array('error' => 'Please make sure you have javascript enabled')));
            if(!wp_verify_nonce($_POST['_wpm'],'themelot-generic-form')) die(json_encode(array('error' => 'Form timeout. Sorry, but please reload and try again')));
            $time = date('r', current_time('timestamp'));
            $name = isset($_POST['_n']) ? trim($_POST['_n']) : '(not provided)';
            $contact = isset($_POST['_c']) ? trim($_POST['_c']) : false;
            $message = isset($_POST['_m']) ? trim($_POST['_m']) : '(not provided)';
            $url = isset($_POST['_p']) ? get_permalink($_POST['_p']) : '';
            if(!$contact) die(json_encode(array('error' => 'Please provide required fields')));
            $message = "$time\n$url\n\nName:\n$name\n\nContact Method:\n$contact\n\nMessage:\n-------\n$message";
            $subject = get_bloginfo('name') . " Form Submission";
            $to = get_bloginfo('admin_email');
            die(json_encode(array('ok' => wp_mail($to, $subject, $message))));
        }

    }



}

new FoodSiteFramework();
