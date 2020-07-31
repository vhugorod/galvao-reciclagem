<?php if ( !defined( 'WPINC' ) ) {
	die;
}
add_action( 'znhgfw_register_metabox_locations', 'znhgkl_register_metabox_locations' );
add_action( 'znhgfw_register_metabox_options', 'znhgkl_register_metabox_options' );
function znhgkl_register_metabox_locations( $metaboxClass ) {
	$zn_meta_locations = array();
	if ( file_exists( THEME_BASE . '/template_helpers/metaboxes/metaboxes_locations.php' ) ) {
		include_once( THEME_BASE . '/template_helpers/metaboxes/metaboxes_locations.php' );
	}
	$zn_meta_locations = apply_filters( 'zn_metabox_locations', $zn_meta_locations );
	foreach ( $zn_meta_locations as $metabox_location ) {
		$metaboxClass->register_meta_location( $metabox_location[ 'slug' ], array( 'title' => $metabox_location[ 'title' ], 'post_type' => $metabox_location[ 'page' ], 'context' => $metabox_location[ 'context' ], 'priority' => $metabox_location[ 'priority' ] ) );
	}
}

function znhgkl_register_metabox_options( $metaboxClass ) {
	$zn_meta_elements = array();
	if ( file_exists( THEME_BASE . '/template_helpers/metaboxes/metaboxes.php' ) ) {
		include_once( THEME_BASE . '/template_helpers/metaboxes/metaboxes.php' );
	}
	$metaboxes_options = apply_filters( 'zn_metabox_elements', $zn_meta_elements );
	foreach ( $metaboxes_options as $metabox_option ) {
		$metaboxClass->register_meta_option( $metabox_option );
	}

}
