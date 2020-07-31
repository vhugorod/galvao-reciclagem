<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_h3a
 *
 * @usage [h3a] Content [/h3a]
 */
class zn_shortcode_h3a extends HG_Shortcode
{

	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'h3a';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		return '<h3 class="m_title m_title_ext text-custom" '.WpkPageHelper::zn_schema_markup('subtitle').'>' . do_shortcode( $content ) . '</h3>';
	}

}