<?php if(! defined('ABSPATH')){ return; }

class ZNB_Column extends ZionElement
{


	function offsets($brp = 'md'){
		return array(
			'' => __('No offset','zn_framework'),
			'col-'.$brp.'-offset-0' => __('0 - Reset Offset','zn_framework'),
			'col-'.$brp.'-offset-1' => __('1 Column Offset','zn_framework'),
			'col-'.$brp.'-offset-2' => __('2 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-3' => __('3 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-4' => __('4 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-5' => __('5 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-6' => __('6 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-7' => __('7 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-8' => __('8 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-9' => __('9 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-10' => __('10 Columns Offset','zn_framework'),
			'col-'.$brp.'-offset-11' => __('11 Columns Offset','zn_framework'),
		);
	}

	function cols($brp = 'sm'){
		return array(
			'' => __('Default','zn_framework'),
			'col-'.$brp.'-1' => '1 / 12',
			'col-'.$brp.'-2' => '2 / 12',
			'col-'.$brp.'-3' => '3 / 12',
			'col-'.$brp.'-4' => '4 / 12',
			'col-'.$brp.'-5' => '5 / 12',
			'col-'.$brp.'-6' => '6 / 12',
			'col-'.$brp.'-7' => '7 / 12',
			'col-'.$brp.'-8' => '8 / 12',
			'col-'.$brp.'-9' => '9 / 12',
			'col-'.$brp.'-10' => '10 / 12',
			'col-'.$brp.'-11' => '11 / 12',
			'col-'.$brp.'-12' => '12 / 12',
			'col-'.$brp.'-1-5' => '1 / 5',
		);
	}

	function options() {

		$uid = $this->data['uid'];
		$colorzilla_url = 'http://www.colorzilla.com/gradient-editor/';
		$helper_video = 'http://hogash.d.pr/8Dze';

		// Inherit large and medium from small
		$_lg_offset_inheritance = !$this->opt('column_offset_lg', '') && $this->opt('column_offset', '') != '' ? str_replace('sm', 'lg', $this->opt('column_offset', '') ) : '';
		$_md_offset_inheritance = !$this->opt('column_offset_md', '') && $this->opt('column_offset', '') != '' ? str_replace('sm', 'md', $this->opt('column_offset', '') ) : '';

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'GENERAL',
				'options' => array(

					array (
						"name"        => __( "Edit Settings for each device breakpoint", 'zn_framework' ),
						"description" => __( "This will enable you to have more control over the settings of this column on each device.", 'zn_framework' ),
						"id"          => "cc_breakpoints",
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

					// OFFSETS
					array(
						'id'          => 'column_offset_lg',
						'name'        => __('Column Offset - Desktops', 'zn_framework'),
						'description' => __('Here you can define an offset for this column ', 'zn_framework'),
						'type'        => 'select',
						'std'        => $_lg_offset_inheritance,
						'options'        => $this->offsets('lg'),
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.zn_pb_el_container[data-uid="'.$this->data['uid'].'"]'
						),
						"dependency"  => array( 'element' => 'cc_breakpoints' , 'value'=> array('lg') ),
					),

					array(
						'id'          => 'column_offset_md',
						'name'        => __('Column Offset - Laptops / large tablets', 'zn_framework'),
						'description' => __('Here you can define an offset for this column', 'zn_framework'),
						'type'        => 'select',
						'std'        => $_md_offset_inheritance,
						'options'        => $this->offsets('md'),
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.zn_pb_el_container[data-uid="'.$this->data['uid'].'"]'
						),
						"dependency"  => array( 'element' => 'cc_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'column_offset',
						'name'        => __('Column offset - Tablets', 'zn_framework'),
						'description' => __('Here you can define an offset for this column', 'zn_framework'),
						'type'        => 'select',
						'std'        => '',
						'options'        => $this->offsets('sm'),
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.zn_pb_el_container[data-uid="'.$this->data['uid'].'"]'
						),
						"dependency"  => array( 'element' => 'cc_breakpoints' , 'value'=> array('sm') ),
					),

					array(
						'id'          => 'column_offset_xs',
						'name'        => __('Column Offset - SmartPhones', 'zn_framework'),
						'description' => __('Here you can define an offset for this column. Usually not used at all.', 'zn_framework'),
						'type'        => 'select',
						'std'         => '',
						'options'     => $this->offsets('xs'),
						"dependency"  => array( 'element' => 'cc_breakpoints' , 'value'=> array('xs') ),
					),


					array(
						'id'          => 'size_large',
						'name'        => __('Column Size on Desktops', 'zn_framework'),
						'description' => __('In View Mode only! <br> Select a size for this column on large devices, for example Desktops with a resolution bigger than 1200px.', 'zn_framework'),
						'type'        => 'select',
						'std'        => '',
						'options'        => $this->cols('lg'),
						"dependency"  => array( 'element' => 'cc_breakpoints' , 'value'=> array('lg') ),
					),

					array(
						'id'          => 'size_small',
						'name'        => __('Column Size on Tablets', 'zn_framework'),
						'description' => __('Select a size for this column on small devices( >= 768px )', 'zn_framework'),
						'type'        => 'select',
						'options'        => $this->cols('sm'),
						"dependency"  => array( 'element' => 'cc_breakpoints' , 'value'=> array('sm') ),
					),

					array(
						'id'          => 'size_xsmall',
						'name'        => __('Columns Size on Smartphones', 'zn_framework'),
						'description' => __('Select a size for this column on extra small devices( <768px )', 'zn_framework'),
						'type'        => 'select',
						'options'        => $this->cols('xs'),
						"dependency"  => array( 'element' => 'cc_breakpoints' , 'value'=> array('xs') ),
					),
				),
			),

			'spacing' => array(
				'title' => 'SPACING',
				'options' => array(

					array(
						'id'          => 'custom_height',
						'name'        => __( 'Inner Height', 'zn_framework'),
						'description' => __( 'Choose the desired height for this column. If you want to reset the height, simply leave the input blank.', 'zn_framework' ),
						'type'        => 'smart_slider',
						'std'        => '',
						'helpers'     => array(
							'min' => '0',
							'max' => '1400'
						),
						'supports' => array('breakpoints'),
						'units' => array('px', 'vh'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.znColumnElement-iW-'.$uid,
							'css_rule'  => 'min-height',
							'unit'      => 'px'
						)
					),

					array(
						'id'          => 'custom_width',
						'name'        => __( 'Inner Width', 'zn_framework'),
						'description' => __( "Choose the desired INNER width for this column. If you want to change the column's size, go to the 1st tab - general options.", 'zn_framework' ),
						'type'        => 'smart_slider',
						'std'        => '100',
						'helpers'     => array(
							'min' => '0',
							'max' => '100'
						),
						'supports' => array('breakpoints'),
						'units' => array('%'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.znColumnElement-iW-'.$uid . ' > .znColumnElement-innerContent:not(.zn_pb_no_content)',
							'css_rule'  => 'width',
							'unit'      => '%'
						)
					),

					array(
						'id'          => 'valign',
						'name'        => __( 'Vertical Align - Inner Content', 'zn_framework'),
						'description' => __( 'Choose how to vertically align content.', 'zn_framework' ),
						'type'        => 'select',
						'std'        => 'top',
						'options'     => array(
							'top' => 'Top',
							'center' => 'Middle',
							'bottom' => 'Bottom',
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.znColumnElement-iW-'.$uid,
							'val_prepend'  => 'znColumnElement-innerWrapper--valign-',
						),
					),

					array(
						'id'          => 'halign',
						'name'        => __( 'Horizontal Align - Inner Content', 'zn_framework'),
						'description' => __( 'Choose how to horizontally align content.', 'zn_framework' ),
						'type'        => 'select',
						'std'        => 'left',
						'options'     => array(
							'left' => __('Left','zn_framework'),
							'center' => __('Center','zn_framework'),
							'right' => __('Right','zn_framework'),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.znColumnElement-iW-'.$uid,
							'val_prepend'  => 'znColumnElement-innerWrapper--halign-',
						),
					),

					array(
						'id'          => 'zindex',
						'name'        => 'Z-Index',
						'description' => __('Select the z-index of this column element.','zn_framework'),
						'type'        => 'slider',
						'std'         => '',
						'helpers'      => array(
							'step' => 1,
							'min' => -10,
							'max' => 100,
						),
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.'',
									'css_rule'  => 'z-index',
									'unit'      => ''
								),
								array(
									'type'      => 'css',
									'css_class' => '.zn_element_zncolumn[data-uid="'.$uid.'"]',
									'css_rule'  => 'z-index',
									'unit'      => ''
								),
							)
						)
					),

					/**
					 * Margins and padding
					 */
					array (
						"name"        => __( "Edit padding & margins for each device breakpoint", 'zn_framework' ),
						"description" => sprintf(__( "This will enable you to have more control over the padding of the container on each device. Click to see <a href='%s' target='_blank'>how box-model works</a>.", 'zn_framework' ), 'http://hogash.d.pr/1f0nW'),
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
						'std'         => '',
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.znColumnElement-iW-'.$uid,
							'css_rule'	=> 'margin',
						),
					),
					array(
						'id'          => 'margin_md',
						'name'        => __('Margin (Medium Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						'std'         => '',
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'margin_sm',
						'name'        => __('Margin (Small Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						'std'         => '',
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'margin_xs',
						'name'        => __('Margin (Extra Small Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						'std'         => '',
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
					),
					// PADDINGS
					array(
						'id'             => 'padding_lg',
						'name'           => __('Padding (Large Breakpoints)','zn_framework'),
						'description'    => __('Select the padding (in percent % or px) for this container.','zn_framework'),
						'type'           => 'boxmodel',
						"allow-negative" => false,
						'std'            => '',
						'placeholder'    => '0px',
						"dependency"     => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.znColumnElement-iW-'.$uid,
							'css_rule'	=> 'padding',
						),
					),
					array(
						'id'             => 'padding_md',
						'name'           => __('Padding (Medium Breakpoints)','zn_framework'),
						'description'    => __('Select the padding (in percent % or px) for this container.','zn_framework'),
						'type'           => 'boxmodel',
						"allow-negative" => false,
						'std'            => '',
						'placeholder'    => '0px',
						"dependency"     => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'             => 'padding_sm',
						'name'           => __('Padding (Small Breakpoints)','zn_framework'),
						'description'    => __('Select the padding (in percent % or px) for this container.','zn_framework'),
						'type'           => 'boxmodel',
						"allow-negative" => false,
						'std'            => '',
						'placeholder'    => '0px',
						"dependency"     => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'             => 'padding_xs',
						'name'           => __('Padding (Extra Small Breakpoints)','zn_framework'),
						'description'    => __('Select the padding (in percent % or px) for this container.','zn_framework'),
						'type'           => 'boxmodel',
						"allow-negative" => false,
						'std'            => '',
						'placeholder'    => '0px',
						"dependency"     => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
					),


				),
			),

			'style' => array(
				'title' => 'STYLE',
				'options' => array(

					array(
						'id'          => 'title1',
						'name'        => __('Background & Color Options', 'zn_framework'),
						'description' => __('These are options to customize the background and colors for this section.', 'zn_framework'),
						'type'        => 'zn_title',
						'class'       => 'zn_full zn-custom-title-large',
					),

					array(
						'id'          => 'background_color',
						'name'        => __('Inner Background color','zn_framework'),
						'description' => __('Here you can override the background color for this section.','zn_framework'),
						'type'        => 'colorpicker',
						'alpha'        => true,
						'std'         => '',
						'live'        => array(
							'type'		=> 'css',
							'css_class' => '.znColumnElement-iW-'.$uid,
							'css_rule'	=> 'background-color',
							'unit'		=> ''
						)
					),

					// Background image/video or youtube
					array (
						"name"        => __( "Background Media", 'zn_framework' ),
						"description" => __( "Please select the source type of the background.", 'zn_framework' ),
						"id"          => "source_type",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							''              => __( "None", 'zn_framework' ),
							'image'         => __( "Image", 'zn_framework' ),
							'video_youtube' => __( "Youtube Video", 'zn_framework' ),
							'video_vimeo'   => __( "Vimeo Video", 'zn_framework' ),
							'video_self'    => __( "Self Hosted Video", 'zn_framework' ),
							'embed_iframe'  => __( "Embed Iframe (Vimeo etc.)", 'zn_framework' )
						)
					),

					array(
						'id'          => 'background_image',
						'name'        => __('Background Image', 'zn_framework'),
						'description' => __('Please choose a background image for this section.', 'zn_framework'),
						'type'        => 'background',
						'options'     => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
						// 'class'    => 'zn_full',
						'dependency'  => array( 'element' => 'source_type' , 'value'=> array('image') )
					),

					// Youtube video
					array (
						"name"        => __( "Youtube URL", 'zn_framework' ),
						"description" => __( "Add an Youtube URL", 'zn_framework' ),
						"id"          => "source_vd_yt",
						"std"         => "",
						"type"        => "text",
						"class"		=> "zn_input_xl",
						"placeholder" => "eg: https://www.youtube.com/watch?v=rKH4XjqZQiY",
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_youtube') )
					),
					// Vimeo video
					array (
						"name"        => __( "Vimeo URL", 'zn_framework' ),
						"description" => __( "Add an Vimeo URL", 'zn_framework' ),
						"id"          => "source_vd_vm",
						"std"         => "",
						"type"        => "text",
						"class"       => "zn_input_xl",
						"placeholder" => "ex: https://vimeo.com/2353562345",
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_vimeo') )
					),

					// Embed Iframe
					array (
						"name"        => __( "Embed Video Iframe (URL)", 'zn_framework' ),
						"description" => __( "Add the full URL for Youtube, Vimeo or DailyMotion. Please remember these videos will not be autoplayed on mobile devices.", 'zn_framework' ),
						"id"          => "source_vd_embed_iframe",
						"std"         => "",
						"type"        => "text",
						"placeholder" => "ex: https://vimeo.com/17874452",
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('embed_iframe') )
					),


					/* LOCAL VIDEO */
					array(
						'id'          => 'source_vd_self_mp4',
						'name'        => __('Mp4 video source', 'zn_framework'),
						'description' => __('Add the MP4 video source for your local video', 'zn_framework'),
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type'         => 'video/mp4',
							'button_title' => __('Add / Change mp4 video', 'zn_framework'),
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_self_ogg',
						'name'        => __('Ogg/Ogv video source', 'zn_framework'),
						'description' => __('Add the OGG video source for your local video', 'zn_framework'),
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type'         => 'video/ogg',
							'button_title' => __('Add / Change ogg video', 'zn_framework'),
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_self_webm',
						'name'        => __('Webm video source', 'zn_framework'),
						'description' => __('Add the WEBM video source for your local video', 'zn_framework'),
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type'         => 'video/webm',
							'button_title' => __('Add / Change webm video', 'zn_framework'),
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_vp',
						'name'        => __('Video poster / Fallback Image', 'zn_framework'),
						'description' => __('Using this option you can add your desired video poster that will be shown on unsuported devices (mobiles, tablets). ', 'zn_framework'),
						'type'        => 'media',
						'std'         => '',
						// 'class'       => 'zn_full',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube', 'video_vimeo', 'embed_iframe') )
					),

					array(
						'id'          => 'mobile_play',
						'name'        => __('Display Play button on Mobiles?', 'zn_framework'),
						'description' => __("By default videos are not displayed in the background on mobile devices. It's too problematic and instead, we added a button trigger which will open the video into a modal.", 'zn_framework'),
						'type'        => 'zn_radio',
						'std'         => 'no',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_youtube', 'video_vimeo', 'embed_iframe') ),
						"options"     => array (
							"yes" => __( "Yes", 'zn_framework' ),
							"no"  => __( "No", 'zn_framework' )
						),
						"class"       => "zn_radio--yesno"
					),

					array(
						'id'          => 'source_vd_autoplay',
						'name'        => __('Autoplay video?', 'zn_framework'),
						'description' => __('Enable autoplay for video? Remember, this option only applies on desktop devices, not mobiles or tablets.', 'zn_framework'),
						'type'        => 'select',
						'std'         => 'yes',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube', 'video_vimeo', 'embed_iframe') ),
						"options"     => array (
							"yes" => __( "Yes", 'zn_framework' ),
							"no"  => __( "No", 'zn_framework' )
						),
						"class"       => "zn_input_xs"
					),
					array(
						'id'          => 'source_vd_loop',
						'name'        => __('Loop video?', 'zn_framework'),
						'description' => __('Enable looping the video? Remember, this option only applies on desktop devices, not mobiles or tablets.', 'zn_framework'),
						'type'        => 'select',
						'std'         => 'yes',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube', 'video_vimeo', 'embed_iframe') ),
						"options"     => array (
							"yes" => __( "Yes", 'zn_framework' ),
							"no"  => __( "No", 'zn_framework' )
						),
						"class"       => "zn_input_xs"
					),
					array(
						'id'          => 'source_vd_muted',
						'name'        => __('Start mute?', 'zn_framework'),
						'description' => __('Start the video with muted audio?', 'zn_framework'),
						'type'        => 'select',
						'std'         => 'yes',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self', 'video_vimeo', 'video_youtube') ),
						"options"     => array (
							"yes" => __( "Yes", 'zn_framework' ),
							"no"  => __( "No", 'zn_framework' )
						),
						"class"       => "zn_input_xs"
					),

					array(
						'id'          => 'source_vd_overlay',
						'name'        => __('Video Overlay?', 'zn_framework'),
						'description' => __('Choose a video Overlay', 'zn_framework'),
						'type'        => 'select',
						'std'         => '1',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self', 'video_vimeo', 'video_youtube') ),
						"options"     => array (
							"0" => __( "Disabled", 'zn_framework' ),
							"1"  => __( "Diagonal Stripes", 'zn_framework' ),
							"2"  => __( "Dotted", 'zn_framework' ),
							"3"  => __( "Subtle Gradient", 'zn_framework' ),
						),
						"class"       => "zn_input_sm"
					),

					array(
						'id'          => 'source_overlay',
						'name'        => __('Background Overlay', 'zn_framework'),
						'description' => __('You can overlay the default background (color or media). Useful when you want to darken or lighten the background.', 'zn_framework'),
						'type'        => 'select',
						'std'         => '0',
						"options"     => array (
							"0" => __( "Disabled", 'zn_framework' ),
							"1" => __( "Normal color", 'zn_framework' ),
							"2" => __( "Horizontal gradient", 'zn_framework' ),
							"3" => __( "Vertical gradient", 'zn_framework' ),
							"4" => __( "Custom CSS generated gradient", 'zn_framework' ),
						)
					),

					array(
						'id'          => 'source_overlay_color',
						'name'        => __('Overlay Background Color', 'zn_framework'),
						'description' => __('Pick a color', 'zn_framework'),
						'type'        => 'colorpicker',
						'std'         => 'rgba(0,0,0,0.4)',
						'alpha'       => true,
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('1', '2', '3') ),
					),

					array(
						'id'          => 'source_overlay_color_gradient',
						'name'        => __('Overlay Gradient 2nd Bg. Color', 'zn_framework'),
						'description' => __('Pick a color', 'zn_framework'),
						'type'        => 'colorpicker',
						'std'         => 'rgba(0,0,0,0.1)',
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('2', '3') ),
					),

					array(
						'id'          => 'source_overlay_custom_css',
						'name'        => __('Custom CSS Gradient Overlay', 'zn_framework'),
						'description' => sprintf( __( 'You can use a tool such as <a href="%s" target="_blank">%s</a> to generate a unique custom gradient. Here is a quick video explainer <a href="%s" target="_blank">%s</a> how to generate and paste the code here', 'zn_framework' ), $colorzilla_url, $colorzilla_url, $helper_video, $helper_video ),
						'type'        => 'textarea',
						'std'         => '',
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('4') ),
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
						// 'live' => array(
						// 	'type'		=> 'css',
						// 	'css_class' => '.'.$uid,
						// 	'css_rule'	=> 'border-style',
						// 	'unit'		=> ''
						// ),
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
						// 'live' => array(
						// 	'type'      => 'css',
						// 	'css_class' => '.'.$uid,
						// 	'css_rule'  => 'border-color',
						// 	'unit'      => ''
						// ),
						"dependency"  => array( 'element' => 'border_style' , 'value'=> array('solid', 'dotted', 'dashed', 'double') ),
					),

					array(
						'id'          => 'corner_radius',
						'name'        => __('Corner radius','zn_framework'),
						'description' => __('Select a corner radius (in pixels) for this column.','zn_framework'),
						'type'        => 'slider',
						'std'		  => '0',
						'helpers'	  => array(
							'min' => '0',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'		=> 'css',
							'css_class' => '.znColumnElement-iW-'.$uid,
							'css_rule'	=> 'border-radius',
							'unit'		=> 'px'
						),
					),


				),
			),

