<?php
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) return;
?>

<?php if ( get_comments_number() ) : ?>

    <div class="contain contain--body contain--margin">

        <?php
            $comments = get_comments(array(
                'post_id' => get_the_ID(),
                'status' => 'all',//'approve', //Change this to the type of comments to be displayed
                'reverse_top_level' => false //Show the latest comments at the top of the list
            ));
        ?>

        <?php foreach($comments as $comment): ?>
            <div class="post-comment" id="comment-<?php echo $comment->comment_ID; ?>">
                <div class="post-comment_image">
                    <div class="image image--rounded">
                        <img src="//www.gravatar.com/avatar/<?php echo md5($comment->comment_author_IP); ?>?d=retro" />
                    </div>
                </div>
                <div class="post-comment_body">
                    <div class="accent">
                        <?php echo esc_html($comment->comment_author); ?>
                    </div>
                    <div class="">
                        <?php if(isset($comment->user_id) && ($user = get_userdata($comment->user_id)) && isset($user->roles)) echo '<span class="badge">' . implode(', ',$user->roles) . '</span>'; ?>
                        <?php echo date('F j, Y \a\t g:ia', strtotime($comment->comment_date)); ?>
                    </div>
                    <div class="copy">
                        <?php echo esc_html(strip_tags($comment->comment_content)); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div id="toggle-comments" class="contain contain--body contain--margin js-post-comment" style="<?php echo get_comments_number() ? '' : 'display:none';?>">
    <div class="module module--pad">
    <?php
        comment_form(array(
            'class_submit' => 'btn',
            'title_reply_before' => '<div class="accent fs18">',
            'title_reply_after' => '</div>'
        ));
    ?>
    </div>
</div>