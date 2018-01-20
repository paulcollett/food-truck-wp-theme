<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link href="<?php site_asset('dist/css/main.css'); ?>" rel="stylesheet">

<!-- WP Head -->
<?php if(function_exists('wp_get_document_title')) wp_get_document_title(); ?>
<?php wp_head(); ?>
<!-- / WP Head -->

<?php site_favicon_tag(); ?>

<!-- GoogleAnalytics -->
<?php site_output_google_analytics(get_field('google_analytics_id','options')); ?>
<!-- / GoogleAnalytics -->

<!-- Site Styles -->
<?php site_output_styles(); ?>
<!-- / SiteStyles -->

</head>
<body <?php body_class('site site-v1' . (get_field('header_fixed','options') === false ? '' : ' site--fixed-header')); ?>> <!-- site--fixed-header -->

<header class="header">

    <div class="header-contain">

        <div class="logo-container">
            <?php do_action('brace-logo-before'); ?>
            <a href="<?php echo home_url(); ?>" class="logo">
                <?php if(!get_field('logo_type', 'options') || get_field('logo_type', 'options') == 'default'): ?>
                    <?php echo get_bloginfo('name', 'display'); ?>
                <?php elseif(get_field('logo_type', 'options') == 'text'): ?>
                    <?php if(get_field('logo_text', 'options')): ?>
                        <?php the_field('logo_text', 'options'); ?>
                    <?php else: ?>
                        ? NO LOGO TEXT ?
                    <?php endif; ?>
                <?php elseif(get_field('logo_type', 'options') == 'image'): ?>
                    <?php if(get_field('logo_image', 'options')): ?>
                        <?php site_image(get_field('logo_image', 'options'), array('w'=>250*2,'h'=>150*2,'alt'=>str_replace('"','\'',get_bloginfo('name', 'display')))); ?>
                    <?php else: ?>
                        ? NO LOGO IMAGE ?
                    <?php endif; ?>
                <?php endif; ?>
            </a>
            <?php do_action('brace-logo-after'); ?>
        </div>

        <div class="navigation-container">
            <div class="navigation-container_right">
                <?php do_action('brace-navgiation-before'); ?>
                <div class="text-right"><?php the_field('navigation-before-text', 'options'); ?></div>
            </div>
            <div class="<?php site_classname_filter(array_merge(array('navigation'),site_request_store_get('navigation-class-array', array()),array('button--compact' => false))); ?>">
                <ul>
                    <?php do_action('brace-navgiation-items-first'); ?>

                    <?php

                        if(site_get_show_custom_menu()) {
                            wp_nav_menu( array(
                                'theme_location' => 'main',
                                'container' => false,
                                //'fallback_cb' => false,
                                'depth' => 1,
                                'items_wrap' => '%3$s'
                            ));
                        } else {
                            wp_list_pages( array(
                                'title_li' => '',
                                'depth' => 1
                            ));
                        }
                    ?>

                    <?php do_action('brace-navgiation-items-last'); ?>
                </ul>
            </div>
            <div class="navigation-container_right">
                <?php do_action('brace-navgiation-after'); ?>
            </div>
        </div>

        <div class="navigation-toggle">
            <div class="menu-icon"><span class="menu-icon_inner"></span></div>
        </div>

    </div>

</header>

<div class="header-faux"></div>
<script>window.BraceFramework && BraceFramework.headerReady()</script>
