<?php if(! defined('ABSPATH')){ return; }

class ZNB_SmartContainer extends ZionElement {

	function options() {
		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'background' => array(
				'title' => __('Style options','zn_framework'),
				'options' => array(

					array(
						'id'          => 'bgcolor',
						'name'        => __('Background color', 'zn_framework'),
						'description' => __('Here you can choose a custom background color for this container.', 'zn_framework'),
						'type'        => 'colorpicker',
						'alpha'        => true,
						'std'         => '',
						'live'        => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'background-color',
							'unit'		=> ''
						)
					),

					array(
						'id'          => 'bg_image',
						'name'        => __('Background image', 'zn_framework'),
						'description' => __('Please choose a background image for this section.', 'zn_framework'),
						'type'        => 'background',
						'options' => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
						// 'class'		  => 'zn_full',
					),

					array (
						"name"        => __( "Border Type", 'zn_framework' ),
						"description" => __( "Select the border type for this button.", 'zn_framework' ),
						'id'          => 'border_style',
						'std'         => 'none',
						'type'        => 'select',
						'options'	  => array(
							''  => __( "None", 'zn_framework' ),
							'solid'    => __( "Solid", 'zn_framework' ),
							'dotted'   => __( "Dotted", 'zn_framework' ),
							'dashed'   => __( "Dashed", 'zn_framework' ),
							'double'   => __( "Double", 'zn_framework' ),
						),
						'live' => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid. ' .zn-imageImg',
							'css_rule'	=> 'border-style',
							'unit'		=> ''
						),
					),

					array(
						'id'          => 'border_width',
						'name'        => __('Border Width', 'zn_framework'),
						'description' => __('Choose a border width.', 'zn_framework'),
						'type'        => 'boxmodel',
						'std'	      => array('linked'=>1),
						'placeholder' => '0px',
						'allow-negative'  => false,
						"dependency"  => array( 'element' => 'border_style' , 'value'=> array('solid', 'dotted', 'dashed', 'double') ),
						// 'live' => array(
						// 	'type'		=> 'boxmodel',
						// 	'css_class' => '.'.$uid,
						// 	'css_rule'	=> 'border-width',
						// ),
					),

					array (
						"name"        => __( "Border-Color", 'zn_framework' ),
						"description" => __( "Select button custom border color.", 'zn_framework' ),
						"id"          => "border_custom_color",
						"std"         => "",
						"alpha"     => true,
						"type"        => "colorpicker",
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid,
							'css_rule'  => 'border-color',
							'unit'      => ''
						),
						"dependency"  => array( 'element' => 'border_style' , 'value'=> array('solid', 'dotted', 'dashed', 'double') ),
					),

					array (
						"name"        => __( "Border Radius", 'zn_framework' ),
						"description" => __( "Customize the border radius (corner roundness)", 'zn_framework' ),
						"id"          => "border_radius",
						"std"         => "",
						"type"        => "slider",
						"helpers"     => array (
							"step" => "1",
							"min" => "0",
							"max" => "300"
						),
					),

