<?php get_header(); ?>

<div class="section--page">
    <div class="module module--pad">

        <h1 class="heading heading--medium"><?php echo get_field('404_error_page_title', 'options') ? get_field('404_error_page_title', 'options') : 'Page not found'; ?></h1>
        <p class="center">
            <a href="<?php echo home_url(); ?>" class="<?php site_classname_filter(site_request_store_get('button-class-array')); ?>">
                <span><?php echo get_bloginfo('name', 'display'); ?></span>
            </a>
        <p>

    </div>
</div>

<?php get_footer(); ?>
