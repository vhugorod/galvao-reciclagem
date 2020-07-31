<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Simple Countdown
 Description: Create and display a Simple Countdown element
 Class: TH_Countdown
 Category: content
 Level: 3
 Keywords: counter
*/

/**
 * Class TH_Countdown
 *
 * Create and display a Simple Countdown element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.9
 */
class TH_Countdown extends ZnElements
{
	public static function getName(){
		return __( "Simple Countdown", 'zn_framework' );
	}

	public function scripts(){
		if(! wp_script_is('jquery', 'enqueued')) {
			wp_enqueue_script('jquery');
		}
		if(! wp_script_is('zn_event_countdown', 'enqueued')) {
			wp_enqueue_script( 'zn_event_countdown', THEME_BASE_URI . '/addons/countdown/jquery.countdown.min.js', array( 'jquery' ), ZN_FW_VERSION, true );
		}
	}


	public function css(){
		$uid = $this->data['uid'];
		$css = '';

		$margin_std_lg = array(
			'top' => ( isset($this->data['options']['top_padding']) && !empty($this->data['options']['top_padding']) ? $this->data['options']['top_padding'] : '' ),
			'bottom' => ( isset($this->data['options']['bottom_padding']) && !empty($this->data['options']['bottom_padding']) ? $this->data['options']['bottom_padding'] : '20' ),
		);

		// Margin
		$margins = array();
		$margins['lg'] = $this->opt('cc_margin_lg', $margin_std_lg ) ?  $this->opt('cc_margin_lg', $margin_std_lg ) : '';
		$margins['md'] = $this->opt('cc_margin_md', '' ) ?  $this->opt('cc_margin_md', '' ) : '';
		$margins['sm'] = $this->opt('cc_margin_sm', '' ) ?  $this->opt('cc_margin_sm', '' ) : '';
		$margins['xs'] = $this->opt('cc_margin_xs', '' ) ?  $this->opt('cc_margin_xs', '' ) : '';
		if( !empty($margins) ){
			$margins['selector'] = '.'.$uid.' .kl-counter-li';
			$margins['type'] = 'margin';
			$css .= zn_push_boxmodel_styles( $margins );
		}


		// Padding
		$paddings = array();
		$paddings['lg'] = $this->opt('cc_padding_lg', '' ) ?  $this->opt('cc_padding_lg', '' ) : '';
		$paddings['md'] = $this->opt('cc_padding_md', '' ) ?  $this->opt('cc_padding_md', '' ) : '';
		$paddings['sm'] = $this->opt('cc_padding_sm', '' ) ?  $this->opt('cc_padding_sm', '' ) : '';
		$paddings['xs'] = $this->opt('cc_padding_xs', '' ) ?  $this->opt('cc_padding_xs', '' ) : '';
		if( !empty($paddings) ){
			$paddings['selector'] = '.'.$uid.' .kl-counter-li';
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}


		$bgColor = $this->opt('background_color', '');
		if(! empty($bgColor)){
			$css .= ".{$uid} .kl-counter-li { background-color: $bgColor !important; }";
		}

		$box_radius = $this->opt('corner_radius', 2);
		if($box_radius != '2'){
			$css .= ".{$uid} .kl-counter-li {border-radius: {$box_radius}px}";
		}

		// Width
		$css .= zn_smart_slider_css( $this->opt( 'width', 70 ), '.'.$uid.' .kl-counter-li', 'width' );


		// Inherit old "text_color" option
		$text_color_std = array();
		if(isset($this->data['options']['text_color']) && $this->data['options']['text_color'] != '' ){
			$text_color_std['color'] = $this->data['options']['text_color'];
		}

		// Digit Styles
		$typo = array();
		$typo['lg'] = $this->opt('typo', '' );
		$typo['md'] = $this->opt('typo_md', '' );
		$typo['sm'] = $this->opt('typo_sm', '' );
		$typo['xs'] = $this->opt('typo_xs', '' );
		if( !empty($typo) ){
			$typo['selector'] = '.'.$uid.' .kl-counter-li';
			$css .= zn_typography_css( $typo );
		}

		// Unit Styles
		$typo = array();
		$typo['lg'] = $this->opt('unit_typo', '' );
		$typo['md'] = $this->opt('unit_typo_md', '' );
		$typo['sm'] = $this->opt('unit_typo_sm', '' );
		$typo['xs'] = $this->opt('unit_typo_xs', '' );
		if( !empty($typo) ){
			$typo['selector'] = '.'.$uid.' .kl-counter-unit';
			$css .= zn_typography_css( $typo );
		}
		
		return $css;
	}
	public function js()
	{
		// General Options
		$date = $this->opt( 'th_sc_date' );

		if(empty($date)){
			$date = array(
				'date' => date('Y-m-d'),
				'time' => '24:00',
			);
		}
		if(empty($date['date'])){
			$date['date'] = date('Y-m-d');
		}
		if(empty($date['time'])){
			$date['time'] = '24:00';
		}

		$str_date = strtotime(trim( $date['date']));
		$y = date('Y', $str_date);
		$mo = date('m', $str_date);
		$d = date('d', $str_date);
		$time = explode(':', $date['time']);
		$h = $time[0];
		$mi = $time[1];

		$years = __('years', 'zn_framework');
		$months = __('months', 'zn_framework');
		$weeks = __('weeks', 'zn_framework');
		$days = __('days', 'zn_framework');
		$hours = __('hours', 'zn_framework');
		$min = __('min', 'zn_framework');
		$sec = __('sec', 'zn_framework');

		$selector = '#'.$this->data['uid'].' .th-counter';

		$shown = $this->opt('display_unit', 'yes') == 'no' ? 'hidden' : '';

		$js = <<<JS_SCRIPT
var th_counterOptions = {
	layout: function ()
	{
		return '<li class="kl-counter-li"><span class="kl-counter-value">{dn}</span><span class="kl-counter-unit {$shown}">{dl}</span></li>' +
			'<li class="kl-counter-li"><span class="kl-counter-value">{hn}</span><span class="kl-counter-unit {$shown}">{hl}</span></li>' +
			'<li class="kl-counter-li"><span class="kl-counter-value">{mn}</span><span class="kl-counter-unit {$shown}">{ml}</span></li>' +
			'<li class="kl-counter-li"><span class="kl-counter-value">{sn}</span><span class="kl-counter-unit {$shown}">{sl}</span></li>';
	}
};

var years  = "{$years}",
	months = "{$months}",
	weeks  = "{$weeks}",
	days   = "{$days}",
	hours  = "{$hours}",
	min    = "{$min}",
	sec    = "{$sec}";

var y = {$y},
	mo = {$mo}-1,
	d = {$d},
	h = {$h},
	mi = {$mi},
	t = new Date(y, mo, d, h, mi, 0);

jQuery('{$selector}').countdown({
	until: t,
	layout: th_counterOptions.layout(),
	labels: [years, months, weeks, days, hours, min, sec],
	labels1: [years, months, weeks, days, hours, min, sec],
	format: 'DHMS'
});
JS_SCRIPT;

		return array( 'th_countdown'.$this->data['uid'] => $js );
	}

	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];

		$classes=array();
		$classes[] = $uid = $this->data['uid'];
		$classes[] = 'text-'.$this->opt('te_alignment', 'left');
		$classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);

		echo '<div id="'.$uid.'" class="ud_counter kl-counter kl-font-alt '.implode(' ', $classes).'" '.$attributes.'>';

		?>
		<ul class="th-counter kl-counter-list">
			<?php

			$units = array(
				_e( 'day', 'zn_framework' ),
				_e( 'hours', 'zn_framework' ),
				_e( 'min', 'zn_framework' ),
				_e( 'sec', 'zn_framework' ),
			);

			$shown = $this->opt('display_unit', 'yes') == 'no' ? 'hidden' : '';

			foreach ($units as $value) {
				echo '<li class="kl-counter-li">'. _e( '0', 'zn_framework' ).'<span class="kl-counter-unit '.$shown.'">'. $value .'</span></li>';
			}
			?>
		</ul>
		<?php
		echo '</div>';
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$text_color_std = array();
		if(isset($this->data['options']['text_color']) && $this->data['options']['text_color'] != '' ){
			$text_color_std['color'] = $this->data['options']['text_color'];
		}

		$margin_std_lg = array(
			'top' => ( isset($this->data['options']['top_padding']) && !empty($this->data['options']['top_padding']) ? $this->data['options']['top_padding'] : '' ),
			'bottom' => ( isset($this->data['options']['bottom_padding']) && !empty($this->data['options']['bottom_padding']) ? $this->data['options']['bottom_padding'] : '20px' ),
			'left' => '10px',
			'right' => '10px',
		);

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General', 'zn_framework'),
				'options' => array(
					array (
						"name"        => __( "Date", 'zn_framework' ),
						"description" => __( "Please specify the end date", 'zn_framework' ),
						"id"          => "th_sc_date",
						"std"         => date('Y-M-D'),
						"type"        => "date_picker",
					),
				)
			),
			'padding' => array(
				'title' => 'Style options',
				'options' => array(

					array (
						"name"        => __( "Alignment", 'zn_framework' ),
						"description" => __( "Select the alignment", 'zn_framework' ),
						"id"          => "te_alignment",
						"std"         => "left",
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
						"class"       => "zn_full zn_breakpoints"
					),


					array (
						"name"        => __( "Digits Typography Settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the digits.", 'zn_framework' ),
						"id"          => "typo",
						"std"         => $text_color_std,
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight', 'spacing', 'case', 'shadow' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'font_breakpoints' , 'value'=> array('lg') ),
					),

					array (
						"name"        => __( "Digits Typography Settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the digits.", 'zn_framework' ),
						"id"          => "typo_md",
						"std"         => array(),
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'font_breakpoints' , 'value'=> array('md') ),
					),

					array (
						"name"        => __( "Digits Typography Settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the digits.", 'zn_framework' ),
						"id"          => "typo_sm",
						"std"         => array(),
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'font_breakpoints' , 'value'=> array('sm') ),
					),

					array (
						"name"        => __( "Digits Typography Settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the digits.", 'zn_framework' ),
						"id"          => "typo_xs",
						"std"         => array(),
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array( 'element' => 'font_breakpoints' , 'value'=> array('xs') ),
					),

					array (
						"name"        => __( "Display Unit?", 'zn_framework' ),
						"description" => __( "Display the unit under the digits?", 'zn_framework' ),
						"id"          => "display_unit",
						"std"         => "yes",
						'type'        => 'zn_radio',
						'options'        => array(
							'yes' => __( "Yes", 'zn_framework' ),
							'no' => __( "No", 'zn_framework' ),
						),
						'class'        => 'zn_radio--yesno',
					),


					array (
						// "name"        => __( "Title Typography settings", 'zn_framework' ),
						// "description" => __( "Adjust the typography of the title as you want on any breakpoint", 'zn_framework' ),
						"id"          => "unit_font_breakpoints",
						"std"         => "lg",
						"tabs"        => true,
						"type"        => "zn_radio",
						"options"     => array (
							"lg"        => __( "LARGE", 'zn_framework' ),
							"md"        => __( "MEDIUM", 'zn_framework' ),
							"sm"        => __( "SMALL", 'zn_framework' ),
							"xs"        => __( "EXTRA SMALL", 'zn_framework' ),
						),
						'dependency' => array( 'element' => 'display_unit' , 'value'=> array('yes') ),
						"class"       => "zn_full zn_breakpoints"
					),

					array (
						"name"        => __( "Unit Typography Settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the units.", 'zn_framework' ),
						"id"          => "unit_typo",
						"std"         => '',
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight', 'spacing', 'case', 'shadow' ),
						"type"        => "font",
						"dependency"  => array(
							array( 'element' => 'unit_font_breakpoints' , 'value'=> array('lg') ),
							array( 'element' => 'display_unit' , 'value'=> array('yes') ),
						),
					),

					array (
						"name"        => __( "Unit Typography Settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the units.", 'zn_framework' ),
						"id"          => "unit_typo_md",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array(
							array( 'element' => 'unit_font_breakpoints' , 'value'=> array('md') ),
							array( 'element' => 'display_unit' , 'value'=> array('yes') ),
						),
					),
					array (
						"name"        => __( "Unit Typography Settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the units.", 'zn_framework' ),
						"id"          => "unit_typo_sm",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array(
							array( 'element' => 'unit_font_breakpoints' , 'value'=> array('sm') ),
							array( 'element' => 'display_unit' , 'value'=> array('yes') ),
						),
					),
					array (
						"name"        => __( "Unit Typography Settings", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the units.", 'zn_framework' ),
						"id"          => "unit_typo_xs",
						"std"         => '',
						'supports'   => array( 'size', 'line', 'spacing' ),
						"type"        => "font",
						"dependency"  => array(
							array( 'element' => 'unit_font_breakpoints' , 'value'=> array('xs') ),
							array( 'element' => 'display_unit' , 'value'=> array('yes') ),
						),
					),

					// Background options

					array(
						'id'          => 'background_color',
						'name'        => 'Box - Background color',
						'description' => 'Here you can specify the background color for the boxes.',
						'type'        => 'colorpicker',
						'alpha'        => true,
						'std'         => '',
					),

					array(
						'id'          => 'corner_radius',
						'name'        => 'Boxes - Corner Radius',
						'description' => 'Select a corner radius (in pixels) for the boxes.',
						'type'        => 'slider',
						'std'		  => '2',
						'helpers'	  => array(
							'min' => '0',
							'max' => '100',
							'step' => '1'
						)
					),

					array(
						"name"        => __( "Boxes Custom Width", 'zn_framework' ),
						"description" => __( "Choose the desired width for the boxes.", 'zn_framework' ),
						"id"          => "width",
						'type'        => 'smart_slider',
						'std'        => '70',
						'helpers'     => array(
							'min' => '0',
							'max' => '300'
						),
						'supports' => array('breakpoints'),
					),

					/**
					 * Margins and padding
					 */
					array (
						"name"        => __( "Edit the boxes padding & margins for each device breakpoint. ", 'zn_framework' ),
						"description" => __( "This will enable you to have more control over the padding of the container on each device. Click to see <a href='http://hogash.d.pr/1f0nW' target='_blank'>how box-model works</a>.", 'zn_framework' ),
						"id"          => "cc_spacing_breakpoints",
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
						'id'          => 'cc_margin_lg',
						'name'        => 'Margin (Large Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container. Accepts negative margin.',
						'type'        => 'boxmodel',
						'std'	  => $margin_std_lg,
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('lg') ),
						// Markup is reloading so live is purposely disabled
						// 'live' => array(
						// 	'type'		=> 'boxmodel',
						// 	'css_class' => '.'.$uid.' .kl-counter-li',
						// 	'css_rule'	=> 'margin',
						// ),
					),
					array(
						'id'          => 'cc_margin_md',
						'name'        => 'Margin (Medium Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'cc_margin_sm',
						'name'        => 'Margin (Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'cc_margin_xs',
						'name'        => 'Margin (Extra Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('xs') ),
					),
					// PADDINGS
					array(
						'id'          => 'cc_padding_lg',
						'name'        => 'Padding (Large Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => array(
							'top' => '15px',
							'bottom' => '15px',
						),
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('lg') ),
						// Markup is reloading so live is purposely disabled
						// 'live' => array(
						// 	'type'		=> 'boxmodel',
						// 	'css_class' => '.'.$uid.' .kl-counter-li',
						// 	'css_rule'	=> 'padding',
						// ),
					),
					array(
						'id'          => 'cc_padding_md',
						'name'        => 'Padding (Medium Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'cc_padding_sm',
						'name'        => 'Padding (Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'cc_padding_xs',
						'name'        => 'Padding (Extra Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('xs') ),
					),

				),
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
