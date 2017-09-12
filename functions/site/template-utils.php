<?php

function get_site_asset($path){

    return get_template_directory_uri() . '/assets/' . $path;

}
function site_asset($path){

    echo get_site_asset($path);

}

function site_include($path, $args = array()){

    if($args) extract($args);

    include get_template_directory() . '/' . $path;

}

function get_site_image($image,$options = array()){
    $options['return'] = true;
    return site_image($image,$options);
}

function site_image($image,$options = array()){

    $options = array_merge(array(
        'w' => INF,
        'h' => INF,
        'crop' => false,
        'upsize' => true,
        'wrapper' => '<img src="%s" alt="%s" %s/>',
        'default' => -1,
        'return' => false,
        'upscale' => true,
        'alt' => false
    ),$options);

    if(is_array($image) && isset($image['url'])){
        $original = $image['url'];
        $alt = $image['alt'];
    }else if($image > 0){
        $image_obj = wp_get_attachment_image_src($image, false);
        $original = isset($image_obj[0]) ? $image_obj[0] : false;
        $alt = isset($image_obj[0]) ? esc_attr(trim(strip_tags(get_post_meta($image, '_wp_attachment_image_alt', true)))) : '';
    }else{
        $original = $image;
        $alt = '';
    }

    if($options['alt']){
        $alt = $options['alt'];
    }

    if(!$options['crop']) {
        $options['w'] = $options['w'] ? $options['w'] : INF;
        $options['h'] = $options['h'] ? $options['h'] : INF;
    }

    //resize on the fly or pull from cache
    $additional_attrs = '';
    if(isset($image['width']) && !$options['crop'] && $image['width'] < $options['w'] && $image['height'] < $options['h']) {
        $resized_url = $original;
        $additional_attrs = 'width="' . $image['width'] . '" height="' . $image['height'] . '"' . ' ';
    }else if($original){
        if(!function_exists('aq_resize')) include get_template_directory() . '/functions/vendor/aq-resizer/aq_resizer.php';
        $resized_obj = aq_resize(
            $original,
            $options['w'],
            $options['h'],
            $options['crop'],
            false,
            $options['upscale']
        );
        $resized_url = isset($resized_obj[0]) ? $resized_obj[0] : false;
        if($resized_url) $additional_attrs = 'width="' . $resized_obj[1] . '" height="' . $resized_obj[2] . '"' . ' ';
    }else{
        $resized_url = false;
    }

    if(!is_string($options['default'])){
        if($options['crop']){
            $size_percent = ($options['h']/$options['w'])*100;
            $options['default'] = '<div style="padding-bottom:' . $size_percent . '%"><!--no img--></div>';
        }else{
            $options['default'] = '<!--no img-->';
        }
    }

    if($options['return']){
        return $resized_url;
    }else{
        echo $resized_url ? sprintf($options['wrapper'],$resized_url,$alt,$additional_attrs) : $options['default'];
    }

}

function _site_oembed_extract_youtube_id($oembed_html){
    if(strpos($oembed_html,'youtube.com/embed') > 0){
        preg_match('#embed/([\w\-]{11})#',$oembed_html,$matches);
        return isset($matches[1]) ? $matches[1] : false;
    }else{
        return false;
    }
}

function _site_oembed_extract_vimeo_id($oembed_html){
    if(strpos($oembed_html,'vimeo.com/') > 0){
        preg_match('#video/([\d]{4,})#',$oembed_html,$matches);
        return isset($matches[1]) ? $matches[1] : false;
    }else{
        return false;
    }
}

function site_oembed_video_image($oembed_html){

    if($ytid = _site_oembed_extract_youtube_id($oembed_html)){
        echo '<img src="http://img.youtube.com/vi/' . $ytid . '/0.jpg" alt="" />';
    }else if($vimid = _site_oembed_extract_vimeo_id($oembed_html)) {
        // ?callback=?
        //$data = json_decode(file_get_contents("http://vimeo.com/api/v2/video/$imgid.json"), true);
        //$url = isset($data[0]['thumbnail_large']) ? $data[0]['thumbnail_large'] : false;
        echo '<div class="js-vimeo-image" data-vimeo-id="' . $vimid . '"></div>';
    }else{
        echo '<!-- no video image available -->';
    }

}

