<div class="contain contain--narrow contain--margin js-subscribe-container">
    <form action="?trucklot_email_sub" method="post" class="grid grid--gutter2 grid--middle js-subscribe-form">
        <div class="grid_fill">
            <input type="email" name="_e" placeholder="Email Address" class="w100">
            <input type="hidden" name="_p" value="<?php echo get_the_ID(); ?>" />
            <input type="hidden" name="_wpm" value="<?php echo wp_create_nonce('themelot-subscribe-form'); ?>" />
            <input type="hidden" name="_c1" value="<?php echo base64_encode(site_get_sub_field_meta_key('mailchimp_api_key')); ?>" />
            <input type="hidden" name="_c2" value="<?php echo base64_encode(site_get_sub_field_meta_key('mailchimp_list_id')); ?>" />
        </div>
        <div class="at500w100 center">
            <?php site_include('/templates/common_button.php', array('label' => 'subscribe', 'link'=>'#', 'class' => 'w100')); ?>
        </div>
    </form>
    <script type="text/html">
        <div class="alert center email-subscribed-message">
            Subscribed
        </div>
    </script>
</div>
<script>window.BraceFramework && window.BraceFramework.subscribeReady()</script>