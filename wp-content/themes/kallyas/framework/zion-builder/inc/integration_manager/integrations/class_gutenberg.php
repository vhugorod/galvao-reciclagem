<?php
if( ! defined( 'ABSPATH' ) ) { 
	return; 
}

/**
 * Class Znb_Polylang_Integration
 */
class Znb_Gutenberg_Integration extends Znb_Integration
{
	/**
	 * Check if we can load this integration or not
	 * @return [type] [description]
	 */
	static public function can_load() {
		return function_exists( 'register_block_type' );
	}


	function initialize() {
		// Disable NextGenGallery resource manager
		if( ! defined('NGG_DISABLE_RESOURCE_MANAGER') ){
			define( 'NGG_DISABLE_RESOURCE_MANAGER', true );
		}
	}

}