function site_oembed_filter($html,$show_unfiltered = false){

    // Youtube
    if($ytid = _site_oembed_extract_youtube_id($html)){
        $markup = '<iframe width="100%" height="100%" src="//www.youtube-nocookie.com/embed/*ID*?modestbranding=1&amp;showinfo=0&amp;rel=0&amp;controls=2&amp;color=white&amp;autoplay=1&wmode=opaque" allowtransparency="true" frameborder="0" allowfullscreen=""></iframe>';
        echo str_replace('*ID*',$ytid,$markup);

    }else if($vimid = _site_oembed_extract_vimeo_id($html)){
        $markup = '<iframe width="100%" height="100%" src="//player.vimeo.com/video/*ID*?autoplay=1&byline=0&badge=0&color=ffffff&portrait=0&title=0" frameborder="0" allowfullscreen=""></iframe>';
        echo str_replace('*ID*',$vimid,$markup);
    }else if($show_unfiltered){
        echo $html;
    }else{
        echo '<!-- no matched embed media found -->';
    }

}

function get_site_encoded_string($content) {
    return is_string($content) ? antispambot( $content ) : '';
}

function site_output_google_analytics($id) {
    if(defined('WP_DEBUG') && WP_DEBUG) return; $id = trim(str_replace(array('\'','\\'),'',$id));
    $fns = $id ? "ga('create','$id','auto');ga('send','pageview')\n" : '';
    echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    {$fns}</script>\n";
}

function site_get_classname_filter($arr = array()) {
    $arr2 = array();

    foreach ($arr as $key => $value) {
        if(!is_string($key)){
            $arr2[trim($value,'.')] = true;
        }else{
            $arr2[trim($key,'.')] = !!$value;
        }
    }

    $arr2 = array_filter($arr2);

    return implode(' ', array_keys($arr2));
}

function site_classname_filter($arr = array()){
    echo site_get_classname_filter($arr);
}

function site_get_styles_to_classname($arr = array()){

    $classname = '.site' . rand(0,999999);
    
    $styles = site_get_css_from_array($arr, $classname);

    if($styles) {
        echo "<style>$styles</style>";
        return $classname;
    } else {
        return null;
    }

}

function site_get_css_from_array($rules = array(), $scope = '') {

    $rule_items = array();

    foreach ($rules as $key => $value) {
        if(is_array($value)){
            foreach ($value as $key1 => $value1){
                if(!$value1) continue;
                $scope_selectors = explode(',',$scope);
                foreach ($scope_selectors as  $scope_selector)
                    $rule_items[] = $scope_selector . ' ' . $key . '{' . $key1 . ':' . $value1 . '}';
            }
        }else{
            if(!$value) continue;
            $rule_items[] = $scope . '{' . $key . ':' . $value . '}';
        }

    }

    $styles = implode($rule_items, '');

    return $styles;

}

function site_get_num_with_unit($str, $default = 'px') {

    $number = (float) $str;

    if(strpos($str, '%') > 0) return $number . '%';
    if(strpos($str, 'px') > 0) return $number . 'px';
    if(strpos($str, 'rem') > 0) return $number . 'rem';
    if(strpos($str, 'em') > 0) return $number . 'em';
    if(strpos($str, 'pt') > 0) return $number . 'pt';
    return $number . $default;
}

