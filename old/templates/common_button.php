<!-- Button Group -->
<?php

    $link = isset($link) ? $link : (site_get_field_link(true, false) ? site_get_field_link(true) : '#');
    $get_field_fn = isset($sub_field) ? 'get_sub_field' : 'get_field';
    $label = isset($label) ? $label : ($get_field_fn('button_label') ? $get_field_fn('button_label') : '?');

    $customstyles = $get_field_fn('button_style_group_conditional_checkbox');

    if($customstyles) {

        $classname = site_get_styles_to_classname(array(
            'border-color' => $get_field_fn('button_style_border_color'),
            'color' => $get_field_fn('button_style_color') ? $get_field_fn('button_style_color') : 'transparent',
            'span' => array('color' => $get_field_fn('button_style_text_color'))
        ));

        $styles = array(
            'button',
            'button--rotate' => !!$get_field_fn('button_style_rotate'),
            'button--cap0' => $get_field_fn('button_style_shape') == 'rectangle',
            'button--cap4' => $get_field_fn('button_style_shape') == 'pill',
            'button--cap1' => $get_field_fn('button_style_shape') == 'diamond',
            'button--cap3' => $get_field_fn('button_style_shape') == 'skew',
            'button--cap2' => $get_field_fn('button_style_shape') == 'trapeze',
            'button--cap5' => $get_field_fn('button_style_shape') == 'disk',
            'button--compact' => $get_field_fn('button_style_size') == 'compact',
            'button--border1' => $get_field_fn('button_style_border') == 'solid',
            'button--border2' => $get_field_fn('button_style_border') == 'double',
            $classname
        );

    } else {

        $styles = site_request_store_get('button-class-array');

    }

    if(isset($class)) {
        $styles[] = $class;
    }

?>

<a href="<?php echo $link; ?>" class="<?php site_classname_filter($styles); ?>">
    <span><?php echo $label; ?></span>
</a>

<!-- / Button Group -->