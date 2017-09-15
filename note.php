<?php die; ?>

Notes on the legacy wordpress checks:

The following functions have been replicated in single.php

paginate_comments_links($args)
wp_enqueue_script("comment-reply")
wp_list_comments($args)
wp_link_pages()
the_posts_navigation()
comments_template()
get_the_tag_list()

$content_width variable is not required as this is a reponsive theme
