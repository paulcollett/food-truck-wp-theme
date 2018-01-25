<?php get_header(); ?>

<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="page-header">
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</header>
	<?php else : ?>
	<header class="page-header">
		<h2 class="page-title"><?php _e( 'Posts', 'twentyseventeen' ); ?></h2>
	</header>
<?php endif; ?>

<section class="contain contain--md ptmd mbmd">
  <div class="content">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>

        <?php
          if ( 'post' === get_post_type() ) {
            echo '<div class="entry-meta">';
              if ( is_single() ) {
                //twentyseventeen_posted_on();
              } else {
                //echo twentyseventeen_time_link();
                //twentyseventeen_edit_link();
              };
            echo '</div><!-- .entry-meta -->';
          };

          if ( is_single() ) {
            the_title( '<h1 class="entry-title">', '</h1>' );
          } elseif ( is_front_page() && is_home() ) {
            the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
          } else {
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
          }
        ?>

        <?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
          <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
            </a>
          </div><!-- .post-thumbnail -->
        <?php endif; ?>

        <?php
          /* translators: %s: Name of current post */
          the_content(sprintf(
            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
            get_the_title()
          ));
        ?>
        <?php
          wp_link_pages(array(
            'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
            'after'       => '</div>',
            'link_before' => '<span class="page-number">',
            'link_after'  => '</span>',
          ));
        ?>

      <?php endwhile; ?>
    <?php else: ?>
      <h1 class="page-title"><?php _e( 'Nothing Found', 'twentyseventeen' ); ?></h1>
      <?php if(is_home() && current_user_can( 'publish_posts' )): ?>
        <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'twentyseventeen' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
      <?php else : ?>
        <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentyseventeen' ); ?></p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
