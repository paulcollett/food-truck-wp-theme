<?php get_header(); ?>

<?php $children = ftt_get_pages_of_root_parent(); if(count($children) > 1): ?>
  <div class="sub-pages">
    <?php foreach ($children as $item): ?>
      <div id="sub-menu-item-<?php echo esc_attr($item['id']); ?>" class="sub-menu-item<?php echo $item['active'] ? ' current-sub-menu-item' : ''; ?>"><a href="<?php echo esc_attr($item['url']); ?>">
          <?php echo esc_html($item['title']); ?>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<section class="contain contain--md ptmd mbmd">
  <div class="content">

		<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part('template-parts/content');

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
		?>

  </div>
</section>

<?php get_footer(); ?>
