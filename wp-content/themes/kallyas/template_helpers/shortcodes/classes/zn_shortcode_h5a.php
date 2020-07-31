<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_h5a
 *
 * @usage [h5a] Content [/h5a]
 */
class zn_shortcode_h5a extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'h5a';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		return '<h5 class="m_title text-custom">' . do_shortcode( $content ) . '</h5>';
	}

}