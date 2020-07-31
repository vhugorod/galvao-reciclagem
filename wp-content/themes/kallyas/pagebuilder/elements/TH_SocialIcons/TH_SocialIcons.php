<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Social Icons List
 Description: Create and display as many social icon links as you want
 Class: TH_SocialIcons
 Category: content
 Level: 3
 Keywords: facebook, twitter, instagram, google
*/
/**
 * Class TH_SocialIcons
 *
 * Create and display as many social icon links as you want
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.8
 */
class TH_SocialIcons extends ZnElements
{
	public static function getName(){
		return __( "Social Icons List", 'zn_framework' );
	}


	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$icon_css = '';
		$uid = $this->data['uid'];

		$layout = $this->opt('sc_layout','normal');

		if($layout == 'vlisttitle'){
			// Title Styles
			$title_styles = '';
			$title_typo = $this->opt('title_typo');
			if( is_array($title_typo) && !empty($title_typo) ){
				foreach ($title_typo as $key => $value) {
					if($value != '') {
						if( $key == 'font-family' ){
							$title_styles .= $key .':'. zn_convert_font($value).';';
						} else {
							$title_styles .= $key .':'. $value.';';
						}
					}
				}
				if(!empty($title_styles)){
					$css .= '.'.$uid.' .elm-sc-title{'.$title_styles.'}';
				}
			}
		}

		// Icon Distance Horizontal
		$icon_distance = $this->opt('icon_distance','3');
		if( $icon_distance != '3' && $layout == 'normal' ){
			$css .= '.'.$uid.' .elm-social-icons-item{margin-left: '.$icon_distance.'px;margin-right:'.$icon_distance.'px}';
		}

		// icon_distance Vertical
		$icon_distance_vert = $this->opt('icon_distance_vert','3');
		if( $icon_distance_vert != '3'  && $layout != 'normal' ){
			$css .= '.'.$uid.' .elm-social-icons-item{margin-top: '.$icon_distance_vert.'px;margin-bottom:'.$icon_distance_vert.'px}';
		}

		// Icon sizes
		$icon_size = $this->opt('sc_size','14');
		if( $icon_size != '14'){
			$icon_css .= 'font-size:'.$icon_size.'px;';
		}
		// Icon Padding
		$icon_padding = $this->opt('icon_padding','30');
		if( $icon_padding != '30' ){
			$icon_css .= 'padding:'.$icon_padding.'px';
		}
		if(!empty($icon_css)){
			$css .= '.'.$uid.' .elm-sc-icon {'.$icon_css.'}';
		}

		$sicons = $this->opt('single_sc');
		$sc_style = $this->opt('sc_style','normal');

