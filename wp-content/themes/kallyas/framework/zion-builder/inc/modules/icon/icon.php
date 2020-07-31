<?php if(! defined('ABSPATH')){ return; }

class ZNB_Icon extends ZionElement
{

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = $ico_css = $ico_hov_css = '';
		$uid = $this->data['uid'];

		$icon_type = $this->opt('icon_type', 'icon');

		// Icon color default and on hover
		if( $icon_type == 'icon' ){
			if($icon_color = $this->opt('icon_color', '' )){
				$ico_css .= 'color:'.$icon_color.';';
			}
			if($icon_color_hover = $this->opt('icon_color_hover', '' )){
				$ico_hov_css .= 'color:'.$icon_color_hover.';';
			}
		}

		if($bg_color = $this->opt('icon_bg_color', '')){
			$ico_css .= 'background-color:'.$bg_color.';';
		}
		if($bg_color_hover = $this->opt('icon_bg_color_hover', '')){
			$ico_hov_css .= 'background-color:'.$bg_color_hover.';';
		}

		// Icon sizes
		$icon_size = $this->opt('icon_size','42');
		if( $icon_type == 'icon' && $icon_size != '42'){
			$ico_css .= 'font-size:'.$icon_size.'px;';
		}

		// Image size
		$img_size = $this->opt('icon_imgwidth','100');
		if( $icon_type == 'img' && $img_size != '100' ){
			$ico_css .= 'width:'.$img_size.'px;';
		}

		// Icon Padding
		$icon_padding = $this->opt('icon_padding','22');
		if( $icon_padding != '22' ){
			$ico_css .= 'padding:'.$icon_padding.'px;';
		}

		// Radius
		if( $radius = $this->opt('corner_radius','') ){
			$ico_css .= 'border-radius:'.$radius.'px;';
		}

		// Icon Opacity
		$icon_opacity = $this->opt('icon_opacity','1');
		if( $icon_opacity != '1' && $icon_opacity != '' ){
			$ico_css .= 'opacity: '.$icon_opacity.';';
		}

