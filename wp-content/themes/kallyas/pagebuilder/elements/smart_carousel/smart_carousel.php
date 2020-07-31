<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Smart Slider
 Description: Create sliders (carousels) containing page builder elements
 Class: ZnSmartCarousel
 Category: content
 Level: 3
 Multiple: true
 Scripts: true
 Legacy: true
*/

class ZnSmartCarousel extends ZnElements
{
	public static function getName(){
		return __( "Smart Slider", 'zn_framework' );
	}

	/**
	 * Load dependant resources
	 */
	function scripts(){
		wp_enqueue_script( 'slick', THEME_BASE_URI . '/addons/slick/slick.min.js', array ( 'jquery' ), ZN_FW_VERSION, true );
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];

		// Margin
		$margins = array();
		$margins['lg'] = $this->opt('margin_lg', '' ) ?  $this->opt('margin_lg', '' ) : '';
		$margins['md'] = $this->opt('margin_md', '' ) ?  $this->opt('margin_md', '' ) : '';
		$margins['sm'] = $this->opt('margin_sm', '' ) ?  $this->opt('margin_sm', '' ) : '';
		$margins['xs'] = $this->opt('margin_xs', '' ) ?  $this->opt('margin_xs', '' ) : '';
		if( !empty($margins) ){
			$margins['selector'] = '.'.$uid;
			$margins['type'] = 'margin';
			$css .= zn_push_boxmodel_styles( $margins );
		}


		// Padding
		$paddings = array();
		$paddings['lg'] = $this->opt('padding_lg', '' ) ?  $this->opt('padding_lg', '' ) : '';
		$paddings['md'] = $this->opt('padding_md', '' ) ?  $this->opt('padding_md', '' ) : '';
		$paddings['sm'] = $this->opt('padding_sm', '' ) ?  $this->opt('padding_sm', '' ) : '';
		$paddings['xs'] = $this->opt('padding_xs', '' ) ?  $this->opt('padding_xs', '' ) : '';
		if( !empty($paddings) ){
			$paddings['selector'] = '.'.$uid;
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}