		if( is_array($sicons) && !empty( $sicons ) ){
			$style_css = '';
			foreach ( $sicons as $k => $icon ) {

				$color_css = $hover_color_css = '';

				$icon_tcolor = $icon['sc_icon_textcolor'];
				if( $icon_tcolor != '#ffffff' && $sc_style != 'normal'){
					$color_css .= 'color:'.$icon_tcolor.';';
				}

				$icon_bgcolor = $icon['sc_icon_color'];
				if( $icon_bgcolor != '#000000' && $sc_style != 'clean' && $sc_style != 'normal' ){
					$color_css .= 'background-color:'.$icon_bgcolor.';';
				}

				// Custom / Clean / Colored / Colored on Hover / (style options)
				if(!empty($color_css) ){
					$style_css .= '.'.$uid.' .sc--'.$sc_style.' .elm-sc-icon-'.$k.($sc_style == 'colored_hov' ? ':hover':'').' .elm-sc-icon{'.$color_css.'}';
				}

				// Custom Color Hover - Options
				if( $sc_style == 'custom' ) {
					if( isset($icon['sc_icon_textcolor_hov']) && !empty($icon['sc_icon_textcolor_hov']) ){
						$hover_color_css .= 'color:'.$icon['sc_icon_textcolor_hov'].';';
					}
					if(isset($icon['sc_icon_color_hov']) && !empty($icon['sc_icon_color_hov']) ){
						$hover_color_css .= 'background-color:'.$icon['sc_icon_color_hov'].';';
					}
					if(!empty($hover_color_css) ){
						$style_css .= '.'.$uid.' .sc--custom .elm-sc-icon-'.$k.':hover .elm-sc-icon{'.$hover_color_css.'}';
					}
				}

			}
			$css .= $style_css;
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

		$classes=array();
		$classes[] = $this->data['uid'];
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'text-'.$this->opt('el_alignment','left');
		$classes[] = 'sc-icon--'.$this->opt('el_alignment','left');

		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$classes[] = 'elm-socialicons--'.$color_scheme;
		$classes[] = 'element-scheme--'.$color_scheme;

		$attributes = zn_get_element_attributes($options);

		echo '<div class="elm-socialicons '.implode(' ', $classes).'" '.$attributes.'>';

			$sicons = $this->opt('single_sc');
			$sc_style = $this->opt('sc_style','normal');
			$layout = $this->opt('sc_layout','normal');

			$list_classes = array();
			$list_classes[] = 'sc--'.$sc_style;
			$list_classes[] = 'sh--'.$this->opt('sc_shape','rounded');
			$list_classes[] = 'sc-lay--'.$layout;

			if( is_array($sicons) && !empty( $sicons ) ){

				echo '<ul class="elm-social-icons '.implode(' ', $list_classes).' clearfix">';

					foreach ( $sicons as $k => $icon ) {

						$icon_color = '';
						if($sc_style != 'normal' && $sc_style != 'clean'){
							$icon_color = isset($icon['sc_icon_color']) && !empty($icon['sc_icon_color']) ? $icon['sc_icon_icon']['unicode'] : 'nocolor';
						}

						echo '<li class="elm-social-icons-item">';

							$sc_icon_link = zn_extract_link($icon['sc_icon_link'], 'elm-sc-link elm-sc-icon-'.$k );

							echo $sc_icon_link['start'];

							if( !empty( $icon['sc_icon_icon'] ) ) {
								echo '<span class="elm-sc-icon " '.zn_generate_icon( $icon['sc_icon_icon'] ).'></span>';
							}
							if( !empty( $icon['sc_icon_title'] ) && $layout == 'vlisttitle' ) {
								echo '<span class="elm-sc-title">'.$icon['sc_icon_title'].'</span>';
							}

							echo $sc_icon_link['end'];

							echo '<div class="clearfix"></div>';

						echo '</li>';
					}

				echo '</ul>';
			}

		echo '</div>';

	}

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
				'title' => 'General options',
				'options' => array(
					array (
						"name"        => __( "Element Alignment", 'zn_framework' ),
						"description" => __( "Please select the alignment of the button/s.", 'zn_framework' ),
						"id"          => "el_alignment",
						"std"         => "left",
						"options"     => array (
							'left' => __( 'Left (default)', 'zn_framework' ),
							'right'          => __( 'Right', 'zn_framework' ),
							'center'          => __( 'Center', 'zn_framework' )
						),
						"type"        => "select",
						'live' => array(
						   'type'           => 'class',
						   'css_class'      => '.'.$uid,
						   'val_prepend'   => 'text-',
						),
					),

					array (
						"name"        => __( "Icons style", 'zn_framework' ),
						"description" => __( "Select the style of the social icons.", 'zn_framework' ),
						"id"          => "sc_style",
						"std"         => "normal",
						"options"     => array(
							'clean' => __( 'Clean icons', 'zn_framework' ),
							'custom' => __( 'Custom Colored', 'zn_framework' ),
							'normal' => __( 'Normal Icons', 'zn_framework' ),
							//#! Not recommended because they have been deprecated in favor of "Custom Colored" icons
							'colored' => __( 'Colored icons (not recommended)', 'zn_framework' ),
							'colored_hov' => __( 'Colored on Hover icons (not recommended)', 'zn_framework' ),
						),
						"type"        => "select",
						"class"        => "zn-smartselect--md",
						'live' => array(
						   'type'           => 'class',
						   'css_class'      => '.'.$uid.' .elm-social-icons',
						   'val_prepend'   => 'sc--',
						),
					),

					array (
						"name"        => __( "Layout", 'zn_framework' ),
						"description" => __( "Select the layout of the social icons.", 'zn_framework' ),
						"id"          => "sc_layout",
						"std"         => "normal",
						"options"     => array (
							'normal'  => __( 'Icons group (Horizontal)', 'zn_framework' ),
							'vlist' => __( 'Vertical Icon List', 'zn_framework' ),
							'vlisttitle' => __( 'Vertical Icon List with Title', 'zn_framework' ),
						),
						"type"        => "select"
					),

					array (
						"name"        => __( "Social Icons Shape", 'zn_framework' ),
						"description" => __( "Select the shape of the social icons.", 'zn_framework' ),
						"id"          => "sc_shape",
						"std"         => "rounded",
						"options"     => array (
							'rounded'  => __( 'Rounded Square', 'zn_framework' ),
							'square' => __( 'Square', 'zn_framework' ),
							'circle' => __( 'Circle', 'zn_framework' ),
							'special1' => __( 'Special shaped (needs bigger padding)', 'zn_framework' )
						),
						"type"        => "select",
						'live' => array(
						   'type'           => 'class',
						   'css_class'      => '.'.$uid.' .elm-social-icons',
						   'val_prepend'   => 'sh--',
						),
						'dependency' => array('element' => 'sc_style', 'value' => array('normal' ,'colored','colored_hov')),
					),

					array (
						"name"        => __( "Social icons Font-size", 'zn_framework' ),
						"description" => __( "Select the size of the social icons.", 'zn_framework' ),
						"id"          => "sc_size",
						"std"         => "14",
						"type"         => "slider",
						'helpers'     => array(
							'min' => '10',
							'max' => '100',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid.' .elm-sc-icon',
							'css_rule'  => 'font-size',
							'unit'      => 'px'
						),
					),

					array (
						"name"        => __( "Social icons padding inside", 'zn_framework' ),
						"description" => __( "Select the size of the social icons.", 'zn_framework' ),
						"id"          => "icon_padding",
						"std"         => "30",
						"type"         => "slider",
						'helpers'     => array(
							'min' => '0',
							'max' => '200',
							'step' => '1'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid.' .elm-sc-icon',
							'css_rule'  => 'padding',
							'unit'      => 'px'
						),
					),

					array (
						"name"        => __( "Social icons Distance (horizontal)", 'zn_framework' ),
						"description" => __( "Select the distance between the social icons.", 'zn_framework' ),
						"id"          => "icon_distance",
						"std"         => "3",
						"type"         => "slider",
						'helpers'     => array(
							'min' => '0',
							'max' => '300',
							'step' => '1'
						),
						'dependency' => array('element' => 'sc_layout', 'value' => 'normal'),
						'live' => array(
							'multiple' => array(
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .elm-social-icons>li',
									'css_rule'  => 'margin-left',
									'unit'      => 'px'
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .elm-social-icons>li',
									'css_rule'  => 'margin-right',
									'unit'      => 'px'
								),
							)
						),
					),

