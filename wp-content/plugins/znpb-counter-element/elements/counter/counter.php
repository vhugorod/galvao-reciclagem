<?php if(! defined('ABSPATH')){ return; }
/*
	Name: Counter Element
	Description: This element will generate an animated number counter
	Class: ZnCounter
	Category: content
	Keywords: progress, number
	Level: 3
	Style: true
	Scripts: true
*/


class ZnCounter extends ZnElements {

	public static function getName(){
		return __( "Counter", 'znpb-counter-element' );
	}
	function options() {

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						"name"        => __( "Number: FROM", 'znpb-counter-element' ),
						"description" => __( "The number for start.", 'znpb-counter-element' ),
						"id"          => "from",
						"std"         => "0",
						"type"        => "text",
						"class"       => "zn_input_sm",
						"numeric"     => true,
						'helpers'     => array(
							'min' => '0',
							'step' => '1'
						),
					),

					array (
						"name"        => __( "Number: TO", 'znpb-counter-element' ),
						"description" => __( "The ending number to animate to.", 'znpb-counter-element' ),
						"id"          => "to",
						"std"         => "100",
						"type"        => "text",
						"class"       => "zn_input_sm",
						"numeric"     => true,
						'helpers'     => array(
							'min' => '1',
							'step' => '1'
						),
					),

					array (
						"name"        => __( "Speed (ms)", 'znpb-counter-element' ),
						"description" => __( "The animation speed in miliseconds.", 'znpb-counter-element' ),
						"id"          => "speed",
						"std"         => "1000",
						"type"        => "text",
						"class"       => "zn_input_sm",
						"numeric"     => true,
						'helpers'     => array(
							'min' => '200',
							'step' => '100'
						),
					),

					array (
						"name"        => __( "Refresh Interval", 'znpb-counter-element' ),
						"description" => __( "How often the element should be updated.", 'znpb-counter-element' ),
						"id"          => "refresh_int",
						"std"         => "100",
						"type"        => "text",
						"class"       => "zn_input_sm",
						"numeric"     => true,
						'helpers'     => array(
							'min' => '1',
							'step' => '1'
						),
					),

					array (
						"name"        => __( "Decimals", 'znpb-counter-element' ),
						"description" => __( "The number of decimal places to show.", 'znpb-counter-element' ),
						"id"          => "decimals",
						"std"         => "0",
						"type"        => "text",
						"class"       => "zn_input_sm",
						"numeric"     => true,
						'helpers'     => array(
							'min' => '0',
							'max' => '10',
							'step' => '1'
						),
					),

					array (
						"name"        => __( "Text Before", 'znpb-counter-element' ),
						"description" => __( "Some text to add before the number.", 'znpb-counter-element' ),
						"id"          => "text_before",
						"std"         => "",
						"type"        => "text",
						"class"       => "zn_input_md",
						"placeholder" => "eg: #",
					),

					array (
						"name"        => __( "Text After", 'znpb-counter-element' ),
						"description" => __( "Some text to add after the number.", 'znpb-counter-element' ),
						"id"          => "text_after",
						"std"         => "",
						"type"        => "text",
						"class"       => "zn_input_md",
					),

