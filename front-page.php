<?php get_header(); ?>

<section class="contain contain--md ptmd mbmd">
  <div class="content">

		<?php // Show the selected frontpage content.
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part('template-parts/content');
			endwhile;
		else :
			get_template_part('template-parts/content', 'none');
		endif; ?>

  </div>
</section>

<?php get_footer(); ?>
