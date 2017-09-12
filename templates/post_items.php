<?php

    if(isset($ids)) {
        $ids = $ids;
    } else if(get_sub_field('posts_to_show') == 'choose') {
        $ids = get_sub_field('posts') ? get_sub_field('posts') : array();
    } else {
        $ids = null;
    }

    $query = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => get_sub_field('count') > 0 ? (int) get_sub_field('count') : 2,
        'post__in' => $ids,
        'orderby' => 'post__in'
    ));

    $show_excerpt = isset($excerpt) ? $excerpt : get_sub_field('show_excerpt');
    $show_image = isset($image) ? $image : get_sub_field('show_featured_image');

    if(isset($columns)) {
        $columns = $columns;
        $contain_size = 'max1200';
    } else if(($show_excerpt || $show_image) && $query->found_posts > 1) {
        $columns = 2;
        $contain_size = 'max900 contain--margin';
    } else {
        $columns = 1;
        $contain_size = 'narrow contain--margin';
    }

?>
<?php if ( $query->have_posts() ) : ?>
    
    <div class="contain contain--<?php echo $contain_size; ?>">
        <div class="news-grid news-grid--cols<?php echo $columns; ?>">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $item_has_image = $show_image ? (get_field('post_featured_image') || has_post_thumbnail()) : false;
            ?>
                <div class="">
                    <div class="news-item<?php echo $item_has_image ? ' news-item--image' : ''; ?>">
                        <?php if($item_has_image): ?>
                            <a href="<?php the_permalink(); ?>" class="news-item_image">
                                <?php if(get_field('post_featured_image')): ?>
                                    <?php site_image(get_field('post_featured_image'),array('w'=>240,'h'=>300)); ?>
                                <?php elseif(has_post_thumbnail()): ?>
                                    <?php site_image(get_post_thumbnail_id(),array('w'=>240,'h'=>300)); ?>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                        <div class="news-item_info">
                            <a href="<?php the_permalink(); ?>" class="accent"><?php the_title(); ?></a>
                            <?php if($show_excerpt): ?>
                                <div class="copy truncate truncate--2lines">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>

<?php elseif(!isset($hide_not_found)) : ?>

    <?php if(current_user_can('edit_posts')): ?>
    <div class="debug-section">
        <a href="#" class="debug-section_link">Add A Post +</a>
        <div class="debug-section_sub">Admin Only Notice:</div>
        <div>No Posts to show in the "post items" module</div>
    </div>
    <?php endif; ?>

<?php endif; ?>
