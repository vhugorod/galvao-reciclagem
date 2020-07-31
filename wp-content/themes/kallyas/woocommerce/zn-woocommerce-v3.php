<?php

add_action( 'after_setup_theme', 'znwoo_enable_woo_features' );
function znwoo_enable_woo_features(){

	if( zget_option('zn_woo_enable_zoom', 'zn_woocommerce_options', false, 'no') == 'yes' ) {
		add_theme_support('wc-product-gallery-zoom');
	}

	if( zget_option('zn_woo_enable_slider', 'zn_woocommerce_options', false, 'no') == 'yes' ){
		add_theme_support( 'wc-product-gallery-slider' );
	}

	// Allow us to zoomm into images
	add_theme_support( 'wc-product-gallery-lightbox' );

}

