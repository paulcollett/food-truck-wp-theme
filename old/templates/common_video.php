<?php if(get_sub_field('video')): ?>
    <div class="video video--fill js-video">
        <div class="video_button accent-color"></div>
        <?php if(get_sub_field('video_cover_image')): ?>
            <div class="cover-image"><?php site_image(get_sub_field('video_cover_image'), array('w'=>750,'h'=>1000)); ?></div>
        <?php else: ?>
            <div class="cover-image"><?php site_oembed_video_image(get_sub_field('video')); ?></div>
        <?php endif; ?>
        <script type="text/html"><?php site_oembed_filter(get_sub_field('video')); ?></script>
    </div>
    <script>window.BraceFramework && window.BraceFramework.videoReady()</script>
<?php endif; ?>
