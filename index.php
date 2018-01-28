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

  <?php
			if (have_posts()):
				// Start the Loop
				while (have_posts()) : the_post();
					get_template_part( 'template-parts/content');
				endwhile;

				the_posts_pagination(array(
					'prev_text' => '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>',
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
				));
			else :
				get_template_part('template-parts/content', 'none');
			endif;
	?>

  </div>
</section>

<?php get_footer(); ?>