		$nav_offset = $this->opt('arrows_offset', '20');
		if( $nav_offset != '' ){
			$css .= '.'.$uid.' .znSmartCarousel-prev {margin-right:'. $nav_offset .'px}';
			$css .= '.'.$uid.' .znSmartCarousel-next {margin-left:'. $nav_offset .'px}';
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

		if( empty ( $options['single_item'] ) ){
			return;
		}

		$single_item = $this->opt('single_item');
		$itemCount = count($single_item);


		$elm_classes = array();
		$elm_classes[] = $uid = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($options);

		$preloader = $this->opt('smc_preloaded', 1) == 1;
		$elm_classes[] = $preloader ? 'znSmartCarousel-hasPreloader' : '';
		$elm_classes[] = ZNB()->utility->isActiveEditor() ? 'znSmartCarouselMode--edit' : 'znSmartCarouselMode--view';

		$attributes = zn_get_element_attributes($options);

		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$elm_classes[] = 'element-scheme--'.$color_scheme;


		// Navigation
		$opt_nav = $this->opt('smc_nav', 'yes');
		$opt_nav_position = $this->opt('smc_nav_position', 'bottom-center');

		// Slick Attributes
		$slick_attributes = array(
			"infinite" => true,
			"slidesToShow" => 1,
			"slidesToScroll" => 1,
			"autoplay" => $this->opt('smc_autoplay', '1') == 1 ? true : false,
			"autoplaySpeed" => (int)$this->opt('smc_speed', 6) * 1000,
			"easing" => $this->opt('smc_easing', 'linear'),
			"speed" => (int)$this->opt('smc_transition_duration', '500'),
			"swipe" => $this->opt('smc_swipe_mouse', 1) == 1 ? true : false,
			"touchMove" => $this->opt('smc_swipe_touch', 1) == 1 ? true : false,
			"adaptiveHeight" => true,
			"responsive" => array(
				"breakpoint" => 1024,
				"settings" => array(
					"slidesToScroll" => 1,
				),
			),
		);

		if($preloader){
			$slick_attributes['loadingContainer'] = '.'. $uid;
		}

		$transition = $this->opt('smc_transition','fade');
		if( $transition == 'fade' || $transition == 'crossfade' ){
			$slick_attributes['fade'] = true;
		}
		elseif ($transition == 'slide') {
			$slidesToScroll = $this->opt('slidesToScroll', 1);
			$slidesToScroll = (float)$slidesToScroll;
			$slick_attributes['slidesToScroll'] = $slidesToScroll;
			if( $slidesToScroll !== 1 ){
				$slick_attributes['adaptiveHeight'] = false;
			}
		}

		// Prepare for Edit Mode
		if(ZNB()->utility->isActiveEditor()){
			$slick_attributes['autoplay'] = false;
			$slick_attributes['slidesToScroll'] = 1;
			$slick_attributes['fade'] = true;
			$slick_attributes['adaptiveHeight'] = false;
		}

		/**
		 * Bullets
		 */
		$opt_bullets = $this->opt('smc_bullets', 'yes');
		$opt_bullets_position = $this->opt('smc_bullets_position', 'bottom-center');

		$bullets = '';
		if( $opt_bullets == 'yes'){
			$bullets = '<div class="znSmartCarousel-pagi znSmartCarousel-bulletsPosition--'.$opt_bullets_position.'"></div>';

			// Slick Attributes
			$slick_attributes['dots'] = true;
			$slick_attributes['appendDots'] = '.'. $uid . ' .znSmartCarousel-pagi';
		}
		else {
			$slick_attributes['dots'] = false;
		}

		$navigation = '';
		if( $opt_nav == 'yes' ){

			$nav_classes = array();
			$nav_classes[] = 'znSmartCarousel-navPosition--'.$opt_nav_position;
			$nav_classes[] = 'znSmartCarousel-navStyle--'.$this->opt('smc_nav_style', 'default');

			$navigation = '<div class="znSmartCarousel-nav '. implode(' ', $nav_classes) .'">';

				$navigation .= '<span class="znSmartCarousel-arr znSmartCarousel-prev ">';
					$navigation .= '<svg viewBox="0 0 256 256"><polyline fill="none" stroke="black" stroke-width="16" stroke-linejoin="round" stroke-linecap="round" points="184,16 72,128 184,240"/></svg>';
				$navigation .= '</span>';

				$navigation .= '<span class="znSmartCarousel-arr znSmartCarousel-next">';
				$navigation .= '<svg viewBox="0 0 256 256"><polyline fill="none" stroke="black" stroke-width="16" stroke-linejoin="round" stroke-linecap="round" points="72,16 184,128 72,240"/></svg>';
				$navigation .= '</span>';

			$navigation .= '</div>';
			$navigation .= '<div class="clearfix"></div>';

			// Slick Attributes
			$slick_attributes['arrows'] = true;
			$slick_attributes['appendArrows'] = '.'. $uid . ' .znSmartCarousel-nav';
			$slick_attributes['prevArrow'] = '.'. $uid . ' .znSmartCarousel-prev';
			$slick_attributes['nextArrow'] = '.'. $uid . ' .znSmartCarousel-next';
		}
		else {
			$slick_attributes['arrows'] = false;
		}

		echo '<div class="znSmartCarousel '.implode(' ', $elm_classes).'" '.$attributes.'>';

		if ( ! empty ( $single_item ) && is_array( $single_item ) )
		{

			if($preloader){
				echo '<div class="znSmartCarousel-loadingContainer">';
			}

			if( !ZNB()->utility->isActiveEditor() && $this->opt('smc_continuous', '') == 1){
				$slick_attributes['speed'] = (int) $this->opt('smc_continuous_speed', '4000');
				$slick_attributes['autoplay'] = true;
				$slick_attributes['autoplaySpeed'] = 0;
				$slick_attributes['cssEase'] = 'linear';
				$slick_attributes['slidesToShow'] = 1;
				$slick_attributes['slidesToScroll'] = 1;
			}

			// Bullets / Pagination
			if( $opt_bullets == 'yes' && in_array($opt_bullets_position, array('top-left', 'top-center', 'top-right' )) ){
				echo $bullets;
			}

			// Navigation Arrows
			if( $opt_nav == 'yes' && in_array($opt_nav_position, array( 'top-left', 'top-center', 'top-right', 'middle' )) ){
				echo $navigation;
			}

			echo '<div class="znSmartCarousel-holder js-slick " data-slick=\''.json_encode($slick_attributes).'\'>';

				foreach($single_item as $i => $sitem)
				{
					$uniq_name = $uid.'_'.$i;
					$ic = $i+1;

					// Slide content
					echo '<div class="znSmartCarousel-item znSmartCarousel-item--'.$ic.' " id="' . $uniq_name . '">';

						if ( ZNB()->utility->isActiveEditor() ){
							$slide_title = 'SLIDE '.$ic.(isset($sitem['smc_title']) && !empty($sitem['smc_title']) ? ' - '.$sitem['smc_title'] : '');
							echo '<div class="znSmartCarousel-PbModeHandler '.( $ic == 1 ? 'pbModeHandler--start':'' ).'" data-slide-title="'.$slide_title.'"></div>';
						}

						// Add complex page builder element
						echo ZNB()->utility->getElementContainer(array(
							'cssClasses' => 'row znSmartCarousel-container '. $this->opt('gutter_size','')
						));

							if ( empty( $this->data['content'][$i] ) ) {
								$column = ZNB()->frontend->addModuleToLayout( 'ZnColumn', array() , array(), 'col-sm-12' );
								$this->data['content'][$i] = array ( $column );
							}

							if ( !empty( $this->data['content'][$i] ) ) {
								// print_z($this);
								ZNB()->frontend->renderElements( $this->data['content'][$i] );
							}
						echo '</div>';

						if ( ZNB()->utility->isActiveEditor() && $ic == $itemCount ){
							echo '<div class="znSmartCarousel-PbModeHandler pbModeHandler--end"></div>';
						}

					echo '</div>';
				}
			echo '</div>';


			// Bullets / Pagination
			if( $opt_bullets == 'yes' && in_array($opt_bullets_position, array( 'bottom-left', 'bottom-center', 'bottom-right' )) ){
				echo $bullets;
			}

			// Navigation Arrows
			if($opt_nav == 'yes' && in_array($opt_nav_position, array( 'bottom-left', 'bottom-center', 'bottom-right' )) ){
				echo $navigation;
			}

			if($preloader){
				echo '</div>';
				echo '<div class="znSmartCarousel-loading"></div>';
			}

			?>

			<div class="clearfix"></div>
<?php
		}
		echo '</div>';
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$extra_options = array (
			"name"           => __( "Carousel Items", 'zn_framework' ),
			"description"    => __( "Here you can create your desired carousel items.", 'zn_framework' ),
			"id"             => "single_item",
			"std"            => "",
			"type"           => "group",
			"add_text"       => __( "Item", 'zn_framework' ),
			"remove_text"    => __( "Item", 'zn_framework' ),
			"group_sortable" => true,
			"element_title" => "smc_title",
			"subelements"    => array (
				array (
					"name"        => __( "Carousel Item Title", 'zn_framework' ),
					"description" => __( "Will be hidden, but please enter the desired title of a slide, mostly for visual guideline in these rows.", 'zn_framework' ),
					"id"          => "smc_title",
					"std"         => "",
					"type"        => "text"
				),
			)
		);

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,

			'items' => array(
				'title' => 'Slides',
				'options' => array(

					$extra_options,

				),
			),

			'general' => array(
				'title' => 'Options',
				'options' => array(

					array (
						"name"        => __( "Autoplay carousel?", 'zn_framework' ),
						"description" => __( "Does the carousel autoplay itself? VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_autoplay",
						"std"         => "1",
						"value"         => "1",
						"type"        => "toggle2"
					),

					array (
						"name"        => __( "Autoplay Duration", 'zn_framework' ),
						"description" => __( "Adjust the speed between sliding timeout in seconds. VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_speed",
						"std"         => "6",
						"type"        => "slider",
						'helpers'     => array(
							'step' => '1',
							'min' => '0',
							'max' => '25',
						),
						"dependency"  => array( 'element' => 'smc_autoplay' , 'value'=> array('1') ),
					),

					array (
						"name"        => __( "Slider Transition", 'zn_framework' ),
						"description" => __( "Select the desired transition that you want to use for this slider. VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_transition",
						"std"         => "fade",
						"type"        => "zn_radio",
						"options"     => array (
							'fade'  => __( 'Fade', 'zn_framework' ),
							// 'crossfade'  => __( 'Cross Fade', 'zn_framework' ),
							'slide' => __( 'Slide', 'zn_framework' )
						),
					),

					array (
						"name"        => __( "Slides to scroll", 'zn_framework' ),
						"description" => __( "# of slides to scroll at a time", 'zn_framework' ),
						"id"          => "slidesToScroll",
						"std"         => "1",
						'type'        => 'select',
						'options'        => array(
							'1' => __( "One Slide", 'zn_framework' ),
							'0.5' => __( "1/2 - Half of slide", 'zn_framework' ),
							'0.3333334' => __( "1/3 - Third of a slide", 'zn_framework' ),
							'0.25' => __( "1/4 - Fourth of a slide", 'zn_framework' ),
							'0.2' => __( "1/5 - Fifth of a slide", 'zn_framework' ),
						),
						"dependency"  => array( 'element' => 'smc_transition' , 'value'=> array('slide') ),
					),

					array (
						"name"        => __( "Transition Easing", 'zn_framework' ),
						"description" => __( "Choose an easing type for the slide animation.", 'zn_framework' ),
						"id"          => "smc_easing",
						"std"         => "",
						'type'        => 'select',
						'options'        => array(
							'' => __( "Swing", 'zn_framework' ),
							'quadratic' => __( "Quadratic", 'zn_framework' ),
							'cubic' => __( "Cubic", 'zn_framework' ),
							'elastic' => __( "Elastic", 'zn_framework' ),
							'easeInQuad' => __( "easeInQuad", 'zn_framework' ),
							'easeOutQuad' => __( "easeOutQuad", 'zn_framework' ),
							'easeInOutQuad' => __( "easeInOutQuad", 'zn_framework' ),
							'easeInCubic' => __( "easeInCubic", 'zn_framework' ),
							'easeOutCubic' => __( "easeOutCubic", 'zn_framework' ),
							'easeInOutCubic' => __( "easeInOutCubic", 'zn_framework' ),
							'easeInQuart' => __( "easeInQuart", 'zn_framework' ),
							'easeOutQuart' => __( "easeOutQuart", 'zn_framework' ),
							'easeInOutQuart' => __( "easeInOutQuart", 'zn_framework' ),
							'easeInQuint' => __( "easeInQuint", 'zn_framework' ),
							'easeOutQuint' => __( "easeOutQuint", 'zn_framework' ),
							'easeInOutQuint' => __( "easeInOutQuint", 'zn_framework' ),
							'easeInSine' => __( "easeInSine", 'zn_framework' ),
							'easeOutSine' => __( "easeOutSine", 'zn_framework' ),
							'easeInOutSine' => __( "easeInOutSine", 'zn_framework' ),
							'easeInExpo' => __( "easeInExpo", 'zn_framework' ),
							'easeOutExpo' => __( "easeOutExpo", 'zn_framework' ),
							'easeInOutExpo' => __( "easeInOutExpo", 'zn_framework' ),
							'easeInCirc' => __( "easeInCirc", 'zn_framework' ),
							'easeOutCirc' => __( "easeOutCirc", 'zn_framework' ),
							'easeInOutCirc' => __( "easeInOutCirc", 'zn_framework' ),
							'easeInElastic' => __( "easeInElastic", 'zn_framework' ),
							'easeOutElastic' => __( "easeOutElastic", 'zn_framework' ),
							'easeInOutElastic' => __( "easeInOutElastic", 'zn_framework' ),
							'easeInBack' => __( "easeInBack", 'zn_framework' ),
							'easeOutBack' => __( "easeOutBack", 'zn_framework' ),
							'easeInOutBack' => __( "easeInOutBack", 'zn_framework' ),
							'easeInBounce' => __( "easeInBounce", 'zn_framework' ),
							'easeOutBounce' => __( "easeOutBounce", 'zn_framework' ),
							'easeInOutBounce' => __( "easeInOutBounce", 'zn_framework' ),
						),
					),

					array (
						"name"        => __( "Transition Duration", 'zn_framework' ),
						"description" => __( "Adjust the transition (animation) in miliseconds, for example 500 equals 0.5 seconds . VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_transition_duration",
						"std"         => "500",
						"type"        => "text",
						"numeric"     => true,
					),

					array (
						"name"        => __( "Arrows Navigation", 'zn_framework' ),
						"description" => __( "Display arrows navigation?", 'zn_framework' ),
						"id"          => "smc_nav",
						"std"         => "yes",
						"type"        => "zn_radio",
						"options"     => array (
							'yes'  => __( 'Yes', 'zn_framework' ),
							'no' => __( 'No', 'zn_framework' )
						),
						"class" => "zn_radio--yesno",
					),

					array (
						"name"        => __( "Arrows Navigation Style", 'zn_framework' ),
						"description" => __( "Select a style for the navigation", 'zn_framework' ),
						"id"          => "smc_nav_style",
						"std"         => "default",
						"type"        => "select",
						"options"     => array (
							'default'  => __( 'Default (minimal)', 'zn_framework' ),
							's1' => __( 'Style #1 (Bigger arrows)', 'zn_framework' ),
							// 's2' => __( 'Style #2 (with background)', 'zn_framework' ),
							// 's3' => __( 'Style #3', 'zn_framework' ),
							// 's4' => __( 'Style #4', 'zn_framework' ),
							// 's5' => __( 'Style #5', 'zn_framework' ),
							// 's6' => __( 'Style #6', 'zn_framework' ),
						),
						"dependency"  => array( 'element' => 'smc_nav' , 'value'=> array('yes') ),
					),

					array (
						"name"        => __( "Arrows Navigation Position", 'zn_framework' ),
						"description" => __( "Select the position of the Arrows", 'zn_framework' ),
						"id"          => "smc_nav_position",
						"std"         => "bottom-center",
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
						"dependency"  => array(
							array( 'element' => 'smc_nav' , 'value'=> array('yes') ),
							array( 'element' => 'smc_nav_style' , 'value'=> array('default', 's1') ),
						),
					),

					array (
						"name"        => __( "Navigation Offset", 'zn_framework' ),
						"description" => __( "Customize the navigation arrow offset.", 'zn_framework' ),
						"id"          => "arrows_offset",
						"std"         => "20",
						"type"        => "slider",
						"helpers"     => array (
							"step" => "1",
							"min" => "-150",
							"max" => "150"
						),
						"dependency"  => array( 'element' => 'smc_nav' , 'value'=> array('yes') ),
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .znSmartCarousel-prev',
									'css_rule'  => 'margin-right',
									'unit'      => 'px'
								),
								array(
									'type'      => 'css',
									'css_class' => '.'.$uid.' .znSmartCarousel-next',
									'css_rule'  => 'margin-left',
									'unit'      => 'px'
								),
							),
						),
					),

					array (
						"name"        => __( "Slider Bullets", 'zn_framework' ),
						"description" => __( "Display navigation bullets? VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_bullets",
						"std"         => "yes",
						"type"        => "zn_radio",
						"options"     => array (
							'yes'  => __( 'Yes', 'zn_framework' ),
							'no' => __( 'No', 'zn_framework' )
						),
						"class" => "zn_radio--yesno",
					),

					array (
						"name"        => __( "Bullets Navigation Position", 'zn_framework' ),
						"description" => __( "Select the position of the bullets. VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_bullets_position",
						"std"         => "bottom-center",
						"type"        => "select",
						"options"     => array (
							'top-left'  => __( 'Top Left', 'zn_framework' ),
							'top-center' => __( 'Top Center', 'zn_framework' ),
							'top-right' => __( 'Top Right', 'zn_framework' ),
							'bottom-left' => __( 'Bottom Left', 'zn_framework' ),
							'bottom-center' => __( 'Bottom Center', 'zn_framework' ),
							'bottom-right' => __( 'Bottom Right', 'zn_framework' ),
						),
						"dependency"  => array( 'element' => 'smc_bullets' , 'value'=> array('yes') ),
					),

					array (
						"name"        => __( "Swipe on touch?", 'zn_framework' ),
						"description" => __( "Enable swipe on touch. Applies to mobile devices or laptops/monitors with touchscreen. VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_swipe_touch",
						"std"         => "1",
						"value"         => "1",
						"type"        => "toggle2"
					),

					array (
						"name"        => __( "Swipe on mouse?", 'zn_framework' ),
						"description" => __( "Enable swipe on mouse drag. Applies generally to desktop normal computers using a mouse. VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_swipe_mouse",
						"std"         => "1",
						"value"         => "1",
						"type"        => "toggle2"
					),

					array (
						"name"        => __( "Enable preloader?", 'zn_framework' ),
						"description" => __( "Enable if you want a preloader to be displayed until loaded. VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_preloaded",
						"std"         => "1",
						"value"         => "1",
						"type"        => "toggle2"
					),

					array (
						"name"        => __( "Enable continuously scrolling?", 'zn_framework' ),
						"description" => __( "Enable if you want a continuously scrolling carousel immediately stopping onMouseOver. VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_continuous",
						"std"         => "",
						"value"         => "1",
						"type"        => "toggle2"
					),

					array (
						"name"        => __( "Continuously scroll speed", 'zn_framework' ),
						"description" => __( "Add the speed in milliseconds. For example 1 second = 1000 milliseconds. VIEW MODE ONLY!", 'zn_framework' ),
						"id"          => "smc_continuous_speed",
						"std"         => "4000",
						"type"        => "text",
						"dependency"  => array( 'element' => 'smc_continuous' , 'value'=> array('1') ),
					),

				),
			),

			'advanced' => array(
				'title' => 'Advanced',
				'options' => array(

					array(
						'id'          => 'gutter_size',
						'name'        => 'Gutter Size',
						'description' => 'Select the gutter distance between columns',
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
							'css_class' => '.'.$uid.' .znSmartCarousel-container.row'
						)
					),


					/**
					 * Margins and padding
					 */
					array (
						"name"        => __( "Edit the boxes padding & margins for each device breakpoint. ", 'zn_framework' ),
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
						// Markup is reloading so live is purposely disabled
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
						'std'	  => array(
							'top' => '15px',
							'bottom' => '15px',
						),
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
						// Markup is reloading so live is purposely disabled
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
							'type'      => 'class',
							'css_class' => '.'.$uid,
							'val_prepend'  => 'element-scheme--',
						),
					),

				),
			),

			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/') ),
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
