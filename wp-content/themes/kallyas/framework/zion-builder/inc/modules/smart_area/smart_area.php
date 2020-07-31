<?php if(! defined('ABSPATH')){ return; }

class ZNB_SmartArea extends ZionElement {

	function options() {

		$uid = $this->data['uid'];
		$pb_templates_options = array('' => '-- Select a template --');
		$all_pb_templates = get_posts( array (
			'post_type'      => 'znpb_template_mngr',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
		) );

		foreach ($all_pb_templates as $key => $value) {
			$pb_templates_options[$value->ID] = $value->post_title;
		}


		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General options','zn_framework'),
				'options' => array(

					array (
						'id'          => 'pb_template',
						'name'        => __('Select Pagebuilder Template', 'zn_framework'),
						'description' => __('Using this option you can select a pre-built Smart Area created in Admin > Page Builder Smart Areas.','zn_framework'),
						'type'        => 'select',
						'options'	=> $pb_templates_options
					),

				),
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				// 'docs'    => 'https://my.hogash.com/documentation/anchor-point-element/',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);

		return $options;

	}

	function element(){

		$template = $this->opt( 'pb_template' );
		if( empty( $template ) ) { return; }

		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-smartAreaEl';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.implode(' ', $classes).'"';

		echo '<div '. implode(' ', $attributes ) .'>';
			$pb_data = get_post_meta( $template, 'zn_page_builder_els', true );
			ZNB()->frontend->renderUneditableContent( $pb_data, $template );
		echo '</div>';

	}

}

ZNB()->elements_manager->registerElement( new ZNB_SmartArea(array(
	'id' => 'ZnSmartArea',
	'name' => __('Page Builder Smart Area', 'zn_framework'),
	'description' => __('This element will display a Smart Area.', 'zn_framework'),
	'level' => 1,
	'category' => 'Layout, Fullwidth',
	'legacy' => false,
)));
