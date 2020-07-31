<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_h2a
 *
 * @usage [h2a] Content [/h2a]
 */
class zn_shortcode_h2a extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'h2a';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		return '<h2 class="m_title text-custom" '.WpkPageHelper::zn_schema_markup('subtitle').'>' . do_shortcode( $content ) . '</h2>';
	}

}
