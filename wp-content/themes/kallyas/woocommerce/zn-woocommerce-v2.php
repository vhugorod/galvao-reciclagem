<?php

// CHANGE WOOCOMMERCE LIGHTBOX
//Remove prettyPhoto lightbox
add_action( 'wp_enqueue_scripts', 'zn_remove_woo_lightbox', 99 );
function zn_remove_woo_lightbox() {

	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
	wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
	wp_dequeue_script( 'prettyPhoto' );
	wp_dequeue_script( 'prettyPhoto-init' );

	// Localize script
	wp_localize_script( 'zn-script', 'ZnWooCommerce', array(
		'thumbs_behavior' => zget_option( 'zn_show_thumb_on_hover', 'zn_woocommerce_options', false, 'yes' )
	));

	wp_enqueue_script( 'zn_product_gallery_v2_js', THEME_BASE_URI . '/woocommerce/product_gallery_v2/product_gallery.js', array( 'jquery' ), ZN_FW_VERSION, true );

	wp_enqueue_style( 'zn_product_gallery_v2_css', THEME_BASE_URI . '/woocommerce/product_gallery_v2/product_gallery.css', false, ZN_FW_VERSION );
}

function zn_woocommerce_single_product_image_html($html) {
	// check if lightbox enabled in WC settings
	$html = str_replace( array('data-rel="prettyPhoto[product-gallery]"', 'data-rel="prettyPhoto"'), 'data-shop-mfp="image"', $html);
	return $html;
}
add_filter('woocommerce_single_product_image_html', 'zn_woocommerce_single_product_image_html', 99, 1); // single image
add_filter('woocommerce_single_product_image_thumbnail_html', 'zn_woocommerce_single_product_image_html', 99, 1); // thumbnails


// If thumbs behavior is "click", add the post thumbnail to gallery
add_filter( 'woocommerce_product_get_gallery_image_ids', 'zn_add_main_image_to_gallery', 10 );
function zn_add_main_image_to_gallery( $ids ){
	$thumbs_behavior = zget_option( 'zn_show_thumb_on_hover', 'zn_woocommerce_options', false, 'yes' );
	if( ($thumbs_behavior == 'click' || $thumbs_behavior == 'yes') && !empty($ids) ){
		if ( has_post_thumbnail() ) {
			array_push($ids, get_post_thumbnail_id());
		}
	}
	return $ids;
}