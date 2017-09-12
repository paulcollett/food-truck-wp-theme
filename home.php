<?php get_header(); ?>

<?php if(is_front_page() && is_super_admin()): ?>
    <!-- Admin Debug Message -->
    <div class="debug-section debug-section--show">
        <div class="debug-section_sub">Admin Only Notice:</div>
        This is your home page. It currently lists your lastest blog posts. Change to a customisable page under <a href="<?php echo admin_url('options-reading.php'); ?>">settings > reading</a>
    </div>
    <!-- / Admin Debug Message -->
<?php endif; ?>

<div class="section section--page">

    <?php if(is_home() && get_field('posts_listing_heading', 'options')): ?>
    <div class="contain contain--body contain--margin">
        <h1 class="heading heading--medium margin-bottom-lg">
            <?php the_field('posts_listing_heading', 'options'); ?>
        </h1>
    </div>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>
        <div class="contain contain--max900 margin-bottom-lg">
            <?php while ( have_posts() ) : the_post(); ?>
            <div class="post-listing-item">
                <div class="post-listing-item_image">
                    <a href="<?php the_permalink(); ?>">
                    <?php if(get_field('post_featured_image')): ?>
                        <?php site_image(get_field('post_featured_image'),array('w'=>600,'h'=>600)); ?>
                    <?php elseif(has_post_thumbnail()): ?>
                        <?php site_image(get_post_thumbnail_id(),array('w'=>600,'h'=>600)); ?>
                    <?php endif; ?>
                    </a>
                </div>
                <div class="post-listing-item_details">
                    <div class="margin-bottom-md">
                        <h2 class="heading heading-size2 accent margin-bottom-xs"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_time('F jS, Y'); ?>
                    </div>
                    <div class="post-listing-item_exerpt copy">
                        <?php echo get_the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>">Continue Reading: <?php the_title(); ?> &rarr;</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <?php extract(site_get_archive_nextprev()); ?>
        <?php if($has_prev_page || $has_next_page): ?>
        <div class="contain contain--body contain--margin">
            
            <div class="center">
                <?php if($has_prev_page): ?>
                    <a href="<?php echo $prev_page; ?>" class="<?php site_classname_filter(site_request_store_get('button-class-array')); ?>"><span>Newer</span></a>
                <?php endif; ?>
                <?php if($has_next_page): ?>
                    <a href="<?php echo $next_page; ?>" class="<?php site_classname_filter(site_request_store_get('button-class-array')); ?>"><span>Older</span></a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="contain contain--max900 center">
            (no posts)
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>