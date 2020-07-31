<?php if(! defined('ABSPATH')){ return; }

/**
 * Save the theme options in DB
 */
function znklfw_save_theme_options( $options ){
	if( empty( $options ) || ! is_array( $options ) ) { return false; }

	// Pass options to filter
	$options = apply_filters( 'zn_options_to_save', $options );

	// Regenerate dynamic css
	if( function_exists( 'ZNHGFW' ) ){
		ZNHGFW()->getComponent('scripts-manager')->deleteDynamicCss();
	}

	return update_option( ZNHGTFW()->getThemeDbId(), $options );

}
