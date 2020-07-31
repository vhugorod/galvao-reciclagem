<?php if ( ! defined('ABSPATH')) {
	return;
}
/*
	Name: Page Builder Smart Area
	Description: This element will generate an empty element with an unique ID that can be used as an achor point
	Class: ZnPbCustomTempalte
	Category: content
	Level: 1
*/

class ZnPbCustomTempalte extends ZnElements {
	function options() {
		$uid                  = $this->data['uid'];
		$pb_templates_options = array('' => '-- Select a template --');
		$all_pb_templates     = get_posts( array (
			'post_type'      => 'znpb_template_mngr',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		) );

		foreach ( $all_pb_templates as $key => $value ) {
			$pb_templates_options[ $value->ID ] = $value->post_title;
		}

		// Remove current post from posts lists
		$current_post_id = ZNB()->utility->getPostID();
		if ( isset( $pb_templates_options[ $current_post_id ] ) ) {
			unset( $pb_templates_options[ $current_post_id ] );
		}

		$options = array(
			'has_tabs'  => true,
			'general'   => array(
				'title'   => 'General options',
				'options' => array(

					array (
						'id'          => 'pb_template',
						'name'        => 'Select Pagebuilder Template',
						'description' => 'Using this option you can select a pre-built template made at Admin > Pagebuilder Tempaltes page.',
						'type'        => 'select',
						'options'     => $pb_templates_options,
					),

				),
			),

			// 'help' => znpb_get_helptab( array(
			// 	'video'   => 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#GAiAelvoOg4',
			// 	'docs'    => 'https://my.hogash.com/documentation/anchor-point-element/',
			// 	'copy'    => $uid,
			// 	'general' => true,
			// )),

		);

		return $options;
	}

	public function element() {
		$options = $this->data['options'];

		$template_post_id = $this->opt( 'pb_template' );
		if ( empty( $template_post_id ) ) {
			return;
		}

		$classes   = array();
		$classes[] = $this->data['uid'];
		$classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);

		$pb_data = get_post_meta( $template_post_id, 'zn_page_builder_els', true );
		echo '<div class="' . implode(' ', $classes) . '" ' . $attributes . '>';
		ZNB()->frontend->renderUneditableContent( $pb_data, $template_post_id );
		echo '</div>';
	}
}

?>
