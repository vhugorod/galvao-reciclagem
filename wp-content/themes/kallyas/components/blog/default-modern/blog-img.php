<?php if(! defined('ABSPATH')){ return; }
/*
 * Get post content (in full-image width context)
 * @since v4.0.12
 */
$sb_archive_imgae_class = ( 'no' === $sb_archive_use_full_image ) ? 'kl-blog-fixedimg' : 'kl-blog-fullimg';
?>
<div class="kl-blog-item-content <?php echo esc_attr( $sb_archive_imgae_class ); ?> clearfix">

    <?php

    /**
     * Layout for normal fixed width image
     */
    if( $sb_archive_use_full_image == 'no' ){

        echo $current_post['content'];

        // Load read more button
        include(locate_template( 'components/blog/default-modern/blog-readmore.php' ));
    }
    /**
     * Layout for FULL image
     */
    elseif( $sb_archive_use_full_image == 'yes' ){

        // Load read more button
        include(locate_template( 'components/blog/default-modern/blog-readmore.php' ));

        echo $current_post['content'];
    }

    ?>

</div>
<!-- end Item Intro Text -->