			'advanced' => array(
				'title' => 'ADVANCED',
				'options' => array(

					array (
						"name"        => __( "Enable Object Scrolling", 'zn_framework' ),
						"description" => __( "This will add a very nice slide up or down effect to this element, upon scrolling.", 'zn_framework' ),
						"id"          => "obj_parallax_enable",
						"std"         => "",
						"type"        => "toggle2",
						"value"        => "yes",
					),

					array (
						"name"        => __( "Distance", 'zn_framework' ),
						"description" => __( "Select the Y axis distance to run the effect. The effect will run on the entire screen, from entering the viewport until leaving it.", 'zn_framework' ),
						"id"          => "obj_parallax_distance",
						"std"         => '1',
						"type"        => "slider",
						"helpers"     => array (
							"step" => "1",
							"min" => "1",
							"max" => "10"
						),
						"dependency"  => array( 'element' => 'obj_parallax_enable' , 'value'=> array('yes') ),
					),

					array (
						"name"        => __( "Easing", 'zn_framework' ),
						"description" => sprintf( __( "Select the effect's easing. You can play with the easing effects <a href='%s' target='_blank'>here</a>.", 'zn_framework' ), 'http://greensock.com/ease-visualizer' ),
						"id"          => "obj_parallax_easing",
						"std"         => "linear",
						"type"        => "select",
						"options"     => array(
							"none"     => __("No Easing",'zn_framework'),
							"linear" => __("Ease Out Linear",'zn_framework'),
							"quad"   => __("Ease Out Quad",'zn_framework'),
							"cubic"  => __("Ease Out Cubic",'zn_framework'),
							"quart"  => __("Ease Out Quart",'zn_framework'),
							"quint"  => __("Ease Out Quint",'zn_framework'),
						),
						"dependency"  => array( 'element' => 'obj_parallax_enable' , 'value'=> array('yes') ),
					),

					array (
						"name"        => __( "Tween in reverse?", 'zn_framework' ),
						"description" => __( "This will make the tween effect to run in opposite direction of the scroll.", 'zn_framework' ),
						"id"          => "obj_parallax_reverse",
						"std"         => "",
						"type"        => "toggle2",
						"value"        => "yes",
						"dependency"  => array( 'element' => 'obj_parallax_enable' , 'value'=> array('yes') ),
					),

					array(
						'id'            => 'enable_ov_hidden',
						'name'          => __('Overflow Hidden', 'zn_framework'),
						'description'   => __('Select if you want to set overflow hidden for this section', 'zn_framework'),
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => 'yes'
					),


				),
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#hBPFBT437_M',
				// 'docs'    => 'https://my.hogash.com/documentation/column/',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);

		return $options;

	}