function site_get_grid_item_sizeclass($cells) {

    $cells = (array) $cells;
    $cells_breaks = array();
    $cells_breaks_bps = array();
    $cells_filtered = array();
    $sizes = array(
        ''=>'',
        'mobtab'=>'(max-width:720px) and (min-width:450px)',
        'desktab'=>'(min-width:450px)',
        'desk'=>'(min-width:1024px)',
        'tab'=>'(max-width:720px) and (min-width:450px)',
        'mob'=>'(max-width:450px)'
    );
    $i = 0;

    while(count($cells)) {

        $cell = $cells[0];
        $cell['original_index'] = $i;
        
        if($cell['acf_fc_layout'] == 'new_row'){
            $k = $cell['new_row_has_conditional'] ? str_replace('all','',$cell['new_row_conditional']) : '';
            if(isset($sizes[$k])){
                $cells_breaks[$k] = isset($cells_breaks[$k]) ? $cells_breaks[$k] : array();
                $cells_breaks[$k][] = count($cells_filtered);
                if(!isset($cells_breaks_bps[$i])) $cells_breaks_bps[$i] = array();
                $cells_breaks_bps[$i][] = $sizes[$k];
            }
        }else{
            $cells_filtered[] = $cell;
        }

        unset($cells[0]);
        $cells = array_values($cells);
        $i++;

    }

    foreach ($sizes as $size_key => $mq_size) {
        foreach ($cells_filtered as $new_index => $cell) {
            if($size_key !== '' && !isset($cells_breaks[$size_key])) continue 1;
            $_break_at_indexes = isset($cells_breaks[$size_key]) ? $cells_breaks[$size_key] : array();
            $_break_at_indexes[] = $new_index;
            sort($_break_at_indexes, SORT_NUMERIC);
            $_break_at_indexes_with_cell_index = array_search($new_index, $_break_at_indexes);
            $_last_cell_end = count($cells_filtered)%2 ? count($cells_filtered) - 1 : count($cells_filtered);
            $row_end = isset($_break_at_indexes[$_break_at_indexes_with_cell_index + 1]) ? $_break_at_indexes[$_break_at_indexes_with_cell_index + 1] : $_last_cell_end;
            $row_start = isset($_break_at_indexes[$_break_at_indexes_with_cell_index - 1]) ? $_break_at_indexes[$_break_at_indexes_with_cell_index-1] : 0;
            $row_count = $row_end - $row_start;
            $cells_sizes[$cell['original_index']][$mq_size] = 100 / max($row_end-$row_start, 1);

        }
    }

    $classes_to_styles = array();
    $cell_indexes_to_classname = array();

    foreach($cells_sizes as $cell_index => $mq_widths_arr){
        $style_str = '';
        foreach ($mq_widths_arr as $mq => $max_width) {
            if($mq == ''){
                $style_str .= sprintf('CLASSNAME{max-width:%s%%}',$max_width);
            }else{
                $style_str .= sprintf('@media %s{CLASSNAME{max-width:%s%%}}',$mq,$max_width);
            }
        }
        $existing_class = array_search($style_str, $classes_to_styles);
        $classname = $existing_class ? $existing_class : 'site_grid' . rand(0,999999);
        $classes_to_styles[$classname] = $style_str;
        $cell_indexes_to_classname[$cell_index] = $classname;
    }

    foreach ($cells_breaks_bps as $break_cell_index => $mq_sizes) {
        $style_str = 'CLASSNAME{display:none}';
        foreach ($mq_sizes as $mq) {
            if($mq){
                $style_str .= sprintf('@media %s{CLASSNAME{display:block}}',$mq);
            } else {
                // can always display block so skip
                continue 2;
            }
        }
        $existing_class = array_search($style_str, $classes_to_styles);
        $classname = $existing_class ? $existing_class : 'site_grid' . rand(0,999999);
        $classes_to_styles[$classname] = $style_str;
        $cell_indexes_to_classname[$break_cell_index] = $classname;
    }

    echo '<style>';
    foreach ($classes_to_styles as $classname => $css) {
        echo str_replace('CLASSNAME','.'.$classname,$css);
    }
    echo '</style>';

    return $cell_indexes_to_classname;

}

