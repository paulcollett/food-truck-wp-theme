<div class="layout-profile">
    <div class="layout-profile_left">
        <?php if(get_sub_field('left_column_image')): ?>
            <div class="image <?php echo get_sub_field('image_rounded') ? 'image--rounded' : ''; ?> center">
                <?php site_image(get_sub_field('left_column_image'),array('w'=>300,'h'=>300)); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="layout-profile_right">
        <?php if(get_sub_field('text')): ?>
            <div class="copy">
                <?php the_sub_field('text'); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="layout-profile_left">
        <?php if(get_sub_field('has_left_text')): ?>
            <div class="copy">
                <?php the_sub_field('text_left'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
