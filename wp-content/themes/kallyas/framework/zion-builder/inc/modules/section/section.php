<?php if(! defined('ABSPATH')){ return; }

class ZNB_Section extends ZionElement {

	function options() {

		$uid = $this->data['uid'];
		$colorzilla_url = 'http://www.colorzilla.com/gradient-editor/';
		$helper_video = 'http://hogash.d.pr/8Dze';

		$defaultShadows = array(
			''  => __( 'No shadow', 'zn_framework' ),
			'1' => __( 'Shadow 1x', 'zn_framework' ),
			'2' => __( 'Shadow 2x', 'zn_framework' ),
			'3' => __( 'Shadow 3x', 'zn_framework' ),
			'4' => __( 'Shadow 4x', 'zn_framework' ),
			'5' => __( 'Shadow 5x', 'zn_framework' ),
			'6' => __( 'Shadow 6x', 'zn_framework' ),
		);
		$defaultShadows = apply_filters( 'hg_section_pb_shadow_styles', $defaultShadows );

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General options','zn_framework'),
				'options' => array(

					array (
						'id'          => 'size',
						'name'        => __('Width', 'zn_framework'),
						'description' => __('Select the desired size type for this section.', 'zn_framework'),
						'type'        => 'select',
						'std'         => 'default',
						'options'	  => array(
							'default'		=> __('Container Width', 'zn_framework'),
							'full'			=> __('Full Width', 'zn_framework'),
							'customPx'		=> __('Custom Width (px)', 'zn_framework'),
							'customPerc'	=> __('Custom Width Percentage (%)', 'zn_framework'),
						),
						'live' => array(
							'type'			=> 'class',
							'css_class'		=> '.'.$uid.' .zn-sectionContainer',
							'val_prepend'	=> 'zn-sectionContainer--',
							'tasks' => array(
								array(
									'condition_type' => 'remove',
									'css_class'      => '.'.$uid.' .zn-sectionContainer',
									'property'       => 'max-width',
									'options'        => array('default', 'full', ),
								),
							),
						)
					),

					array(
						'id'          => 'custom_width',
						'name'        => __( 'Custom Width', 'zn_framework'),
						'description' => __( 'Choose the desired width for the section\'s container.', 'zn_framework' ),
						'type'        => 'slider',
						'std'         => '1400',
						'helpers'     => array(
							'min' => '767',
							'max' => '1920'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' .zn-sectionContainer--customPx',
							'css_rule'  => 'max-width',
							'unit'      => 'px'
						),
						'dependency' => array( 'element' => 'size' , 'value'=> array('customPx') )
					),

					array(
						'id'          => 'custom_width_percent',
						'name'        => __( 'Custom Width ( in Percentage %)', 'zn_framework'),
						'description' => __( 'Choose the desired width for the section\'s container.', 'zn_framework' ),
						'type'        => 'slider',
						'std'         => '100',
						'helpers'     => array(
							'min' => '20',
							'max' => '100'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' .zn-sectionContainer--customPerc',
							'css_rule'  => 'max-width',
							'unit'      => '%'
						),
						'dependency' => array( 'element' => 'size' , 'value'=> array('customPerc') )
					),

					array(
						'id'            => 'side_padding',
						'name'          => __('Side Padding', 'zn_framework'),
						'description'   => __('Disable this option if you want to hide the container\'s side padding.', 'zn_framework'),
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => 'zn-sectionContainer--sidePad',
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.'.$uid.' .zn-sectionContainer',
						),
					),

					array (
						'id'          => 'section_height',
						'name'        => __('Height Type', 'zn_framework'),
						'description' => __('Select the desired height for this section.', 'zn_framework'),
						'type'        => 'zn_radio',
						'std'         => 'auto',
						'options'     => array(
							'auto' => __('Auto', 'zn_framework'),
							'custom' => __('Custom Height', 'zn_framework'),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.'.$uid,
							'val_prepend'  => 'zn-section--height-',
						)
					),

					array(
						'id'          => 'custom_height',
						'name'        => __( 'Custom Height', 'zn_framework'),
						'description' => __( 'Choose the desired height for this section. You can choose either height or min-height as a property. Height will force a fixed size rather than just a minimum. <br>*TIP: Use 100vh to have a full-height element.', 'zn_framework' ),
						'type'        => 'smart_slider',
						'std'         => '100',
						'helpers'     => array(
							'min' => '0',
							'max' => '1400'
						),
						'supports' => array('breakpoints'),
						'units' => array('px', 'vh'),
						'properties' => array('min-height','height'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. '.zn-section--height-custom',
							'css_rule'  => 'min-height',
							'unit'      => 'px'
						),
						'dependency' => array( 'element' => 'section_height' , 'value'=> array('custom') )
					),

					array(
						'id'          => 'valign',
						'name'        => __( 'Vertical Align', 'zn_framework'),
						'description' => __( 'Choose how to vertically align content.', 'zn_framework' ),
						'type'        => 'select',
						'std'         => 'top',
						'options'     => array(
							'top'    => __('Top', 'zn_framework'),
							'middle' => __('Middle', 'zn_framework'),
							'bottom' => __('Bottom', 'zn_framework'),
						),
						'live' => array(
							'type'        => 'class',
							'css_class'   => '.'.$uid,
							'val_prepend' => 'zn-section--contentValign-',
						),
						'dependency' => array( 'element' => 'section_height' , 'value'=> array('custom') )
					),

					array(
						'id'          => 'gutter_size',
						'name'        => __('Gutter Size (Gaps)', 'zn_framework'),
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
									'css_class' => '.'.$uid. '>.zn-sectionContainer>.row>[class*="col-"]',
									'css_rule'  => 'padding-left',
									'unit'      => 'px'
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid. '>.zn-sectionContainer>.row>[class*="col-"]',
									'css_rule'  => 'padding-right',
									'unit'      => 'px'
								),
							),
						)
					),

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
						'std'         => array('left'=> 'auto', 'right'=> 'auto' ),
						'disable'     => array('left', 'right'),
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
						'std'         => array('left'=> 'auto', 'right'=> 'auto' ),
						'disable'     => array('left', 'right'),
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'margin_sm',
						'name'        => __('Margin (Small Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						'std'         => array('left'=> 'auto', 'right'=> 'auto' ),
						'disable'     => array('left', 'right'),
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'margin_xs',
						'name'        => __('Margin (Extra Small Breakpoints)','zn_framework'),
						'description' => __('Select the margin (in percent % or px) for this container.','zn_framework'),
						'type'        => 'boxmodel',
						'std'         => array('left'=> 'auto', 'right'=> 'auto' ),
						'disable'     => array('left', 'right'),
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
							'css_class' => '.'.$uid,
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

			'background' => array(
				'title' => 'Styles Options',
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
						'name'        => __('Background Color', 'zn_framework'),
						'description' => __('Here you can override the background color for this section.', 'zn_framework'),
						'type'        => 'colorpicker',
						'std'         => '',
						'alpha'       => true,
						'live'        => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
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

					array (
						'id'          => 'enable_parallax',
						'name'        => __('Enable Scrolling Parallax effect', 'zn_framework'),
						'description' => __('Select if you want to enable parallax scrolling effect on background image.', 'zn_framework'),
						"std"         => "no",
						'type'        => 'zn_radio',
						'options'     => array(
							'yes' => __( "Yes", 'zn_framework' ),
							'no'  => __( "No", 'zn_framework' ),
						),
						'class'      => 'zn_radio--yesno',
						'dependency' => array( 'element' => 'source_type' , 'value'=> array('image') ),
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
						'description' => __('By default videos are not displayed in the background on mobile devices. It\'s too problematic and instead, we added a button trigger which will open the video into a modal.', 'zn_framework'),
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
						'description' => sprintf( __( 'You can use a tool such as <a href="%s" target="_blank">%s</a> to generate a unique custom gradient. Here\'s a quick video explainer <a href="%s" target="_blank">%s</a> how to generate and paste the code here', 'zn_framework' ), $colorzilla_url, $colorzilla_url, $helper_video, $helper_video ),
						'type'        => 'textarea',
						'std'         => '',
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('4') ),
					),

					array(
						'id'            => 'source_overlay_gloss',
						'name'          => __('Gloss Overlay', 'zn_framework'),
						'description'   => __('Display a gloss over the background', 'zn_framework'),
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => '1'
					),

					array(
						'id'    => 'title1',
						'name'  => __('Other Options', 'zn_framework'),
						'type'  => 'zn_title',
						'class' => 'zn_full zn-custom-title-large',
					),

					array (
						"name"        => __( "Shadow", 'zn_framework' ),
						"description" => __( "Please select a shadow style.", 'zn_framework' ),
						"id"          => "shadow",
						"std"         => "",
						"options"     => $defaultShadows,
						"type" => "select",
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.'.$uid,
							'val_prepend'	=> 'znBoxShadow-',
						),
					),

					array (
						"name"        => __( "Shadow Hover", 'zn_framework' ),
						"description" => __( "Please select a shadow style for hover state.", 'zn_framework' ),
						"id"          => "shadow_hover",
						"std"         => "",
						"options"     => array(
							''  => __( 'No shadow', 'zn_framework' ),
							'1' => __( 'Shadow 1x', 'zn_framework' ),
							'2' => __( 'Shadow 2x', 'zn_framework' ),
							'3' => __( 'Shadow 3x', 'zn_framework' ),
							'4' => __( 'Shadow 4x', 'zn_framework' ),
							'5' => __( 'Shadow 5x', 'zn_framework' ),
							'6' => __( 'Shadow 6x', 'zn_framework' ),
						),
						"type"        => "select",
					),

				),
			),

			'advanced' => array(
				'title' => 'Advanced',
				'options' => array(


					array(
						'id'            => 'enable_ov_hidden',
						'name'          => __('Overflow Hidden', 'zn_framework'),
						'description'   => __('Select if you want to set overflow hidden for this section', 'zn_framework'),
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => 'yes'
					),

					array (
						"name"        => __( "Z-Index Stack Order", 'zn_framework' ),
						"description" => __( "Please select a z-index order in layer.", 'zn_framework' ),
						"id"          => "zIndex",
						"std"         => "",
						"type"        => "text",
						"dragup" => array(
							'unit' => '',
						),
						"class"       => "zn_input_xs",
						'live' => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'z-index',
							'unit'		=> ''
						),
					),

					// Layers
					array(
						'id'    => 'title1',
						'name'  => __('Section Layers', 'zn_framework'),
						'type'  => 'zn_title',
						'class' => 'zn_full zn-custom-title-large',
					),

					array (
						"name"           => __( "Parallax Layers", 'zn_framework' ),
						"description"    => __( "Here you can add your desired layers.", 'zn_framework' ),
						"id"             => "layers",
						"std"            => "",
						"type"           => "group",
						"add_text"       => __( "Layer", 'zn_framework' ),
						"remove_text"    => __( "Layer", 'zn_framework' ),
						"group_sortable" => true,
						"element_title" => "title",
						"subelements"    => array (

							array (
								"name"        => __( "Layer Title", 'zn_framework' ),
								"description" => __( "Add a title for this layer. Optional, not visible.", 'zn_framework' ),
								"id"          => "title",
								"std"         => "",
								"type"        => "text"
							),

							array (
								"name"        => __( "Layer Image", 'zn_framework' ),
								"description" => __( "Choose an image.", 'zn_framework' ),
								"id"          => "img",
								"std"         => "",
								"type"        => "media"
							),

							array (
								"name"        => __( "Image Size", 'zn_framework' ),
								"description" => __( "Choose the image's size", 'zn_framework' ),
								"id"          => "image_size",
								"std"         => "thumbnail",
								'type'        => 'select',
								'options'        => zn_get_image_sizes_list(),
							),

							array (
								"name"        => __( "Start Position", 'zn_framework' ),
								"description" => __( "Choose the starting point on the X/Y axis of the section.", 'zn_framework' ),
								"id"          => "start",
								"std"         => "top-left",
								'type'        => 'select',
								'options'        => array(
									'top-left'      => __( "Top Left", 'zn_framework' ),
									'top-center'    => __( "Top Center", 'zn_framework' ),
									'top-right'     => __( "Top Right", 'zn_framework' ),
									'center-left'   => __( "Center Left", 'zn_framework' ),
									'center'        => __( "Center", 'zn_framework' ),
									'center-right'  => __( "Center Right", 'zn_framework' ),
									'bottom-left'   => __( "Bottom Left", 'zn_framework' ),
									'bottom-center' => __( "Bottom Center", 'zn_framework' ),
									'bottom-right'  => __( "Bottom Right", 'zn_framework' ),
								),
								'live' => array(
									'type'        => 'class',
									'css_class'   => '.'.$uid.' .zn-prLayer',
									'val_prepend' => 'zn-prLayer--align-',
								),
							),

							array(
								'name'        => __('Margins', 'zn_framework'),
								'description' => __('Position the layer using margins.', 'zn_framework'),
								'id'          => 'margin',
								'type'        => 'boxmodel',
								'std'         => '',
								'placeholder' => '0px',
								'live' => array(
									'type'		=> 'boxmodel',
									'css_class' => '.'.$uid.' .zn-prLayer',
									'css_rule'	=> 'margin',
								),
							),

							array (
								"name"        => __( "Blur Effect", 'zn_framework' ),
								"description" => __( "Select if you want to add a blurring effect. Works only in browsers supporting CSS Filters.", 'zn_framework' ),
								"id"          => "blur",
								"std"         => "",
								'type'        => 'select',
								'options'        => array(
									'none'   => __( "Disabled", 'zn_framework' ),
									'simple' => __( "Simple Blur", 'zn_framework' ),
									'deep'   => __( "Deep Blur", 'zn_framework' ),
									'deeper' => __( "Deeper Blur (3x)", 'zn_framework' ),
								),
							),

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

							array (
								"name"        => __( "Over Container", 'zn_framework' ),
								"description" => __( "Enable if you want to display this layer over the section's container.", 'zn_framework' ),
								"id"          => "zaxis",
								"std"         => "under",
								'type'        => 'select',
								'options'     => array(
									'over'  => __( "Over Container", 'zn_framework' ),
									'under' => __( "Behind Container", 'zn_framework' ),
								),
								'live' => array(
									'type'        => 'class',
									'css_class'   => '.'.$uid.' .zn-prLayer',
									'val_prepend' => 'zn-prLayer--',
								),
							),

							array (
								"name"        => __( "Hide on Breakpoints", 'zn_framework' ),
								"description" => __( "Choose to hide this layer on either desktop, mobile or tablets.", 'zn_framework' ),
								"id"          => "hide",
								"std"         => array('xs', 'sm'),
								"type"        => "checkbox",
								"supports"	  => array( 'zn_radio' ),
								"options"     => array (
									"lg" => '',
									"md"  => '',
									"sm"  => '',
									"xs"  => ''
								),
								'class' => 'zn_breakpoints_classic zn--minimal',
							)

						)
					),
				),
			),

			'help' => znpb_get_helptab( array(
				// 'video'  => 'https://my.hogash.com/video_category/',
				// 'docs'   => 'https://my.hogash.com/documentation/section-and-columns/',
				'copy'      => $uid,
				'general'   => true,
				'custom_id' => true,
			)),
		);

		return $options;

	}

	/**
	 * Output the element
	 * IMPORTANT : The UID needs to be set on the top parent container
	 */
	function element() {

		$options = $this->data['options'];
		$uid = $this->data['uid'];
		$classes = $attributes = array();

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-section';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		$classes[] = $this->opt('enable_parallax') === 'yes' && $this->opt('source_type','') == 'image' ? 'zn-section--parallax' : '';
		$classes[] = $this->opt('enable_ov_hidden') === 'yes' ? 'u-ov-hidden' : '';
		$classes[] = 'zn-section--height-'.$this->opt('section_height','auto');
		$classes[] = 'zn-section--contentValign-'.$this->opt('valign','top');
		// Shadow
		$classes[] = $this->opt('shadow','') ? 'znBoxShadow-'.$this->opt('shadow','') : '';
		$classes[] = $this->opt('shadow_hover','') ? 'znBoxShadow--hov-'.$this->opt('shadow_hover','') : '';

		// Add an empty column if pb editor is active
		if (  ZNB()->utility->isActiveEditor() && empty( $this->data['content'] ) ) {
			$this->data['content'] = array ( ZNB()->frontend->addModuleToLayout( 'ZnColumn', array() , array(), 'col-sm-12' ) );
		}

		echo '<section class="'.zn_join_spaces($classes).'" '. zn_join_spaces( $attributes ) .'>';

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

			/**
			 * Parallax Layers
			 * Various Layers with images that will animate themselves upon scrolling
			 */
			$layers_markup = '';
			$layers = $this->opt('layers','');
			if( !empty($layers) ){
				foreach ($layers as $i => $layer) {

					$img = isset($layer['img']) ? $layer['img'] : '';
					$img = attachment_url_to_postid( $layer['img'] );
					$img_size = isset($layer['image_size']) ? $layer['image_size'] : 'thumbnail';
					$start = isset($layer['start']) ? $layer['start'] : 'top-left';

					$layer_classes = array();
					$layer_classes[] = 'zn-prLayer';
					$layer_classes[] = 'zn-prLayer--align-'.$start;
					$layer_classes[] = 'zn-prLayer--' . ( isset($layer['zaxis']) ? $layer['zaxis'] : 'under' );
					$layer_classes[] = 'zn-prLayer--blur-' . ( isset($layer['blur']) ? $layer['blur'] : 'none' );
					$layer_classes[] = 'zn-prLayer_' . $uid . '_' . $i;

					$hide = isset($layer['hide']) && !empty($layer['hide']) ? $layer['hide'] : array('xs', 'sm');
					if( $hide ){
						$layer_classes[] = znb_breakpoint_classes_output( $hide );
					}

					$attr = array();
					$attr['class'] = 'zn-prLayer-img';

					if( isset($layer['obj_parallax_enable']) && $layer['obj_parallax_enable'] == 'yes' ){
						// Classes
						$attr['class'] .= ' js-doObjParallax zn-objParallax';
						$attr['class'] .= ' zn-objParallax--ease-'. ( isset($layer['obj_parallax_easing']) ? $layer['obj_parallax_easing'] : 'linear' );
						// Attributes
						$distance = isset($layer['obj_parallax_distance']) ? $layer['obj_parallax_distance'] : '1';
						$dir = isset($layer['obj_parallax_reverse']) && $layer['obj_parallax_reverse'] == 'yes' ? '' : '-';
						$attr['data-rellax-speed'] = $dir . $distance;
						$attr['data-rellax-percentage'] = 0.5;
					}

					echo '<span class="'.zn_join_spaces($layer_classes).'">';
					echo wp_get_attachment_image( $img, $img_size, false, $attr);
					echo '</span>';

				}
			}


			$container_classes[] = 'zn-sectionContainer';
			$container_classes[] = 'zn-sectionContainer--'.$this->opt('size','default');
			$container_classes[] = $this->opt('side_padding','');

			echo '<div class="' . zn_join_spaces($container_classes) . '">';

				echo znb_get_element_container(array(
					'cssClasses' => 'row zn-section-MainRow '
				));

				ZNB()->frontend->renderContent( $this->data['content'] );

				echo '</div>'; // row
			echo '</div>';
		echo '</section>';

	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		//print_z($this);
		$uid = $this->data['uid'];
		$css = '';
		$s_css = '';

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

		$s_css .= $this->opt('background_color') ? 'background-color:'.$this->opt('background_color').';' : '';
		$s_css .= $this->opt('zIndex','') ? 'z-index:'.$this->opt('zIndex').';' : '';

		if ( !empty($s_css) )
		{
			$css .= '.'.$uid.'{'.$s_css.'}';
		}

		// Container Width
		$container_size = $this->opt('size','default');

		if( $container_size == 'customPx' ) {
			$custom_width = (int)$this->opt( 'custom_width', '1400' );
			if( !empty($custom_width) ){
				$css .= '@media (min-width: '.$custom_width.'px) {.'.$uid.' .zn-sectionContainer--customPx {max-width:'.$custom_width.'px;} }';
				$css .= '@media (max-width: '.($custom_width-1).'px) {.'.$uid.' .zn-sectionContainer--customPx {max-width:100%;} }';
			}
		}
		else if($container_size == 'customPerc'){
			$css .= zn_smart_slider_css($this->opt( 'custom_width_percent', 100 ), '.'.$uid.' .zn-sectionContainer--customPerc', 'max-width', '%');
		}

		if( $this->opt('section_height','auto') == 'custom' ) {
			$selector = '.'.$uid.'.zn-section--height-custom';
			$css .= zn_smart_slider_css( $this->opt( 'custom_height' ), $selector );
		}

		// Layers Margins
		$layers = $this->opt('layers','');
		if( !empty($layers) ){
			foreach ($layers as $i => $layer) {
				$lay_margin['lg'] = isset($layer['margin']) && !empty($layer['margin']) ? $layer['margin'] : '';
				if( !empty($lay_margin) ){
					$lay_margin['selector'] = '.'.$uid.' .zn-prLayer_' . $uid . '_' . $i;
					$lay_margin['type'] = 'margin';
					$css .= zn_push_boxmodel_styles( $lay_margin );
				}
			}
		}
		// Gutter size
		$gutter_size = $this->opt( 'gutter_size', 20 );
		if( $gutter_size != 20 ){
			$css .= '.'.$uid. '>.zn-sectionContainer>.row>[class*="col-"] {padding-left:'.$gutter_size.'px;padding-right:'.$gutter_size.'px}';
		}

		return $css;
	}

}

ZNB()->elements_manager->registerElement( new ZNB_Section(array(
	'id'          => 'ZnSection',
	'name'        => __('Section', 'zn_framework'),
	'description' => __('This element will generate a section in which you can add elements', 'zn_framework'),
	'level'       => 1,
	'category'    => 'Layout, Fullwidth',
	'legacy'      => false,
	'keywords'    => array('row', 'container', 'block'),
)));
