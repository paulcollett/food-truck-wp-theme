<?php get_header(); ?>

<?php ftt_content_contain_before(); ?>

<?php if (have_posts()) : ?>
  <header class="page-header">
    <?php
      the_archive_title( '<h1 class="page-title">', '</h1>' );
      the_archive_description( '<div class="taxonomy-description">', '</div>' );
    ?>
  </header>
<?php endif; ?>

<?php if(have_posts()): ?>
  <?php
    while ( have_posts() ) : the_post();
      get_template_part( 'template-parts/content');
    endwhile;

    the_posts_pagination(array(
      'prev_text' => '<span class="screen-reader-text">' . __( 'Previous page', 'food-truck' ) . '</span>',
      'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'food-truck' ) . '</span>',
      'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'food-truck' ) . ' </span>',
    ));
  ?>
<?php else: ?>
  <?php get_template_part( 'template-parts/content', 'none' ); ?>
<?php endif; ?>

<?php ftt_content_contain_after(); ?>

<?php get_footer();