function site_get_parse_acf_googlefont_data($plugin_response_data){

    $font = isset($plugin_response_data['font']) ? (string) $plugin_response_data['font'] : null;
    if(!$font) return $font;
    
    if(isset($plugin_response_data['variants']) && count((array) $plugin_response_data['variants'])){
        $font .= ':' . implode(',', $plugin_response_data['variants']);
    }

    return $font;

}

function site_get_fontstack($chosen_default_stack = 'sans-serif') {

    $default_stack = array(
        'sans-serif' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        'serif'      => 'Georgia, "Times New Roman", Times, serif',
        'monospace'  => 'Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',
        'inherit'    => 'inherit'
    );

    return isset($default_stack[$chosen_default_stack]) ? $default_stack[$chosen_default_stack] : false;

}

function site_get_google_fontstack($input_fonts, $chosen_default_stack = 'sans-serif') {

    $fonts = array();
    $google_fonts = array();


    $arr = (array) $input_fonts;

    foreach ($arr as $id => $font) {
        
        $default = site_get_fontstack($chosen_default_stack);

        if(is_array($font)){
            if(isset($font['default']))
                $default = site_get_fontstack($font['default']) ? site_get_fontstack($font['default']) : $font['default'];
            $font = $font['font'];
        }

        if(site_get_fontstack($font) || !$font){
            $fonts[$id] = $default;
            continue;
        }

        // patch: remove google variats part from font name
        if(strpos($font, ':') > 0){
            $font = explode(':', $font);
            $font = $font[0];
        }

        $google_fonts[] = $font;
        $fonts[$id] = '"' . $font . '", ' . $default;

    }

    if($google_fonts){
        //family query format: Tangerine:b,bi|Inconsolata:i|Droid+Sans
        echo '<link href="//fonts.googleapis.com/css?family=' . urlencode(implode('|', $google_fonts)) . '" rel="stylesheet" />';
    }

    return isset($fonts[0]) && count($fonts) == 1 ? $fonts[0] : $fonts;

}

function site_get_field_link($sub_field = false, $default = '#nolink'){

    $get_field_fn = $sub_field ? 'get_sub_field' : 'get_field';

    if($get_field_fn('link_type') == 'page'){
        return $get_field_fn('link_to_page');
    }elseif($get_field_fn('link_type') == 'internal'){
        return $get_field_fn('link_to_internal');
    }elseif($get_field_fn('link_type') == 'external'){
        return $get_field_fn('link_to_external');
    }elseif($get_field_fn('link_type') == 'file'){
        return $get_field_fn('link_to_file');
    }else{
        return $default;
    }

}

function site_request_store_set($key, $value){

    global $_site_data_store_for_request;

    $_site_data_store_for_request[$key] = $value;

}

function site_request_store_get($key = false, $default = null){

    global $_site_data_store_for_request;

    return isset($_site_data_store_for_request[$key]) ? $_site_data_store_for_request[$key] : $default;

}

function site_favicon_tag(){
    $tag = '<link rel="shortcut icon" type="%s" href="%s">';
    
    if(!$favicon_image = get_field('favicon_image', 'options')) return;

    $image_url = get_site_image($favicon_image, array('w'=>64,'h'=>64,'wrapper'=>false));
    $mime = isset($favicon_image['mime_type']) ? $favicon_image['mime_type'] : 'image/png';

    echo sprintf($tag, $mime, $image_url);
}

