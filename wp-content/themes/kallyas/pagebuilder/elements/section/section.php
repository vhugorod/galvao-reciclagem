<?php if(! defined('ABSPATH')){ return; }
/*
	Name: Section
	Description: This element will generate a section in which you can add elements
	Class: ZnSection
	Category: Layout, Fullwidth
	Keywords: row, container, block
	Level: 1
	Style: true
*/

class ZnSection extends ZnElements {

	function options() {

		$uid = $this->data['uid'];
		$colorzilla_url = 'http://www.colorzilla.com/gradient-editor/';

		// backwards compatibility for top and bottom padding
		$sct_padding_std = array('top' => '35px', 'bottom'=> '35px');
		if(isset($this->data['options']['top_padding']) && $this->data['options']['top_padding'] != '' ){
			$sct_padding_std['top'] = $this->data['options']['top_padding'].'px';
		}
		if(isset($this->data['options']['bottom_padding']) && $this->data['options']['bottom_padding'] != '' ){
			$sct_padding_std['bottom'] = $this->data['options']['bottom_padding'].'px';
		}

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						'id'          => 'size',
						'name'        => 'Section Width',
						'description' => 'Select the desired size for this section.',
						'type'        => 'select',
						'std'        => 'container',
						'options'	  => array(
							'container' => 'Fixed width',
							'full_width' => 'Full width',
							'container custom_width' => 'Custom width (px)',
							'container custom_width_perc' => 'Custom width Percentage (%)'
						),
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.'.$uid.' .zn_section_size',
							'tasks' => array(
								array(
									'condition_type' => 'remove',
									'css_class' => '.'.$uid.' .zn_section_size',
									'property' => 'width',
									'options' => array(
										'container',
										'full_width',
									),
								),
							),
						)
					),

					array(
						'id'          => 'custom_width',
						'name'        => __( 'Section Container Width (on Large breakpoints, 1200px)', 'zn_framework'),
						'description' => __( 'Choose the desired width for the section\'s container.', 'zn_framework' ),
						'type'        => 'slider',
						'std'        => '1170',
						'helpers'     => array(
							'min' => '1170',
							'max' => '1900'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' > .custom_width.container',
							'css_rule'  => 'width',
							'unit'      => 'px'
						),
						'dependency' => array( 'element' => 'size' , 'value'=> array('container custom_width') )
					),

					array(
						'id'          => 'custom_width_percent',
						'name'        => __( 'Section Container Width ( in Percentage %)', 'zn_framework'),
						'description' => __( 'Choose the desired width for the section\'s container.', 'zn_framework' ),
						'type'        => 'slider',
						'std'        => '100',
						'helpers'     => array(
							'min' => '20',
							'max' => '100'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' > .custom_width_perc.container',
							'css_rule'  => 'width',
							'unit'      => '%'
						),
						'dependency' => array( 'element' => 'size' , 'value'=> array('container custom_width_perc') )
					),

					array (
						'id'          => 'sec_height',
						'name'        => 'Section Height',
						'description' => 'Select the desired height for this section.',
						'type'        => 'select',
						'std'        => 'auto',
						'options'     => array(
							'auto' => 'Auto',
							'custom_height' => 'Custom Fixed Height'
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.'.$uid.' .zn_section_size',
							'val_prepend'  => 'zn-section-height--',
						)
					),

					array(
						'id'          => 'custom_height',
						'name'        => __( 'Section Custom Height', 'zn_framework'),
						'description' => __( 'Choose the desired height for this section. You can choose either height or min-height as a property. Height will force a fixed size rather than just a minimum. <br>*TIP: Use 100vh to have a full-height element.', 'zn_framework' ),
						'type'        => 'smart_slider',
						'std'        => '100',
						'helpers'     => array(
							'min' => '0',
							'max' => '1400'
						),
						'supports' => array('breakpoints'),
						'units' => array('px', '%', 'vh'),
						'properties' => array('min-height','height'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' > .zn-section-height--custom_height',
							'css_rule'  => 'min-height',
							'unit'      => 'px'
						),
						'dependency' => array( 'element' => 'sec_height' , 'value'=> array('custom_height') )
					),

					array(
						'id'          => 'valign',
						'name'        => __( 'Section Vertical Align', 'zn_framework'),
						'description' => __( 'Choose how to vertically align content.', 'zn_framework' ),
						'type'        => 'select',
						'std'        => 'top',
						'options'     => array(
							'top' => 'Top',
							'middle' => 'Middle',
							'bottom' => 'Bottom',
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.'.$uid.' .zn_section_size',
							'val_prepend'  => 'zn-section-content_algn--',
						),
						'dependency' => array( 'element' => 'sec_height' , 'value'=> array('custom_height') )
					),

					array(
						'id'          => 'gutter_size',
						'name'        => __('Gutter Size', 'zn_framework'),
						'description' => __('Select the gutter distance between columns', 'zn_framework'),
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							'' => __( 'Default (15px)', 'zn_framework' ),
							'gutter-xs' => __( 'Extra Small (5px)', 'zn_framework' ),
							'gutter-sm' => __( 'Small (10px)', 'zn_framework' ),
							'gutter-md' => __( 'Medium (25px)', 'zn_framework' ),
							'gutter-lg' => __( 'Large (40px)', 'zn_framework' ),
							'gutter-0' => __( 'No distance - 0px', 'zn_framework' ),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.'.$uid.' > .zn_section_size > .row.zn_columns_container'
						)
					),



					/**
					 * Margins and padding
					 */
					array (
						"name"        => __( "Edit padding & margins for each device breakpoint", 'zn_framework' ),
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
						'std'	  => 	array('left'=> 'auto', 'right'=> 'auto' ),
						'disable'	=> array('left', 'right'),
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'margin',
						),
					),
					array(
						'id'          => 'cc_margin_md',
						'name'        => 'Margin (Medium Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	array('left'=> 'auto', 'right'=> 'auto' ),
						'disable'	=> array('left', 'right'),
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'cc_margin_sm',
						'name'        => 'Margin (Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	array('left'=> 'auto', 'right'=> 'auto' ),
						'disable'	=> array('left', 'right'),
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'cc_margin_xs',
						'name'        => 'Margin (Extra Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	array('left'=> 'auto', 'right'=> 'auto' ),
						'disable'	=> array('left', 'right'),
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
						'std'	  => $sct_padding_std,
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'padding',
						),
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


				)
			),

			'background' => array(
				'title' => 'Styles Options',
				'options' => array(

					array(
						'id'          => 'title1',
						'name'        => 'Background & Color Options',
						'description' => 'These are options to customize the background and colors for this section.',
						'type'        => 'zn_title',
						'class'        => 'zn_full zn-custom-title-large',
					),

					array(
						'id'          => 'background_color',
						'name'        => 'Background color',
						'description' => 'Here you can override the background color for this section.',
						'type'        => 'colorpicker',
						'std'         => '',
						'live'        => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'background-color',
							'unit'		=> ''
						)
					),

					// Background image/video or youtube
					array (
						"name"        => __( "Background Source Type", 'zn_framework' ),
						"description" => __( "Please select the source type of the background.", 'zn_framework' ),
						"id"          => "source_type",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							''  => __( "None (Will just rely on the background color (if any) )", 'zn_framework' ),
							'image'  => __( "Image", 'zn_framework' ),
							'video_self' => __( "Self Hosted Video", 'zn_framework' ),
							'video_youtube' => __( "Youtube Video", 'zn_framework' ),
							'video_vimeo' => __( "Vimeo Video", 'zn_framework' ),
							'embed_iframe' => __( "Embed Iframe (Vimeo etc.)", 'zn_framework' )
						)
					),

					array(
						'id'          => 'background_image',
						'name'        => 'Background image',
						'description' => 'Please choose a background image for this section.',
						'type'        => 'background',
						'options' => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
						'dependency' => array( 'element' => 'source_type' , 'value'=> array('image') )
					),

					// Youtube video
					array (
						"name"        => __( "Youtube ID", 'zn_framework' ),
						"description" => __( "Add an Youtube ID", 'zn_framework' ),
						"id"          => "source_vd_yt",
						"std"         => "",
						"type"        => "text",
						"placeholder" => "ex: tR-5AZF9zPI",
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_youtube') )
					),
					// Vimeo video
					array (
						"name"        => __( "Vimeo ID", 'zn_framework' ),
						"description" => __( "Add an Vimeo ID", 'zn_framework' ),
						"id"          => "source_vd_vm",
						"std"         => "",
						"type"        => "text",
						"placeholder" => "ex: 2353562345",
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_vimeo') )
					),

					array (
						'id'            => 'enable_parallax',
						'name'          => 'Enable Scrolling Parallax effect',
						'description'   => 'Select if you want to enable parallax scrolling effect on background image.',
						"std"         => "no",
						'type'        => 'zn_radio',
						'options'        => array(
							'yes' => __( "Yes", 'zn_framework' ),
							'no' => __( "No", 'zn_framework' ),
						),
						'class'        => 'zn_radio--yesno',
						'dependency' => array( 'element' => 'source_type' , 'value'=> array('image') ),
					),

					array (
						"name"        => __( "Skewed Shaped background?", 'zn_framework' ),
						"description" => __( "This option is deprecated! Please use Top & Bottom Masks options.", 'zn_framework' ),
						"id"          => "skewed_bg",
						"std"         => "no",
						"type"        => "select",
						"options"     => array (
							'no'  => __( "No", 'zn_framework' ),
							'skewed'  => __( "Skewed", 'zn_framework' ),
							'skewed-flipped' => __( "Skewed Flipped", 'zn_framework' )
						),
						"dependency"  => array(
							array( 'element' => 'source_type' , 'value'=> array('image', '') ),
							array( 'element' => 'enable_parallax' , 'value'=> array('no') )
						),
						"deprecated" => true
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
						'name'        => 'Mp4 video source',
						'description' => 'Add the MP4 video source for your local video',
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/mp4',
							'button_title' => 'Add / Change mp4 video',
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_self_ogg',
						'name'        => 'Ogg/Ogv video source',
						'description' => 'Add the OGG video source for your local video',
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/ogg',
							'button_title' => 'Add / Change ogg video',
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_self_webm',
						'name'        => 'Webm video source',
						'description' => 'Add the WEBM video source for your local video',
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/webm',
							'button_title' => 'Add / Change webm video',
						),
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self') )
					),
					array(
						'id'          => 'source_vd_vp',
						'name'        => 'Video poster',
						'description' => 'Using this option you can add your desired video poster that will be shown on unsuported devices (mobiles, tablets). ',
						'type'        => 'media',
						'std'         => '',
						'class'       => 'zn_full',
						"dependency"  => array( 'element' => 'source_type' , 'value'=> array('video_self','video_youtube', 'video_vimeo', 'embed_iframe') )
					),
					array(
						'id'          => 'mobile_play',
						'name'        => 'Display Play button on Mobiles?',
						'description' => 'By default videos are not displayed in the background on mobile devices. It\'s too problematic and instead, we added a button trigger which will open the video into a modal.',
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
						'name'        => 'Autoplay video?',
						'description' => 'Enable autoplay for video? Remember, this option only applies on desktop devices, not mobiles or tablets.',
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
						'name'        => 'Loop video?',
						'description' => 'Enable looping the video? Remember, this option only applies on desktop devices, not mobiles or tablets.',
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
						'name'        => 'Start mute?',
						'description' => 'Start the video with muted audio?',
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
						'id'          => 'source_vd_controls',
						'name'        => 'Video controls',
						'description' => 'Enable video controls? Please know that for some captions it might not be reachable.',
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
						'id'          => 'source_vd_controls_pos',
						'name'        => 'Video controls position',
						'description' => 'Video controls position in the slide',
						'type'        => 'select',
						'std'         => 'bottom-right',
						"dependency"  => array(
							array('element' => 'source_type' , 'value'=> array('video_self','video_youtube', 'video_vimeo')),
							array('element' => 'source_vd_controls' , 'value'=> array('yes'))
						),
						"options"     => array (
							"top-right" => __( "top-right", 'zn_framework' ),
							"top-left" => __( "top-left", 'zn_framework' ),
							"top-center"  => __( "top-center", 'zn_framework' ),
							"bottom-right"  => __( "bottom-right", 'zn_framework' ),
							"bottom-left"  => __( "bottom-left", 'zn_framework' ),
							"bottom-center"  => __( "bottom-center", 'zn_framework' ),
							"middle-right"  => __( "middle-right", 'zn_framework' ),
							"middle-left"  => __( "middle-left", 'zn_framework' ),
							"middle-center"  => __( "middle-center", 'zn_framework' )
						),
						"class"       => "zn_input_sm"
					),

					array(
						'id'          => 'source_overlay',
						'name'        => 'Background colored overlay',
						'description' => 'Add slide color overlay over the image or video to darken or enlight?',
						'type'        => 'select',
						'std'         => '0',
						"options"     => array (
							"1" => __( "Yes (Normal color)", 'zn_framework' ),
							"2" => __( "Yes (Horizontal gradient)", 'zn_framework' ),
							"3" => __( "Yes (Vertical gradient)", 'zn_framework' ),
							"4" => __( "Yes (Custom CSS generated gradient)", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						)
					),

					array(
						'id'          => 'source_overlay_color',
						'name'        => 'Overlay background color',
						'description' => 'Pick a color',
						'type'        => 'colorpicker',
						'std'         => '#353535',
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('1', '2', '3') ),
					),
					array(
						'id'          => 'source_overlay_opacity',
						'name'        => 'Overlay\'s opacity.',
						'description' => 'Overlay background colors opacity level.',
						'type'        => 'slider',
						'std'         => '30',
						"helpers"     => array (
							"step" => "5",
							"min" => "0",
							"max" => "100"
						),
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('1', '2', '3') ),
					),

					array(
						'id'          => 'source_overlay_color_gradient',
						'name'        => 'Overlay Gradient 2nd Bg. Color',
						'description' => 'Pick a color',
						'type'        => 'colorpicker',
						'std'         => '#353535',
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('2', '3') ),
					),
					array(
						'id'          => 'source_overlay_color_gradient_opac',
						'name'        => 'Gradient Overlay\'s 2nd Opacity.',
						'description' => 'Overlay gradient 2nd background color opacity level.',
						'type'        => 'slider',
						'std'         => '30',
						"helpers"     => array (
							"step" => "5",
							"min" => "0",
							"max" => "100"
						),
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('2', '3') ),
					),

					array(
						'id'          => 'source_overlay_custom_css',
						'name'        => 'Custom CSS Gradient Overlay',
						'description' => sprintf(__('You can use a tool such as <a href="%s" target="_blank">%s</a> to generate a unique custom gradient.
Here\'s a quick video explainer <a href="%s" >%s</a> how to generate and paste the video here.', 'zn_framework'),
								$colorzilla_url,
								'http://www.colorzilla.com/gradient-editor/',
								'http://hogash.d.pr/8Dze',
								'http://hogash.d.pr/8Dze'),
						'type'        => 'textarea',
						'std'         => '',
						"dependency"  => array( 'element' => 'source_overlay' , 'value'=> array('4') ),
					),

					array(
						'id'            => 'source_overlay_gloss',
						'name'          => 'Enable Gloss Overlay',
						'description'   => 'Display a gloss over the background',
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => '1'
					),

					array(
						'id'          => 'section_scheme',
						'name'        => 'Text color scheme',
						'description' => 'Select the color scheme of this section. For example for using a light backgorund, use Dark scheme. You will most likely need to customize the elements within this section, as the text color is applied global, but not specific.',
						'type'        => 'select',
						'std'         => '',
						'options'        => array(
							'' => 'Inherit from Global (Color options)',
							'light' => 'Light',
							'dark' => 'Dark'
						),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.'.$uid,
							'val_prepend'  => 'element-scheme--',
							'unit'      => ''
						)
					),

					array(
						'id'          => 'title1',
						'name'        => 'Other Options',
						'description' => 'These are options to customize the background and colors for this section.',
						'type'        => 'zn_title',
						'class'        => 'zn_full zn-custom-title-large',
					),

					// Top masks
					array (
						"name"        => __( "Top Mask", 'zn_framework' ),
						"description" => __( "Style the top of this section with a custom shaped mask.", 'zn_framework' ),
						"id"          => "section_topmasks",
						"std"         => "none",
						"type"        => "select",
						"options"     => zn_get_masks(),
					),
					array(
						'id'          => 'topmasks_bg',
						'name'        => 'Top Mask Background Color',
						'description' => 'If you need the mask to have a different color than the main site background, please choose the color. Usually this color is needed when the next section, under this one has a different background color.',
						'type'        => 'colorpicker',
						'std'         => '',
						"dependency"  => array( 'element' => 'section_topmasks' , 'value'=> zn_get_masks_deps() ),
					),
					array(
						'id'          => 'top_mask_bg_image',
						'name'        => 'Top Mask Background Image',
						'description' => 'Select the image for the mask.',
						'type'        => 'background',
						'options' => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
						"dependency"  => array( 'element' => 'section_topmasks' , 'value'=> array('custom') ),
					),
					array(
						'id'          => 'top_mask_bg_height',
						'name'        => __( 'Custom Mask height (in pixels)', 'zn_framework'),
						'description' => __( 'Specify the height for the custom mask.', 'zn_framework' ),
						'type'        => 'slider',
						'std'        => '100',
						'helpers'     => array(
							'min' => '1',
							'max' => '200'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' > .kl-mask--custom.kl-topmask',
							'css_rule'  => 'height',
							'unit'      => 'px'
						),
						"dependency"  => array( 'element' => 'section_topmasks' , 'value'=> array('custom') ),
					),


					// Bottom masks
					array (
						"name"        => __( "Bottom Mask", 'zn_framework' ),
						"description" => __( "Style the bottom of this section with a custom shaped mask.", 'zn_framework' ),
						"id"          => "hm_header_bmasks",
						"std"         => "none",
						"type"        => "select",
						"options"     => zn_get_masks(),
					),

					array(
						'id'          => 'bottom_mask_bg_image',
						'name'        => 'Bottom Mask Background Image',
						'description' => 'Select the image for the mask.',
						'type'        => 'background',
						'options' => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
						"dependency"  => array( 'element' => 'hm_header_bmasks' , 'value'=> array('custom') ),
					),
					array(
						'id'          => 'bottom_mask_bg_height',
						'name'        => __( 'Custom Mask height (in pixels)', 'zn_framework'),
						'description' => __( 'Specify the height for the custom mask.', 'zn_framework' ),
						'type'        => 'slider',
						'std'        => '100',
						'helpers'     => array(
							'min' => '1',
							'max' => '200'
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' > .kl-mask--custom.kl-bottommask',
							'css_rule'  => 'height',
							'unit'      => 'px'
						),
						"dependency"  => array( 'element' => 'hm_header_bmasks' , 'value'=> array('custom') ),
					),


					array(
						'id'          => 'hm_header_bmasks_bg',
						'name'        => 'Bottom Mask Background Color',
						'description' => 'If you need the mask to have a different color than the main site background, please choose the color. Usually this color is needed when the next section, under this one has a different background color.',
						'type'        => 'colorpicker',
						'std'         => '',
						"dependency"  => array( 'element' => 'hm_header_bmasks' , 'value'=> zn_get_masks_deps() ),
					),

					array(
						'id'            => 'enable_ov_hidden',
						'name'          => 'Enable Overflow Hidden',
						'description'   => 'Select if you want to set overflow hidden for this section',
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => 'yes'
					),

					array(
						'id'            => 'dsb_sidemargins',
						'name'          => 'Disable side margins on mobiles',
						'description'   => 'This option will turn off the left and right margins for this section, on mobiles and tablets. This option is usually used when having a background placed that stretches fully',
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => 'yes'
					),

					array (
						"name"        => __( "Section Shadow", 'zn_framework' ),
						"description" => __( "Please select a shadow style.", 'zn_framework' ),
						"id"          => "image_box_shadow",
						"std"         => "",
						"options"     => array(
							''  => __( 'No shadow', 'zn_framework' ),
							'1'  => __( 'Shadow 1x', 'zn_framework' ),
							'2'  => __( 'Shadow 2x', 'zn_framework' ),
							'3'  => __( 'Shadow 3x', 'zn_framework' ),
							'4'  => __( 'Shadow 4x', 'zn_framework' ),
							'5'  => __( 'Shadow 5x', 'zn_framework' ),
							'6'  => __( 'Shadow 6x', 'zn_framework' ),
						),
						"type"        => "select",
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.'.$uid,
							'val_prepend'	=> 'znBoxShadow-',
						),
					),

					array (
						"name"        => __( "Section Shadow on Hover", 'zn_framework' ),
						"description" => __( "Please select a shadow style for hover state.", 'zn_framework' ),
						"id"          => "image_box_shadow_hover",
						"std"         => "",
						"options"     => array(
							''  => __( 'No shadow', 'zn_framework' ),
							'1'  => __( 'Shadow 1x', 'zn_framework' ),
							'2'  => __( 'Shadow 2x', 'zn_framework' ),
							'3'  => __( 'Shadow 3x', 'zn_framework' ),
							'4'  => __( 'Shadow 4x', 'zn_framework' ),
							'5'  => __( 'Shadow 5x', 'zn_framework' ),
							'6'  => __( 'Shadow 6x', 'zn_framework' ),
						),
						"type"        => "select",
					),

					array (
						"name"        => __( "Z-Index Layering", 'zn_framework' ),
						"description" => __( "Please select a z-index order in layer.", 'zn_framework' ),
						"id"          => "zIndex",
						"std"         => "",
						"options"     => array(
							''  => __( 'No zIndex', 'zn_framework' ),
							'1'  => __( 'Z-index 1', 'zn_framework' ),
							'2'  => __( 'Z-index 2', 'zn_framework' ),
							'3'  => __( 'Z-index 3', 'zn_framework' ),
							'4'  => __( 'Z-index 4', 'zn_framework' ),
							'5'  => __( 'Z-index 5', 'zn_framework' ),
							'10'  => __( 'Z-index 10', 'zn_framework' ),
						),
						"type"        => "select",
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.'.$uid,
							'val_prepend'	=> 'u-zindex-',
						),
					),

				),
			),

			'advanced' => array(
				'title' => 'Advanced',
				'options' => array(

					array(
						'id'          => 'title1',
						'name'        => __('Inline Modal Options', 'zn_framework'),
						'description' => __('Make this section as content for a modal window.', 'zn_framework'),
						'type'        => 'zn_title',
						'class'       => 'zn_full zn-custom-title-large',
					),

					array(
						'id'            => 'enable_inlinemodal',
						'name'          => 'Enable INLINE Modal Window',
						'description'   => 'If you enable this, this section <strong>will be hidden in View mode (non-pagebuilder)</strong> and will contain any elements you want that will be displayed as a <em>modal window</em>, linked by an url from the page. <br><br> In order to properly link to this modal, copy the unique ID and paste it into the button link field, with a hash in front, for example <em>"#this_unique_id"</em> . ',
						'type'          => 'toggle2',
						'std'           => '',
						'value'         => 'yes'
					),

					array(
						'id'          => 'window_size',
						'name'        => 'Window Size (inline modal)',
						'description' => 'Select the modal window width size in px. Default 1200px',
						"std"         => "1200",
						"type"        => "text",
						'dependency' => array( 'element' => 'enable_inlinemodal' , 'value'=> array('yes') )
					),

					array(
						'id'          => 'window_autopopup',
						'name'        => 'Auto-Popup window?',
						'description' => 'Select wether you want to autopopup this modal window',
						"std"         => "0",
						"type"        => "select",
						"options"     => array (
							'' => __( 'No', 'zn_framework' ),
							'immediately' => __( 'Immediately ', 'zn_framework' ),
							'delay' => __( 'After a delay of "x" seconds', 'zn_framework' ),
							'scroll' => __( 'When user scrolls halfway down the page', 'zn_framework' ),
						),
						'dependency' => array( 'element' => 'enable_inlinemodal' , 'value'=> array('yes') )
					),

					array(
						'id'          => 'autopopup_delay',
						'name'        => 'Auto-Popup delay',
						'description' => 'Select the autopopup delay in seconds. This option is used only if <em>"After a delay of "x" seconds"</em> option is selected in the <strong>"Auto-Popup window?"</strong> option above.',
						"std"         => "5",
						"type"        => "text",
						'dependency' => array(
							array( 'element' => 'enable_inlinemodal' , 'value'=> array('yes') ),
							array( 'element' => 'window_autopopup' , 'value'=> array('delay') )
						),
					),

					array(
						'id'          => 'autopopup_cookie',
						'name'        => 'Prevent re-opening Auto-popup',
						'description' => 'Enable this if you want the autopopup to appear only once (assigning a cookie), rather than opening each time the page is refreshed. The cookie expires after one hour.',
						"std"         => "no",
						"type"        => "select",
						"options"     => array (
							'no' => __( 'No - Always open', 'zn_framework' ),
							'halfhour' => __( 'Yes - Set cookie for 30 min', 'zn_framework' ),
							'hour' => __( 'Yes - Set cookie for 1 hour', 'zn_framework' ),
							'day' => __( 'Yes - Set cookie for 1 day', 'zn_framework' ),
							'week' => __( 'Yes - Set cookie for 1 week', 'zn_framework' ),
							'2week' => __( 'Yes - Set cookie for 2 weeks', 'zn_framework' ),
							'month' => __( 'Yes - Set cookie for 1 month', 'zn_framework' ),
							'1year' => __( 'Yes - Set cookie for 1 year', 'zn_framework' ),
						),
						'dependency' => array(
							array( 'element' => 'enable_inlinemodal' , 'value'=> array('yes') ),
							array( 'element' => 'window_autopopup' , 'value'=> array('immediately','delay','scroll') )
						),
					),

					array(
						'id'          => 'title1',
						'name'        => __('Parallax Layers Options', 'zn_framework'),
						'description' => __('Using this feature you can place various images throughout the section, as scrolling decorations.', 'zn_framework'),
						'type'        => 'zn_title',
						'class'       => 'zn_full zn-custom-title-large',
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
								"type"        => "media",
								"supports"    => "id",
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
								"description" => __( "Selet if you want to add a blurring effect. Works only in browsers supporting CSS Filters.", 'zn_framework' ),
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
								"name"        => __( "Enable Scrolling Animate Effect?", 'zn_framework' ),
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
								"std"         => '',
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
								"description" => __( "Select the effect's easing. You can play with the easing effects <a href=\"http://greensock.com/ease-visualizer\" target=\"_blank\">here</a>.", 'zn_framework' ),
								"id"          => "obj_parallax_easing",
								"std"         => "linear",
								"type"        => "select",
								"options"     => array(
									"none"     => "No Easing",
									"linear" => "Ease Out Linear",
									"quad"   => "Ease Out Quad",
									"cubic"  => "Ease Out Cubic",
									"quart"  => "Ease Out Quart",
									"quint"  => "Ease Out Quint",
								),
								"dependency"  => array( 'element' => 'obj_parallax_enable' , 'value'=> array('yes') ),
							),

							array (
								"name"        => __( "Tween in reverse?", 'zn_framework' ),
								"description" => __( "This will make the tween effect to run in opposite direction of the scroll.", 'zn_framework' ),
								"id"          => "obj_parallax_reverse",
								"std"         => "",
								"value"       => "yes",
								"type"        => "toggle2",
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
				)
			),

			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#vcux4GW2ctg') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/section-and-columns/') ),
				'copy'    => $uid,
				'general' => true,
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

		$uid = $this->data['uid'];
		$element_id = $this->opt('custom_id') ? $this->opt('custom_id') : $uid;

		$options = $this->data['options'];

		$section_classes = array();

		$section_classes[] = $uid;
		$section_classes[] = $this->opt('ustyle') ? $this->opt('ustyle') : '';
		$section_classes[] = zn_get_element_classes($options);
		$section_classes[] = $this->opt('enable_parallax') === 'yes' && $this->opt('source_type','') == 'image' ? 'zn_parallax' : '';
		$section_classes[] = $this->opt('enable_ov_hidden') === 'yes' ? 'zn_ovhidden' : '';
		$section_classes[] = $this->opt('dsb_sidemargins','') === 'yes' ? '' : 'section-sidemargins';
		$section_classes[] = $this->opt('zIndex','') ? 'u-zindex-'.$this->opt('zIndex','') : '';

		$section_classes[] = $this->opt('image_box_shadow','') ? 'znBoxShadow-'.$this->opt('image_box_shadow','') : '';
		$section_classes[] = $this->opt('image_box_shadow_hover','') ? 'znBoxShadow--hov-'.$this->opt('image_box_shadow_hover',''). ' znBoxShadow--hover' : '';

		// Add an empty column if pb editor is active
		if (  ZNB()->utility->isActiveEditor() && empty( $this->data['content'] ) ) {
			$this->data['content'] = array ( ZNB()->frontend->addModuleToLayout( 'ZnColumn', array() , array(), 'col-sm-12' ) );
		}

		$bottom_mask = $this->opt('hm_header_bmasks','none');
		if($bottom_mask != 'none'){
			$section_classes[] = 'zn_section--masked';

		}

		if( $this->opt('source_type', '') != '' || $this->opt('source_overlay', '0') != 0 || $this->opt('hm_header_bmasks','none') != 'none' || $this->opt('section_topmasks','none') != 'none' || $this->opt('source_overlay_gloss', '') == 1){
			$section_classes[] = 'zn_section--relative';
		}

		$other_attrs = array();
		if($this->opt('enable_inlinemodal','') == 'yes'){
			$section_classes[] = 'zn_section--inlinemodal mfp-hide';
			$section_classes[] = $this->opt('window_size', '1200') < 1200 ? 'zn_section--stretch-container' : '';
			$section_classes[] = $this->opt('window_autopopup','') != '' ? 'zn_section--auto-'.$this->opt('window_autopopup','') : '';
			// Add delay
			if( $this->opt('window_autopopup','') == 'delay' ){
				$del = $this->opt('autopopup_delay','5');
				$other_attrs[] = 'data-auto-delay="'.esc_attr($del).'"';
			}
		}

		if($this->opt('autopopup_cookie','no') != 'no'){
			$acook = $this->opt('autopopup_cookie','no');
			$other_attrs[] = 'data-autoprevent="'.esc_attr($acook).'"';
		}

		$section_classes[] = 'section--'.$this->opt('skewed_bg','no');
		$section_classes[] = $this->opt('section_scheme','') != '' ? 'element-scheme--'.$this->opt('section_scheme','') : '';

		?>
		<section class="zn_section <?php echo implode(' ', $section_classes); ?>" id="<?php echo esc_attr( $element_id ); ?>" <?php echo implode(' ', $other_attrs); ?> <?php echo zn_get_element_attributes($options); ?>>

			<?php

			/**
			 * Parallax Layers
			 * Various Layers with images that will animate themselves upon scrolling
			 */
			$layers_markup = '';
			$layers = $this->opt('layers','');
			if( !empty($layers) ){
				foreach ($layers as $i => $layer) {

					$img = isset($layer['img']) ? $layer['img'] : '';
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
						foreach( $hide as $value ){
							$layer_classes[] = 'hidden-' . $value;
						}
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
						$attr['data-rellax-percentage'] = '0.5';
					}

					echo '<span class="'.implode(' ',$layer_classes).'">';
					echo wp_get_attachment_image( $img, $img_size, false, $attr);
					echo '</span>';

				}
			}


			WpkPageHelper::zn_background_source( array(
				'source_type' => $this->opt('source_type'),
				'source_background_image' => $this->opt('background_image'),
				'source_vd_yt' => $this->opt('source_vd_yt'),
				'source_vd_vm' => $this->opt('source_vd_vm'),
				'source_vd_embed_iframe' => $this->opt('source_vd_embed_iframe'),
				'source_vd_self_mp4' => $this->opt('source_vd_self_mp4'),
				'source_vd_self_ogg' => $this->opt('source_vd_self_ogg'),
				'source_vd_self_webm' => $this->opt('source_vd_self_webm'),
				'source_vd_vp' => $this->opt('source_vd_vp'),
				'source_vd_autoplay' => $this->opt('source_vd_autoplay'),
				'source_vd_loop' => $this->opt('source_vd_loop'),
				'source_vd_muted' => $this->opt('source_vd_muted'),
				'source_vd_controls' => $this->opt('source_vd_controls'),
				'source_vd_controls_pos' => $this->opt('source_vd_controls_pos'),
				'source_overlay' => $this->opt('source_overlay'),
				'source_overlay_color' => $this->opt('source_overlay_color'),
				'source_overlay_opacity' => $this->opt('source_overlay_opacity'),
				'source_overlay_color_gradient' => $this->opt('source_overlay_color_gradient'),
				'source_overlay_color_gradient_opac' => $this->opt('source_overlay_color_gradient_opac'),
				'source_overlay_gloss' => $this->opt('source_overlay_gloss',''),
				'enable_parallax' => $this->opt('enable_parallax'),
				'source_overlay_custom_css' => $this->opt('source_overlay_custom_css',''),
				'mobile_play' => $this->opt('mobile_play', 'no'),
			) );

			?>

			<div class="zn_section_size <?php echo esc_attr( $this->opt('size','container') );?> zn-section-height--<?php echo esc_attr( $this->opt('sec_height','auto') );?> zn-section-content_algn--<?php echo esc_attr( $this->opt('valign','top') );?> ">

				<?php echo ZNB()->utility->getElementContainer(array(
					'cssClasses' => 'row '. $this->opt('gutter_size','')
				)); ?>

					<?php
						ZNB()->frontend->renderElements( $this->data['content'] );
					?>

				</div>
			</div>

			<?php
			// top mask
			if( $this->opt('section_topmasks','none') != 'none' ){
				zn_bottommask_markup(
					$this->opt('section_topmasks','none'),
					$this->opt('topmasks_bg',''),
					'top',
					$this->opt('top_mask_bg_image'),
					$this->opt('top_mask_bg_height')
				);
			}
			// bottom mask
			if($bottom_mask != 'none'){
				zn_bottommask_markup(
					$bottom_mask,
					$this->opt('hm_header_bmasks_bg',''),
					'bottom',
					$this->opt('bottom_mask_bg_image'),
					$this->opt('bottom_mask_bg_height')
				);
			}
		?>
		</section>


		<?php
		// Modal Overlay
		if( ZNB()->utility->isActiveEditor() && $this->opt('enable_inlinemodal','') == 'yes'){
			?>
			<div class="zn_section-modalInfo">
				<span class="zn_section-modalInfo-title">MODAL WINDOW</span>
				<span class="zn_section-modalInfo-tip">
					<a href="<?php echo sprintf( '%s', esc_url('https://my.hogash.com/documentation/section-as-modal-window/') );?>" target="_blank"><span class="dashicons dashicons-info"></span></a>
					<span class="zn_section-modalInfo-bubble"><?php echo __('This section is a Modal Window. It will appear only in Page Builder mode and visible upon being triggered by a modal window target link.','zn_framework'); ?></span>
				</span>
				<a href="#" class="zn_section-modalInfo-toggleVisible js-toggle-class" data-target=".<?php echo esc_attr( $uid ); ?>" data-target-class="modal-overlay-hidden">
					<span class="dashicons dashicons-visibility"></span>
				</a>
			</div>
			<div class="zn_section-modalOverlay"></div>
			<?php
		}

	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		//print_z($this);
		$uid = $this->data['uid'];
		$css = '';
		$s_css = '';

		// backwards compatibility for top and bottom padding
		$sct_padding_std = array('top' => '35px', 'bottom'=> '35px');
		if(isset($this->data['options']['top_padding']) && $this->data['options']['top_padding'] != '' ){
			$sct_padding_std['top'] = $this->data['options']['top_padding'].'px';
		}
		if(isset($this->data['options']['bottom_padding']) && $this->data['options']['bottom_padding'] != '' ){
			$sct_padding_std['bottom'] = $this->data['options']['bottom_padding'].'px';
		}

		// Margin
		$margins = array();
		$margins['lg'] = $this->opt('cc_margin_lg', '' );
		$margins['md'] = $this->opt('cc_margin_md', '' );
		$margins['sm'] = $this->opt('cc_margin_sm', '' );
		$margins['xs'] = $this->opt('cc_margin_xs', '' );
		if( !empty($margins) ){
			$margins['selector'] = '.'.$uid;
			$margins['type'] = 'margin';
			$css .= zn_push_boxmodel_styles( $margins );
		}

		// Padding
		$paddings = array();
		$paddings['lg'] = $this->opt('cc_padding_lg', $sct_padding_std );
		$paddings['md'] = $this->opt('cc_padding_md', '' );
		$paddings['sm'] = $this->opt('cc_padding_sm', '' );
		$paddings['xs'] = $this->opt('cc_padding_xs', '' );
		if( !empty($paddings) ){
			$paddings['selector'] = '.'.$uid;
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}

		$s_css .= $this->opt('background_color') ? 'background-color:'.$this->opt('background_color').';' : '';

		if ( !empty($s_css) )
		{
			$css .= '.zn_section.'.$uid.'{'.$s_css.'}';
		}

		$width = $this->opt('enable_inlinemodal','') == 'yes' ? 'width:'.$this->opt('window_size', '1200').'px' : '';
		if ( !empty($width) )
		{
			$css .= '@media screen and (min-width:'.$this->opt('window_size', '1200').'px) {';
			$css .= '.zn_section--inlinemodal.'.$uid.' {'.$width.'}';
			$css .= '}';
		}

		// Container Width
		$container_size = $this->opt('size','container');
		$general_layout_style = zget_option( 'zn_width' , 'layout_options', false, '1170' );
		if( $container_size == 'container custom_width' ) {

			$custom_width = (int)$this->opt( 'custom_width', '1170' );
			$zn_custom_width = (int)zget_option( 'custom_width' , 'layout_options', false, '1170' );
			if( $general_layout_style == 'custom_perc' || ( !empty($custom_width) && ( $custom_width != $zn_custom_width.'px' || $custom_width != $zn_custom_width ) ) ){
				$custom_width_extra = $custom_width+30;
				$css .= '@media (min-width: '.$custom_width_extra.'px) {.'.$uid.' .container.custom_width {width:'.$custom_width.'px;} }';
				$css .= '@media (min-width:1200px) and (max-width: '.($custom_width_extra-1).'px) {.'.$uid.' .container.custom_width{width:100%;} }';
			}
		}
		else if($container_size == 'container custom_width_perc'){
			$css .= zn_smart_slider_css($this->opt( 'custom_width_percent', 100 ), '.'.$uid.' .container.custom_width_perc', 'width', '%');
		}
		if( $this->opt('sec_height','auto') == 'custom_height' ) {
			$selector = '.'.$uid.' .zn-section-height--custom_height';
			$height_value = $this->opt( 'custom_height' );
			$alignment    = $this->opt('valign', 'top');
			$css .= zn_smart_slider_css( $height_value, $selector );

			// IE needs to have a height in order to properly align items
			if ( ! empty($height_value['lg']) && isset( $height_value['properties'] ) && 'min-height' === $height_value['properties'] && 'middle' === $alignment ) {
				// Remove the properties key so that the function will use the provided unit
				unset( $height_value['properties'] );
				$css .= ".is-ie11 {$selector} { height:1px; }";
			}

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

		return $css;
	}


}

?>
