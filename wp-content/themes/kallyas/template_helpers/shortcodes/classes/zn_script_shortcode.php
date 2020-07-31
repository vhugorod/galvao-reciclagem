<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_script_shortcode
 *
 * @usage [zn_script src=""]
 */
class zn_script_shortcode extends HG_Shortcode
{

	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'zn_script';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [zn_script src=""]
		extract( shortcode_atts( array(
			"src" => '',
		), $atts ) );
		if( empty($src)){
			return '';
		}
		return '<script type="text/javascript" src="'.esc_url($src).'"></script>';
	}
}