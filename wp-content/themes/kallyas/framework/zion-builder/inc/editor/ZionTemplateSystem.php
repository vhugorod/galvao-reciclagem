<?php if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionTemplateSystem
 *
 *
 */
class ZionTemplateSystem
{
	/**
	 * Class constructor
	 */
	function __construct()
	{
		// Register post type
		$this->zn_register_template_system();
	}

	function zn_register_template_system() {
		$args = array(
			'labels'            => array( 'name' => 'Zn Framework' ),
			'show_ui'           => false,
			'query_var'         => true,
			'capability_type'   => 'post',
			'hierarchical'      => false,
			'rewrite'           => false,
			'supports'          => array( 'title' ),
			'can_export'        => true,
			'public'            => false,
			'show_in_nav_menus' => false,
		);
		register_post_type( 'zn_pb_templates', $args );
	}

	/**
	 *
	 *    GENERATE A TEMPLATE KEY FOR USE IN UPDATE POST META
	 *
	 */
	function generateKey( $name ) {
		return "_zn_pb_template" . str_replace( " ", "_", strtolower( $name ) );
	}

	/**
	 *    GET POST ID IF EXISTS OR CREATE A NEW POST USING THE NAME PROVIDED
	 */
	function getPostID( $post_title = 'zn_pb_templates' ) {
		// GET THE POST THAT CONTAINS ALL THE TEMPLATES
		$zn_pb_template_post = get_page_by_title( $post_title, 'ARRAY_A', 'zn_pb_templates' );

		if ( !isset( $zn_pb_template_post[ 'ID' ] ) ) {

			$post = array(
				'post_type'      => 'zn_pb_templates',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_content'   => '',
				'post_title'     => $post_title,
			);

			$post_id = wp_insert_post( $post );
		}
		else {
			$post_id = $zn_pb_template_post[ 'ID' ];
		}

		return $post_id;
	}

	/**
	 *    Retrieves all saved templates
	 */
	function getPageBuilderTemplates( $post_name = 'zn_pb_templates', $template_name = '_zn_pb_template%', $compare = 'LIKE' ) {

		global $wpdb;

		$post_id = $this->getPostID( $post_name );

		$r = $wpdb->get_col( $wpdb->prepare( "
			SELECT meta_value FROM {$wpdb->postmeta}
			WHERE  meta_key {$compare} '%s'
			AND post_id = '%s'
		", $template_name, $post_id ) );

		return $r;
	}

}
