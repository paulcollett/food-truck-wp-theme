<section class="no-results not-found">

  <h1 class="page-title"><?php _e( 'Nothing Found', 'twentyseventeen' ); ?></h1>

  <?php if(is_home() && current_user_can( 'publish_posts' )): ?>
    <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'twentyseventeen' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
  <?php else : ?>
    <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentyseventeen' ); ?></p>
  <?php endif; ?>

</section><!-- .no-results -->
