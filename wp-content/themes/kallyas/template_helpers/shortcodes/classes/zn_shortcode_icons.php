<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_icons
 *
 * @usage [icon white="false" ] ICON_NAME [/icon]
 */
class zn_shortcode_icons extends HG_Shortcode
{

	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'icon';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$white = $css_white = '';
		// [icon white="false" ] ICON_NAME [/icon]
		extract( shortcode_atts( array(
			"white" => false
		), $atts ) );
		if ( $white != 'false' ) {
			$css_white = 'kl-icon-white';
		}
		$icon = '<i class="glyphicon glyph' . preg_replace( '/\s+/', '', $content ) . ' ' . esc_attr($css_white) . '"></i>';
		return $icon;
	}

	public function scripts() {
		// Load scripts
		wp_enqueue_style( 'accordion-css', THEME_BASE_URI . '/pagebuilder/elements/TH_Accordion/style.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
	}
}
