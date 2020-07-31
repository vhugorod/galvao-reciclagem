<?php if(! defined('ABSPATH')){ return; }
/*
Name: iOS Slider v2
Description: Create and display an iOS Slider element
Class: TH_IosSlider2
Category: header, Fullwidth
Level: 1
Scripts: true
*/

class TH_IosSlider2 extends ZnElements
{

	public static function getName(){
		return __( "iOS Slider", 'zn_framework' );
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$extra_options = array (
			"name"           => __( "Slides", 'zn_framework' ),
			"description"    => __( "Here you can create your iOS Slider Slides.", 'zn_framework' ),
			"id"             => "single_iosslider",
			"std"            => "",
			"type"           => "group",
			"add_text"       => __( "Slide", 'zn_framework' ),
			"remove_text"    => __( "Slide", 'zn_framework' ),
			"group_sortable" => true,
			"element_title"  => "title",
			"subelements"    => array (
				'has_tabs' => true,
				'media'    => array(
					'title'   => 'Slide Media',
					'options' => array(

						array (
							"name"        => __( "Slide Type", 'zn_framework' ),
							"description" => __( "Please select the slide type", 'zn_framework' ),
							"id"          => "type",
							"std"         => "image",
							"type"        => "select",
							"options"     => array (
								'image'         => __( "Image", 'zn_framework' ),
								'video_self'    => __( "Self Hosted Video", 'zn_framework' ),
								'video_youtube' => __( "Youtube Video", 'zn_framework' ),
								'video_vimeo'   => __( "Vimeo Video", 'zn_framework' ),
								'embed_iframe'  => __( "Embed Iframe (Vimeo etc.)", 'zn_framework' )
							)
						),

						array (
							"name"        => __( "Slide Image", 'zn_framework' ),
							"description" => __( "Select an image for this slide", 'zn_framework' ),
							"id"          => "image",
							"std"         => "",
							"type"        => "media",
							"supports"    => "id",
							"dependency"  => array( 'element' => 'type' , 'value'=> array('image') )
						),

						array (
							"name"        => __( "Slide Image - Vertical Position", 'zn_framework' ),
							"description" => __( "Select the vertical position of the image. Your image might be bigger in height than the actual slider's height so you can select which part should be visible", 'zn_framework' ),
							"id"          => "image_vert_pos",
							"std"         => "center",
							"type"        => "select",
							"options"     => array (
								"top"    => __( "Top", 'zn_framework' ),
								"center" => __( "Center", 'zn_framework' ),
								"bottom" => __( "Bottom", 'zn_framework' )
							),
							"dependency"  => array( 'element' => 'type' , 'value'=> array('image') )
						),

						array (
							"name"        => __( "Youtube ID", 'zn_framework' ),
							"description" => __( "Add an Youtube ID.", 'zn_framework' ),
							"id"          => "vd_yt",
							"std"         => "",
							"type"        => "text",
							"placeholder" => "ex: tR-5AZF9zPI",
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_youtube') )
						),

						// Vimeo video
						array (
							"name"        => __( "Vimeo ID", 'zn_framework' ),
							"description" => __( "Add an Vimeo ID", 'zn_framework' ),
							"id"          => "vd_vm",
							"std"         => "",
							"type"        => "text",
							"placeholder" => "ex: 2353562345",
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_vimeo') )
						),

						// Embed Iframe
						array (
							"name"        => __( "Embed Video Iframe (URL)", 'zn_framework' ),
							"description" => __( "Add the full URL for Youtube, Vimeo or DailyMotion. Please remember these videos will not be autoplayed on mobile devices.", 'zn_framework' ),
							"id"          => "vd_embed_iframe",
							"std"         => "",
							"type"        => "text",
							"placeholder" => "ex: https://vimeo.com/17874452",
							"dependency"  => array( 'element' => 'type' , 'value'=> array('embed_iframe') )
						),

						/** LOCAL VIDEO **/
						array(
							'id'          => 'vd_self_mp4',
							'name'        => __('Mp4 video source', 'zn_framework'),
							'description' => __('Add the MP4 video source for your local video', 'zn_framework'),
							'type'        => 'media_upload',
							'std'         => '',
							'data'  => array(
								'type' => 'video/mp4',
								'button_title' => __('Add / Change mp4 video', 'zn_framework'),
							),
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_self') )
						),
						array(
							'id'          => 'vd_self_ogg',
							'name'        => __('Ogg/Ogv video source', 'zn_framework'),
							'description' => __('Add the OGG video source for your local video', 'zn_framework'),
							'type'        => 'media_upload',
							'std'         => '',
							'data'  => array(
								'type' => 'video/ogg',
								'button_title' => __('Add / Change ogg video', 'zn_framework'),
							),
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_self') )
						),
						array(
							'id'          => 'self_webm',
							'name'        => __('Webm video source', 'zn_framework'),
							'description' => __('Add the WEBM video source for your local video', 'zn_framework'),
							'type'        => 'media_upload',
							'std'         => '',
							'data'  => array(
								'type' => 'video/webm',
								'button_title' => __('Add / Change webm video', 'zn_framework'),
							),
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_self') )
						),
						array(
							'id'          => 'vd_vp',
							'name'        => __('Video poster', 'zn_framework'),
							'description' => __('Using this option you can add your desired video poster that will be shown on unsuported devices (mobiles, tablets). ', 'zn_framework'),
							'type'        => 'media',
							'std'         => '',
							'class'       => 'zn_full',
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_self','video_youtube','embed_iframe') )
						),
						array(
							'id'          => 'vd_autoplay',
							'name'        => __('Autoplay video?', 'zn_framework'),
							'description' => __('Enable autoplay for video? Remember, this option only applies on desktop devices, not mobiles or tablets.', 'zn_framework'),
							'type'        => 'select',
							'std'         => 'yes',
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_self','video_youtube','embed_iframe') ),
							"options"     => array (
								"yes" => __( "Yes", 'zn_framework' ),
								"no"  => __( "No", 'zn_framework' )
							),
							"class"       => "zn_input_xs"
						),

						array(
							'id'          => 'vd_loop',
							'name'        => __('Loop video?', 'zn_framework'),
							'description' => __('Enable looping the video? Remember, this option only applies on desktop devices, not mobiles or tablets.', 'zn_framework'),
							'type'        => 'select',
							'std'         => 'yes',
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_self','video_youtube','embed_iframe') ),
							"options"     => array (
								"yes" => __( "Yes", 'zn_framework' ),
								"no"  => __( "No", 'zn_framework' )
							),
							"class"       => "zn_input_xs"
						),

						array(
							'id'          => 'vd_muted',
							'name'        => __('Start mute?', 'zn_framework'),
							'description' => __('Start the video with muted audio?', 'zn_framework'),
							'type'        => 'select',
							'std'         => 'yes',
							"dependency"  => array( 'element' => 'type' , 'value'=> array('video_self','video_youtube') ),
							"options"     => array (
								"yes" => __( "Yes", 'zn_framework' ),
								"no"  => __( "No", 'zn_framework' )
							),
							"class"       => "zn_input_xs"
						),


						array(
							'id'          => 'overlay',
							'name'        => __('Background colored overlay', 'zn_framework'),
							'description' => __('Add slide color overlay over the image or video to darken or enlight?', 'zn_framework'),
							'type'        => 'select',
							'std'         => '0',
							"options"     => array (
								"0" => __( "Disabled", 'zn_framework' ),
								"1" => __( "Normal color", 'zn_framework' ),
								"2" => __( "Horizontal gradient", 'zn_framework' ),
								"3" => __( "Vertical gradient", 'zn_framework' ),
							)
						),

						array(
							'id'          => 'overlay_color',
							'name'        => __('Overlay background color', 'zn_framework'),
							'description' => __('Pick a color', 'zn_framework'),
							'type'        => 'colorpicker',
							'std'         => '',
							'alpha'       => 'true',
							"dependency"  => array( 'element' => 'overlay' , 'value'=> array('1', '2', '3') ),
						),

						array(
							'id'          => 'overlay_color_gradient',
							'name'        => __('Overlay Gradient 2nd Bg. Color', 'zn_framework'),
							'description' => __('Pick a color', 'zn_framework'),
							'type'        => 'colorpicker',
							'std'         => '',
							'alpha'       => 'true',
							"dependency"  => array( 'element' => 'overlay' , 'value'=> array('2', '3') ),
						),

					),
				),
				'caption' => array(
					'title' => 'Slide caption',
					'options' => array(

						array(
							"name"        => __( "Slide Caption Options", 'zn_framework' ),
							"id"          => "scapt_title",
							"std"         => "",
							"type"        => "zn_title",
							"class"       => "zn-custom-title-xl"
						),

						array (
							"name"        => __( "Slider Caption Style", 'zn_framework' ),
							"description" => __( "Select the desired style for this slide. !!! STYLE PREVIEW LINK TO ADD !!!", 'zn_framework' ),
							"id"          => "caption_style",
							"std"         => "",
							"type"        => "select",
							"options"     => array (
								"style1"       => __( "Style 1", 'zn_framework' ),
								"style2"       => __( "Style 2", 'zn_framework' ),
								"style3"       => __( "Style 3", 'zn_framework' ),
								"style3 s3ext" => __( "Style 3s", 'zn_framework' ),
								"style4"       => __( "Style 4", 'zn_framework' ),
								"style4 s4ext" => __( "Style 4s", 'zn_framework' ),
								"style5"       => __( "Style 5", 'zn_framework' ),
								"style6"       => __( "Style 6", 'zn_framework' )
							),
						),

						array (
							"name"        => __( "POPUP Video Youtube ID", 'zn_framework' ),
							"description" => __( "Add an Youtube ID to be displayed inside the popup", 'zn_framework' ),
							"id"          => "s6_yt",
							"std"         => "",
							"type"        => "text",
							"placeholder" => "ex: tR-5AZF9zPI",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style6') )
						),

						array (
							"name"        => __( "Slide main title", 'zn_framework' ),
							"description" => __( "Enter a main title for this slide. Accepts HTML.", 'zn_framework' ),
							"id"          => "title",
							"std"         => "",
							"type"        => "text",
							"class"       => "zn_input_xl"
						),
						array (
							"name"        => __( "Add square box?", 'zn_framework' ),
							"description" => __( "Add a dark square box behind the main title?", 'zn_framework' ),
							"id"          => "s5_sqbox",
							"std"         => "0",
							"type"        => "zn_radio",
							"options"     => array (
								"1" => __( "Yes", 'zn_framework' ),
								"0"  => __( "No", 'zn_framework' )
							),
							"class"        => "zn_radio--yesno zn_input_xs",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style5') )
						),
						array (
							"name"        => __( "Add Separator Line?", 'zn_framework' ),
							"description" => __( "Add a fancy separator line under the main title?", 'zn_framework' ),
							"id"          => "sep_line",
							"std"         => "0",
							"type"        => "zn_radio",
							"options"     => array (
								"1" => __( "Yes", 'zn_framework' ),
								"0"  => __( "No", 'zn_framework' )
							),
							"class"        => "zn_radio--yesno zn_input_xs",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style5') )
						),
						array (
							"name"        => __( "Slide big title", 'zn_framework' ),
							"description" => __( "Enter a title for this slide. Accepts HTML.", 'zn_framework' ),
							"id"          => "b_title",
							"std"         => "",
							"type"        => "text",
							"class"       => "zn_input_xl",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') )
						),
						array (
							"name"        => __( "Slide small title", 'zn_framework' ),
							"description" => __( "Enter a small title for this slide. Accepts HTML.", 'zn_framework' ),
							"id"          => "s_title",
							"std"         => "",
							"type"        => "text",
							"class"       => "zn_input_xl",
							//"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') )
						),
						array (
							"name"        => __( "Slide small top text", 'zn_framework' ),
							"description" => __( "Enter a text that will be displayed before the main title. Accepts HTML.", 'zn_framework' ),
							"id"          => "s_title_top",
							"std"         => "",
							"type"        => "text",
							"class"       => "zn_input_xl",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style5') )
						),
						array (
							"name"        => __( "Slide link", 'zn_framework' ),
							"description" => __( "Here you can add a link to your slide", 'zn_framework' ),
							"id"          => "link",
							"std"         => "",
							"type"        => "link",
							"options"     => zn_get_link_targets(),
							"class"       => "zn_link_styled",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') )
						),

						array (
							"name"        => __( "Button Text", 'zn_framework' ),
							"description" => __( "Add a text inside this button.", 'zn_framework' ),
							"id"          => "btn_text",
							"std"         => "",
							"type"        => "text",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') )
						),

						array (
							"name"        => __( "2nd Button Link", 'zn_framework' ),
							"description" => __( "Here you can add link for a second button", 'zn_framework' ),
							"id"          => "link2",
							"std"         => "",
							"type"        => "link",
							"options"     => zn_get_link_targets(),
							"class"       => "zn_link_styled",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style5') )
						),

						array (
							"name"        => __( "2nd Button Text Link", 'zn_framework' ),
							"description" => __( "Add a text inside this button.", 'zn_framework' ),
							"id"          => "btn2_text",
							"std"         => "",
							"type"        => "text",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style5') )
						),

						array (
							"name"        => __( "Buttons sizes", 'zn_framework' ),
							"description" => __( "You can select the sizes of the buttons", 'zn_framework' ),
							"id"          => "io_btn_sizes",
							"std"         => "",
							"type"        => "select",
							"options"     => array (
								''          => __( "Default", 'zn_framework' ),
								'btn-lg'    => __( "Large", 'zn_framework' ),
								'btn-md'    => __( "Medium", 'zn_framework' ),
								'btn-sm'    => __( "Small", 'zn_framework' ),
								'btn-xs'    => __( "Extra small", 'zn_framework' ),
							),
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style3','style3 s3ext','style5') )
						),

						array (
							"name"        => __( "Link Image?", 'zn_framework' ),
							"description" => __( "Select yes if you want to also link the slide image.", 'zn_framework' ),
							"id"          => "link_image",
							"std"         => "no",
							"type"        => "zn_radio",
							"options"     => array (
								"yes" => __( "Yes", 'zn_framework' ),
								"no"  => __( "No", 'zn_framework' )
							),
							"class"        => "zn_radio--yesno zn_input_xs",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') )
						),
						array (
							"name"        => __( "Slider Caption Entry Animation/Position", 'zn_framework' ),
							"description" => __( "Select the desired entry Animation/Position for this slide.", 'zn_framework' ),
							"id"          => "caption_pos",
							"std"         => "",
							"type"        => "select",
							"options"     => array (
								"fromleft"  => __( "Slide from Left", 'zn_framework' ),
								"fromright" => __( "Slide from Right", 'zn_framework' ),
								"zoomin"    => __( "Zoom In", 'zn_framework' ),
								"sfb"       => __( "Slide from bottom", 'zn_framework' )
							),
							"class"       => "",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') )
						),
						array (
							"name"        => __( "Slider Caption Horizontal Position", 'zn_framework' ),
							"description" => __( "Select the desired horizontal position for this slide. Center only works for Style 5 and 6.", 'zn_framework' ),
							"id"          => "caption_pos_horiz",
							"std"         => "left",
							"type"        => "select",
							"options"     => array (
								"left"       => __( "Left (default)", 'zn_framework' ),
								"center"     => __( "Center (Only for caption style 5 and 6)", 'zn_framework' ),
								"right"      => __( "Right", 'zn_framework' )
							),
							"class"       => "",
							// "dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style5') )
						),
						array (
							"name"        => __( "Slider Caption Vertical Position", 'zn_framework' ),
							"description" => __( "Select the desired vertical position for this slide.", 'zn_framework' ),
							"id"          => "caption_pos_vert",
							"std"         => "bottom",
							"type"        => "select",
							"options"     => array (
								"bottom"       => __( "Bottom (default)", 'zn_framework' ),
								"middle"       => __( "Middle", 'zn_framework' )
							),
							"class"       => ""
						),

						array (
							"name"        => __( "Add image boxes?", 'zn_framework' ),
							"description" => __( "This feature will enable displaying multiple small images/thumbs.", 'zn_framework' ),
							"id"          => "imgboxes",
							"std"         => "0",
							"type"        => "zn_radio",
							"options"     => array (
								"1" => __( "Yes", 'zn_framework' ),
								"0"  => __( "No", 'zn_framework' )
							),
							"class"        => "zn_radio--yesno zn_input_xs",
							"dependency"  => array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') )
						),
						array (
							"name"        => __( "Image Box 1", 'zn_framework' ),
							"description" => __( "Select an image for this Image Box", 'zn_framework' ),
							"id"          => "imgboxes_i1_src",
							"std"         => "",
							"type"        => "media",
							"supports"    => "id",
							"dependency"  => array(
								array( 'element' => 'imgboxes' , 'value'=> array('1') ),
								array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') ),
							),
						),

						array (
							"name"        => __( "Image Box 1 - URL", 'zn_framework' ),
							"description" => __( "Add an url for this Image Box", 'zn_framework' ),
							"id"          => "imgboxes_i1_url",
							"std"         => "",
							"type"        => "link",
							"options"     => zn_get_link_targets(),
							"class"       => "zn_link_styled",
							"dependency"  => array(
								array( 'element' => 'imgboxes' , 'value'=> array('1') ),
								array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') ),
							),
						),
						array (
							"name"        => __( "Image Box 2", 'zn_framework' ),
							"description" => __( "Select an image for this Image Box", 'zn_framework' ),
							"id"          => "imgboxes_i2_src",
							"std"         => "",
							"type"        => "media",
							"supports"    => "id",
							"dependency"  => array(
								array( 'element' => 'imgboxes' , 'value'=> array('1') ),
								array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') ),
							),
						),
						array (
							"name"        => __( "Image Box 2 - URL", 'zn_framework' ),
							"description" => __( "Add an url for this Image Box", 'zn_framework' ),
							"id"          => "imgboxes_i2_url",
							"std"         => "",
							"type"        => "link",
							"options"     => zn_get_link_targets(),
							"class"       => "zn_link_styled",
							"dependency"  => array(
								array( 'element' => 'imgboxes' , 'value'=> array('1') ),
								array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') ),
							),
						),
						array (
							"name"        => __( "Image Box 3", 'zn_framework' ),
							"description" => __( "Select an image for this Image Box", 'zn_framework' ),
							"id"          => "imgboxes_i3_src",
							"std"         => "",
							"type"        => "media",
							"supports"    => "id",
							"dependency"  => array(
								array( 'element' => 'imgboxes' , 'value'=> array('1') ),
								array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') ),
							),
						),
						array (
							"name"        => __( "Image Box 3 - URL", 'zn_framework' ),
							"description" => __( "Add an url for this Image Box", 'zn_framework' ),
							"id"          => "imgboxes_i3_url",
							"std"         => "",
							"type"        => "link",
							"options"     => zn_get_link_targets(),
							"class"       => "zn_link_styled",
							"dependency"  => array(
								array( 'element' => 'imgboxes' , 'value'=> array('1') ),
								array( 'element' => 'caption_style' , 'value'=> array('style1','style2','style3','style3 s3ext','style4','style4 s4ext','style5') ),
							),
						),
					)
				)
			)
		);

		return array (
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array(
						'name'        => __( 'Custom Height', 'zn_framework'),
						'description' => __( 'Choose the desired height for this element. <br>*TIP: Use 100vh to have a full-height element.', 'zn_framework' ),
						'id'          => 'height',
						'type'        => 'smart_slider',
						'std'         => array(
							'lg' => '800'
						),
						'helpers' => array(
							'min' => '0',
							'max' => '1080'
						),
						'supports' => array('breakpoints'),
						'units' => array('px', 'vh', '%'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. '.zn-iosSliderEl',
							'css_rule'  => 'height',
							'unit'      => 'px'
						),
					),

					array (
						"name"        => __( "Autoplay?", 'zn_framework' ),
						"description" => __( "Autoplay slider?", 'zn_framework' ),
						"id"          => "autoplay",
						"std"         => "1",
						"value"       => "1",
						"type"        => "toggle2",
					),

					array (
						"name"        => __( "Loop Slides?", 'zn_framework' ),
						"description" => __( "Choose if you want your slides to loop.", 'zn_framework' ),
						"id"          => "infinite",
						"std"         => "1",
						"value"       => "1",
						"type"        => "toggle2",
					),

					array (
						"name"        => __( "Transition Speed", 'zn_framework' ),
						"description" => __( "Enter a numeric value for the transition speed (default: 5000 milliseconds)", 'zn_framework' ),
						"id"          => "autoplaySpeed",
						"std"         => "5000",
						"type"        => "text",
						"class"       => "zn_input_xs"
					),

					array (
						"name"        => __( "Slider Dots", 'zn_framework' ),
						"description" => __( "Choose the type of dots navigation.", 'zn_framework' ),
						"id"          => "navigation",
						"std"         => "bullets",
						"type"        => "select",
						"options"     => array (
							"off"      => __( "Disabled", 'zn_framework' ),
							"bullets"  => __( "Circle Dots", 'zn_framework' ),
							"bullets2" => __( "Thin Stripes", 'zn_framework' ),
						),
					),

					array (
						"name"        => __( "Arrow Navigation", 'zn_framework' ),
						"description" => __( "Enable arrows navigation.", 'zn_framework' ),
						"id"          => "arrow_navigation",
						"std"         => "1",
						"value"       => "1",
						"type"        => "toggle2",
					),

					array (
						"name"        => __( "Enable Slide Dragging", 'zn_framework' ),
						"description" => __( "Enable Slides Dragging with your mouse cursor?", 'zn_framework' ),
						"id"          => "dragging",
						"std"         => "1",
						"value"       => "1",
						"type"        => "toggle2",
					),
				)
			),

