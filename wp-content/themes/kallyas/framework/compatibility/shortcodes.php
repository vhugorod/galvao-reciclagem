<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}
if ( !class_exists( 'ZnHgFw_ShortcodesManager' ) ) {
	$fp = trailingslashit( get_template_directory() ) . 'template_helpers/shortcodes/shortcodes.php';
	if ( is_file( $fp ) ) {
		require_once( $fp );
	}
}
