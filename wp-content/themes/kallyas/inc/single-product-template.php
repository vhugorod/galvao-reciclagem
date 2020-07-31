<?php if(! defined('ABSPATH')){ return; }
/*
 * This template will render the content defined in the selected Smart Area assigned to replace the default content of the Single Product page
 * @kos
 * @since v4.16
 */
get_header();

$pbTemplate = zget_option( 'woo_single_product_smart_area', 'zn_woocommerce_options', false, 'no_template' );

while ( have_posts() ) :
	the_post();
	$pb_data = get_post_meta( $pbTemplate, 'zn_page_builder_els', true );
	ZNB()->frontend->renderUneditableContent( $pb_data, $pbTemplate );
endwhile;

get_footer();
