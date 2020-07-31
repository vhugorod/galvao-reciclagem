<?php if(! defined('ABSPATH')){ return; }

class ZNB_Spacer extends ZionElement
{

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General options','zn_framework'),
				'options' => array(

					array(
						'id'          => 'spacer_height',
						'name'        => __( 'Spacer Height', 'zn_framework'),
						'description' => __( 'Choose the desired height for this element.', 'zn_framework' ),
						'type'        => 'smart_slider',
						'std'        => '30',
						'helpers'     => array(
							"step" => "1",
							'min' => '0',
							'max' => '600'
						),
						'supports' => array('breakpoints'),
						'units' => array('px', 'vh'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid,
							'css_rule'  => 'height',
							'unit'      => 'px'
						),
					),

				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#O03njJEtSNQ') ),
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);

		return $options;
	}
	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-spacer';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.zn_join_spaces($classes).'"';

		echo '<div '. zn_join_spaces($attributes ) .'></div>';
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		$uid = $this->data['uid'];
		$css = zn_smart_slider_css( $this->opt( 'spacer_height', 30 ), '.'.$uid, 'height' );
		return $css;
	}

}

ZNB()->elements_manager->registerElement( new ZNB_Spacer(array(
	'id' => 'ZnSpacer',
	'name' => __('Spacer', 'zn_framework'),
	'description' => __('This element will generate a transparent spacer.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content',
	'legacy' => false,
	'keywords' => array('divider', 'distance', 'spacing'),
)));
