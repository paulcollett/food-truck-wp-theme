<?php

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
