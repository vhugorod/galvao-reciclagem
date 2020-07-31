<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_social_share_icons
 *
 * Social Share icons shortcode
 *
 * @usage [zn_social_share share_title="My title" share_text="" share_url="" share_media="" share_on_text=""]
 */
class zn_shortcode_social_share_icons extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'zn_social_share';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [zn_social_share share_title="My title" share_text="" share_url="" share_media="" share_on_text=""]
		extract( shortcode_atts( array(
			"share_title" => '',
			"share_text" => '',
			"share_url" => '',
			"share_media" => '',
			"share_on" => '',
		), $atts ) );

		if( ! function_exists('zn_social_share_icons')){
			return '';
		}
		return zn_social_share_icons( array_filter($atts) );
	}

}