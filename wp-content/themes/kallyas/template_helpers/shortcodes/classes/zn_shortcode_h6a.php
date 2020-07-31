<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_h6a
 *
 * @usage [h6a] Content [/h6a]
 */
class zn_shortcode_h6a extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'h6a';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		return '<h6 class="m_title text-custom">' . do_shortcode( $content ) . '</h6>';
	}

}