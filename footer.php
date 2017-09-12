<?php

    // Footer Background Styles
    $bg_classname = site_get_styles_to_classname(array(
        'background-color' => get_field('footer_background_color', 'options'),
        'color' => get_field('footer_text_color', 'options'),
    ));

?>

<footer class="<?php site_classname_filter(array('footer', $bg_classname)); ?>">

    <?php if(get_field('footer_content', 'options')): ?>
    <div class="margin-bottom-md">
        <?php while ( have_rows('footer_content', 'options') ) : the_row(); ?>
            <?php if( get_row_layout() == 'text' ): ?>
                <div class="module module--pad-top">
                    <?php site_include('/templates/text.php'); ?>
                </div>
            <?php elseif( get_row_layout() == 'social_buttons' ): ?>
                <div class="module module--pad-top">
                    <div class="contain contain--margin center">
                        <?php if(get_sub_field('facebook_url')): ?><a target="_blank" href="<?php the_sub_field('facebook_url'); ?>" class="button button--social"><span><i class="icon icon-facebook"></i></span></a><?php endif; ?>
                        <?php if(get_sub_field('twitter_url')): ?><a target="_blank" href="<?php the_sub_field('twitter_url'); ?>" class="button button--social"><span><i class="icon icon-twitter"></i></span></a><?php endif; ?>
                        <?php if(get_sub_field('instagram_url')): ?><a target="_blank" href="<?php the_sub_field('instagram_url'); ?>" class="button button--social"><span><i class="icon icon-instagram"></i></span></a><?php endif; ?>
                    </div>
                </div>
            <?php elseif( get_row_layout() == 'grid' ): ?>
                <div class="module module--pad-top">
                    <?php site_include('/templates/grid.php'); ?>
                </div>
            <?php elseif( get_row_layout() == 'advanced' ): ?>
                <div class="module module--pad-top">
                    <?php site_include('/templates/advanced.php'); ?>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>

    <?php if(get_field('footer_credit', 'options')): ?>
    <div class="footer_credit">
        <?php
            echo str_ireplace(array_keys($credit_replace_map = array(
                '{copy}' => '&copy;',
                '{site_name}' => get_bloginfo('name','display'),
                '{year}' => current_time('Y'),
                '{powered_by}' => 'Powered by <a href="{**THEMEURI**}" target="_blank">{**THEMENAME**}</a>'
            )),array_values($credit_replace_map), get_field('footer_credit', 'options'));
        ?>
    </div>
    <?php endif; ?>

</footer>

<!-- WP Footer -->
<?php wp_footer(); ?>
<!-- / WP Footer -->

<?php
    $custom_js = trim(str_replace(array('<script>','</script>','<script type=\'text/javascript\'>','<script type="text/javascript">','<script src="'),'',get_field('custom_js', 'options')));
    echo $custom_js ? ( '<!-- START CUSTOM JS --><script>(function($){try{' . $custom_js . '}catch(e){console.error("CUSTOM JS ERROR:");console.warn(e)}})(window.jQuery)</script><!-- END CUSTOM JS-->' ) : '';
?>

</body>
</html>