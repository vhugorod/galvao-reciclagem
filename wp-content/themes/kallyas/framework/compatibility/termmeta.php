<?php if ( !defined( 'WPINC' ) ) { die; }


// Register Theme's term meta's
add_action( 'znhgfw_register_termmeta_options', 'znhgfw_load_termmeta_options' );
function znhgfw_load_termmeta_options( $termMetaClass ) {
	$zn_term_meta = array();
	if ( file_exists( THEME_BASE . '/template_helpers/termmeta/termoptions.php' ) ) {
		include( THEME_BASE . '/template_helpers/termmeta/termoptions.php' );
	}
	$zn_term_meta = apply_filters( 'zn_termmeta_elements', $zn_term_meta );

	if( ! empty( $zn_term_meta ) && is_array( $zn_term_meta ) ){
		foreach ( $zn_term_meta as $metabox_option ) {
			$termMetaClass->register_termmeta_option( $metabox_option );
		}
	}
}
