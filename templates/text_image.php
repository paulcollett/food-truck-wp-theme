<div class="contain contain--margin contain--max900">
    <div class="layout-text-img <?php echo get_sub_field('image_position') == 'left' ? 'layout-text-img--left' : 'layout-text-img--right'; ?>">
        <div class="layout-text-img_img">
            <?php if(get_sub_field('image')): ?>
                <?php site_image(get_sub_field('image'),array('w'=>800,'h'=>800)); ?>
            <?php endif; ?>
        </div>
        <div class="layout-text-img_text">
            <div class="copy">
                <?php if(get_sub_field('text')): ?>
                    <?php the_sub_field('text'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