					array (
						"name"        => __( "Image Shadow", 'zn_framework' ),
						"description" => __( "Please select a shadow style.", 'zn_framework' ),
						"id"          => "shadow",
						"std"         => "",
						"options"     => array(
							''  => __( 'No shadow', 'zn_framework' ),
							'1'  => __( 'Shadow 1x', 'zn_framework' ),
							'2'  => __( 'Shadow 2x', 'zn_framework' ),
							'3'  => __( 'Shadow 3x', 'zn_framework' ),
							'4'  => __( 'Shadow 4x', 'zn_framework' ),
							'5'  => __( 'Shadow 5x', 'zn_framework' ),
							'6'  => __( 'Shadow 6x', 'zn_framework' ),
						),
						"type"        => "select",
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.'.$uid. ' .zn-imageImg',
							'val_prepend'	=> 'znBoxShadow-',
						),
					),

					array (
						"name"        => __( "Image Shadow - Hover", 'zn_framework' ),
						"description" => __( "Please select a shadow style for hover state.", 'zn_framework' ),
						"id"          => "shadow_hover",
						"std"         => "",
						"options"     => array(
							''  => __( 'No shadow', 'zn_framework' ),
							'1'  => __( 'Shadow 1x', 'zn_framework' ),
							'2'  => __( 'Shadow 2x', 'zn_framework' ),
							'3'  => __( 'Shadow 3x', 'zn_framework' ),
							'4'  => __( 'Shadow 4x', 'zn_framework' ),
							'5'  => __( 'Shadow 5x', 'zn_framework' ),
							'6'  => __( 'Shadow 6x', 'zn_framework' ),
						),
						"type"        => "select",
					),
				)
			),
			'spacing' => array(
				'title' => __('Spacing options','zn_framework'),
				'options' => array(


				)
			),
			'advanced' => array(
				'title' => 'Advanced',
				'options' => array(

					array(
						'id'          => 'gutter_size',
						'name'        => __('Gutter Size', 'zn_framework'),
						'description' => __('Select the gutter distance between columns', 'zn_framework'),
						"std"         => "20",
						"type"        => "slider",
						"helpers"     => array (
							"step" => "1",
							"min" => "0",
							"max" => "100"
						),
						'live' => array(
							'multiple' => array(
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid. '>.row>[class*="col-"]',
									'css_rule'  => 'padding-left',
									'unit'      => 'px'
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid. '>.row>[class*="col-"]',
									'css_rule'  => 'padding-right',
									'unit'      => 'px'
								),
							),
						)
					),

				)
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				// 'docs'    => 'https://my.hogash.com/documentation/custom-container/',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),
		);

		$options['spacing']['options'] = array_merge($options['spacing']['options'], zn_margin_padding_options($uid) );

		return $options;

	}

	function element() {

		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-smartCont clearfix';
		$classes[] = $this->opt('shadow','') ? 'znBoxShadow-'.$this->opt('shadow','') : '';
		$classes[] = $this->opt('shadow_hover','') ? 'znBoxShadow--hov-'.$this->opt('shadow_hover','') : '';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.implode(' ', $classes).'"';

		echo '<div '. zn_join_spaces( $attributes ) .'>';

			echo znb_get_element_container(array(
					'cssClasses' => 'row '
				));

				if (  ZNB()->utility->isActiveEditor() && empty( $this->data['content'] ) ) {
					$this->data['content'] = array ( ZNB()->frontend->addModuleToLayout( 'ZnColumn', array() , array(), 'col-sm-12' ) );
				}
				if ( !empty( $this->data['content'] ) ) {
					ZNB()->frontend->renderContent( $this->data['content'] );
				}

			echo '</div>';
		echo '</div>';
	}

	function css(){

		$css = $sm_css = '';
		$uid = $this->data['uid'];

		// BG Color
		if ( $bg_color = $this->opt('bgcolor','') ) {
			$sm_css .= 'background-color:'.$bg_color.';';
		}

		// BG Image
		$bg_image = $this->opt('bg_image',array());
		if( !empty($bg_image['image']) ){
			if( !empty( $bg_image['image'] ) ) { $sm_css .= 'background-image:url("'.$bg_image['image'].'");'; }
			if( !empty( $bg_image['repeat'] ) ) { $sm_css .= 'background-repeat:'.$bg_image['repeat'].';'; }
			if( !empty( $bg_image['position'] ) ) { $sm_css .= 'background-position:'.$bg_image['position']['x'].' '.$bg_image['position']['y'].';'; }
			if( !empty( $bg_image['attachment'] ) ) { $sm_css .= 'background-attachment:'.$bg_image['attachment'].';'; }
		}

		// Border
		if( $border_style = $this->opt('border_style','') ){

			// Style
			$sm_css .= 'border-style:'.$border_style.';';

			// Width
			$borders['lg'] = $this->opt('border_width', '' );
			if( !empty($borders) ){
				$borders['selector'] = '.'.$uid;
				$borders['type'] = 'border-width';
				$css .= zn_push_boxmodel_styles( $borders );
			}

			// Button Border Color
			if( $border_color = $this->opt('border_custom_color','') ){
				$sm_css .= 'border-color:'. $border_color .';';
			}
		}

		// Border Radius
		if( $border_radius = $this->opt('border_radius', '') ){
			$sm_css .= 'border-radius:'.$border_radius.'px;';
		}

		if( !empty($sm_css) ){
			$css .= '.'.$uid.'{'.$sm_css.'}';
		}

		// Margin
		$margins = array();
		$margins['lg'] = $this->opt('margin_lg', '' );
		$margins['md'] = $this->opt('margin_md', '' );
		$margins['sm'] = $this->opt('margin_sm', '' );
		$margins['xs'] = $this->opt('margin_xs', '' );
		if( !empty($margins) ){
			$margins['selector'] = '.'.$uid;
			$margins['type'] = 'margin';
			$css .= zn_push_boxmodel_styles( $margins );
		}

		// Padding
		$paddings = array();
		$paddings['lg'] = $this->opt('padding_lg', '' );
		$paddings['md'] = $this->opt('padding_md', '' );
		$paddings['sm'] = $this->opt('padding_sm', '' );
		$paddings['xs'] = $this->opt('padding_xs', '' );
		if( !empty($paddings) ){
			$paddings['selector'] = '.'.$uid;
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}

		// Gutter size
		$gutter_size = $this->opt( 'gutter_size', 20 );
		if( $gutter_size != 20 ){
			$css .= '.'.$uid. '>.row>[class*="col-"] {padding-left:'.$gutter_size.'px;padding-right:'.$gutter_size.'px}';
		}

		return $css;
	}

}

ZNB()->elements_manager->registerElement( new ZNB_SmartContainer(array(
	'id' => 'SmartContainer',
	'name' => __('Smart Container', 'zn_framework'),
	'description' => __('This element will generate a smart custom container in which you can add elements.', 'zn_framework'),
	'level' => 3,
	'category' => 'Layout',
	'legacy' => false,
	'keywords' => array('container', 'row'),
)));