					array (
						"name"        => __( "Social icons Distance (Vertical)", 'zn_framework' ),
						"description" => __( "Select the distance between the social icons.", 'zn_framework' ),
						"id"          => "icon_distance_vert",
						"std"         => "3",
						"type"         => "slider",
						'helpers'     => array(
							'min' => '0',
							'max' => '300',
							'step' => '1'
						),
						'dependency' => array('element' => 'sc_layout', 'value' => array('vlist','vlisttitle')),
						'live' => array(
							'multiple' => array(
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .elm-social-icons>li',
									'css_rule'  => 'margin-top',
									'unit'      => 'px'
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .elm-social-icons>li',
									'css_rule'  => 'margin-bottom',
									'unit'      => 'px'
								),
							)
						),
					),

					array (
						"name"        => __( "Title settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the title.", 'zn_framework' ),
						"id"          => "title_typo",
						"std"         => '',
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight' ),
						"type"        => "font",
						'dependency' => array('element' => 'sc_layout', 'value' => array('vlisttitle')),
					),

					array(
						'id'          => 'element_scheme',
						'name'        => 'Element Color Scheme',
						'description' => 'Select the color scheme of this element',
						'type'        => 'select',
						'std'         => '',
						'options'        => array(
							'' => 'Inherit from Kallyas options > Color Options [Requires refresh]',
							'light' => 'Light (default)',
							'dark' => 'Dark'
						),
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'zn_text_box-',
								),
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'element-scheme--',
								),
							)
						)
					),


					/**
					 * Margins and padding
					 */
					array (
						"name"        => __( "Edit element padding & margins for each device breakpoint. ", 'zn_framework' ),
						"description" => __( "This will enable you to have more control over the padding of the container on each device. Click to see <a href='http://hogash.d.pr/1f0nW' target='_blank'>how box-model works</a>.", 'zn_framework' ),
						"id"          => "spacing_breakpoints",
						"std"         => "lg",
						"tabs"        => true,
						"type"        => "zn_radio",
						"options"     => array (
							"lg"        => __( "LARGE", 'zn_framework' ),
							"md"        => __( "MEDIUM", 'zn_framework' ),
							"sm"        => __( "SMALL", 'zn_framework' ),
							"xs"        => __( "EXTRA SMALL", 'zn_framework' ),
						),
						"class"       => "zn_full zn_breakpoints"
					),
					// MARGINS
					array(
						'id'          => 'margin_lg',
						'name'        => 'Margin (Large Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container. Accepts negative margin.',
						'type'        => 'boxmodel',
						'std'	  => '',
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'margin',
						),
					),
					array(
						'id'          => 'margin_md',
						'name'        => 'Margin (Medium Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'margin_sm',
						'name'        => 'Margin (Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'margin_xs',
						'name'        => 'Margin (Extra Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
					),
					// PADDINGS
					array(
						'id'          => 'padding_lg',
						'name'        => 'Padding (Large Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => '',
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'padding',
						),
					),
					array(
						'id'          => 'padding_md',
						'name'        => 'Padding (Medium Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'padding_sm',
						'name'        => 'Padding (Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'padding_xs',
						'name'        => 'Padding (Extra Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
					),

				),
			),

			'icons' => array(
				'title' => 'Social Icons',
				'options' => array(
					array(
						"name"           => __( "Social Icons", 'zn_framework' ),
						"description"    => __( "Add Social Icons.", 'zn_framework' ),
						"id"             => "single_sc",
						"std"            => "",
						"type"           => "group",
						"add_text"       => __( "Social Icon", 'zn_framework' ),
						"remove_text"    => __( "Social Icon", 'zn_framework' ),
						"group_sortable" => true,
						"element_title" => "sc_icon_title",
						"subelements"    => array (
							'has_tabs'  => true,
							'general' => array(
								'title' => 'General',
								'options' => array(
									array (
										"name"        => __( "Icon title", 'zn_framework' ),
										"description" => __( "Here you can enter a title for this social icon.Please note that this is just
											for your information as this text will not be visible on the site.", 'zn_framework' ),
										"id"          => "sc_icon_title",
										"std"         => "",
										"type"        => "text"
									),
									array (
										"name"        => __( "Social icon link", 'zn_framework' ),
										"description" => __( "Please enter your desired link for the social icon. If this field is left
											blank, the icon will not be linked.", 'zn_framework' ),
										"id"          => "sc_icon_link",
										"std"         => "",
										"type"        => "link",
										"options"     => zn_get_link_targets(),
									),
									array (
										"name"        => __( "Social icon", 'zn_framework' ),
										"description" => __( "Select your desired social icon.", 'zn_framework' ),
										"id"          => "sc_icon_icon",
										"std"         => "",
										"type"        => "icon_list",
										'class'       => 'zn_full'
									),
								),
							),

							'colors' => array(
								'title' => 'Colors options',
								'options' => array(

									array (
										"name"        => __( "Icon Background color", 'zn_framework' ),
										"description" => __( "Select a background color for the icon.", 'zn_framework' ),
										"id"          => "sc_icon_color",
										"std"         => "#000000",
										"type"        => "colorpicker",
										"alpha" => true,
									),
									array (
										"name"        => __( "Icon color", 'zn_framework' ),
										"description" => __( "Select a color for the icon.", 'zn_framework' ),
										"id"          => "sc_icon_textcolor",
										"std"         => "#ffffff",
										"type"        => "colorpicker",
										"alpha" => true,
									),
									array (
										"name"        => __( "Icon Background color on Hover", 'zn_framework' ),
										"description" => __( "Select a background color for the icon. Will only work with \"Colored\", \"Colored on Hover \" and \"Custom Colors\" style options.", 'zn_framework' ),
										"id"          => "sc_icon_color_hov",
										"std"         => "",
										"type"        => "colorpicker",
										"alpha" => true,
									),
									array (
										"name"        => __( "Icon color on Hover", 'zn_framework' ),
										"description" => __( "Select a color for the icon. Will only work with \"Colored\", \"Colored on Hover \" and \"Custom Colors\" style options.", 'zn_framework' ),
										"id"          => "sc_icon_textcolor_hov",
										"std"         => "",
										"type"        => "colorpicker",
										"alpha" => true,
									),
								),
							),

						),
					),
				),
			),
		);
		return $options;
	}
}
