<?php

class ZnHgFw_TFW_integration {
	function __construct() {
		add_filter( 'znhgfw_google_fonts_list', array( $this, 'add_google_fonts' ) );
		add_filter( 'znhgfw_custom_fonts_list', array( $this, 'add_custom_fonts' ) );
		add_filter( 'znhgfw_google_fonts_subsets', array( $this, 'add_google_fonts_subsets' ) );
		add_filter( 'znhgfw_icons_locations', array( $this, 'add_theme_icons' ) );
	}

	function add_google_fonts() {
		return zget_option('zn_google_fonts_setup', 'google_font_options');
	}

	function add_google_fonts_subsets() {
		return zget_option('zn_google_fonts_subsets', 'google_font_options');
	}

	function add_custom_fonts() {
		return zget_option('zn_custom_fonts', 'google_font_options', false, array() );
	}

	function add_theme_icons( $icons ) {
		$default_fonts      = array();
		$default_fonts_base = ZNHGTFW()->getThemePath('template_helpers/icons/');
		$url                = ZNHGTFW()->getThemeUrl('template_helpers/icons/');
		$url                = preg_replace( '#^https?://#', '//', $url );

		$fontFiles = glob( $default_fonts_base . '*' );

		if ( is_array( $fontFiles ) ) {
			foreach ( $fontFiles as $file ) {
				$path_parts                                 = pathinfo( $file );
				$default_fonts[ $path_parts[ 'basename' ] ] = array(
					'url'      => $url, // USED TO LOAD THE FILES
					'filepath' => $default_fonts_base, // USED INTERNALLY
				);
			}
		}

		// COMBINE DEFAULT AND CUSTOM FONTS
		return array_merge( $default_fonts, $icons );
	}
}

return new ZnHgFw_TFW_integration();
