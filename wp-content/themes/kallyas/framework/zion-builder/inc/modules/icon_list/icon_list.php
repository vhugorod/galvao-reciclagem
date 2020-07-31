<?php if(! defined('ABSPATH')){ return; }

class ZNB_IconList extends ZionElement
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

					array (
						"name"        => __( "List Layout", 'zn_framework' ),
						"description" => __( "Select the list's layout.", 'zn_framework' ),
						"id"          => "list_layout",
						"std"         => "vertical",
						'type'        => 'select',
						'options'        => array(
							'vertical' => __( "Vertical.", 'zn_framework' ),
							'horizontal' => __( "Horizontal.", 'zn_framework' ),
						),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.'.$uid.' .zn-iconList',
							'val_prepend'  => 'zn-iconList--layout-',
						),
					),

					array (
						"name"        => __( "Alignment", 'zn_framework' ),
						"description" => __( "Select the list alignment.", 'zn_framework' ),
						"id"          => "alignment",
						"std"         => "center",
						"type"        => "select",
						"options"     => array(
							"left" => __("Left", 'zn_framework' ),
							"center" => __("Center", 'zn_framework' ),
							"right" => __("Right", 'zn_framework' ),
							"justify" => __("Justify", 'zn_framework' ),
						),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.'.$uid.' .zn-iconList',
							'val_prepend'  => 'zn-iconList-alg-',
						),
					),

					array (
						"name"        => __( "Items Distance", 'zn_framework' ),
						"description" => __( "Select the distance between items.", 'zn_framework' ),
						"id"          => "distance",
						"std"         => "10",
						'type'        => 'slider',
						'helpers'     => array(
							'min' => '0',
							'max' => '200',
							'step' => '1'
						),
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .zn-iconList--layout-vertical .zn-iconList-item',
									'css_rule'  => 'margin-top',
									'unit'      => 'px'
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .zn-iconList--layout-vertical .zn-iconList-item',
									'css_rule'  => 'margin-bottom',
									'unit'      => 'px'
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .zn-iconList--layout-horizontal .zn-iconList-item',
									'css_rule'  => 'margin-left',
									'unit'      => 'px'
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .zn-iconList--layout-horizontal .zn-iconList-item',
									'css_rule'  => 'margin-right',
									'unit'      => 'px'
								),
							)
						)
					),

					array(
						"name"           => __( "List Items", 'zn_framework' ),
						"description"    => __( "Add List Items.", 'zn_framework' ),
						"id"             => "single_item",
						"std"            => "",
						"type"           => "group",
						"add_text"       => __( "List Item", 'zn_framework' ),
						"remove_text"    => __( "List Item", 'zn_framework' ),
						"group_sortable" => true,
						"element_title" => "text",
						"subelements"    => array (

							array (
								"name"        => __( "List item text", 'zn_framework' ),
								"description" => __( "Here you can add text. Please know it also accepts HTML code.", 'zn_framework' ),
								"id"          => "text",
								"std"         => "",
								"type"        => "textarea"
							),
							array (
								"name"        => __( "List item link", 'zn_framework' ),
								"description" => __( "Wrap the list item into a custom link. If this field is left blank, the item will not be linked.", 'zn_framework' ),
								"id"          => "link",
								"std"         => "",
								"type"        => "link",
								"options"     => zn_get_link_targets(),
							),

							array (
								"name"        => __( "Select icon", 'zn_framework' ),
								"description" => __( "Select your desired icon.", 'zn_framework' ),
								"id"          => "icon",
								"std"         => "",
								"type"        => "icon_list",
								'class'       => 'zn_icon_list',
								'compact'       => true,
							),

							array (
								"name"        => __( "Icon Color (Custom)", 'zn_framework' ),
								"description" => __( "Color of the icon.", 'zn_framework' ),
								"id"          => "icon_color",
								"std"         => "",
								"type"        => "colorpicker",
							),

							array (
								"name"        => __( "Icon Hover Color (Custom)", 'zn_framework' ),
								"description" => __( "Hover Color of the icon.", 'zn_framework' ),
								"id"          => "icon_color_hover",
								"std"         => "",
								"type"        => "colorpicker",
							),

							array (
								"name"        => __( "Background Color (Custom)", 'zn_framework' ),
								"description" => __( "The icon's background color on normal state.", 'zn_framework' ),
								"id"          => "icon_bg_color",
								"std"         => "",
								"type"        => "colorpicker",
							),

							array (
								"name"        => __( "Background Hover Color", 'zn_framework' ),
								"description" => __( "The icon's background color on hover.", 'zn_framework' ),
								"id"          => "icon_bg_color_hover",
								"std"         => "",
								"type"        => "colorpicker",
							),
						),
					),



				),
			),

			'style' => array(
				'title' => 'Icon Style',
				'options' => array(

					// COLORS
					array (
						"name"        => __( "Icon Colors" , 'zn_framework' ),
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
						'live' => array(
						   'type'        => 'css',
						   'css_class' => '.'.$uid.' .zn-iconList-itemIcon',
						   'css_rule'    => 'color',
						   'unit'        => ''
						),
					),

					array (
						"name"        => __( "Icon Hover Color", 'zn_framework' ),
						"description" => __( "Hover Color of the icon.", 'zn_framework' ),
						"id"          => "icon_color_hover",
						"std"         => "",
						"type"        => "colorpicker",
					),

					array (
						"name"        => __( "Background Color", 'zn_framework' ),
						"description" => __( "The icon's background color on normal state.", 'zn_framework' ),
						"id"          => "icon_bg_color",
						"std"         => "",
						"type"        => "colorpicker",
						'live' => array(
						   'type'        => 'css',
						   'css_class' => '.'.$uid.' .zn-iconList-itemIcon',
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
						"name"        => __( "Icon Sizing" , 'zn_framework' ),
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
							'css_class' => '.'.$uid .' .zn-iconList-itemIcon',
							'css_rule'  => 'font-size',
							'unit'      => 'px'
						),
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
							'css_class' => '.'.$uid .' .zn-iconList-itemIcon',
							'css_rule'  => 'padding',
							'unit'      => 'px'
						),
					),

					array (
						"name"        => __( "Icon Decorations" , 'zn_framework' ),
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
						"value" => 'zn-iconList--border',
						'type'        => 'toggle2',
						'live' => array(
							'type'        => 'class',
							'css_class' => '.'.$uid.' .zn-iconList',
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
							'css_class' => '.'.$uid .' .zn-iconList-itemIcon',
							'css_rule'  => 'border-radius',
							'unit'      => 'px'
						),
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
							'css_class' => '.'.$uid .' .zn-iconList-itemIcon',
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
						"value" => 'zn-iconList--eq',
						'type'        => 'toggle2',
						'live' => array(
							'type'        => 'class',
							'css_class' => '.'.$uid.' .zn-iconList',
						),
					),
				),
			),


			'other' => array(
				'title' => 'Other',
				'options' => array(

					array (
						"name"        => __( "List items typography", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the list items.", 'zn_framework' ),
						"id"          => "list_typo",
						"std"         => '',
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight', 'spacing', 'case' ),
						"type"        => "font",
						'live' => array(
							'type'      => 'font',
							'css_class' => '.'.$uid. ' .zn-iconList-itemText',
						),
					),

				),
			),


			'spacing' => array(
				'title' => 'Spacing',
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

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-iconList-el';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		echo '<div class="'.zn_join_spaces($classes).'" '. zn_join_spaces( $attributes ) .'>';

			$list_items = $this->opt('single_item', array(
				array(
					'text' => __('List item','zn_framework'),
				),
				array(
					'text' => __('List item','zn_framework'),
				),
				array(
					'text' => __('List item','zn_framework'),
					'link' => array(
						'url' => '#',
						'target' => '_self',
						'title' => 'Press me',
					),
				),
			));

			if( is_array($list_items) && !empty( $list_items ) ){

				$list_classes[] = 'zn-iconList-alg-'.$this->opt('alignment', 'center');
				$list_classes[] = 'zn-iconList--layout-'.$this->opt('list_layout', 'vertical');
				$list_classes[] = $this->opt('border','');
				$list_classes[] = $this->opt('force_square','');

				echo '<ul class="zn-iconList '.zn_join_spaces($list_classes).'">';

					foreach ( $list_items as $k => $i ) {

						echo '<li class="zn-iconList-item">';

							$link['start'] = $link['end'] ='';

							if(isset( $i['link'] ) && !empty($i['link'])){
								$link = zn_extract_link( $i['link'], 'zn-iconList-itemLink' );
							}

							echo $link['start'];
							if( !empty( $i['icon'] ) ) {
								echo '<span class="zn-iconList-itemIcon zn-iconList-itemIcon-'.$k.' '. (empty( $i['text'] ) ? 'is-single':'is-not-single') .'" '.zn_generate_icon( $i['icon'] ).'></span>';
							}
							if( !empty( $i['text'] ) ) {
								echo '<span class="zn-iconList-itemText">'.$i['text'].'</span>';
							}
							echo $link['end'];

						echo '</li>';
					}
				echo '</ul>';
			}
		echo '</div>';

	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$uid = $this->data['uid'];
		$css = $ico_css = $ico_hov_css = '';

		$icon_list = $this->opt('single_item','');
		$list_layout = $this->opt('list_layout','vertical');

		if( !empty($icon_list) ){
			foreach ($icon_list as $i => $item) {
				$sg_ico_css = $sg_ico_hov_css = '';
				// Icon color default and on hover
				if(isset($item['icon_color']) && ($icon_color = $item['icon_color'])){
					$sg_ico_css .= 'color:'.$icon_color.';';
				}
				if(isset($item['icon_color_hover']) && ($icon_color_hover = $item['icon_color_hover'])){
					$sg_ico_hov_css .= 'color:'.$icon_color_hover.';';
				}
				if(isset($item['icon_bg_color']) && ($bg_color = $item['icon_bg_color'])){
					$sg_ico_css .= 'background-color:'.$bg_color.';';
				}
				if(isset($item['icon_bg_color_hover']) && ($bg_color_hover = $item['icon_bg_color_hover'])){
					$sg_ico_hov_css .= 'background-color:'.$bg_color_hover.';';
				}
				if(!empty($sg_ico_css)){
					$css .= '.'.$uid.' .zn-iconList-itemIcon.zn-iconList-itemIcon-'.$i.'{'.$sg_ico_css.'} ';
				}
				if(!empty($sg_ico_hov_css)){
					$css .= '.'.$uid.' .zn-iconList-item:hover .zn-iconList-itemIcon.zn-iconList-itemIcon-'.$i.', .'.$uid.' .zn-iconList-item:focus .zn-iconList-itemIcon.zn-iconList-itemIcon-'.$i.' {'.$sg_ico_hov_css.'} ';
				}
			}
		}

		// Icon color default and on hover
		if($icon_color = $this->opt('icon_color', '' )){
			$ico_css .= 'color:'.$icon_color.';';
		}
		if($icon_color_hover = $this->opt('icon_color_hover', '' )){
			$ico_hov_css .= 'color:'.$icon_color_hover.';';
		}

		if($bg_color = $this->opt('icon_bg_color', '')){
			$ico_css .= 'background-color:'.$bg_color.';';
		}
		if($bg_color_hover = $this->opt('icon_bg_color_hover', '')){
			$ico_hov_css .= 'background-color:'.$bg_color_hover.';';
		}

		// Icon sizes
		$icon_size = $this->opt('icon_size','22');
		if( $icon_size != '22'){
			$ico_css .= 'font-size:'.$icon_size.'px;';
		}

		// Icon Padding
		$icon_padding = $this->opt('icon_padding','10');
		if( $icon_padding != '10' ){
			$ico_css .= 'padding:'.$icon_padding.'px;';
		}

		// Icon Opacity
		$icon_opacity = $this->opt('icon_opacity','1');
		if( $icon_opacity != '1' && $icon_opacity != '' ){
			$ico_css .= 'opacity: '.$icon_opacity.';';
		}

		// Radius
		if( $radius = $this->opt('corner_radius','') ){
			$ico_css .= 'border-radius:'.$radius.'px;';
		}

		if(!empty($ico_css)){
			$css .= '.'.$uid.' .zn-iconList-itemIcon{'.$ico_css.'} ';
		}
		if(!empty($ico_hov_css)){
			$css .= '.'.$uid.' .zn-iconList-item:hover .zn-iconList-itemIcon, .'.$uid.' .zn-iconList-item:focus .zn-iconList-itemIcon{'.$ico_hov_css.'} ';
		}

		// Distance
		if( $distance = $this->opt('distance','10') ){
			$dists = array(
				'vertical' => array('top', 'bottom'),
				'horizontal' => array('left', 'right'),
			);
			$css .= '.'.$uid.' .zn-iconList--layout-'. $list_layout .' .zn-iconList-item {margin-'.$dists[ $list_layout ][0].': '.$distance.'px; margin-'.$dists[ $list_layout ][1].': '.$distance.'px;}';
		}

		$typo = array();
		$typo['lg'] = $this->opt('list_typo', '' );
		if( !empty($typo) ){
			$typo['selector'] = '.'.$uid. ' .zn-iconList-itemText';
			$css .= zn_typography_css( $typo );
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


}

ZNB()->elements_manager->registerElement( new ZNB_IconList(array(
	'id' => 'ZnIconList',
	'name' => __('IconList', 'zn_framework'),
	'description' => __('Create a vertical or horizontal list with icons.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content',
	'legacy' => false,
	'keywords' => array('ul', 'li', 'icon', 'icon list', 'social icons'),
)));
