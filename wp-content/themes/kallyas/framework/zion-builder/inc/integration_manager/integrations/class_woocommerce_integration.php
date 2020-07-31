<?php

class Znb_WooCommerce_Integration extends Znb_Integration
{

	/**
	 * Check if we can load this integration or not
	 * @return bool
	 */
	static public function can_load()
	{
		return class_exists( 'WooCommerce' );
	}

	function initialize()
	{
		add_filter( 'zn_get_the_id', array( $this, 'filter_page_id' ), 100 );
	}

	function filter_page_id( $crtID )
	{
		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$crtID = get_option( 'woocommerce_shop_page_id' );
		}
		return $crtID;
	}
}
