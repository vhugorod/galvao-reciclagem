<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}


/**
 * Revolution slider fonts integration
 *
 * Will add theme loaded google fonts to the fonts list
 */
class ZnHgFw_RevSlider_integration {

	/**
	 * Main class constructor
	 *
	 * @uses 'revslider_operations_getArrFontFamilys' filter to insert theme fonts
	 * @uses 'admin_enqueue_scripts' filter to add theme fonts scripts in rev slider edit screen
	 */
	public function __construct() {
		if ( class_exists( 'RevSliderFront' ) ) {
			add_filter( 'revslider_operations_getArrFontFamilys', array( __CLASS__, 'add_theme_fonts' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_scripts' ) );
			add_action( 'admin_head', array( __CLASS__, 'print_custom_fonts_styles' ) );
		}
	}

	/**
	 * Add Theme Fonts
	 *
	 * Will add theme loaded fonts to Revolution Slider fonts lists.
	 * The theme fonts are better optimized than Revolution slider fonts manager
	 *
	 * @param array $font_list The font list received from Revolution slider
	 * @return array List of fonts containing the theme fonts
	 */
	public static function add_theme_fonts( $font_list ) {
		$theme_google_fonts = ZNHGFW()->getComponent( 'font-manager' )->get_google_fonts();
		$theme_custom_fonts = ZNHGFW()->getComponent( 'font-manager' )->get_custom_fonts();
		$theme_fonts_list   = array();

		// Add Google fonts
		foreach ( $theme_google_fonts as $font_key => $font_name ) {
			$theme_fonts_list[] = array(
				'type'    => 'googlefonts',
				'version' => 'Theme Fonts',
				'label'   => $font_key,
			);
		}

		// Add custom fonts
		foreach ( $theme_custom_fonts as $font_key => $font_config ) {
			$theme_fonts_list[] = array(
				'type'    => 'websafe',
				'version' => 'Theme Fonts',
				'label'   => $font_config['cf_name'],
			);
		}

		// Combine theme fonts with Rev slider fonts
		$font_list = array_merge( $theme_fonts_list, $font_list );

		return $font_list;
	}


	/**
	 * Enqueue Admin Scripts
	 *
	 * Will enqueue theme loaded Google Fonts into Rev slider edit screen
	 *
	 * @return void
	 */
	public static function enqueue_admin_scripts() {
		$screen = get_current_screen();

		if ( 'toplevel_page_revslider' === $screen->id ) {
			ZNHGFW()->getComponent( 'font-manager' )->enqueue_fonts();
		}
	}

	public static function print_custom_fonts_styles() {
		$screen = get_current_screen();

		if ( 'toplevel_page_revslider' === $screen->id ) {
			$custom_fonts_css = ZNHGFW()->getComponent( 'font-manager' )->get_custom_fonts_styles();

			$output  = '<style>';
			$output .= $custom_fonts_css;
			$output .= '</style>';

			echo '' . $output;
		}
	}
}

new ZnHgFw_RevSlider_integration();
