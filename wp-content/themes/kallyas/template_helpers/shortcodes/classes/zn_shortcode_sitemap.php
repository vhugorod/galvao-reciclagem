<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_sitemap
 *
 * @usage [sitemap menu="Main Menu"]
 */
class zn_shortcode_sitemap extends HG_Shortcode
{
	/**
	 * Internal flag to know whether or not the scripts were loaded
	 * @var bool
	 */
	private $_scriptsLoaded = false;


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'sitemap';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$menu = $output = null;
		extract( shortcode_atts( array( 'menu' => null, ), $atts ) );
		if( ! empty($menu) ) {
			$output = '<div class="sitemap">';
			$output .= wp_nav_menu( array( 'menu' => $menu, 'echo' => false ) );
			$output .= '</div>';
		}
		return $output;
	}

	public function scripts() {
		// Load scripts
		wp_enqueue_style( 'sh-sitemap-css', THEME_BASE_URI . '/css/shortcodes/sitemap.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
	}
}