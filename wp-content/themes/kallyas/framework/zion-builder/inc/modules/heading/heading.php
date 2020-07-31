<?php

class ZNB_Heading extends ZionElement
{
	function options() {

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General options','zn_framework'),
				'options' => array(

					array (
						"name"        => __( "Title Text", 'zn_framework' ),
						"description" => __( "Add the title. Shortcodes and HTML code are allowed.", 'zn_framework' ),
						"id"          => "title",
						"std"         => __("Hello World!",'zn_framework'),
						"type"        => "textarea",
					),

					array (
						"name"        => __( "Title Heading Type", 'zn_framework' ),
						"description" => __( "Select a title heading. The title will be wrapped in this tag.", 'zn_framework' ),
						"id"          => "tag",
						"std"         => "h3",
						"type"        => "select",
						"options"     => array(
								"h1" => "H1",
								"h2" => "H2",
								"h3" => "H3",
								"h4" => "H4",
								"h5" => "H5",
								"h6" => "H6",
								"div" => "div",
								"p" => __("Paragraph",'zn_framework'),
							)
					),

					array (
						"name"        => __( "Add Link", 'zn_framework' ),
						"description" => __( "Add a link to this heading.", 'zn_framework' ),
						"id"          => "link",
						"std"         => "",
						"type"        => "link",
						"options"     => zn_get_link_targets(),
					),

					array (
						// "name"        => __( "Title Typography settings", 'zn_framework' ),
						// "description" => __( "Adjust the typography of the title as you want on any breakpoint", 'zn_framework' ),
						"id"          => "font_breakpoints",
						"std"         => "lg",
						"tabs"        => true,
						"type"        => "zn_radio",
						"options"     => array (
							"lg"        => __( "LARGE", 'zn_framework' ),
							"md"        => __( "MEDIUM", 'zn_framework' ),
							"sm"        => __( "SMALL", 'zn_framework' ),
							"xs"        => __( "EXTRA SMALL", 'zn_framework' ),
						),
						"class"       => "zn_full zn_breakpoints zn_breakpoints--small"
					),

					array (
						"name"        => __( "Title settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the title.", 'zn_framework' ),
						"id"          => "title_typo",
						"std"         => '',
						'supports'   => array( 'size', 'font', 'style', 'line', 'weight', 'spacing', 'case', 'align' ),
						"type"        => "font",
						'live' => array(
							'type'      => 'font',
							'css_class' => '.'.$uid,
						),
						"dependency"  => array( 'element' => 'font_breakpoints' , 'value'=> array('lg') ),
					),

					array (
						"name"        => __( "Title settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the title.", 'zn_framework' ),
						"id"          => "title_typo_md",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing', 'align' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'font_breakpoints' , 'value'=> array('md') ),
					),

					array (
						"name"        => __( "Title settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the title.", 'zn_framework' ),
						"id"          => "title_typo_sm",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing', 'align' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'font_breakpoints' , 'value'=> array('sm') ),
					),

					array (
						"name"        => __( "Title settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the title.", 'zn_framework' ),
						"id"          => "title_typo_xs",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing', 'align' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'font_breakpoints' , 'value'=> array('xs') ),
					),

					array (
						"name"        => __( "Color", 'zn_framework' ),
						"description" => __( "Choose the text color.", 'zn_framework' ),
						"id"          => "color",
						"std"         => "",
						"type"        => "colorpicker",
						"alpha"       => "true",
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid,
									'css_rule'  => 'color',
									'unit'      => ''
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .dn-headingLink',
									'css_rule'  => 'color',
									'unit'      => ''
								),
							)
						)
					),

					array (
						"name"        => __( "Hover Color", 'zn_framework' ),
						"description" => __( "Choose the Hover Color. Will work only if linked!!", 'zn_framework' ),
						"id"          => "hover_color",
						"std"         => "",
						"type"        => "colorpicker",
						"alpha"       => "true",
					),

				)
			),


			'spacing' => array(
				'title' => __('Spacing settings','zn_framework'),
				'options' => array(

				)
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				// 'docs'    => 'https://my.hogash.com/documentation/title-element/',
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
		$classes[] = 'dn-heading';

		$attributes[] = 'class="'.implode(' ', $classes).'"';
		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = zn_schema_markup('title');

		$title_heading = $this->opt('tag', 'h3');

		$link = zn_extract_link( $this->opt('link',''), 'dn-headingLink' );

		echo '<'.$title_heading.' '. implode(' ', $attributes ) .'>';
			echo $link['start'];
				echo do_shortcode( $this->opt('title', 'Hello World!') );
			echo $link['end'];
		echo '</'.$title_heading.'>';

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

		// Title Typography
		$typo = array();
		$typo['lg'] = $this->opt('title_typo', '' );
		$typo['md'] = $this->opt('title_typo_md', '' );
		$typo['sm'] = $this->opt('title_typo_sm', '' );
		$typo['xs'] = $this->opt('title_typo_xs', '' );
		if( !empty($typo) ){
			$typo['selector'] = '.'.$uid;
			$css .= zn_typography_css( $typo );
		}

		$link = $this->opt('link','');
		$selector = '.' . $uid . ( isset($link['url']) && !empty($link['url']) ? ' .dn-headingLink' : '' );

		// Color
		if( $color = $this->opt('color', '') ){
			$css .= $selector.'{color:'.$color.'}';
		}

		if( !empty($link) && ($hover_color = $this->opt('hover_color', '')) ){
			$css .= $selector.':hover{color:'.$hover_color.'}';
		}

		return $css;
	}

}

ZNB()->elements_manager->registerElement( new ZNB_Heading(array(
	'id' => 'ZnHeading',
	'name' => __('Heading', 'zn_framework'),
	'description' => __('This element will generate a heading element.', 'zn_framework'),
	'level' => 3,
	'category' => 'content',
	'legacy' => false,
	'keywords' => array('heading', 'headline', 'title', 'subtitle', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
)));
