<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Text Box
 Description: Create and display a Text Box element
 Class: TH_TextBox
 Category: content
 Level: 3
 Keywords: shortcode
*/

/**
 * Class TH_TextBox
 *
 * Create and display a Text Box element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class ZNB_TextBox extends ZionElement
{
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

		return $css;
	}
	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];
		$element_id = $this->opt('custom_id') ? $this->opt('custom_id') : $uid;

		if( empty( $options ) ) { return; }

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-text';

		$attributes[] = zn_get_element_attributes($options);
		$attributes[] = 'id="'.$element_id.'"';
		$attributes[] = 'class="'.implode(' ', $classes).'"';

		echo '<div '.implode(' ', $attributes).'>';

		$stb_content = $this->opt('stb_content','');

		global $wp_embed;
		if ( is_object( $wp_embed ) ) {
			$content = $wp_embed->autoembed( $stb_content );
		}
		$content = wpautop( $content );
		if ( ! empty ( $stb_content ) ) {
			echo ZNB()->utility->makeTextEditable( $content, 'stb_content' );
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
				'title' => 'Content',
				'options' => array(
					array (
						"name"        => __( "Content", 'zn_framework' ),
						"description" => __( "Please enter the box content.<br> ** If you plan on <strong style='color:black'>pasting a shortcode</strong>, please make sure to add it in <strong style='color:black'><em>Text Mode</em></strong> of the editor.", 'zn_framework' ),
						"id"          => "stb_content",
						"std"         => "",
						"type"        => "visual_editor",
						"class"        => "zn_full",
					),

				)
			),

			'padding' => array(
				'title' => 'Spacing options',
				'options' => array(

					/**
					 * Margins and padding
					 */
					array (
						"name"        => __( "Edit padding & margins for each device breakpoint", 'zn_framework' ),
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
						'name'        => __('Margin (Large Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container. Accepts negative margin.','zn_framework'),
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
						'name'        => __('Margin (Medium Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'margin_sm',
						'name'        => __('Margin (Small Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'margin_xs',
						'name'        => __('Margin (Extra Small Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
					),
					// PADDINGS
					array(
						'id'          => 'padding_lg',
						'name'        => __('Padding (Large Breakpoints)','zn_framework'),
						'description' => __('Select the padding (in percent % or px) for this container.','zn_framework'),
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
						'name'        => __('Padding (Medium Breakpoints)','zn_framework'),
						'description' => __('Select the padding (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'padding_sm',
						'name'        => __('Padding (Small Breakpoints)','zn_framework'),
						'description' => __('Select the padding (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'padding_xs',
						'name'        => __('Padding (Extra Small Breakpoints)','zn_framework'),
						'description' => __('Select the padding (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
					),
				)
			),

			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#_ModlDp5ghI') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/text-box/') ),
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}

ZNB()->elements_manager->registerElement( new ZNB_TextBox(array(
	'id' => 'TH_TextBox', // Backwards compatibility for Kallyas
	'name' => __('Text Box', 'zn_framework'),
	'description' => __('Create and display a Text Box element', 'zn_framework'),
	'level' => 3,
	'category' => 'content',
	'keywords' => 'shortcode'
)));