function site_output_styles() {

    $styles = array();

    $fontstack_fallback = !get_field('body_font_font','options') || get_field('body_font_font','options') == 'default' ? 'sans-serif' : get_field('body_font_font','options');
    $fontstack_fallback_accent = !get_field('heading_font_font','options') || get_field('heading_font_font','options') == 'default' ? $fontstack_fallback : get_field('heading_font_font','options');

    $fontstacks = site_get_google_fontstack(array(
        'body' => array(
            'font' => get_field('body_font_has_google_font','options') ? site_get_parse_acf_googlefont_data(get_field('body_font_google_font','options')) : null,
            'default' => $fontstack_fallback,
        ),
        'accent' => array(
            'font' => get_field('heading_font_has_google_font', 'options') ? site_get_parse_acf_googlefont_data(get_field('heading_font_google_font','options')) : null,
            'default' => $fontstack_fallback_accent,
        )
    ), $fontstack_fallback);

    site_request_store_set('fontstack-body', $fontstacks['body']);
    site_request_store_set('fontstack-accent', $fontstacks['accent']);

    // default body styles
    $styles[] = site_get_css_from_array(array(
        'background-color' => get_field('scheme_background', 'options'),
        'color' => get_field('scheme_color', 'options'),
        'font-family' => site_request_store_get('fontstack-body'),
    ), 'body');

    // default element styles
    $styles[] = site_get_css_from_array(array(
        'a' => array('color' => get_field('scheme_link', 'options') ? get_field('scheme_link', 'options') : get_field('scheme_accent', 'options')),
        '.accent-color' => array('color' => get_field('scheme_accent', 'options')),
        '.heading, .h1, .h2, .h3, .h4, .accent, .copy h1,.copy h2,.copy h3,.copy h4' => array('font-family' => site_request_store_get('fontstack-accent')),
        '.heading, .h1, .h2, .h3, .h4, .copy h1,.copy h2,.copy h3,.copy h4' => array('color' => get_field('scheme_heading', 'options')),
    ), '');

    // default button styles
    $styles[] = site_get_css_from_array($default_button_styles = array_filter(array(
        'border-color' => get_field('default_button_style_border_color', 'options'),
        'color' => get_field('default_button_style_color', 'options') ? get_field('default_button_style_color', 'options') : (get_field('default_button_style_text_color', 'options') ? 'transparent' : get_field('scheme_accent', 'options')),
        'span' => array('color' => get_field('default_button_style_text_color', 'options')),
        'font-family' => site_request_store_get('fontstack-accent'),
    )), '.button, button, .btn');


    // default button classes
    $default_button_classes = array(
        'button',
        'button--rotate' => !!get_field('default_button_style_rotate', 'options'),
        'button--cap0' => get_field('default_button_style_shape', 'options') == 'rectangle',
        'button--cap4' => get_field('default_button_style_shape', 'options') == 'pill',
        'button--cap1' => get_field('default_button_style_shape', 'options') == 'diamond',
        'button--cap3' => get_field('default_button_style_shape', 'options') == 'skew',
        'button--cap2' => get_field('default_button_style_shape', 'options') == 'trapeze',
        'button--cap5' => get_field('default_button_style_shape', 'options') == 'disk',
        'button--compact' => get_field('default_button_style_size', 'options') == 'compact',
        'button--border1' => get_field('default_button_style_border', 'options') == 'solid',
        'button--border2' => get_field('default_button_style_border', 'options') == 'double'
    );

    site_request_store_set('button-class-array', $default_button_classes);

    if(get_field('nav_button_style_group_conditional_checkbox', 'options')){

        $navigation_classes = array(
            'navigation--anim1',
            'navigation--rotate' => !!get_field('nav_button_style_rotate', 'options'),
            'navigation--cap0' => get_field('nav_button_style_shape', 'options') == 'rectangle',
            'navigation--cap4' => get_field('nav_button_style_shape', 'options') == 'pill',
            'navigation--cap1' => get_field('nav_button_style_shape', 'options') == 'diamond',
            'navigation--cap3' => get_field('nav_button_style_shape', 'options') == 'skew',
            'navigation--cap2' => get_field('nav_button_style_shape', 'options') == 'trapeze',
            'navigation--cap5' => get_field('nav_button_style_shape', 'options') == 'disk',
            'navigation--border1' => get_field('nav_button_style_border', 'options') == 'solid',
            'navigation--border2' => get_field('nav_button_style_border', 'options') == 'double'
        );

        $styles[] = site_get_css_from_array($default_button_styles = array_filter(array(
            'border-color' => get_field('nav_button_style_border_color', 'options'),
            'color' => get_field('nav_button_style_color', 'options') ? get_field('nav_button_style_color', 'options') : 'transparent',
            'a' => array('color' => get_field('nav_button_style_text_color', 'options')),
            'font-family' => site_request_store_get('fontstack-accent'),
        )), '.navigation li');

    } else {

        $navigation_classes = array(
            'navigation--anim1',
            'navigation--rotate' => !!get_field('default_button_style_rotate', 'options'),
            'navigation--cap0' => get_field('default_button_style_shape', 'options') == 'rectangle',
            'navigation--cap4' => get_field('default_button_style_shape', 'options') == 'pill',
            'navigation--cap1' => get_field('default_button_style_shape', 'options') == 'diamond',
            'navigation--cap3' => get_field('default_button_style_shape', 'options') == 'skew',
            'navigation--cap2' => get_field('default_button_style_shape', 'options') == 'trapeze',
            'navigation--cap5' => get_field('default_button_style_shape', 'options') == 'disk',
            'navigation--border1' => get_field('default_button_style_border', 'options') == 'solid',
            'navigation--border2' => get_field('default_button_style_border', 'options') == 'double'
        );

        $styles[] = site_get_css_from_array($default_button_styles = array_filter(array(
            'border-color' => get_field('default_button_style_border_color', 'options'),
            'color' => get_field('default_button_style_color', 'options') ? get_field('default_button_style_color', 'options') : get_field('scheme_accent', 'options'),
            'a' => array('color' => get_field('default_button_style_text_color', 'options')),
            'font-family' => site_request_store_get('fontstack-accent'),
        )), '.navigation li');

    }

    site_request_store_set('navigation-class-array', $navigation_classes);

    $styles[] = site_get_css_from_array(array(
        'color' => get_field('mobile_button_color', 'options') ? get_field('mobile_button_color', 'options') : get_field('scheme_accent', 'options'),
    ), '.menu-icon');

    $styles[] = site_get_css_from_array(array(
        'background-image' => get_field('navigation_background_image', 'options') ? 'url(' . get_site_image(get_field('navigation_background_image', 'options')) . ')' : null,
        'background-color' => get_field('navigation_background', 'options') ? get_field('navigation_background', 'options') : null,
    ), '.header');


    // logo custom styles
    if(get_field('logo_type') !== 'image'){

        if(!get_field('logo_style_font','options') || get_field('logo_style_font','options') == 'default'){
            $logo_fontfamily = site_request_store_get('fontstack-accent');
        }else if(get_field('logo_style_has_google_font','options')){
            $logo_fontfamily = site_get_google_fontstack(site_get_parse_acf_googlefont_data(get_field('logo_style_google_font','options')), get_field('logo_style_font','options'));
        }else{
            $logo_fontfamily = site_get_fontstack(get_field('logo_style_font','options'));
        }

        $styles[] = site_get_css_from_array(array(
            'font-family' => $logo_fontfamily,
            'color' => get_field('logo_style_has_color', 'options') && get_field('logo_style_font_color', 'options') ? get_field('logo_style_font_color', 'options') : get_field('scheme_accent', 'options'),
        ), '.logo');

    }

    // output site styles
    $styles = implode($styles, '');
    if($styles) echo "<style>$styles</style>\n";

    // output styles provided in the advanced page
    $advanced_custom_css = trim(strip_tags(get_field('custom_css', 'options')));
    if($advanced_custom_css) echo "\n<!-- START Custom -->\n<style>$advanced_custom_css</style>\n</!-- / END Custom -->\n";

}

