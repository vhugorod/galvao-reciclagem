<?php if(! defined('ABSPATH')){ return; }

/**
 * Class Znb_PaidMembership_Integration
 */
class Znb_PaidMembership_Integration extends Znb_Integration
{
	/**
	 * Check if we can load this integration or not
	 * @return bool Whatever we can load the class or not
	 */
	static public function can_load(){
		return defined('PMPRO_VERSION');
	}


	function initialize()
	{
		add_filter( 'znpb_can_load_template', array( $this, 'canLoadPbTemplate' ) );


	}

	function canLoadPbTemplate( $canLoadTemplate ){
		$hasaccess = pmpro_has_membership_access(NULL, NULL, true);
		if(is_array($hasaccess))
		{
			$hasaccess = $hasaccess[0];
		}
		if( ! $hasaccess ){
			$canLoadTemplate = false;
		}

		return $canLoadTemplate;
	}

}
