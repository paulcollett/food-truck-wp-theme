<?php get_header(); ?>

<section class="contain contain--md ptmd mbmd">
  <div class="content">

		<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

        // TODO:
				//get_template_part( 'template-parts/page/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
		?>

  </div>
</section>

<?php get_footer(); ?>