function site_get_related_post_ids() {

    global $wpdb, $post;
   
    $ids = array();

    $prev_post = get_previous_post();
    if(isset($prev_post->ID)) {
        $ids[] = $prev_post->ID;
    }

    $next_post = get_next_post();
    if(isset($next_post->ID)) {
        $ids[] = $next_post->ID;
    }

    return $ids;

}

function site_get_archive_nextprev() {
    global $wp_query, $paged;

    return array(
        'has_next_page' => $wp_query->max_num_pages > 1 && intval($paged?$paged:1) < $wp_query->max_num_pages,
        'next_page' => next_posts($wp_query->max_num_pages,false),
        'has_prev_page' => intval($paged) > 1,
        'prev_page' => previous_posts(false)
    );
}

function site_get_sub_field_meta_key($selector){
    // get current row
    $row = acf_get_loop('active');

    if(!$row) return false;

    $post_meta_key = /*$row['name'] . '_' . */$row['selector'] . '_' . $row['i'] . '_' . $selector;

    return $post_meta_key;
}

function site_get_show_custom_menu() {
    $navigation_source = get_field('navigation_source', 'options');
    $automatic = !$navigation_source || $navigation_source == 'auto';
    $show_custom_menu = $navigation_source == 'custom';

    if($automatic){
        if(isset($_POST['customized'])){
            $show_custom_menu = @array_filter(json_decode(wp_unslash($_POST['customized']), true));
        } else {
            $theme_menu_locations = get_nav_menu_locations();
            $show_custom_menu = isset($theme_menu_locations['main']) && get_objects_in_term( $theme_menu_locations['main'], 'nav_menu' );
        }
    }

    return $show_custom_menu;
}

