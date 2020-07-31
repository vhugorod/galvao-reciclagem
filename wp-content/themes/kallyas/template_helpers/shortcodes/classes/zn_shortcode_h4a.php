<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_h4a
 *
 * @usage [h4a] Content [/h4a]
 */
class zn_shortcode_h4a extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'h4a';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		return '<h4 class="m_title text-custom">' . do_shortcode( $content ) . '</h4>';
	}

}