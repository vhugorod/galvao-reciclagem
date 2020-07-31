<?php
// Exit if accessed directly
if ( ! defined ( 'ABSPATH' ) ) { exit; }

/**
 * This function will register a framework version
 */
if ( ! function_exists ( 'znhgfw_register_framework' ) ) {
	function znhgfw_register_framework( $framework_path ){
		global $znhgfw_data;

		$default_headers = array (
			'Version'    => 'Version',
		);

		$framework_data = get_file_data( $framework_path, $default_headers );

		// Only add the framework if it has a higher version than the one already registered
		if ( ! empty( $znhgfw_data ) && is_array( $znhgfw_data ) ) {
			if ( version_compare( $znhgfw_data['version'], $framework_data[ 'Version' ], '>' ) ) {
				return;
			}
		}

		$znhgfw_data = array(
			'path' => dirname( $framework_path ),
			'version' => $framework_data[ 'Version' ]
		);
	}
}

/**
 * This function will load the main plugin framework
 */
if( ! function_exists( 'znhgfw_load_framework' ) ){
	function znhgfw_load_framework(){
		global $znhgfw_data;
		if( ! empty( $znhgfw_data ) ){
			$fw_main_file = wp_normalize_path( trailingslashit( $znhgfw_data['path'] ) . 'class-znhgfw.php' );
			require_once( $fw_main_file );
			ZNHGFW( $znhgfw_data );
		}
	}
}

if( ! function_exists( 'znhgfw_init_framework' ) ){
	function znhgfw_init_framework(){
		znhgfw_load_framework();
	}
	add_action( 'after_setup_theme', 'znhgfw_init_framework' );
}
