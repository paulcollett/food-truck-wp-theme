<?php if(get_sub_field('text') || get_sub_field('has_button')): ?>
    <div class="contain contain--margin">
        <div class="center">
            
            <div class="hero-text accent">
                <div class="copy<?php echo get_sub_field('button_label') ? ' margin-bottom-sm' : ''; ?>">
                    <?php the_sub_field('text'); ?>
                </div>
            </div>

            <?php if(get_sub_field('button_label')): ?>

                <!-- Button Group -->
                <?php site_include('/templates/common_button.php', array('sub_field' => true)); ?>

            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