			'styling' => array(
				'title' => 'Styles Options',
				'options' => array(

					array (
						"name"        => __( "Add Fade Effect?", 'zn_framework' ),
						"description" => __( "Choose if you want to add a bottom fade effect to your slider.", 'zn_framework' ),
						"id"          => "fade",
						"std"         => "0",
						"type"        => "zn_radio",
						"options"     => array ( "1" => __( "Yes", 'zn_framework' ), "0" => __( "No", 'zn_framework' ) ),
						"class"       => "zn_radio--yesno zn_input_xs"
					),

					array(
						'id'          => 'fade_color',
						'name'        => __('Color for the fading background', 'zn_framework'),
						'description' => __('Pick a color', 'zn_framework'),
						'type'        => 'colorpicker',
						'std'         => '#f5f5f5',
						"dependency"  => array( 'element' => 'fade' , 'value'=> array('1') ),
					),

					array (
						"name"        => __( "Add scrolling effect?", 'zn_framework' ),
						"description" => sprintf(
							'%s <br><strong style="color: #9B4F4F">%s</strong> ',
							__( "Choose if you want the slider to have a scrolling effect on the entire slider and captions. The captions will fade while the slider will slowly move downwards upon scrolling.", 'zn_framework' ),
							__( "Use with caution because it's an intensive CPU consumer!", 'zn_framework' ) ),
						"id"          => "scrolling_effect",
						"std"         => "0",
						"type"        => "zn_radio",
						"options"     => array ( "1" => __( "Yes", 'zn_framework' ), "0" => __( "No", 'zn_framework' ) ),
						"class"        => "zn_radio--yesno zn_input_xs"
					),

					// Bottom masks overrides
					array (
						"name"        => __( "Bottom masks override", 'zn_framework' ),
						"description" => __( "The new masks are svg based, vectorial and color adapted.", 'zn_framework' ),
						"id"          => "bottom_mask",
						"std"         => "none",
						"type"        => "select",
						"options"     => zn_get_masks(),
					),

					array(
						'name'        => __('Bottom Mask Background Color', 'zn_framework'),
						'description' => __('If you need the mask to have a different color than the main site background, please choose the color. Usually this color is needed when the next section, under this one has a different background color.', 'zn_framework'),
						'id'          => 'bottom_mask_bg',
						'type'        => 'colorpicker',
						'std'         => '',
						"dependency"  => array('element' => 'bottom_mask' , 'value'=> zn_get_masks_deps() ),
					),
				),
			),

