<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php
    if ( 'post' === get_post_type() ) {
      echo '<div class="entry-meta">';
        if ( is_single() ) {
          fft_posted_on();
        } else {
          echo fft_time_link();
          fft_edit_link();
        };
      echo '</div>';
    };

    if ( is_single() ) {
      the_title( '<h2 class="entry-title">', '</h2>' );
    } elseif ( is_home() ) {
      the_title( '<h2 class="entry-title">', '</h2>' );
    }
  ?>

  <?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
    <div class="post-thumbnail">
      <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail('large'); ?>
      </a>
    </div>
  <?php endif; ?>

  <?php
    /* translators: %s: Name of current post */
    the_content(sprintf(
      __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'food-truck' ),
      get_the_title()
    ));

    wp_link_pages(array(
      'before'      => '<div class="page-links">' . __( 'Pages:', 'food-truck' ),
      'after'       => '</div>',
      'link_before' => '<span class="page-number">',
      'link_after'  => '</span>',
    ));
  ?>
</article>
