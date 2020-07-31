<?php

class ZNB_Separator extends ZionElement
{
	function options() {

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General options','zn_framework'),
				'options' => array(


					array(
						'id'          => 'color',
						'name'        => __('Separator color','zn_framework'),
						'description' => __('Select the color for separator line.','zn_framework'),
						'type'        => 'colorpicker',
						'std'		  => '#000000',
						'live' => array(
							'multiple' => array(
								array(
									'type'		=> 'css',
									'css_class' => '.'.$uid . ' .zn-separatorIcon-no',
									'css_rule'	=> 'border-top-color',
									'unit'		=> ''
								),
								array(
									'type'		=> 'css',
									'css_class' => '.'.$uid . ' .zn-separatorIcon-yes .zn-separatorLine',
									'css_rule'	=> 'border-top-color',
									'unit'		=> ''
								),
							)
						)
					),
					array(
						'id'          => 'height',
						'name'        => __('Separator height','zn_framework'),
						'description' => __('Select the separator line height (in pixels).','zn_framework'),
						'type'        => 'slider',
						'std'		  => '2',
						// 'class'		  => 'zn_full',
						'helpers'	  => array(
							'min' => '1',
							'max' => '15',
							'step' => '1'
						),
						'live' => array(
							'multiple' => array(
								array(
									'type'		=> 'css',
									'css_class' => '.'.$uid . ' .zn-separatorIcon-no',
									'css_rule'	=> 'border-top-width',
									'unit'		=> 'px'
								),
								array(
									'type'		=> 'css',
									'css_class' => '.'.$uid . ' .zn-separatorIcon-yes .zn-separatorLine',
									'css_rule'	=> 'border-top-width',
									'unit'		=> 'px'
								)
							)
						)
					),

					array(
						"name"        => __( "Size (Max-Width)", 'zn_framework' ),
						"description" => __( "Customize the size of this separator.", 'zn_framework' ),
						"id"          => "max_width",
						'type'        => 'smart_slider',
						'std'        => '100',
						'helpers'     => array(
							'min' => '0',
							'max' => '2000'
						),
						'supports' => array('breakpoints'),
						'units' => array('%', 'px'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' .zn-separator',
							'css_rule'  => 'max-width',
							'unit'      => '%'
						),
					),

					array (
						"name"        => __( "Separator Alignment", 'zn_framework' ),
						"description" => __( "Choose the separator's alignment.", 'zn_framework' ),
						"id"          => "alignment_breakpoints",
						"std"         => "lg",
						"tabs"        => true,
						"type"        => "zn_radio",
						"options"     => array ( "lg" => '', "md" => '', "sm" => '', "xs" => '' ),
						"class"       => "zn_breakpoints zn--minimal"
					),

							array (
								"name"        => __( "Alignment", 'zn_framework' ),
								"description" => __( "Select the separator alignment.", 'zn_framework' ),
								"id"          => "alignment",
								"std"         => "center",
								"type"        => "select",
								"options"     => array(
									"left" => __("Left", 'zn_framework' ),
									"center" => __("Center", 'zn_framework' ),
									"right" => __("Right", 'zn_framework' ),
								),
								'live'        => array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'text-',
								),
								"dependency"  => array( 'element' => 'alignment_breakpoints' , 'value'=> array('lg') ),
							),

							array (
								"name"        => __( "Alignment (Tablets)", 'zn_framework' ),
								"description" => __( "Select the separator alignment.", 'zn_framework' ),
								"id"          => "alignment_md",
								"std"         => "",
								"type"        => "select",
								"options"     => array(
									"" => __("Default", 'zn_framework' ),
									"left" => __("Left", 'zn_framework' ),
									"center" => __("Center", 'zn_framework' ),
									"right" => __("Right", 'zn_framework' ),
								),
								"dependency"  => array( 'element' => 'alignment_breakpoints' , 'value'=> array('md') ),
							),
							array (
								"name"        => __( "Alignment (Small tablets)", 'zn_framework' ),
								"description" => __( "Select the separator alignment.", 'zn_framework' ),
								"id"          => "alignment_sm",
								"std"         => "",
								"type"        => "select",
								"options"     => array(
									"" => __("Default", 'zn_framework' ),
									"left" => __("Left", 'zn_framework' ),
									"center" => __("Center", 'zn_framework' ),
									"right" => __("Right", 'zn_framework' ),
								),
								"dependency"  => array( 'element' => 'alignment_breakpoints' , 'value'=> array('sm') ),
							),
							array (
								"name"        => __( "Alignment (Smartphones)", 'zn_framework' ),
								"description" => __( "Select the separator alignment.", 'zn_framework' ),
								"id"          => "alignment_xs",
								"std"         => "",
								"type"        => "select",
								"options"     => array(
									"" => __("Default", 'zn_framework' ),
									"left" => __("Left", 'zn_framework' ),
									"center" => __("Center", 'zn_framework' ),
									"right" => __("Right", 'zn_framework' ),
								),
								"dependency"  => array( 'element' => 'alignment_breakpoints' , 'value'=> array('xs') ),
							),



				),
			),

			'icon' => array(
				'title' => 'Icon options',
				'options' => array(

					array (
						"name"        => __( "Add Icon?", 'zn_framework' ),
						"description" => __( "Choose if you want to add an icon in the center of the separator.", 'zn_framework' ),
						"id"          => "enable_icon",
						"std"         => "no",
						"type"        => "zn_radio",
						"options"     => array(
							"yes" => __( "Yes", 'zn_framework' ),
							"no" => __( "No", 'zn_framework' ),
						),
						"class"        => "zn_radio--yesno",
					),

					array (
						"name"        => __( "Icon", 'zn_framework' ),
						"description" => __( "Add icon.", 'zn_framework' ),
						"id"          => "icon",
						"std"         => "",
						"type"        => "icon_list",
						'class'       => 'zn_full',
						"dependency"  => array( 'element' => 'enable_icon' , 'value'=> array('yes') )
					),

					array (
						"name"        => __( "Icon Color", 'zn_framework' ),
						"description" => __( "Select icon color.", 'zn_framework' ),
						"id"          => "icon_color",
						"std"         => "#000",
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'enable_icon' , 'value'=> array('yes') ),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid.' .zn-separatorIcon',
							'css_rule'  => 'color',
							'unit'      => ''
						)
					),

					array(
						'id'          => 'icon_size',
						'name'        => 'Icon Size',
						'description' => __('Select the icon size in px.','zn_framework'),
						'type'        => 'slider',
						'std'         => '20',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '14',
							'max' => '80',
							'step' => '2'
						),
						"dependency"  => array( 'element' => 'enable_icon' , 'value'=> array('yes') ),
					),
				),
			),


			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),
		);

		$options['general']['options'] = array_merge($options['general']['options'], zn_margin_padding_options($uid) );

		return $options;

	}

	function element() {

		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];


		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-separatorEl';
		$classes[] = znb_alignment_breakpoint_classes_output( array(
				'lg' => $this->opt('alignment', 'center'),
				'md' => $this->opt('alignment_md', ''),
				'sm' => $this->opt('alignment_sm', ''),
				'xs' => $this->opt('alignment_xs', ''),
			) );

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.zn_join_spaces($classes).'"';

		echo '<div '. zn_join_spaces($attributes ) .'>';

			$enable_icon = $this->opt('enable_icon', 'no');

			echo '<div class="zn-separator zn-separatorIcon-'.$enable_icon.'">';

				if($enable_icon == 'yes'){
					echo '<span class="zn-separatorLine zn-separatorLine--left"></span>';
					$icon = $this->opt('icon');
					if( isset($icon['family']) && !empty( $icon['family'] ) ){
						echo '<span class="zn-separatorIcon" '.zn_generate_icon( $icon ).'></span>';
					}
					echo '<span class="zn-separatorLine zn-separatorLine--right"></span>';
				}

				// For 1px separator, make sure notification is not displayed in PB Mode
				if( ZNB()->utility->isActiveEditor() && $this->opt('height','2') == '1' ){
					echo '<div class="sep-no-notification clearfix"></div>';
				}

			echo '</div>';
		echo '</div>';

	}


	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		$uid = $this->data['uid'];
		$css = '';

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
		$padding = array();
		$padding['lg'] = $this->opt('padding_lg', '' );
		$padding['md'] = $this->opt('padding_md', '' );
		$padding['sm'] = $this->opt('padding_sm', '' );
		$padding['xs'] = $this->opt('padding_xs', '' );
		if( !empty($padding) ){
			$padding['selector'] = '.'.$uid;
			$padding['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $padding );
		}


		if( $max_width = $this->opt('max_width', '100') ){
			$css .= zn_smart_slider_css( $max_width, '.'.$uid.' .zn-separator', 'max-width', '%');
		}

		$height = 'border-top-width:'.$this->opt('height', '2').'px;';
		$color = 'border-top-color:'.$this->opt('color', '#000000').';';

		if( $this->opt('enable_icon', 'no') == 'no'){
			$css .= ".{$uid} .zn-separator.zn-separatorIcon-no { $height $color }";
		}
		else{
			$icon_size = $this->opt('icon_size', '20');
			$icon_size_css = 'font-size:'.$icon_size.'px;';
			$icon_color = 'color:'.$this->opt('icon_color', '#000');

			$icon_size_calc = $icon_size + 40; // add 20px side margins
			$width = 'width: calc(50% - '.($icon_size_calc/2).'px);';

			$css .= "#{$uid} .zn-separator.zn-separatorIcon-yes .zn-separatorLine { $height $color $width }";
			$css .= "#{$uid} .zn-separatorIcon { $icon_size_css $icon_color }";
		}

		return $css;
	}

}

ZNB()->elements_manager->registerElement( new ZNB_Separator(array(
	'id' => 'ZnSeparator',
	'name' => __('Separator', 'zn_framework'),
	'description' => __('This element will generate a separator line with various styles.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content, Fullwidth',
	'legacy' => false,
	'keywords' => array('divider', 'spacer', 'line', 'decoration'),
)));