function _site_walk_childs_of_custom_menu($current_menu_item, $menu_items_by_parent, &$sub_pages) {
    if(isset($menu_items_by_parent[$current_menu_item->ID]) && $menu_items_by_parent[$current_menu_item->ID]) {
        ksort($menu_items_by_parent[$current_menu_item->ID], SORT_NUMERIC);
        foreach ($menu_items_by_parent[$current_menu_item->ID] as $item) {
            $sub_pages[] = $item;
            _site_walk_childs_of_custom_menu($item, $menu_items_by_parent, $sub_pages);
        }
    }
}

function site_get_pages_of_root_parent($include_parent = false){
    $page_id = get_the_ID();

    if(site_get_show_custom_menu()) {

        $theme_menu_locations = get_nav_menu_locations();

        if(!isset($theme_menu_locations['main'])) return false;

        $menu_items = wp_get_nav_menu_items($theme_menu_locations['main'], array( 'update_post_term_cache' => false ));

        $menu_items_by_children = array();
        $menu_items_by_id = array();
        $current_page_nav = false;

        foreach ( (array) $menu_items as $menu_item ) {
            if($menu_item->object_id == $page_id) $current_page_nav = $menu_item;
            $menu_items_by_id[$menu_item->ID] = $menu_item;
            if(!isset($menu_items_with_children[ $menu_item->menu_item_parent ])) {
                $menu_items_with_children[ $menu_item->menu_item_parent ] = array();
            }
            $menu_items_with_children[ $menu_item->menu_item_parent ][$menu_item->menu_order] = $menu_item;
        }

        if(!$current_page_nav) return false;

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
        
        _site_walk_childs_of_custom_menu($current_loop_item, $menu_items_with_children, $sub_pages);

        $children = array();

        foreach ($sub_pages as $item) {
            $url = in_array($item->object, array('page','post')) ? get_permalink($item->object_id) : $item->url;
            $children[] = array(
                'active' => $current_page_nav->ID == $item->ID,
                'title' => $item->title,
                'url' => $url
            );
        }

    } else {
        $root_page = array_slice(get_ancestors( get_the_ID(), 'page' ), -1, 1);
        $root_page = isset($root_page[0]) ? (int) $root_page[0] : get_the_ID();
        $sub_pages = get_pages(array(
            'child_of' => $root_page,
            'orderby' => 'menu_order'
        ));
        if($include_parent) array_unshift($sub_pages, get_page($root_page));
        $children = array();
        foreach ($sub_pages as $item) {
            $children[] = array(
                'active' => $page_id == $item->ID,
                'title' => $item->post_title,
                'url' => get_permalink($item->ID)
            );
        }
    }
    return $children;
};