		if(!empty($ico_css)){
			$css .= '.'.$uid.' .zn-iconIco{'.$ico_css.'} ';
		}
		if(!empty($ico_hov_css)){
			$css .= '.'.$uid.' .zn-iconIco:hover, .'.$uid.' .zn-iconIco:focus {'.$ico_hov_css.'} ';
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

		return $css;
	}

	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];
		$uid = $this->data['uid'];
		$classes = $attributes = array();

		$icon_type = $this->opt('icon_type', 'icon');

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-icon';
		$classes[] = 'zn-icon--type-'.$icon_type;
		$classes[] = $this->opt('border','');
		$classes[] = $this->opt('force_square','');
		$classes[] = $this->opt('floating_animation','');
		$classes[] = znb_alignment_breakpoint_classes_output( array(
				'lg' => $this->opt('alignment', 'center'),
				'md' => $this->opt('alignment_md', ''),
				'sm' => $this->opt('alignment_sm', ''),
				'xs' => $this->opt('alignment_xs', ''),
			) );

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		echo '<div class="'.zn_join_spaces($classes).'" '. zn_join_spaces( $attributes ) .'>';

			$theicon = $this->opt('icon', '');
			$icon_img = $this->opt('icon_image','');
			$icon_img = attachment_url_to_postid($icon_img);

			$link_markup['start'] = $link_markup['end'] = '';
			$link = $this->opt('link','');
			if( isset($link['url']) && !empty($link['url']) ){
				$link_markup = zn_extract_link( $link, 'zn-iconLink' );
			}

			echo $link_markup['start'];

			// Icon Font
			if( $icon_type == 'icon' && is_array($theicon) && !empty($theicon['unicode']) ){
				echo '<span class="zn-iconIco" '. zn_generate_icon( $this->opt('icon','') ) .'></span>';
			}

			// Icon Image
			elseif ( $icon_type == 'img' && !empty($icon_img) ){
				echo wp_get_attachment_image( $icon_img, 'full', false, 'class=zn-iconIco img-responsive' );
			}

			echo $link_markup['end'];

		echo '</div>';
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$options = array (
			'has_tabs'  => true,
			'general' => array(
				'title' => 'GENERAL',
				'options' => array(

					array (
						"name"        => __( "Icon Type", 'zn_framework' ),
						"description" => __( "Type of the icon.", 'zn_framework' ),
						"id"          => "icon_type",
						"std"         => "icon",
						"type"        => "zn_radio",
						"options"     => array (
							'icon' => __( 'Font Icon', 'zn_framework' ),
							'img' => __( 'Image', 'zn_framework' ),
						),
					),

					array (
						"name"        => __( "Image Icon", 'zn_framework' ),
						"description" => __( "Upload an Icon Image.", 'zn_framework' ),
						"id"          => "icon_image",
						"std"         => "",
						"type"        => "media",
						"dependency"  => array( 'element' => 'icon_type' , 'value'=> array('img') ),
					),

					array (
						"name"        => __( "Select Icon", 'zn_framework' ),
						"description" => __( "Select an icon to display.", 'zn_framework' ),
						"id"          => "icon",
						"std"         => "",
						"type"        => "icon_list",
						'class'       => 'zn_icon_list',
						'compact'       => true,
						"dependency"  => array( 'element' => 'icon_type' , 'value'=> array('icon') ),
					),

					array (
						"name"        => __( "Custom URL", 'zn_framework' ),
						"description" => __( "Choose a custom URL", 'zn_framework' ),
						"id"          => "link",
						"std"         => "",
						"type"        => "link",
						"options"     => zn_get_link_targets(),
					),

				),
			),
			'styling' => array(
				'title' => 'STYLES',
				'options' => array(

					// POSITIONING
					array (
						"name"        => __( "Alignment" , 'zn_framework' ),
						"id"          => "ibstg_docs",
						"std"         => "",
						"type"        => "zn_title",
						"class"       => "zn_full zn-custom-title-large"
					),

					array (
						"name"        => __( "Alignment", 'zn_framework' ),
						"description" => __( "Choose the image's alignment.", 'zn_framework' ),
						"id"          => "alignment_breakpoints",
						"std"         => "lg",
						"tabs"        => true,
						"type"        => "zn_radio",
						"options"     => array ( "lg" => '', "md" => '', "sm" => '', "xs" => '' ),
						"class"       => "zn_breakpoints zn--minimal"
					),

						array (
							"name"        => __( "Alignment", 'zn_framework' ),
							"description" => __( "Select the image alignment.", 'zn_framework' ),
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
							"description" => __( "Select the image alignment.", 'zn_framework' ),
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
							"description" => __( "Select the image alignment.", 'zn_framework' ),
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
							"description" => __( "Select the image alignment.", 'zn_framework' ),
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

					// COLORS
					array (
						"name"        => __( "Colors" , 'zn_framework' ),
						"id"          => "ibstg_docs",
						"std"         => "",
						"type"        => "zn_title",
						"class"       => "zn_full zn-custom-title-large"
					),

					array (
						"name"        => __( "Icon Color", 'zn_framework' ),
						"description" => __( "Color of the icon.", 'zn_framework' ),
						"id"          => "icon_color",
						"std"         => "",
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'icon_type' , 'value'=> array('icon') ),
						'live' => array(
						   'type'        => 'css',
						   'css_class' => '.'.$uid.' span.zn-iconIco',
						   'css_rule'    => 'color',
						   'unit'        => ''
						),
						"class" => 'zn-non-dependent',
					),

					array (
						"name"        => __( "Icon Hover Color", 'zn_framework' ),
						"description" => __( "Hover Color of the icon.", 'zn_framework' ),
						"id"          => "icon_color_hover",
						"std"         => "",
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'icon_type' , 'value'=> array('icon') ),
						"class" => 'zn-non-dependent',
					),

					array (
						"name"        => __( "Background Color", 'zn_framework' ),
						"description" => __( "The icon's background color on normal state.", 'zn_framework' ),
						"id"          => "icon_bg_color",
						"std"         => "",
						"type"        => "colorpicker",
						'live' => array(
						   'type'        => 'css',
						   'css_class' => '.'.$uid.' .zn-iconIco',
						   'css_rule'    => 'background-color',
						   'unit'        => ''
						),
					),

					array (
						"name"        => __( "Background Hover Color", 'zn_framework' ),
						"description" => __( "The icon's background color on hover.", 'zn_framework' ),
						"id"          => "icon_bg_color_hover",
						"std"         => "",
						"type"        => "colorpicker",
					),

					// SIZING
					array (
						"name"        => __( "Sizing" , 'zn_framework' ),
						"id"          => "ibstg_docs",
						"std"         => "",
						"type"        => "zn_title",
						"class"       => "zn_full zn-custom-title-large"
					),

					array (
						"name"        => __( "Icon Size", 'zn_framework' ),
						"description" => __( "Select the size of the icon.", 'zn_framework' ),
						"id"          => "icon_size",
						"std"         => "42",
						'type'        => 'slider',
						'helpers'     => array(
							'min' => '10',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid .' span.zn-iconIco',
							'css_rule'  => 'font-size',
							'unit'      => 'px'
						),
						"dependency"  => array( 'element' => 'icon_type' , 'value'=> array('icon') ),
						"class" => 'zn-non-dependent',
					),

					array (
						"name"        => __( "Image Size", 'zn_framework' ),
						"description" => __( "Select the size of the image.", 'zn_framework' ),
						"id"          => "icon_imgwidth",
						"std"         => "100",
						'type'        => 'slider',
						'helpers'     => array(
							'min' => '10',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid .' img.zn-iconIco',
							'css_rule'  => 'width',
							'unit'      => 'px'
						),
						"dependency"  => array( 'element' => 'icon_type' , 'value'=> array('img') ),
						"class" => 'zn-non-dependent',
					),

					array (
						"name"        => __( "Icon Padding", 'zn_framework' ),
						"description" => __( "Select the padding of the icon.", 'zn_framework' ),
						"id"          => "icon_padding",
						"std"         => "22",
						'type'        => 'slider',
						'helpers'     => array(
							'min' => '2',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid .' .zn-iconIco',
							'css_rule'  => 'padding',
							'unit'      => 'px'
						),
					),

					// SIZING
					array (
						"name"        => __( "Styling" , 'zn_framework' ),
						"id"          => "ibstg_docs",
						"std"         => "",
						"type"        => "zn_title",
						"class"       => "zn_full zn-custom-title-large"
					),

					array (
						"name"        => __( "Add Border", 'zn_framework' ),
						"description" => __( "Enable if you want to add a border around the icon.", 'zn_framework' ),
						"id"          => "border",
						"std"         => "",
						"value" => 'zn-iconIco--border',
						'type'        => 'toggle2',
						'live' => array(
							'type'        => 'class',
							'css_class' => '.'.$uid,
						),
					),

					array (
						"name"        => __( "Border Radius", 'zn_framework' ),
						"description" => __( "Choose the corner roundness of the icon. Only works if there is a background color or border.", 'zn_framework' ),
						"id"          => "corner_radius",
						"std"         => "",
						"type"        => "slider",
						"helpers"     => array (
							"step" => "1",
							"min" => "0",
							"max" => "200"
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid .' .zn-iconIco',
							'css_rule'  => 'border-radius',
							'unit'      => 'px'
						),
						"class" => 'zn-non-dependent',
					),

					array (
						"name"        => __( "Icon Opacity", 'zn_framework' ),
						"description" => __( "Select the opacity of the icon.", 'zn_framework' ),
						"id"          => "icon_opacity",
						"std"         => "1",
						'type'        => 'slider',
						"helpers"     => array (
							"step" => "0.05",
							"min" => "0.1",
							"max" => "1"
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid .' .zn-iconIco',
							'css_rule'  => 'opacity',
							'unit'      => ''
						),
					),


					array (
						"name"        => __( "Misc. options" , 'zn_framework' ),
						"id"          => "ibstg_docs",
						"std"         => "",
						"type"        => "zn_title",
						"class"       => "zn_full zn-custom-title-large"
					),

					array (
						"name"        => __( "Force equal dimensions", 'zn_framework' ),
						"description" => __( "Enable if you want to force the icon's width and height to be equal (eg: perfect circle, perfect square).", 'zn_framework' ),
						"id"          => "force_square",
						"std"         => "",
						"value" => 'zn-iconIco--eq',
						'type'        => 'toggle2',
						'live' => array(
							'type'        => 'class',
							'css_class' => '.'.$uid,
						),
					),

					array (
						"name"        => __( "Add Floating Animation", 'zn_framework' ),
						"description" => __( "Enable this if you want to apply a floating up and down animation.", 'zn_framework' ),
						"id"          => "floating_animation",
						"std"         => "",
						"value"       => 'zn-spIcon--animFloat',
						'type'        => 'toggle2',
						'live' => array(
							'type'        => 'class',
							'css_class' => '.'.$uid,
						),
					),
				),
			),

			'spacing' => array(
				'title' => 'SPACING',
				'options' => array(


				),
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				// 'docs'    => 'https://my.hogash.com/documentation/icon-box/',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);

		$options['spacing']['options'] = array_merge($options['spacing']['options'], zn_margin_padding_options($uid) );

		return $options;
	}

}

ZNB()->elements_manager->registerElement( new ZNB_Icon(array(
	'id' => 'ZnIcon',
	'name' => __('Icon', 'zn_framework'),
	'description' => __('This element will display an icon.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content, Media',
	'legacy' => false,
	'keywords' => array('pictogram', 'vector', 'svg'),
)));
