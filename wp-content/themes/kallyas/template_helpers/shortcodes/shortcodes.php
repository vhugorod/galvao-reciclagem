<?php if(! defined('ABSPATH')){ return; }
/*
 * Allow shortcodes to be added to text widget
 */
add_filter( 'widget_text', 'do_shortcode' );

// Load all Shortcodes
add_action('znhgfw_shortcodes_init', 'hg_register_shortcodes');
function hg_register_shortcodes($shortcodesManager){
	$files = glob( dirname(__FILE__) .'/classes/*.php' );
	if ( !empty( $files ) ) {
		foreach ( $files as $filePath ) {
			$fn = basename( $filePath, '.php' );
			require_once( $filePath );
			$shortcodesManager->registerShortcode( new $fn() );
		}
	}
}

//#! Add filter for shortcodes sections
add_filter( 'hg_shortcode_sections', 'hg_register_shortcodes_section', 11 );
function hg_register_shortcodes_section($sections){
	$name = __('Typography', 'zn_framework');
	if ( !in_array( $name, $sections ) ) {
		array_push( $sections, $name );
	}
	return $sections;
}


