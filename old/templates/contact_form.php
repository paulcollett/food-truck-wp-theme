<div class="contain contain--narrow contain--margin">
    <form class="js-contact-form" action="?trucklot_generic_contact=1" method="post">
        <div class="field">
            <label class="accent">Your Name</label>
            <input type="text" name="_n" />
        </div>

        <div class="field">
            <label class="accent">Phone / Email</label>
            <input type="text" name="_c" />
        </div>

        <div class="field">
            <label class="accent">Message</label>
            <textarea name="_m"></textarea>
        </div>

        <div class="field field--hidden">
            <input type="text" name="_s" />
        </div>

        <input type="hidden" name="_p" value="<?php echo get_the_ID(); ?>" />
        <input type="hidden" name="_wpm" value="<?php echo wp_create_nonce('themelot-generic-form'); ?>" />

        <?php site_include('/templates/common_button.php', array('label' => 'Send', 'link' => '#submit', 'class'=> 'at500w100')); ?>

        <script type="text/html">
            <div class="alert">
                <?php if(get_sub_field('success_message')): ?>
                    <div class="copy">
                        <?php the_sub_field('success_message'); ?>
                    </div>
                <?php else: ?>
                    <p class="alert">Your message has been sucessfully sent</p>
                <?php endif; ?>
            </div>
        </script>
    </form>
    <script>window.BraceFramework && window.BraceFramework.contactFormReady()</script>
</div>
