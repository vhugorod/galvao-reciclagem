<?php if(! defined('ABSPATH')){ return; }

get_header();

$pb_template = zget_option( '404_smart_area', 'zn_404_options');
$pb_template = apply_filters( 'wpml_object_id', $pb_template );
if( ! empty( $pb_template ) ){

	$pb_data = get_post_meta( $pb_template, 'zn_page_builder_els', true );
	ZNB()->frontend->renderUneditableContent( $pb_data, $pb_template );
}

get_footer(); ?>
</div>
