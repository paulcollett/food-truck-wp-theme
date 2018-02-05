<?php get_header(); ?>

<?php ftt_content_contain_before(); ?>

<?php
  /* Start the Loop */
  while ( have_posts() ) : the_post();

    get_template_part('template-parts/content');

    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
      comments_template();
    endif;

    the_post_navigation( array(
      'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'food-truck' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'food-truck' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . '</span>%title</span>',
      'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'food-truck' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'food-truck' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . '</span></span>',
    ) );

  endwhile; // End of the loop.
?>

<?php ftt_content_contain_after(); ?>

<?php get_footer(); ?>