					array (
						"name"        => __( "Fade in animation?", 'znpb-counter-element' ),
						"description" => __( "Add a fade in animation when in viewport? If you enabled Animations in Kallyas options > Layout > Entering animations, you can disable this option and use that instead.", 'znpb-counter-element' ),
						"id"          => "fadein",
						"std"         => "yes",
						"value"         => "yes",
						"type"        => "toggle2",
					),

				),
			),

			'styling' => array(
				'title' => 'Styling options',
				'options' => array(

					array (
						"name"        => __( "Edit settings for each device breakpoint. ", 'znpb-counter-element' ),
						"description" => __( "This will enable you to have more control over the font typography, margins or padding of the element on each device. .", 'znpb-counter-element' ),
						"id"          => "breakpoints",
						"std"         => "lg",
						"tabs"        => true,
						"type"        => "zn_radio",
						"options"     => array (
							"lg"        => __( "LARGE", 'znpb-counter-element' ),
							"md"        => __( "MEDIUM", 'znpb-counter-element' ),
							"sm"        => __( "SMALL", 'znpb-counter-element' ),
							"xs"        => __( "EXTRA SMALL", 'znpb-counter-element' ),
						),
						"class"       => "zn_full zn_breakpoints"
					),

					array (
						"name"        => __( "Typography settings", 'znpb-counter-element' ),
						"description" => __( "Specify the typography properties for the text.", 'znpb-counter-element' ),
						"id"          => "text_typo_lg",
						"std"         => array(
							'font-size' => '16px',
							'line-height' => '28px'
						),
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight', 'spacing', 'case', 'shadow' ),
						"type"        => "font",
						'live' => array(
							'type'      => 'font',
							'css_class' => '.'.$uid,
						),
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('lg') ),
					),

					array (
						"name"        => __( "Typography settings", 'znpb-counter-element' ),
						"description" => __( "Specify the typography properties for the text.", 'znpb-counter-element' ),
						"id"          => "text_typo_md",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('md') ),
					),

					array (
						"name"        => __( "Typography settings", 'znpb-counter-element' ),
						"description" => __( "Specify the typography properties for the text.", 'znpb-counter-element' ),
						"id"          => "text_typo_sm",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('sm') ),
					),

					array (
						"name"        => __( "Typography settings", 'znpb-counter-element' ),
						"description" => __( "Specify the typography properties for the text.", 'znpb-counter-element' ),
						"id"          => "text_typo_xs",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('xs') ),
					),


					/**
					 * Margins and padding
					 */

					// MARGINS
					array(
						'id'          => 'margin_lg',
						'name'        => 'Margin (Large Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container. Accepts negative margin.',
						'type'        => 'boxmodel',
						'std'		=> array('bottom' => '30px'),
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('lg') ),
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
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'margin_sm',
						'name'        => 'Margin (Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'margin_xs',
						'name'        => 'Margin (Extra Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('xs') ),
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
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('lg') ),
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
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'padding_sm',
						'name'        => 'Padding (Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'padding_xs',
						'name'        => 'Padding (Extra Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'breakpoints' , 'value'=> array('xs') ),
					),


					array (
						"name"        => __( "Alignment", 'znpb-counter-element' ),
						"description" => __( "Select the alignment", 'znpb-counter-element' ),
						"id"          => "alignment",
						"std"         => "center",
						"type"        => "select",
						"options"     => array(
							"left" => "Left",
							"center" => "Center",
							"right" => "Right"
						),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.'.$uid,
							'val_prepend'  => 'text-',
						),
					),


				)
			),

		);

		return $options;
	}

	function element() {

		$options = $this->data['options'];

		//Class
		$classes = array();
		$classes[] = $uid = $this->data['uid'];
		$classes[] = 'text-'. $this->opt('alignment', 'center');
		$classes[] = zn_get_element_classes($options);

		if( $this->opt('fadein','yes') == 'yes'){
			$classes[] = 'zn-counterElm-fadeIn';
		}

		$attributes = zn_get_element_attributes($options);

		$count_options = array(
			"from" => $this->opt('from', '0'),
			"to" => $this->opt('to', '100'),
			"speed" => $this->opt('speed', '1000'),
			"refreshInterval" => $this->opt('refresh_int', '100'),
			"decimals" => $this->opt('decimals', '0'),
		);
		$attributes .= ' data-zn-count=\''.json_encode($count_options).'\'';

		if( $this->opt('text_before','') ){
			$attributes .= ' data-text-before="'. $this->opt('text_before','') .'"';
		}
		if( $this->opt('text_after','') ){
			$attributes .= ' data-text-after="'. $this->opt('text_after','') .'"';
		}

		echo '<div class="zn-counterElm '. implode(' ', $classes) .'" '. $attributes . '>'.$this->opt('from', '0').'</div>';

	}

	function css(){

		$uid = $this->data['uid'];
		$css = '';

		// Margins
		$margins = array();

		$margins['lg'] = $this->opt( 'margin_lg', array('bottom' => '30px') );
		if($this->opt('margin_md', '' )) $margins['md'] = $this->opt('margin_md');
		if($this->opt('margin_sm', '' )) $margins['sm'] = $this->opt('margin_sm');
		if($this->opt('margin_xs', '' )) $margins['xs'] = $this->opt('margin_xs');
		if( !empty($margins) ){
			$margins['selector'] = '.'.$uid;
			$margins['type'] = 'margin';
			$css .= zn_push_boxmodel_styles( $margins );
		}


		// Paddings
		$paddings = array();
		if($this->opt('padding_lg', '' )) $paddings['lg'] = $this->opt('padding_lg');
		if($this->opt('padding_md', '' )) $paddings['md'] = $this->opt('padding_md');
		if($this->opt('padding_sm', '' )) $paddings['sm'] = $this->opt('padding_sm');
		if($this->opt('padding_xs', '' )) $paddings['xs'] = $this->opt('padding_xs');
		if( !empty($paddings) ){
			$paddings['selector'] = '.'.$uid;
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}

		// Typography
		$typo = array();
		$typo['lg'] = $this->opt('text_typo_lg', array('font-size' => '16px', 'line-height' => '28px') );
		if($this->opt('text_typo_md', '' )) $typo['md'] = $this->opt('text_typo_md');
		if($this->opt('text_typo_sm', '' )) $typo['sm'] = $this->opt('text_typo_sm');
		if($this->opt('text_typo_xs', '' )) $typo['xs'] = $this->opt('text_typo_xs');
		if( !empty($typo) ){
			$typo['selector'] = '.'.$uid;
			$css .= zn_typography_css( $typo );
		}

		return $css;

	}

	/**
	 * Load dependant resources
	 */
	function scripts(){
		wp_enqueue_script( 'countto', plugin_dir_url( __FILE__ ) . 'js/jquery.countTo.min.js', array ( 'jquery' ), ZN_FW_VERSION, true );
		wp_enqueue_script( 'countto_el', plugin_dir_url( __FILE__ ) . 'js/app.js', array ( 'countto' ), ZN_FW_VERSION, true );
	}
}