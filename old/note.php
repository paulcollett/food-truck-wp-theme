<?php die; ?>

Notes on the wordpress checks:

The following functions have been replicated in single.php

paginate_comments_links($args)
wp_enqueue_script("comment-reply")
wp_list_comments($args)
wp_link_pages()
the_posts_navigation()
comments_template()
get_the_tag_list()
the_post_thumbnail()

While we dont have the following functions we provide the same
functionality through our our own customisation.
add_theme_support( "custom-header", $args )
add_theme_support( "custom-background", $args )
add_editor_style()

$content_width variable is not required as this is a reponsive theme
