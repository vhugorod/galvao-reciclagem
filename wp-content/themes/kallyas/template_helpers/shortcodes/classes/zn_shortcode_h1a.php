<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_h1a
 *
 * @usage [h1a] Content [/h1a]
 */
class zn_shortcode_h1a extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'h1a';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		return '<h1 class="m_title text-custom" '.WpkPageHelper::zn_schema_markup('title').'>' . do_shortcode( $content ) . '</h1>';
	}

}