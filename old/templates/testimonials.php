<?php if(get_sub_field('testimonials')): ?>
<div class="contain contain--max1200">

    <div class="testimonials">

        <?php while ( have_rows('testimonials') ) : the_row(); ?>

            <div class="">
                <?php

                    $stars_map = array(
                        '5' => str_repeat('<i class="icon icon--star"></i>', 5),
                        '4.5' => str_repeat('<i class="icon icon--star"></i>', 4) . '<i class="icon icon--star-half"></i>',
                        '4' => str_repeat('<i class="icon icon--star"></i>', 4),
                        '3.5' => str_repeat('<i class="icon icon--star"></i>', 3) . '<i class="icon icon--star-half"></i>',
                        '3' => str_repeat('<i class="icon icon--star"></i>', 3),
                        '2.5' => str_repeat('<i class="icon icon--star"></i>', 2) . '<i class="icon icon--star-half"></i>',
                        '2' => str_repeat('<i class="icon icon--star"></i>', 2),
                        '1.5' => str_repeat('<i class="icon icon--star"></i>', 1) . '<i class="icon icon--star-half"></i>',
                        '1' => str_repeat('<i class="icon icon--star"></i>', 1),
                        '0' => ''
                    );

                    $stars_html = isset($stars_map[(string) get_sub_field('rating')]) ? $stars_map[(string) get_sub_field('rating')] : false;

                ?>

                <div class="testimonial">
                    <?php if(get_sub_field('title')): ?><div class="testimonial_title accent"><?php the_sub_field('title'); ?></div><?php endif; ?>
                    <?php if(get_sub_field('text')): ?><div class="testimonial_text"><?php the_sub_field('text'); ?></div><?php endif; ?>
                    <?php if($stars_html): ?><div class="testimonial_stars"><?php echo $stars_html; ?></div><?php endif; ?>
                    <?php if(get_sub_field('image')): ?><div class="testimonial_image image image--rounded"><?php site_image(get_sub_field('image'),array('w'=>200,'h'=>200,'crop'=>true)); ?></div><?php endif; ?>
                    <?php if(get_sub_field('cite')): ?><div class="testimonial_cite"><?php the_sub_field('cite'); ?></div><?php endif; ?>
                    <?php if(get_sub_field('cite_location')): ?><div class="testimonial_location"><?php the_sub_field('cite_location'); ?></div><?php endif; ?>
                </div>
            </div>

        <?php endwhile; ?>

    </div>

</div>
<?php endif; ?>
