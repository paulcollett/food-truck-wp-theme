<?php get_header(); ?>

<?php
  ftt_content_contain_before(array(
    'container_class' => array('error-404', 'not-found')
  ));
?>

<header class="page-header">
  <h1 class="page-title"><?php _e( 'This page can&rsquo;t be found.', 'food-truck' ); ?></h1>
</header>

<p><?php _e( 'Try the home page or one of the navigation links', 'food-truck' ); ?></p>

<?php ftt_content_contain_after(); ?>

<?php get_footer();
