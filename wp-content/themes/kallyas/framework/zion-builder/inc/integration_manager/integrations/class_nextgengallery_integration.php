<?php if(! defined('ABSPATH')){ return; }

/**
 * Class Znb_Polylang_Integration
 */
class Znb_NextGenGallery_Integration extends Znb_Integration
{
	/**
	 * Check if we can load this integration or not
	 * @return [type] [description]
	 */
	static public function can_load(){
		return class_exists( 'C_NextGEN_Bootstrap' );
	}


	function initialize()
	{
		// Disable NextGenGallery resource manager
		if( ! defined('NGG_DISABLE_RESOURCE_MANAGER') ){
			define( 'NGG_DISABLE_RESOURCE_MANAGER', true );
		}
	}

}