	function element() {

		$options = $this->data['options'];
		$uid = $this->data['uid'];
		$classes = $attributes = array();

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'znColumnElement';

		if( ! ZNB()->utility->isActiveEditor() ){
			$column_offset = $this->opt('column_offset', '');
			$classes[] = !$this->opt('column_offset_lg', '') && $column_offset != '' ? str_replace('sm', 'lg', $column_offset ) : $this->opt('column_offset_lg', '');
			$classes[] = !$this->opt('column_offset_md', '') && $column_offset != '' ? str_replace('sm', 'md', $column_offset ) : $this->opt('column_offset_md', '');
			$classes[] = $column_offset;
			$classes[] = $this->opt('column_offset_xs', '');


			$classes[] = $width = ( $this->data['width'] ) ? $this->data['width'] : 'col-md-12';
			$classes[] = $this->opt('size_small', str_replace("md","sm",$width));
			$classes[] = $this->opt('size_xsmall','');
			$classes[] = $this->opt('size_large','');
		}



		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		echo '<div class="'. zn_join_spaces($classes) .'" '. zn_join_spaces($attributes) .' >';

				// Inner Wrapper attributes
				$innerWrapper_class = $innerWrapper_attributes = array();

				if( $this->opt('obj_parallax_enable','') == 'yes' ){
					// Classes
					$innerWrapper_class[] = 'js-doObjParallax zn-objParallax';
					$innerWrapper_class[] = 'zn-objParallax--ease-'.$this->opt('obj_parallax_easing', 'linear');
					// Attributes
					$dir = $this->opt('obj_parallax_reverse', '') == 'yes' ? '' : '-';
					$innerWrapper_attributes[] = 'data-rellax-speed="' . $dir . $this->opt('obj_parallax_distance', '1' ) .'"';
					$innerWrapper_attributes[] = 'data-rellax-percentage="0.5"';
				}

				// Classes
				$innerWrapper_class[] = 'znColumnElement-innerWrapper';
				$innerWrapper_class[] = 'znColumnElement-iW-'.$uid;
				$innerWrapper_class[] = 'znColumnElement-innerWrapper--valign-' . $this->opt('valign','top');
				$innerWrapper_class[] = 'znColumnElement-innerWrapper--halign-' . $this->opt('halign','left');
				$innerWrapper_class[] = $this->opt('enable_ov_hidden') === 'yes' ? 'u-ov-hidden' : '';
				$innerWrapper_attributes[] = 'class="'. zn_join_spaces($innerWrapper_class) .'"';


				echo '<div '. zn_join_spaces($innerWrapper_attributes) .' >';


					if( $this->opt('source_type', '') != '' ){
						znb_background_source( array(
							'uid'                           => $uid,
							'source_type'                   => $this->opt('source_type'),
							'source_background_image'       => $this->opt('background_image'),
							'source_vd_yt'                  => $this->opt('source_vd_yt'),
							'source_vd_vm'                  => $this->opt('source_vd_vm'),
							'source_vd_embed_iframe'        => $this->opt('source_vd_embed_iframe'),
							'source_vd_self_mp4'            => $this->opt('source_vd_self_mp4'),
							'source_vd_self_ogg'            => $this->opt('source_vd_self_ogg'),
							'source_vd_self_webm'           => $this->opt('source_vd_self_webm'),
							'source_vd_vp'                  => $this->opt('source_vd_vp'),
							'source_vd_autoplay'            => $this->opt('source_vd_autoplay'),
							'source_vd_loop'                => $this->opt('source_vd_loop'),
							'source_vd_muted'               => $this->opt('source_vd_muted'),
							'source_overlay'                => $this->opt('source_vd_overlay'),
							'source_overlay'                => $this->opt('source_overlay'),
							'source_overlay_color'          => $this->opt('source_overlay_color'),
							'source_overlay_color_gradient' => $this->opt('source_overlay_color_gradient'),
							'source_overlay_gloss'          => $this->opt('source_overlay_gloss',''),
							'enable_parallax'               => $this->opt('enable_parallax'),
							'source_overlay_custom_css'     => $this->opt('source_overlay_custom_css',''),
							'mobile_play'                   => $this->opt('mobile_play', 'no'),
						) );
					}

					?>

				<?php echo znb_get_column_container(array(
					'cssClasses' => 'znColumnElement-innerContent'
				)); ?>
					<?php ZNB()->frontend->renderContent( $this->data['content'] ); ?>
				</div>
			</div>
		</div>
	<?php
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		$uid = $this->data['uid'];
		$css = '';

		$innerwrapper = '';

		$border_style = $this->opt('border_style','');

		if( $border_style != '' ){

			$borders['lg'] = $this->opt('border_width', '' );
			if( !empty($borders) ){
				$borders['selector'] = '.znColumnElement-iW-'.$uid;
				$borders['type'] = 'border-width';
				$css .= zn_push_boxmodel_styles( $borders );
			}
			$innerwrapper .= 'border-style:'.$border_style.';';
			// Button Border Color
			if( $border_color = $this->opt('border_custom_color','') ){
				$innerwrapper .= 'border-color:'. $border_color .';';
			}
		}

		//** Set the corner radius
		$corner_radius = $this->opt('corner_radius','');
		if (!empty($corner_radius))
		{
			$innerwrapper .=  " border-radius:{$corner_radius}px;";
		}

		// Inner Wrapper Styles
		$innerwrapper .= $this->opt('background_color', '') ? ' background-color:'.$this->opt('background_color', '').';':'';

		if(!empty($innerwrapper)){
			$css .= '.znColumnElement-iW-'.$uid. '{'.$innerwrapper.'}';
		}

		// Height
		$css .= zn_smart_slider_css( $this->opt( 'custom_height', '' ), '.znColumnElement-iW-'.$uid , 'min-height' );

		// Width
		$inner_width = $this->opt( 'custom_width' );
		if(!empty($inner_width) && isset($inner_width['lg']) && $inner_width['lg'] != '100'){
			$css .= zn_smart_slider_css( $inner_width, '.znColumnElement-iW-'.$uid .' > .znColumnElement-innerContent:not(.zn_pb_no_content)' , 'width' );
		}

		// Margin
		$margins = array();
		$margins['lg'] = $this->opt('margin_lg', '' );
		$margins['md'] = $this->opt('margin_md', '' );
		$margins['sm'] = $this->opt('margin_sm', '' );
		$margins['xs'] = $this->opt('margin_xs', '' );
		if( !empty($margins) ){
			$margins['selector'] = '.znColumnElement-iW-'.$uid;
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
			$paddings['selector'] = '.znColumnElement-iW-'.$uid;
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}


		$zindex = $this->opt('zindex', '');

		if(!empty($zindex)){
			$css .= '.'.$uid. '{z-index:'.$zindex.'}';

			// Fix for PB mode
			if( ZNB()->utility->isActiveEditor() ){
				$css .= '.zn_element_zncolumn[data-uid="'.$uid. '"]{z-index:'.$zindex.'}';
			}
		}

		return $css;
	}

}

ZNB()->elements_manager->registerElement( new ZNB_Column(array(
	'id' => 'ZnColumn',
	'name' => __('Column', 'zn_framework'),
	'description' => __('This element will generate a column in which you can add elements', 'zn_framework'),
	'level' => 2,
	'category' => 'Layout',
	'flexible' => true
)));
