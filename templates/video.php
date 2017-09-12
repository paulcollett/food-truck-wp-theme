<?php if(get_sub_field('video')): ?>
    <div class="contain <?php echo get_sub_field('size') ? 'contain--' . get_sub_field('size') : ''; ?>">
        <div class="frame">
            <!-- common video -->
            <?php site_include('/templates/common_video.php'); ?>
        </div>
    </div>
<?php endif; ?>
