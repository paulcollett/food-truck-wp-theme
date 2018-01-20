<?php if(get_sub_field('slider')): ?>
<div class="slider slider--fill js-slider">
    <div>
    <?php while ( have_rows('slider') ) : the_row(); ?>
        <?php if( get_row_layout() == 'image' ): ?>
            <div class="slider_slide">
                <?php if(site_get_field_link(true, false)): ?>
                    <a href="<?php echo site_get_field_link(true); ?>">
                        <div class="cover-image">
                            <?php if(get_sub_field('image')): ?><?php site_image(get_sub_field('image'),array('w'=>1600,'h'=>1600)); ?><?php endif; ?>
                        </div>
                    </a>
                <?php else: ?>
                    <div class="cover-image">
                        <?php if(get_sub_field('image')): ?><?php site_image(get_sub_field('image'),array('w'=>1600,'h'=>1600)); ?><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif( get_row_layout() == 'video' ): ?>
            <div class="slider_slide">

                <!-- common video -->
                <?php site_include('/templates/common_video.php'); ?>

            </div>
        <?php elseif( get_row_layout() == 'text_and_button' ): ?>
            <div class="slider_slide">
                <div class="cover-image">
                    <?php if(get_sub_field('background_image')): ?><?php site_image(get_sub_field('background_image'),array('w'=>1600,'h'=>1600)); ?><?php endif; ?>
                </div>
                <div class="centered-fill-text centered-fill-text--style1">
                    <div class="center">
                    <?php if(get_sub_field('text')): ?>
                        <div class="centered-fill-text_text copy fs18 margin-bottom-sm accent">
                            <?php the_sub_field('text'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(get_sub_field('button_label')): ?>
                        <!-- Button Group -->
                        <?php site_include('/templates/common_button.php', array('sub_field' => true)); ?>
                    <?php endif; ?>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    <?php endwhile; ?>
    </div>
</div>
<script>window.BraceFramework && window.BraceFramework.sliderReady()</script>
<?php endif; ?>