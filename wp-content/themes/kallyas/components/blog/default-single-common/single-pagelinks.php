<?php if(! defined('ABSPATH')){ return; }
/**
 * Single Page Links
 */

wp_link_pages( array (
    'before' => '<div class="page-link kl-blog-post-pagelink">',
    'after'  => '</div>',
    'link_before'      => '<span>',
    'link_after'       => '</span>',
    'next_or_number'   => 'number',
) );
