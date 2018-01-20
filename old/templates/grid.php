<?php

    $cells = get_sub_field('grid');

    // if we have items in our grid
    if($cells):
        $grid_item_i = -1;
        $cell_indexes_to_classname = site_get_grid_item_sizeclass($cells);
?>

<div class="contain contain--margin contain--<?php echo get_sub_field('container_size') ? get_sub_field('container_size') : 'max1200'; ?>">
    <div class="<?php site_classname_filter(array(
        'grid',
        'grid--middle' => get_sub_field('grid_vertical_alignment'),
        'grid--gutter2' => get_sub_field('grid_gutters') && get_sub_field('grid_gutters') == 'small',
        'grid--gutter3' => !get_sub_field('grid_gutters') || get_sub_field('grid_gutters') == 'normal',
        'grid--gutter4' => get_sub_field('grid_gutters') && get_sub_field('grid_gutters') == 'large'
    )); ?>">

        <?php
            while ( have_rows('grid') ) :
                the_row();
                $grid_item_i++;
                $gitem_class = isset($cell_indexes_to_classname[$grid_item_i]) ? $cell_indexes_to_classname[$grid_item_i] : '';
        ?>
            <?php if( get_row_layout() == 'text' ): ?>
                <?php if(get_sub_field('text')): ?>
                    <div class="<?php echo $gitem_class; ?>">
                        <?php the_sub_field('text'); ?>
                    </div>
                <?php endif; ?>
            <?php elseif( get_row_layout() == 'video' ): ?>
                <div class="<?php echo $gitem_class; ?>">
                    <div class="frame frame--widescreen frame--min-sm">
                        <?php site_include('/templates/common_video.php'); ?>
                    </div>
                </div>
            <?php elseif( get_row_layout() == 'image' ): ?>
                <?php if(get_sub_field('image')): ?>
                <div class="<?php echo $gitem_class; ?>">
                    <div style="<?php echo get_sub_field('image_max_width') ? 'max-width:' . site_get_num_with_unit(get_sub_field('image_max_width')) : ''; ?>">
                    <?php if(site_get_field_link(true, false)): ?>
                        <a href="<?php echo site_get_field_link(true); ?>">
                            <?php site_image(get_sub_field('image'),array('w' => get_sub_field('image_max_width') ? get_sub_field('image_max_width')*2 : 1600)); ?>
                        </a>
                    <?php else: ?>
                        <div class="center">
                            <?php site_image(get_sub_field('image'),array('w' => get_sub_field('image_max_width') ? get_sub_field('image_max_width')*2 : 1600)); ?>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php elseif( get_row_layout() == 'button' ): ?>
                <div class="<?php echo $gitem_class; ?>">
                    <div>
                        <?php site_include('/templates/common_button.php', array('sub_field' => true)); ?>
                    </div>
                </div>
            <?php elseif( get_row_layout() == 'new_row' ): ?>
                <div class="grid_separator <?php echo $gitem_class; ?>"></div>
            <?php endif; ?>
        <?php endwhile; ?>

    </div>
</div>

<?php endif; ?>
