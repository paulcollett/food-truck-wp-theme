<?php get_header(); ?>

<?php if(get_theme_mod('header_text', true)): ?>
  <section class="ftt-banner-tagline<?php echo get_theme_mod('ftt_theme_mod_tagline_bgtint', false) ? ' ftt-banner-tagline--tint' : ''; ?>">
    <div class="ftt-banner-tagline_contain content">
      <h1 class="ftt-banner-tagline-text">
        <span class="ftt-banner-tagline-text_line"><?php esc_html(bloginfo('description')); ?></span>
      </h1>
      <?php if(!!get_theme_mod('ftt_theme_mod_tagline_text', false)): ?>
        <p class="ftt-banner-tagline-text">
          <span class="ftt-banner-tagline-text_line"><?php echo esc_html(get_theme_mod('ftt_theme_mod_tagline_text', '')); ?></span>
        </p>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>

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

<?php get_footer();
