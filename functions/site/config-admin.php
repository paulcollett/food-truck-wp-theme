<?php

class BraceAdmin
{

    function __construct() {
        
        add_filter('acf/fields/wysiwyg/toolbars', array(&$this,'acf_wysiwyg') );

        add_filter( 'wpseo_metabox_prio', array(&$this, 'move_yoast_to_bottom') );

        add_action( 'admin_bar_menu', array(&$this, 'modify_admin_bar'), 100 );

        add_action( 'admin_menu', array(&$this, 'reorder_admin_menu'), 500 );

        if(isset($_GET['activated'])) add_action( 'admin_init', array(&$this, 'on_first_run'));

    }

    function modify_admin_bar( $wp_admin_bar ){
        if(is_admin()) return;
        $wp_admin_bar->add_menu( array(
            'id' => 'themelot-debug',
            'title' => '(Toggle Theme Debug)',
            'href' => '#',
            'meta' => array(
                'onclick' => 'window.jQuery(".debug-section").toggle();return false;'
            )
        ));
    }


    function reorder_admin_menu() {
       global $menu;

        $separator = array(
          0 => '',
          1 => 'read',
          2 => 'separator1',
          3 => '',
          4 => 'wp-menu-separator'
        );

        // add in separator
        $menu['tl-sep1'] = $separator;
        $menu['tl-sep2'] = $separator;

        $from_to = array(
            '20' => '4.1', // pages
            '5' => '4.2', //posts
            '20.1' => '4.3', // locations & dates
            '20.2' => '4.4', // menus
            'tl-sep2' => '4.5',
            '81' => '4.6', // themelot
            'tl-sep1' => '4.7'
        );

        //$dashboard = 
        $menu_copy = array();
        foreach ($menu as $key => $value) {
            if(isset($from_to[$key])){
                $menu_copy[(string) $from_to[$key]] = $value;
            } else {
                $menu_copy[$key] = $value;
            }
        }

        $menu = $menu_copy;

    }

    function on_first_run() {
        add_option('brace_theme_first_version', THEME_VERSION, false);
        update_option('brace_theme_current_version', THEME_VERSION, false);
        add_option('brace_theme_first_install', @gmmktime(), false);
        update_option('brace_theme_current_install', @gmmktime(), false);

        wp_redirect(admin_url( 'admin.php?page=food-framework&first_run=1' ));
    }

    function acf_wysiwyg(){

        $toolbars = array();

        //'formatselect',
        $toolbars['page'][1] = apply_filters('teeny_mce_buttons', array('bold' , 'italic' , 'bullist' , 'numlist' , 'link' , 'unlink', 'blockquote', 'alignleft', 'aligncenter', 'alignright', 'hr' ));
        $toolbars['post'][1] = apply_filters('teeny_mce_buttons', array('bold' , 'italic' , 'bullist' , 'numlist' , 'link' , 'unlink', 'blockquote', 'alignleft', 'aligncenter', 'alignright', 'hr' ));
        $toolbars['text-along-image'][1] = apply_filters('teeny_mce_buttons', array('bold' , 'italic' , 'bullist' , 'numlist' , 'link' , 'unlink', 'blockquote', 'alignleft', 'aligncenter', 'alignright' ));
        $toolbars['grid-text'][1] = apply_filters('teeny_mce_buttons', array('bold' , 'italic' , 'bullist' , 'numlist' , 'link' , 'unlink', 'blockquote', 'alignleft', 'aligncenter', 'alignright' ));
        $toolbars['generic-form-success'][1] = apply_filters('teeny_mce_buttons', array('bold' , 'italic' , 'bullist' , 'numlist' , 'link' , 'unlink', 'alignleft', 'aligncenter', 'alignright' ));
        $toolbars['footer-text'][1] = apply_filters('teeny_mce_buttons', array('bold' , 'italic' , 'bullist' , 'numlist' , 'link' , 'unlink', 'alignleft', 'aligncenter', 'alignright' ));
        // hr, outdent, indent, alignleft, aligncenter, alignright, undo, redo
        return $toolbars;

    }

    function move_yoast_to_bottom() {
        return 'low';
    }

}

new BraceAdmin();