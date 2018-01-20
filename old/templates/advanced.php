<div class="contain <?php echo get_sub_field('container_size') ? 'contain--' . get_sub_field('container_size') : ''; ?>">

    <?php if(get_sub_field('advance_type') == 'html'): ?>
        <?php if(get_sub_field('advanced_html')): ?><?php echo get_sub_field('advanced_html'); ?><?php endif; ?>
    <?php elseif(get_sub_field('advance_type') == 'shortcodes'): ?>
        <?php if(get_sub_field('advanced_shortcodes')): ?><?php echo do_shortcode(get_sub_field('advanced_shortcodes')); ?><?php endif; ?>
    <?php endif; ?>

</div>
