<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php
    if ( 'post' === get_post_type() ) {
      echo '<div class="entry-meta">';
        if ( is_single() ) {
          //twentyseventeen_posted_on();
        } else {
          //echo twentyseventeen_time_link();
          //twentyseventeen_edit_link();
        };
      echo '</div>';
    };

    if ( is_single() ) {
      the_title( '<h1 class="entry-title">', '</h1>' );
    } elseif ( /*is_front_page() &&*/ is_home() ) {
      the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
    } else {
      //the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
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

</article>