			'slides' => array(
				'title' => 'Slides options',
				'options' => array(
					$extra_options,
				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#Hgwzjxw7ng4') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/ios-slider/') ),
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);
	}


	/**
	 * Load dependant resources
	 */
	function scripts(){
		wp_enqueue_script( 'slick');
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		$css = '';
		$uid = $this->data['uid'];

		$selector = '.'.$uid.'.zn-iosSliderEl, .'.$uid.' .zn-iosSlider-scroll';
		$css .= zn_smart_slider_css( $this->opt( 'height', '800' ), $selector );

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

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-iosSliderEl';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		$slides = $this->opt('single_iosslider','');
		if( empty($slides) ) return;

		// Faded Slider
		if ( $this->opt('fade',0) == 1 ) {
			$classes[] = 'zn-iosSl--faded';
		}

		// bottom mask
		$bottom_mask = $this->opt('bottom_mask','none');
		$classes[] = $bottom_mask != 'none' ? 'maskcontainer--'.$bottom_mask : '';

		// Check if has bullets:
		$navigation = $this->opt('navigation', 'bullets');
		$arr_navigation = $this->opt('arrow_navigation', '1') == '1';

		// Scrolling Effect
		$is_screffect = $this->opt('scrolling_effect',0 ) == 1;

		?>

		<div class="<?php echo implode(" ", $classes); ?>" <?php echo implode(" ", $attributes); ?>>

			<?php
				if($is_screffect){
					echo '<div class="zn-elmScroll js-doParallax">';
					echo '<div class="zn-elmScroll-inner">';
				}
			?>

			<div class="zn-iosSl-loader"></div>

			<?php

			$slick_attributes = array(

				"infinite"         => false,
				"slidesToShow"     => 1,
				"slidesToScroll"   => 1,
				"autoplay"         => $this->opt('autoplay', 1) == 1 ? true : false,
				"autoplaySpeed"    => $this->opt('autoplaySpeed', 5000),
				"easing"           => 'easeOutExpo',
				"dragging"         => $this->opt('dragging',1) == 1 ? true : false,
				"loadingContainer" => '.'.$uid,

				"arrows"           => false,
				"dots"             => false,

			);

			if( $arr_navigation ){
				$slick_attributes["arrows"] = true;
				$slick_attributes["appendArrows"] = '.'.$uid.' .zn-iosSl-nav';
				$slick_attributes["prevArrow"] = '.'.$uid.' .znSlickNav-prev';
				$slick_attributes["nextArrow"] = '.'.$uid.' .znSlickNav-next';
			}

			if($navigation != 'disabled'){
				$slick_attributes['dots'] = true;
				$slick_attributes['appendDots'] = '.'.$uid.' .zn-iosSl-dots';
			}

			// This will fix the Ios Slider when using only one slide
			if ( count( $slides ) > 1 ) {
				$slick_attributes['infinite'] = $this->opt('infinite', 1) == 1;
			}

			?>

			<div class="zn-iosSlider js-ios-slick" data-slick='<?php echo json_encode($slick_attributes) ?>'>

				<?php

				if ( !empty( $slides ) ) {
					foreach ( $slides as $i => $slide ) {

						$link = $slide['link'];

						// In case it has thumbs
						$slide_attr = array();

						/**
						 * Main Link
						 */
						$img_slide_link['start'] = $img_slide_link['end'] = '';
						if ( ! empty( $slide['link_image'] ) && $slide['link_image'] == 'yes' ) {
							$img_slide_link = zn_extract_link( $link, 'zn-iosSl-imageLink');
						}

						/**
						 * Media
						 */
						$media_markup = '';

						// Slide type
						$slideType = isset ( $slide['type'] ) && ! empty ( $slide['type'] ) ? $slide['type'] : 'image' ;

						/**
						 * Image
						 */
						if($slideType == 'image' && isset($slide['image']) && !empty($slide['image']) ) {

							$image_vert_pos = 'zn-iosSl-img--'.( (isset($slide['image_vert_pos']) && !empty($slide['image_vert_pos']) ) ? $slide['image_vert_pos'] : 'center');

							$media_markup .= $img_slide_link['start'];
							$media_markup .= wp_get_attachment_image( $slide['image'], 'full', false, array('class' => 'zn-iosSl-img cover-fit-img '.$image_vert_pos) );
							$media_markup .= $img_slide_link['end'];

						}

						/**
						 * Video
						 */
						else if($slideType == 'video_self' || $slideType == 'video_youtube' || $slideType == 'video_vimeo') {

							$media_markup .= znb_VideoBg(array(
								'video_class'            => 'iosslider-video',
								'source_type'            => $slideType,
								'source_vd_yt'           => isset($slide['vd_yt']) ? 'https://www.youtube.com/watch?v=' . $slide['vd_yt'] : '',
								'source_vd_vm'           => isset($slide['vd_vm']) ? 'https://vimeo.com/' . $slide['vd_vm'] : '',
								'source_vd_self_mp4'     => isset($slide['vd_self_mp4']) ? $slide['vd_self_mp4'] : '',
								'source_vd_self_ogg'     => isset($slide['vd_self_ogg']) ? $slide['vd_self_ogg'] : '',
								'source_vd_self_webm'    => isset($slide['self_webm']) ? $slide['self_webm'] : '',
								'source_vd_vp'           => isset($slide['vd_vp']) ? $slide['vd_vp'] : '',
								'source_vd_autoplay'     => isset($slide['vd_autoplay']) ? $slide['vd_autoplay'] : '',
								'source_vd_loop'         => isset($slide['vd_loop']) ? $slide['vd_loop'] : '',
								'source_vd_muted'        => isset($slide['vd_muted']) ? $slide['vd_muted'] : '',
								'source_vd_controls'     => isset($slide['vd_controls']) ? $slide['vd_controls'] : '',
								'source_vd_controls_pos' => (!empty($slide['vd_controls_pos']) ? $slide['vd_controls_pos'] : 'bottom-right' ),
								'mobile_play'            => 'no',
								'video_overlay'          => 1,
							) );

							$slide_attr[] = 'data-video-slide="1"';
						}

						/**
						 * Embed Iframe
						 */
						else if($slideType == 'embed_iframe') {

							$vd_embed_iframe = isset($slide['vd_embed_iframe']) ? $slide['vd_embed_iframe'] : '';

							if( !empty($vd_embed_iframe) ) {

								$vd_vp = isset($slide['vd_vp']) ? $slide['vd_vp'] : '';

								$video_attributes = array(
									'loop' => isset($slide['vd_loop']) && !empty($slide['vd_loop']) && $slide['vd_loop'] == 'yes' ? 1 : 0,
									'autoplay' => isset($slide['vd_autoplay']) && !empty($slide['vd_autoplay']) && $slide['vd_autoplay'] == 'yes' ? 1 : 0
								);
								// Source Video
								echo '<div class="kl-bg-source__iframe-wrapper">';
									echo '<div class="kl-bg-source__iframe iframe-valign iframe-halign">';
										echo get_video_from_link( $vd_embed_iframe, 'no-adjust', '100%', null, $video_attributes );
										if(!empty($vd_vp)) {
											echo '<div style="background-image:url('.$vd_vp.');" class="kl-bg-source__iframe-poster"></div>';
										}
									echo '</div>';
								echo '</div>';
							}
						}

						/**
						 * Background Overlay
						 */
						$overlay_markup = '';
						if ( isset ( $slide['overlay'] ) && $slide['overlay'] != 0 ) {

							$overlay_color = $slide['overlay_color'];
							$ovstyle = 'background-color:'.$overlay_color;

							// Gradient
							if ( $slide['overlay'] == 2 || $slide['overlay'] == 3 ) {

								$gr_overlay_color = $slide['overlay_color_gradient'];

								// Gradient Horizontal
								if ( $slide['overlay'] == 2 ) {
									$ovstyle = 'background: -webkit-linear-gradient(left, ' . $overlay_color . ' 0%,' . $gr_overlay_color . ' 100%); background: linear-gradient(to right, ' . $overlay_color . ' 0%,' . $gr_overlay_color . ' 100%); ';
								}
								// Gradient Vertical
								if ( $slide['overlay'] == 3 ) {
									$ovstyle = 'background: -webkit-linear-gradient(top,  ' . $overlay_color . ' 0%,' . $gr_overlay_color . ' 100%); background: linear-gradient(to bottom,  ' . $overlay_color . ' 0%,' . $gr_overlay_color . ' 100%); ';
								}
							}
							$overlay_markup = '<div class="zn-iosSl-overlay" style="'.$ovstyle.'"></div>';
						}

						/**
						 * Fade Mask
						 */
						$caption_markup = '';

						if ( isset ( $options['fade'] ) && ! empty ( $options['fade'] ) ) {
							$fadeColor = isset ( $options['fade_color'] ) && ! empty ( $options['fade_color'] ) ? $options['fade_color'] : '#f5f5f5';
							$caption_markup .= '<div class="zn-iosSl-fadeMask" style="background: -webkit-linear-gradient(top, ' . zn_hex2rgba_str($fadeColor, 0) . ' 0%,' . $fadeColor . ' 100%); background: linear-gradient(to bottom, ' . zn_hex2rgba_str($fadeColor, 0) . ' 0%,' . $fadeColor . ' 100%);"></div>';
						}

						/**
						 * Caption
						 */
						$caption_classes = array();

						$caption_style = isset ( $slide['caption_style'] ) && ! empty ( $slide['caption_style'] ) ? $slide['caption_style'] : 'style1';
						$caption_position = isset($slide['caption_pos']) && ! empty ($slide['caption_pos']) ? $slide['caption_pos'] : 'fromleft';
						$caption_position_vert = isset ( $slide['caption_pos_vert'] ) && ! empty ( $slide['caption_pos_vert'] ) ? $slide['caption_pos_vert'] : 'bottom';
						$caption_position_horiz = isset ( $slide['caption_pos_horiz'] ) && ! empty ( $slide['caption_pos_horiz'] ) ? $slide['caption_pos_horiz'] : 'left';

						$caption_classes[] = 'container zn-iosSl-caption';
						$caption_classes[] = 'zn-iosSl-caption--' . $caption_style;
						$caption_classes[] = 'zn-iosSl-caption--effect-' . $caption_position;
						$caption_classes[] = 'zn-iosSl-caption--hAlign-' . $caption_position_horiz;
						$caption_classes[] = 'zn-iosSl-caption--vAlign-' . $caption_position_vert;

						$caption_markup .= '<div class="'. implode(' ', $caption_classes) .'">';

						$bigTitle = isset ( $slide['b_title'] ) && ! empty ( $slide['b_title'] ) ? $slide['b_title'] : '';
						$smallTitle = isset ( $slide['s_title'] ) && ! empty ( $slide['s_title'] ) ? $slide['s_title'] : '';

						// Slide TOP!! SMALL TITLE (for style5)
						if( $caption_style != 'style6' ){
							if ( isset ( $slide['s_title_top'] ) && ! empty ( $slide['s_title_top'] ) && $caption_style == 'style5' ) {
								$caption_markup .= '<h4 class="zn-iosSl-titleTop">' . $slide['s_title_top'] . '</h4>';
							}
						}

						// Slide Main TITLE
						if ( isset ( $slide['title'] ) && ! empty ( $slide['title'] ) ) {

							$squarebox = isset ( $slide['s5_sqbox'] ) && ! empty ( $slide['s5_sqbox'] ) && $caption_style != 'style6' ? '<span class="zn-iosSl-sqbox"></span>':'';
							$has_squarebox = ! empty ( $squarebox ) ? 'zn-iosSl-mainTitle--hasSqBox':'';

							$has_titlebig = !empty($bigTitle) ? 'zn-iosSl-mainTitle--hasTitleBig':'';
							$has_sepline = isset ( $slide['sep_line'] ) && ! empty ( $slide['sep_line'] ) ? 'zn-iosSl-hasLine':'';

							$caption_markup .= '<h2 class="zn-iosSl-mainTitle '.$has_titlebig.' '.$has_squarebox.' '.$has_sepline.'" '.WpkPageHelper::zn_schema_markup('title').'>';
							$caption_markup .= $squarebox;
							$caption_markup .= '<span>' . $slide['title'] . '</span></h2>';
						}

						if( $caption_style != 'style6' ) {

							// Separator line
							if ( isset ( $slide['sep_line'] ) && ! empty ( $slide['sep_line'] ) && $caption_style == 'style5' ) {
								$has_titlebig = !empty($bigTitle) ? 'zn-iosSl-mainTitle--hasTitleBig':'';
								$has_imageboxes = isset ( $slide['imgboxes'] ) && ! empty ( $slide['imgboxes'] ) ? 'zn-iosSl-mainTitle--hasImageBoxes':'';
								$caption_markup .= '<div class="zn-iosSl-separatorLine '.$has_titlebig.' '.$has_imageboxes.'"><div class="zn-iosSl-separatorLine-inner"><span></span></div></div>';
							}

							// Slide SMALL TITLE (for style3 extended only)
							if ( ! empty ( $smallTitle ) && $caption_style == 'style3 s3ext' ) {
								$caption_markup .= '<h4 class="zn-iosSl-smallTitle" '.WpkPageHelper::zn_schema_markup('subtitle').'>' . $smallTitle . '</h4>';
							}

							// Slide BIG TITLE
							if ( !empty($bigTitle) ) {
								$caption_markup .= '<h3 class="zn-iosSl-bigTitle" '.WpkPageHelper::zn_schema_markup('subtitle').'>' . $bigTitle . '</h3>';
							}

							$buttons_sizes = isset($slide['io_btn_sizes']) && !empty($slide['io_btn_sizes']) ? $slide['io_btn_sizes'] : '';

							// Links for style 4
							if ( ( $caption_style == 'style4' || $caption_style == 'style4 s4ext' ) ) {
								$no_titlebig = !empty($bigTitle) ? '':'no_titlebig';
								$s4_link = zn_extract_link( $link, 'zn-iosSl-more '.$no_titlebig);
								if(!empty($s4_link['start'])){
									$caption_markup .= $s4_link['start'];
									$caption_markup .= isset($slide['btn_text']) ? $slide['btn_text'] : '';
									$caption_markup .= $s4_link['end'];
								}
							}
							// Links for Style 5
							elseif ( $caption_style == 'style5' ) {
								$caption_markup .= '<div class="zn-iosSl-more">';

									$s5_link = zn_extract_link( $link, 'btn btn-fullcolor '.$buttons_sizes);
									if( ! empty ( $s5_link['start'] ) ){
										$caption_markup .= $s5_link['start'];
										$caption_markup .= isset($slide['btn_text']) ? $slide['btn_text'] : '';
										$caption_markup .= $s5_link['end'];
									}

									// Secondary link
									if( isset($slide['link2']['url']) && !empty($slide['link2']['url']) ){
										$s5_sec_link = zn_extract_link( $slide['link2'], 'btn btn-lined '.$buttons_sizes);
										$caption_markup .= $s5_sec_link['start'];
										$caption_markup .= isset($slide['btn2_text']) ? $slide['btn2_text'] : '';
										$caption_markup .= $s5_sec_link['end'];
									}

								 $caption_markup .= '</div>';
							}

							 // Links for style 1 or style 2
							elseif ( $caption_style == 'style1' || $caption_style == 'style2' ) {
								$s1_link = zn_extract_link( $link, 'zn-iosSl-more');
								if(! empty ( $s1_link['start'] )){
									$caption_markup .= $s1_link['start'] . '<span class="glyphicon glyphicon-chevron-right kl-icon-white zn-iosSl-moreArrow"></span>' . $s1_link['end'];
								}
							}
							// style3
							elseif ( $caption_style == 'style3' || $caption_style == 'style3 s3ext'  ) {
								$s3_link = zn_extract_link( $link, 'btn btn-fullcolor '.$buttons_sizes);
								if(!empty($s3_link['start'])){
									$caption_markup .= '<div class="zn-iosSl-more">';
									$caption_markup .= $s3_link['start'];
									$caption_markup .= isset($slide['btn_text']) ? $slide['btn_text'] : '';
									$caption_markup .= $s3_link['end'];
									$caption_markup .= '</div>';
								}
							}

						}// end check if it's not style6

						// Slide SMALL TITLE
						if ( ! empty ( $smallTitle ) && $caption_style != 'style3 s3ext' ) {
							$caption_markup .= '<h4 class="zn-iosSl-smallTitle" '.WpkPageHelper::zn_schema_markup('subtitle').'>' . $smallTitle . '</h4>';
						}

						// Style 6 (circle play)
						if($caption_style == 'style6' && isset ( $slide['s6_yt'] ) && ! empty ( $slide['s6_yt'] )){
							$yt_params = '?loop=1&amp;start=0&amp;autoplay=1&amp;controls=0&amp;showinfo=0&amp;wmode=transparent&amp;iv_load_policy=3&amp;modestbranding=1&amp;rel=0';

							$caption_markup .= '
							<div class="zn-iosSl-playvid">
								<a href="http://www.youtube.com/watch?v='.$slide['s6_yt'].$yt_params.'" data-lightbox="youtube">
									<i class="kl-icon glyphicon glyphicon-play circled-icon ci-large"></i>
								</a>
							</div>';
						}

						$caption_markup .= $img_slide_link['start'] . $img_slide_link['end'];

						$caption_markup .= '</div>'; // end caption


						/**
						 * Image Boxes
						 */
						$imageBoxes_markup = '';
						if( isset ( $slide['imgboxes'] ) && ! empty ( $slide['imgboxes']) ) {

							// Image Boxes
							$imageBoxes_markup .= '<div class="zn-iosSl-imageBoxes '. $caption_position . ' zn-iosSl-imageBoxes--hAlign-'. $caption_position_horiz .' zn-iosSl-imageBoxes--vAlign-' . $caption_position_vert . ' ">';
							$imageBoxes_markup .= '<div class="zn-iosSl-imageBoxes-inner"> ';

								$ibox_class = 'zn-iosSl-imageBoxes-item zn-iosSl-imageBoxes-item--';

								//  Image box 1
								if( isset($slide['imgboxes_i1_src']) && ($imgb1 = $slide['imgboxes_i1_src']) ){

									$imgboxes_i1_url = zn_extract_link($slide['imgboxes_i1_url'], $ibox_class.'1', '', '<div class="'.$ibox_class.'1">','</div>' );

									$imageBoxes_markup .= $imgboxes_i1_url['start'];
									$imageBoxes_markup .= wp_get_attachment_image( $imgb1, 'medium', false, array('class'=>'zn-iosSl-imageBoxes-img cover-fit-img') );
									$imageBoxes_markup .= $imgboxes_i1_url['end'];
								}

								//  Image box 2
								if( isset($slide['imgboxes_i2_src']) && ($imgb2 = $slide['imgboxes_i2_src']) ){

									$imgboxes_i2_url = zn_extract_link($slide['imgboxes_i2_url'], $ibox_class.'2', '', '<div class="'.$ibox_class.'2">','</div>' );

									$imageBoxes_markup .= $imgboxes_i2_url['start'];
									$imageBoxes_markup .= wp_get_attachment_image( $imgb2, 'medium', false, array('class'=>'zn-iosSl-imageBoxes-img cover-fit-img') );
									$imageBoxes_markup .= $imgboxes_i2_url['end'];
								}

								//  Image box 3
								if( isset($slide['imgboxes_i3_src']) && ($imgb3 = $slide['imgboxes_i3_src']) ){

									$imgboxes_i3_url = zn_extract_link($slide['imgboxes_i3_url'], $ibox_class.'3', '', '<div class="'.$ibox_class.'3">','</div>' );

									$imageBoxes_markup .= $imgboxes_i3_url['start'];
									$imageBoxes_markup .= wp_get_attachment_image( $imgb3, 'medium', false, array('class'=>'zn-iosSl-imageBoxes-img cover-fit-img') );
									$imageBoxes_markup .= $imgboxes_i3_url['end'];
								}

							$imageBoxes_markup .= '</div></div>';
						}
						// end Image Boxes


						/**
						 * Start Slide
						 */
						echo '<div class="zn-iosSl-item" '. implode(' ', $slide_attr) .'>';

							echo $media_markup;
							echo $overlay_markup;
							echo $caption_markup;
							echo $imageBoxes_markup;

						echo '</div><!-- end item -->';
					}
				}
				?>

			</div><!-- /.zn-iosSlider -->

			<?php

			if ( count( $slides ) > 1 && $arr_navigation ) {

				echo '
				<div class="zn-iosSl-nav znSlickNav">
					<div class="znSlickNav-arr znSlickNav-prev">
						<svg viewBox="0 0 256 256"><polyline fill="none" stroke="black" stroke-width="16" stroke-linejoin="round" stroke-linecap="round" points="184,16 72,128 184,240"></polyline></svg>
						<div class="btn-label">' . __( 'PREV', 'zn_framework' ) . '</div>
					</div>
					<div class="znSlickNav-arr znSlickNav-next">
						<svg viewBox="0 0 256 256"><polyline fill="none" stroke="black" stroke-width="16" stroke-linejoin="round" stroke-linecap="round" points="72,16 184,128 72,240"></polyline></svg>
						<div class="btn-label">' . __( 'NEXT', 'zn_framework' ) . '</div>
					</div>
				</div>';
			}

			if ( count( $slides ) > 1 && $navigation != 'disabled' ) {
				echo '<div class="zn-iosSl-dots '.$navigation.'"></div>';
			}

			zn_bottommask_markup($bottom_mask, $this->opt('bottom_mask_bg',''));

			?>
			<!-- header bottom style -->

			<?php
				if($is_screffect){
					echo '</div></div>';
				}
			?>

		</div>

	<?php
	}
}
