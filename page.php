<?php get_header(); ?>

<?php $children = site_get_pages_of_root_parent(true); if ( $children && count($children) > 1 ) : ?>
    <div class="sub-pages">
    <?php foreach ( $children as $item ): ?>
        <div><a href="<?php echo esc_attr($item['url']); ?>" class="<?php site_classname_filter(site_request_store_get('button-class-array'), 'button--compact'); ?>"><span><?php echo esc_html($item['title']); ?></span></a></div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if(get_field('layout_type') == 'themelot'): ?>

    <!-- Layout Modules Section -->
    <div class="section section--modules">

    <?php while ( have_rows('layout') ) : the_row(); $layout_index = isset($layout_index) ? $layout_index+1 : 1; ?>

        <?php if(get_row_layout() !== 'background') do_action('brace-module-before-section', get_row_layout(), $layout_index); ?>

        <!-- Layout Module -->
        <?php if(current_user_can('edit_posts')): ?>
            <!-- Admin Debug Message -->
            <div class="debug-section debug-section--module">
                <div class="debug-section_sub">Admin Only Notice:</div>
                &darr; PAGE Layout Module #<?php echo $layout_index; ?>: <?php echo get_row_layout(); ?>
            </div>
            <!-- / Admin Debug Message -->
        <?php endif; ?>

        <?php do_action('brace-module-before', get_row_layout(), $layout_index); ?>

        <?php if( get_row_layout() == 'home_module' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
                <?php site_include('/templates/home_module.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'post_items' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/post_items.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'hero_text' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/hero_text.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'location_module' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/location_module.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'display_menus' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/display_menus.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'video' ): ?>

            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/video.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'image' ): ?>

            <div class="module module--pad-top module<?php echo $layout_index; ?>">
            <?php site_include('/templates/image.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'text' ): ?>
            
            <div class="module module--pad-top module<?php echo $layout_index; ?>">
            <?php site_include('/templates/text.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'heading' ): ?>
            
            <div class="module module--pad-top module<?php echo $layout_index; ?>">
            <?php site_include('/templates/heading.php', array('h1_used' => isset($h1_used))); $h1_used = true; ?>
            </div>

        <?php elseif( get_row_layout() == 'map' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/map.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'contact_form' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/contact_form.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'text_image' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/text_image.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'button' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/button.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'testimonials' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/testimonials.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'slider' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/slider.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'layout_spacer' ): ?>
            
            <div class="module module<?php echo $layout_index; ?>">
            <?php site_include('/templates/layout_spacer.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'grid' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/grid.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'email_subscribe' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/email_subscribe.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'advanced' ): ?>
            
            <div class="module module--pad module<?php echo $layout_index; ?>">
            <?php site_include('/templates/advanced.php'); ?>
            </div>

        <?php elseif( get_row_layout() == 'background' ): ?>

        <!-- / Layout Module -->

    </div>
    <!-- / Layout Modules Section -->
    <?php

        // New Background Styles
        $bg_classname = site_get_styles_to_classname(array(
            'background-color' => get_sub_field('background_color'),
            'color' => get_sub_field('text_color'),
        ));

    ?>
    <!-- Layout Modules Section -->
    <div class="<?php site_classname_filter(array('section','section--background',$bg_classname)); ?>">
        <?php endif; ?>

        <!-- / Layout Module -->
        <?php if(get_row_layout() !== 'background') do_action('brace-module-after', get_row_layout(), $layout_index); ?>

    <?php endwhile; ?>


    </div>
    <!-- / Layout Modules Section -->


<?php else: ?>

    <div class="section section--page">
        <div class="contain contain--body contain--margin">
            <?php the_post(); the_content(); ?>
        </div>
    </div>

<?php endif; ?>

<?php
    // note: $content_width = 900;
    // is not required as this is a reponsive theme.
    // this message is here as it is a legacy wordpress check
?>

<?php get_footer(); ?>