<?php get_header(); the_post(); ?>

    <div class="section--page">
        <div class="module module--pad">
            <div class="contain contain--body contain--margin">

                <div class="margin-bottom-md">
                    <h1 class="heading heading--small accent margin-bottom-xs"><?php the_title(); ?></h1>
                    <?php the_time('F j, Y'); ?>
                </div>

                <div class="copy">
                    <div class="post-feature-image">
                        <?php if(get_field('post_featured_image')): ?>
                            <?php site_image(get_field('post_featured_image'),array('w'=>800,'h'=>700)); ?>
                        <?php elseif(has_post_thumbnail()): ?>
                            <?php site_image(get_post_thumbnail_id(),array('w'=>800,'h'=>700)); ?>
                        <?php endif; ?>
                    </div>

                    <?php the_content(); ?>
                </div>

            </div>
        </div>

    <?php if(get_field('layout')): ?>

        <?php while ( have_rows('layout') ) : the_row(); $layout_index = isset($layout_index) ? $layout_index+1 : 1; ?>

            <?php do_action('brace-post-module-before', get_row_layout()); ?>

            <!-- POST Layout Modules -->
            <?php if(current_user_can('edit_posts')): ?>
                <!-- Admin Debug Message -->
                <div class="debug-section debug-section--module">
                    <div class="debug-section_sub">Admin Only Notice:</div>
                    &darr; POST Layout Module #<?php echo $layout_index; ?>: <?php echo get_row_layout(); ?>
                </div>
                <!-- / Admin Debug Message -->
            <?php endif; ?>

            <?php if( get_row_layout() == 'video' ): ?>

                <div class="module module--pad">
                    <?php site_include('/templates/video.php'); ?>
                </div>

            <?php elseif( get_row_layout() == 'image' ): ?>

                <div class="module module--pad">
                    <?php site_include('/templates/image.php'); ?>
                </div>

            <?php elseif( get_row_layout() == 'text' ): ?>
                
                <div class="module module--pad">
                    <?php site_include('/templates/text.php'); ?>
                </div>

            <?php elseif( get_row_layout() == 'heading' ): ?>
                
                <?php site_include('/templates/heading.php'); ?>

            <?php elseif( get_row_layout() == 'map' ): ?>
                
                <div class="module module--pad">
                    <?php site_include('/templates/map.php'); ?>
                </div>

            <?php elseif( get_row_layout() == 'text_image' ): ?>
                
                <div class="module module--pad">
                    <?php site_include('/templates/text_image.php'); ?>
                </div>

            <?php elseif( get_row_layout() == 'button' ): ?>
                
                <div class="module module--pad">
                    <?php site_include('/templates/button.php'); ?>
                </div>

            <?php elseif( get_row_layout() == 'advanced' ): ?>
                
                <div class="module module--pad">
                    <?php site_include('/templates/advanced.php'); ?>
                </div>

            <?php elseif( get_row_layout() == 'testimonials' ): ?>
                
                <div class="module module--pad">
                <?php site_include('/templates/testimonials.php'); ?>
                </div>

            <?php elseif( get_row_layout() == 'slider' ): ?>
                
                <div class="module module--pad">
                <?php site_include('/templates/slider.php'); ?>
                </div>

            <?php endif; ?>
            <!-- / POST Layout Modules -->

            <?php do_action('brace-post-module-after', get_row_layout()); ?>

        <?php endwhile; ?>

    <?php endif; ?>

    <?php if(get_field('posts_social_icons', 'options') || get_field('posts_comment_button', 'options')): ?>
    <div class="module module--pad">
        <div class="contain contain--body contain--margin">
            <div class="post-toolbar">
                <?php if(get_field('posts_social_icons', 'options')): ?>
                <div class="post-toolbar_social">
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>&t=<?php echo urlencode(get_the_title()); ?>" class="button button--social"><span><i class="icon icon-facebook"></i></span></a>
                    <a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_the_permalink()); ?>" class="button button--social"><span><i class="icon icon-twitter"></i></span></a>
                </div>
                <?php endif; ?>
                <?php if(get_field('posts_comment_button', 'options')): ?>
                <div class="post-toolbar_comment">
                    <!-- Button Group -->
                    <?php site_include('/templates/common_button.php', array('sub_field' => true, 'label' => 'Leave a Comment', 'link' => '#toggle-comments')); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php site_include('/templates/comment_form.php'); ?>

    <?php if($related_item_ids = site_get_related_post_ids()): ?>
        <div class="contain contain--max900">
            <div class="module module--pad">
                <?php
                    site_include('/templates/post_items.php', array(
                        'ids' => $related_item_ids,
                        'columns' => min(3, count($related_item_ids)),
                        'image' => true,
                        'excerpt' => true
                    ));
                ?>
            </div>
        </div>
    <?php endif; ?>

</div>
<?php get_footer(); ?>