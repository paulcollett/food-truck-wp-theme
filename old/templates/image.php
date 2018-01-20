<?php if(get_sub_field('image')): ?>
    <div class="contain <?php echo get_sub_field('size') ? 'contain--' . get_sub_field('size') : ''; ?> center">

        <?php if(site_get_field_link(true, false)): ?>
            <a href="<?php echo site_get_field_link(true); ?>">
                <?php site_image(get_sub_field('image'),array('w'=>1600,'h'=>1600)); ?>
            </a>
        <?php else: ?>
            <?php site_image(get_sub_field('image'),array('w'=>1600,'h'=>1600)); ?>
        <?php endif; ?>
        
    </div>
<?php endif; ?>
