<?php if(! defined('ABSPATH')){ return; }

$uid = $this->data['uid'];
$ceaserUrl = 'https://matthewlein.com/ceaser/';
$options = array(
	'has_tabs'  => true,

	'general' => array(
		'title' => 'Content',
		'options' => array(

			array (
				"name"        => __( "Content Source", 'zn_framework' ),
				"description" => __( "Select the content source type.", 'zn_framework' ),
				"id"          => "source",
				"std"         => "",
				'type'        => 'select',
				'options'        => array(
					'bulk' => __( "Bulk Images", 'zn_framework' ),
					'pb'   => __( "Page Builder Content", 'zn_framework' ),
				),
			),

			// Bulk images
			array(
				"name"        => __("Add Images", 'zn_framework'),
				"description" => __("Add images to the slider.", 'zn_framework'),
				"id"          => "bulk_images",
				"std"         => "",
				"type"        => "gallery",
				"dependency"  => array( 'element' => 'source' , 'value'=> array('bulk') ),
			),

			array (
				"name"        => __( "Images Size", 'zn_framework' ),
				"description" => __( "Choose the image's size", 'zn_framework' ),
				"id"          => "image_size",
				"std"         => "medium_large",
				'type'        => 'select',
				'options'     => zn_get_image_sizes_list(),
				"dependency"  => array( 'element' => 'source' , 'value'=> array('bulk') ),

			),

			array (
				"name"           => __( "Carousel Items", 'zn_framework' ),
				"description"    => __( "Here you can create your desired carousel items.", 'zn_framework' ),
				"id"             => "single_item",
				"std"            => "",
				"type"           => "group",
				"add_text"       => __( "Item", 'zn_framework' ),
				"remove_text"    => __( "Item", 'zn_framework' ),
				"group_sortable" => true,
				"element_title"  => "title",
				"subelements"    => array (
					array (
						"name"        => __( "Carousel Item Title", 'zn_framework' ),
						"description" => __( "Optional, just for visual identification purposes.", 'zn_framework' ),
						"id"          => "title",
						"std"         => "",
						"type"        => "text"
					),
				),
				"dependency"  => array( 'element' => 'source' , 'value'=> array('pb') ),
			),

		),
	),

	'slider_options' => array(
		'title' => 'Slider Options',
		'options' => array(

			array(
				'id'    => 'title1',
				'name'  => __('Basic Options', 'zn_framework'),
				'type'  => 'zn_title',
				'class' => 'zn_full zn-custom-title-large',
			),

			array (
				"name"        => __( "Autoplay", 'zn_framework' ),
				"description" => __( "Enables auto play of slides.", 'zn_framework' ),
				"id"          => "autoplay",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
			),

			array (
				"name"        => __( "Autoplay Speed", 'zn_framework' ),
				"description" => __( "Auto play change interval. In Miliseconds.", 'zn_framework' ),
				"id"          => "autoplaySpeed",
				"std"         => "3000",
				"type"        => "text",
				"class"       => "zn_input_sm",
				"dependency"  => array( 'element' => 'autoplay' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Enable Fade", 'zn_framework' ),
				"description" => __( "Enable fade effect.", 'zn_framework' ),
				"id"          => "fade",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
			),

			array (
				"name"        => __( "Infinite looping", 'zn_framework' ),
				"description" => __( "Infinite looping.", 'zn_framework' ),
				"id"          => "infinite",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
			),

			array (
				"name"        => __( "Slides To Show", 'zn_framework' ),
				"description" => __( "# of slides to show at a time.", 'zn_framework' ),
				"id"          => "slidesToShow",
				"std"         => "3",
				"type"        => "text",
				"class"       => "zn_input_xs",
				"numeric"        => true,
				"helpers"        => array(
					"min" => 1,
					"max" => 16,
					"step" => 1,
				),
			),

			array (
				"name"        => __( "Slides To Scroll", 'zn_framework' ),
				"description" => __( "# of slides to scroll at a time.", 'zn_framework' ),
				"id"          => "slidesToScroll",
				"std"         => "1",
				"type"        => "text",
				"class"       => "zn_input_xs",
				"numeric"        => true,
				"helpers"        => array(
					"min" => 1,
					"max" => 16,
					"step" => 1,
				),
			),

			array (
				"name"        => __( "Speed", 'zn_framework' ),
				"description" => __( "Transition speed.", 'zn_framework' ),
				"id"          => "speed",
				"std"         => "300",
				"type"        => "text",
				"class"       => "zn_input_sm",
				"numeric"        => true,
				"helpers"        => array(
					"min" => 0,
					"max" => 20000,
					"step" => 100,
				),
			),

			array (
				"name"        => __( "Swipe", 'zn_framework' ),
				"description" => __( "Enables touch swipe.", 'zn_framework' ),
				"id"          => "swipe",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
			),


			array(
				'id'    => 'title1',
				'name'  => __('Advanced Options', 'zn_framework'),
				'type'  => 'zn_title',
				'class' => 'zn_full zn-custom-title-large',
			),

			array (
				"name"        => __( "Enable Advanced Options?", 'zn_framework' ),
				"description" => __( "Enables tabbing and arrow key navigation", 'zn_framework' ),
				"id"          => "advanced_options",
				"std"         => "",
				"value"       => "yes",
				'type'        => 'toggle2',
			),

			// BREAKPOINT GROUPS
			array (
				"name"           => __( "Breakpoint Options", 'zn_framework' ),
				"description"    => __( "Change settings per breakpoint.", 'zn_framework' ),
				"id"             => "responsive",
				"std"            => "",
				"type"           => "group",
				"group_sortable" => true,
				"element_title" => "breakpoint",
				"subelements"    => array (

					array (
						"name"        => __( "Breakpoint (px)", 'zn_framework' ),
						"description" => __( "Enables custom settings at this given breakpoint. Eg: 1440, 1200, 992, 767.", 'zn_framework' ),
						"id"          => "breakpoint",
						"std"         => "",
						"type"        => "text",
						"class"       => "zn_input_xs",
						"numeric"        => true,
						"helpers"        => array(
							"min" => 320,
							"max" => 2560,
							"step" => 1,
						),
					),

					array (
						"name"        => __( "Slides To Show", 'zn_framework' ),
						"description" => __( "# of slides to show at a time.", 'zn_framework' ),
						"id"          => "slidesToShow",
						"std"         => "1",
						"type"        => "text",
						"class"       => "zn_input_xs",
						"numeric"        => true,
						"helpers"        => array(
							"min" => 1,
							"max" => 16,
							"step" => 1,
						),
					),

					array (
						"name"        => __( "Slides To Scroll", 'zn_framework' ),
						"description" => __( "# of slides to scroll at a time.", 'zn_framework' ),
						"id"          => "slidesToScroll",
						"std"         => "1",
						"type"        => "text",
						"class"       => "zn_input_xs",
						"numeric"        => true,
						"helpers"        => array(
							"min" => 1,
							"max" => 16,
							"step" => 1,
						),
					),

					array (
						"name"        => __( "Enable arrows navigation?", 'zn_framework' ),
						"description" => __( "Enables navigation arrows.", 'zn_framework' ),
						"id"          => "arrows",
						"std"         => "yes",
						'type'        => 'zn_radio',
						'options'        => array(
							'yes' => __( "Yes", 'zn_framework' ),
							'no' => __( "No", 'zn_framework' ),
						),
						'class'        => 'zn_radio--yesno',
					),

					array (
						"name"        => __( "Enable Dots Nov.", 'zn_framework' ),
						"description" => __( "Current slide indicator dots.", 'zn_framework' ),
						"id"          => "dots",
						"std"         => "no",
						'type'        => 'zn_radio',
						'options'        => array(
							'yes' => __( "Yes", 'zn_framework' ),
							'no' => __( "No", 'zn_framework' ),
						),
						'class'        => 'zn_radio--yesno',
					),

					array (
						"name"        => __( "Disable Slider?", 'zn_framework' ),
						"description" => __( "Disable slick slider on this breakpoint?", 'zn_framework' ),
						"id"          => "unslick",
						"std"         => "no",
						'type'        => 'zn_radio',
						'options'        => array(
							'yes' => __( "Yes", 'zn_framework' ),
							'no' => __( "No", 'zn_framework' ),
						),
						'class'        => 'zn_radio--yesno',
					),

				),
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),


			array (
				"name"        => __( "Accessibility", 'zn_framework' ),
				"description" => __( "Enables tabbing and arrow key navigation", 'zn_framework' ),
				"id"          => "accessibility",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Adaptive Height", 'zn_framework' ),
				"description" => __( "Adapts slider height to the current slide.", 'zn_framework' ),
				"id"          => "adaptiveHeight",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'     => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),


			array (
				"name"        => __( "Enable Center Mode", 'zn_framework' ),
				"description" => __( "Enables centered view with partial prev/next slides. Use with odd numbered slidesToShow counts.", 'zn_framework' ),
				"id"          => "centerMode",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Center Padding", 'zn_framework' ),
				"description" => __( "Side padding when in center mode. (px or %).", 'zn_framework' ),
				"id"          => "centerPadding",
				"std"         => "50px",
				"type"        => "text",
				"class"       => "zn_input_xs",
				"dragup"     => array(
					'min' => '0',
					'max' => '300',
					'unit' => 'px'
				),
				"dependency"  => array(
					array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
					array( 'element' => 'centerMode' , 'value'=> array('yes') ),
				),
			),

			array (
				"name"        => __( "CSS Ease", 'zn_framework' ),
				"description" => sprintf( __( 'CSS3 easing. Eg: <a href="%s" target="_blank">Ceaser Tool</a> (copy the cubic-bezier property).', 'zn_framework' ), $ceaserUrl ),
				"id"          => "cssEase",
				"std"         => "ease",
				"type"        => "text",
				"class"       => "zn_input_sm",
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Desktop Dragging", 'zn_framework' ),
				"description" => __( "Enables desktop dragging", 'zn_framework' ),
				"id"          => "dragging",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Edge Friction", 'zn_framework' ),
				"description" => __( "Resistance when swiping edges of non-infinite carousels.", 'zn_framework' ),
				"id"          => "edgeFriction",
				"std"         => "0.15",
				"type"        => "text",
				"class"       => "zn_input_sm",
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Slider Syncing", 'zn_framework' ),
				"description" => __( "You can sync multiple image sliders. Add the CSS selector of the other image slider you want this image slider to sync with.", 'zn_framework' ),
				"id"          => "asNavFor",
				"std"         => "",
				"type"        => "text",
				"placeholder" => "eg: .zn-Slider-eluid4262c4de",
				"class"       => "zn_input_xl",
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Initial Slide", 'zn_framework' ),
				"description" => __( "Slide to start on.", 'zn_framework' ),
				"id"          => "initialSlide",
				"std"         => "0",
				"type"        => "text",
				"class"       => "zn_input_xs",
				"numeric"        => true,
				"helpers"        => array(
					"min" => 0,
					"max" => 20,
					"step" => 1,
				),
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Enable Lazy Load", 'zn_framework' ),
				"description" => __( "Enable loading images progresively.", 'zn_framework' ),
				"id"          => "lazyLoad",
				"std"         => "no",
				'type'        => 'select',
				'options'        => array(
					'no' => __( "Disabled.", 'zn_framework' ),
					'ondemand' => __( "On Demand.", 'zn_framework' ),
					'progressive' => __( "Progressive.", 'zn_framework' ),
				),
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Pause On Focus", 'zn_framework' ),
				"description" => __( "Pauses autoplay when slider is focussed.", 'zn_framework' ),
				"id"          => "pauseOnFocus",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Pause On Hover", 'zn_framework' ),
				"description" => __( "Pauses autoplay when slider is hovered.", 'zn_framework' ),
				"id"          => "pauseOnHover",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Pause On Dots Hover", 'zn_framework' ),
				"description" => __( "Pauses autoplay when a dot is hovered.", 'zn_framework' ),
				"id"          => "pauseOnDotsHover",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Respond To", 'zn_framework' ),
				"description" => __( "Width that responsive object responds to.", 'zn_framework' ),
				"id"          => "respondTo",
				"std"         => "window",
				'type'        => 'select',
				'options'        => array(
					'window' => __( "Window", 'zn_framework' ),
					'slider' => __( "Slider", 'zn_framework' ),
					'min' => __( "the smaller of the two.", 'zn_framework' ),
				),
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Rows", 'zn_framework' ),
				"description" => __( "Setting this to more than 1 initializes grid mode. Use 'Slides Per Row' to set how many slides should be in each row.", 'zn_framework' ),
				"id"          => "rows",
				"std"         => "1",
				"type"        => "text",
				"class"       => "zn_input_xs",
				"numeric"        => true,
				"helpers"        => array(
					"min" => 1,
					"max" => 5,
					"step" => 1,
				),
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Slides per Row", 'zn_framework' ),
				"description" => __( "With grid mode initialized via the rows option, this sets how many slides are in each grid row.", 'zn_framework' ),
				"id"          => "slidesPerRow",
				"std"         => "1",
				"type"        => "text",
				"class"       => "zn_input_xs",
				"numeric"        => true,
				"helpers"        => array(
					"min" => 1,
					"max" => 16,
					"step" => 1,
				),
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),


			array (
				"name"        => __( "Swipe To Slide", 'zn_framework' ),
				"description" => __( "Swipe to slide irrespective of slidesToScroll", 'zn_framework' ),
				"id"          => "swipeToSlide",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Touch Move", 'zn_framework' ),
				"description" => __( "Enables slide moving with touch.", 'zn_framework' ),
				"id"          => "touchMove",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Touch Threshold", 'zn_framework' ),
				"description" => __( "To advance slides, the user must swipe a length of ( 1 / touchThreshold) * the width of the slider.", 'zn_framework' ),
				"id"          => "touchThreshold",
				"std"         => "5",
				"type"        => "text",
				"class"       => "zn_input_xs",
				"numeric"        => true,
				"helpers"        => array(
					"min" => 1,
					"max" => 40,
					"step" => 1,
				),
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Enable Variable Width", 'zn_framework' ),
				"description" => __( "Disables automatic slide width calculation.", 'zn_framework' ),
				"id"          => "variableWidth",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Enable Vertical Mode", 'zn_framework' ),
				"description" => __( "Vertical slide direction.", 'zn_framework' ),
				"id"          => "vertical",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Vertical Swiping", 'zn_framework' ),
				"description" => __( "Changes swipe direction to vertical.", 'zn_framework' ),
				"id"          => "verticalSwiping",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Enable RTL mode?", 'zn_framework' ),
				"description" => __( "Change the slider's direction to become right-to-left.", 'zn_framework' ),
				"id"          => "rtl",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Wait for animate", 'zn_framework' ),
				"description" => __( "Ignores requests to advance the slide while animating.", 'zn_framework' ),
				"id"          => "waitForAnimate",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'advanced_options' , 'value'=> array('yes') ),
			),



		),
	),



	'style' => array(
		'title' => 'Style',
		'options' => array(

			array(
				'id'    => 'title1',
				'name'  => __('Arrow Navigation Options', 'zn_framework'),
				'type'  => 'zn_title',
				'class' => 'zn_full zn-custom-title-large',
			),

			array (
				"name"        => __( "Enable arrows navigation?", 'zn_framework' ),
				"description" => __( "Enables navigation arrows.", 'zn_framework' ),
				"id"          => "arrows",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
			),

			array (
				"name"        => __( "Arrow Style", 'zn_framework' ),
				"description" => __( "Select the arrows Style", 'zn_framework' ),
				"id"          => "arrow_style",
				"std"         => "1",
				'type'        => 'select',
				'options'        => array(
					'1' => __( "Style 1", 'zn_framework' ),
					'2' => __( "Style 2", 'zn_framework' ),
					'3' => __( "Style 3", 'zn_framework' ),
				),
				"dependency"  => array( 'element' => 'arrows' , 'value'=> array('yes') ),
				'live'        => array(
					'type'    => 'class',
					'css_class' => '.'.$uid.' .zn-SliderNav',
					'val_prepend'  => 'zn-SliderNav--style',
				),
			),

			array (
				"name"        => __( "Rounded", 'zn_framework' ),
				"description" => __( "Choose if you want to have circle shaped arrows.", 'zn_framework' ),
				"id"          => "arrows_rounded",
				"std"         => "yes",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
				"dependency"  => array( 'element' => 'arrows' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Arrows Navigation Position", 'zn_framework' ),
				"description" => __( "Select the position of the Arrows", 'zn_framework' ),
				"id"          => "arrow_pos",
				"std"         => "middle",
				"type"        => "select",
				"options"     => array (
					'top-left'  => __( 'Top Left', 'zn_framework' ),
					'top-center' => __( 'Top Center', 'zn_framework' ),
					'top-right' => __( 'Top Right', 'zn_framework' ),
					'middle' => __( 'Vertically Middle', 'zn_framework' ),
					'bottom-left' => __( 'Bottom Left', 'zn_framework' ),
					'bottom-center' => __( 'Bottom Center', 'zn_framework' ),
					'bottom-right' => __( 'Bottom Right', 'zn_framework' ),
				),
				"dependency"  => array( 'element' => 'arrows' , 'value'=> array('yes') ),
				// 'live'        => array(
				// 	'type'    => 'class',
				// 	'css_class' => '.'.$uid.' .zn-SliderNav',
				// 	'val_prepend'  => 'zn-SliderNav--pos-',
				// ),
			),

			array (
				"name"        => __( "Navigation Offset", 'zn_framework' ),
				"description" => __( "Customize the navigation arrow offset.", 'zn_framework' ),
				"id"          => "arrows_offset",
				"std"         => "0",
				"type"        => "slider",
				"helpers"     => array (
					"step" => "1",
					"min" => "-150",
					"max" => "150"
				),
				"dependency"  => array( 'element' => 'arrows' , 'value'=> array('yes') ),
				'live'        => array(
					'multiple' => array(
						array(
							'type'      => 'css',
							'css_class' => '.'.$uid.' .znSlickNav-prev',
							'css_rule'  => 'margin-right',
							'unit'      => 'px'
						),
						array(
							'type'      => 'css',
							'css_class' => '.'.$uid.' .znSlickNav-next',
							'css_rule'  => 'margin-left',
							'unit'      => 'px'
						),
					)
				)
			),


			array (
				"name"        => __( "Navigation Vertical Offset", 'zn_framework' ),
				"description" => __( "Customize the navigation arrow vertical offset.", 'zn_framework' ),
				"id"          => "arrows_vertical_offset",
				"std"         => "50",
				"type"        => "slider",
				"helpers"     => array (
					"step" => "1",
					"min" => "1",
					"max" => "100"
				),
				"dependency"  => array(
					array( 'element' => 'arrows' , 'value'=> array('yes') ),
					array( 'element' => 'arrow_pos' , 'value'=> array('middle') )
				),
			),


			array (
				"name"        => __( "Arrows Size", 'zn_framework' ),
				"description" => __( "Choose the arrows sizes.", 'zn_framework' ),
				"id"          => "arrows_size",
				"std"         => "normal",
				'type'        => 'select',
				'options'        => array(
					'normal' => __( "Default", 'zn_framework' ),
					'large' => __( "Large", 'zn_framework' ),
					'xlarge' => __( "Larger", 'zn_framework' ),
				),
				'live'        => array(
					'type'    => 'class',
					'css_class' => '.'.$uid.' .zn-SliderNav',
					'val_prepend'  => 'zn-SliderNav--size-',
				),
				"dependency"  => array( 'element' => 'arrows' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Arrows Theme", 'zn_framework' ),
				"description" => __( "Choose the arrows color theme.", 'zn_framework' ),
				"id"          => "arrows_theme",
				"std"         => "dark",
				'type'        => 'select',
				'options'        => array(
					'dark' => __( "Dark", 'zn_framework' ),
					'light' => __( "Light", 'zn_framework' ),
				),
				'live'        => array(
					'type'    => 'class',
					'css_class' => '.'.$uid.' .zn-SliderNav',
					'val_prepend'  => 'zn-SliderNav--theme-',
				),
				"dependency"  => array( 'element' => 'arrows' , 'value'=> array('yes') ),
			),



			array(
				'id'    => 'title1',
				'name'  => __('Bullets Navigation Options', 'zn_framework'),
				'type'  => 'zn_title',
				'class' => 'zn_full zn-custom-title-large',
			),

			array (
				"name"        => __( "Enable Dots Nov.", 'zn_framework' ),
				"description" => __( "Current slide indicator dots.", 'zn_framework' ),
				"id"          => "dots",
				"std"         => "no",
				'type'        => 'zn_radio',
				'options'        => array(
					'yes' => __( "Yes", 'zn_framework' ),
					'no' => __( "No", 'zn_framework' ),
				),
				'class'        => 'zn_radio--yesno',
			),

			array (
				"name"        => __( "Dots Navigation Position", 'zn_framework' ),
				"description" => __( "Select the position of the dots", 'zn_framework' ),
				"id"          => "dots_pos",
				"std"         => "bottom-center",
				"type"        => "select",
				"options"     => array (
					'bottom-left' => __( 'Bottom Left', 'zn_framework' ),
					'bottom-center' => __( 'Bottom Center', 'zn_framework' ),
					'bottom-right' => __( 'Bottom Right', 'zn_framework' ),
					'top-left' => __( 'Top Left', 'zn_framework' ),
					'top-center' => __( 'Top Center', 'zn_framework' ),
					'top-right' => __( 'Top Right', 'zn_framework' ),
				),
				"dependency"  => array( 'element' => 'dots' , 'value'=> array('yes') ),
			),

			array (
				"name"        => __( "Dots Theme", 'zn_framework' ),
				"description" => __( "Choose the dots color theme.", 'zn_framework' ),
				"id"          => "dots_theme",
				"std"         => "dark",
				'type'        => 'select',
				'options'        => array(
					'dark' => __( "Dark", 'zn_framework' ),
					'light' => __( "Light", 'zn_framework' ),
				),
				"dependency"  => array( 'element' => 'dots' , 'value'=> array('yes') ),
			),


			// bullets style
			// thumbs
			// thumbs on hover
			// caption options

		),
	),

	'help' => znpb_get_helptab( array(
		// 'video'   => 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#O03njJEtSNQ',
		'copy'    => $uid,
		'general' => true,
		'custom_id' => true,
	)),

);
