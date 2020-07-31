<?php if(! defined('ABSPATH')){ return; }
/*
Name: Smart Carousel
Description: Create and display a slider or carousel element
Class: ZnSmartCarousel2
Category: Content
Level: 3
Scripts: true
Keywords: carousel, slider, slick
Multiple: true
*/

class ZnSmartCarousel2 extends ZionElement
{

	function options() {
		// Load Options
		require dirname(__FILE__).'/options.inc.php';
		return $options;
	}

	function element() {

		$options = $this->data['options'];

		if( empty( $options ) ){
			return;
		}

		$classes = $attributes = array();
		$uid = $this->data['uid'];
		$pb_active = ZNB()->utility->isActiveEditor();
		$source = $this->opt('source', 'bulk');
		$top_values = array('top-left', 'top-center', 'top-right');
		$dots_pos_top = $this->opt('dots_pos', 'bottom-center');
		$bullets_on_top = in_array($dots_pos_top, $top_values) ? 'bullets-on-top' : '';

		/**
		 * Element Classes and Attributes
		 */
		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-SliderEl';
		$classes[] = 'clearfix';
		$classes[] = $bullets_on_top;
		$classes[] = $pb_active ? 'zn-SliderEl--edit' : 'zn-SliderEl--view';
		$classes[] = $source == 'pb' ? 'zn-SliderEl--pb' : '';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		/**
		 * Source of the slides
		 */
		$items = array();
		if( $source == 'bulk' ){
			$items = explode(',', $this->opt('bulk_images', ''));
		}
		elseif( $source == 'pb' ){
			$items = $this->opt('single_item', '');
		}

		/**
		 * Check for items
		 */
		if (count($items) == 0) return;

		/**
		 * Slick Options
		 */
		// Defaults
		$slick_attributes = array(
			"slidesToShow" => (int) $this->opt('slidesToShow','3'),
			"responsive" => array()
		);

		$arrows = $this->opt('arrows','yes');
		$dots = $this->opt('dots','no');

		// Basic
		$slick_attributes['autoplay']       = $this->check_option( $this->opt('autoplay','no'), 'no', true );
		$slick_attributes['autoplaySpeed']  = $this->check_option( (int) $this->opt('autoplaySpeed','3000'), '3000' );
		$slick_attributes['dots']           = $this->check_option( $dots, 'no', true );
		$slick_attributes['fade']           = $this->check_option( $this->opt('fade','no'), 'no', true );
		$slick_attributes['arrows']         = $this->check_option( $arrows, 'yes', true );
		$slick_attributes['infinite']       = $this->check_option( $this->opt('infinite','yes'), 'yes', true );
		$slick_attributes['slidesToScroll'] = $this->check_option( (int) $this->opt('slidesToScroll','1'), '1' );
		$slick_attributes['speed']          = $this->check_option( (int) $this->opt('speed','300'), '300' );
		$slick_attributes['swipe']          = $this->check_option( $this->opt('swipe','yes'), 'yes', true );

		// Advanced
		if( $this->opt('advanced_options','') == 'yes' ){
			$slick_attributes['accessibility']    = $this->check_option( $this->opt('accessibility','yes'), 'yes', true );
			$slick_attributes['adaptiveHeight']   = $this->check_option( $this->opt('adaptiveHeight','no'), 'no', true );
			$slick_attributes['centerMode']       = $this->check_option( $this->opt('centerMode','no'), 'no', true );
			$slick_attributes['centerPadding']    = $this->check_option( $this->opt('centerPadding','50px'), '50px' );
			$slick_attributes['cssEase']          = $this->check_option( $this->opt('cssEase','ease'), 'ease' );
			$slick_attributes['dragging']         = $this->check_option( $this->opt('dragging','yes'), 'yes', true );
			$edgeFriction                         = $this->opt('edgeFriction','0.15');
			$slick_attributes['edgeFriction']     = $this->check_option( floatval( $edgeFriction ), '0.15' );
			$slick_attributes['lazyLoad']         = $this->check_option( $this->opt('lazyLoad','no'), 'no' );
			$slick_attributes['pauseOnFocus']     = $this->check_option( $this->opt('pauseOnFocus','yes'), 'yes', true );
			$slick_attributes['pauseOnHover']     = $this->check_option( $this->opt('pauseOnHover','yes'), 'yes', true );
			$slick_attributes['pauseOnDotsHover'] = $this->check_option( $this->opt('pauseOnDotsHover','no'), 'no', true );
			$slick_attributes['respondTo']        = $this->check_option( $this->opt('respondTo','window'), 'window' );
			$slick_attributes['rows']             = $this->check_option( (int) $this->opt('rows','1'), '1' );
			$slick_attributes['slidesPerRow']     = $this->check_option( (int) $this->opt('slidesPerRow','1'), '1' );
			$slick_attributes['swipeToSlide']     = $this->check_option( $this->opt('swipeToSlide','no'), 'no', true );
			$slick_attributes['touchMove']        = $this->check_option( $this->opt('touchMove','yes'), 'yes', true );
			$slick_attributes['touchThreshold']   = $this->check_option( (int) $this->opt('touchThreshold','5'), '5' );
			$slick_attributes['variableWidth']    = $this->check_option( $this->opt('variableWidth','no'), 'no', true );
			$slick_attributes['vertical']         = $this->check_option( $this->opt('vertical','no'), 'no', true );
			$slick_attributes['verticalSwiping']  = $this->check_option( $this->opt('verticalSwiping','no'), 'no', true );
			$slick_attributes['rtl']              = $this->check_option( $this->opt('rtl','no'), 'no', true );
			$slick_attributes['waitForAnimate']   = $this->check_option( $this->opt('waitForAnimate','yes'), 'yes', true );
			$slick_attributes['asNavFor']         = $this->check_option( $this->opt('asNavFor',''), '' );

			// Breakpoint settings
			if( ($resp = $this->opt('responsive', array())) && !empty($resp) ){
				$slick_attributes["responsive"] = array();
				foreach ($resp as $key => $r) {
					if( isset($r['breakpoint']) && !empty($r['breakpoint']) ){
						$slick_attributes["responsive"][$key]['breakpoint'] = (int) $r['breakpoint'];
					}
					if( isset($r['slidesToShow']) && !empty($r['slidesToShow']) ){
						$slick_attributes["responsive"][$key]['settings']['slidesToShow'] = (int) $r['slidesToShow'];
					}
					if( isset($r['slidesToScroll']) && !empty($r['slidesToScroll']) ){
						$slick_attributes["responsive"][$key]['settings']['slidesToScroll'] = (int) $r['slidesToScroll'];
					}
					if( isset($r['arrows']) && !empty($r['arrows']) ){
						$slick_attributes["responsive"][$key]['settings']['arrows'] = $this->is_true($r['arrows']);
					}
					if( isset($r['dots']) && !empty($r['dots']) ){
						$slick_attributes["responsive"][$key]['settings']['dots'] = $this->is_true($r['dots']);
					}
					if( isset($r['unslick']) && $r['unslick'] == 'yes' ){
						$slick_attributes["responsive"][$key]['settings'] = 'unslick';
					}
				}
			}
		}


		$nav_output = $top_nav_output = $bottom_nav_output = $dots_pos = '';

		/**
		 * Navigation
		 */
		if( $arrows == 'yes' ){

			// Append Arrows to specific location
			$slick_attributes['appendArrows'] = '.'. $uid . ' .zn-SliderNav';

			$arrows_pos = $this->opt('arrow_pos', 'middle');

			$nav_classes[] = 'zn-SliderNav';
			$nav_classes[] = 'zn-SliderNav--pos-'.$arrows_pos;
			$nav_classes[] = 'zn-SliderNav--style'.$this->opt('arrow_style', '1');
			$nav_classes[] = 'zn-SliderNav--size-'.$this->opt('arrows_size', 'normal');
			$nav_classes[] = 'zn-SliderNav--round-'.$this->opt('arrows_rounded', 'yes');
			$nav_classes[] = 'zn-SliderNav--theme-'.$this->opt('arrows_theme', 'dark');

			$nav_output .= '<div class="'.zn_join_spaces($nav_classes).'"></div>';

			if( in_array($arrows_pos, array( 'top-left', 'top-center', 'top-right', 'middle' )) ){
				$top_nav_output = $nav_output;
			}

			if( in_array($arrows_pos, array( 'bottom-left', 'bottom-center', 'bottom-right' )) ){
				$bottom_nav_output = $nav_output;
			}
		}

		/**
		 * Dots
		 */

		if( $dots == 'yes' ){
			$dots_pos = $this->opt('dots_pos', 'bottom-center');

			$dots_classes[] = 'slick-dots';
			$dots_classes[] = 'zn-SliderDots';
			$dots_classes[] = 'zn-SliderDots--pos-'.$dots_pos;
			$dots_classes[] = 'zn-SliderDots--theme-'.$this->opt('dots_theme', 'dark');

			// Add custom class to Dots
			$slick_attributes['dotsClass'] = zn_join_spaces($dots_classes);
			$slick_attributes['appendDots'] = '.'. $uid;
		}


		/**
		 * Slider Markup
		 */

		if( $pb_active && $source == 'pb' ){

			$slick_attributes['autoplay'] = false;
			$slick_attributes['swipe'] = false;
			$slick_attributes['dragging'] = false;
			$slick_attributes['dots'] = true;
			$slick_attributes['infinite'] = false;
			$slick_attributes['slidesToScroll'] = 1;

			if($slick_attributes['fade']){
				$classes[] = 'zn-SliderEl--fade';
			}
		}


		$slick_attributes = array_filter( $slick_attributes, array($this, 'filter_empty') );
		$slider_attr[] = 'data-slick=\''.json_encode($slick_attributes).'\'';

		echo '<div class="'.zn_join_spaces($classes).'" '. zn_join_spaces($attributes ) .'>';

			echo $top_nav_output;

			/**
			 * Slider Classes and Attributes
			 */
			$slider_classes[] = 'zn-Slider';
			$slider_classes[] = 'zn-Slider-'.$uid;
			$slider_classes[] = 'zn-Slider--cols' . $slick_attributes['slidesToShow'];
			$slider_classes[] = 'js-slick'; // Activate Slider;
			$slider_classes[] = 'mfp-gallery mfp-gallery--misc'; // Activate Modals and Gallery mode;

			echo '<div class="'. zn_join_spaces($slider_classes) .'" '. zn_join_spaces($slider_attr) .'>';

				foreach ($items as $i => $item) {

					echo '<div class="zn-Slider-item" data-title="'. ( isset($item['title']) ? $item['title']:'' ) .'">';
					echo '<div class="zn-Slider-itemInner">';

					if( $source == 'bulk' ) {

						$attachment_id = $item;
						$attachment_size = $this->opt('image_size','medium_large');
						$attachment_url = wp_get_attachment_url( $attachment_id );
						$attachment_title = get_the_title($attachment_id);
						$attachment_attr = array(
							'class' => 'zn-Slider-img'
						);

						$img = wp_get_attachment_image( $attachment_id, $attachment_size, false, $attachment_attr );

						if( $this->opt('lazyLoad','yes') == 'yes' ){
							$sizes = zn_get_image_size($attachment_size);
							$img = sprintf(
								'<img data-lazy="%s" title="%s" alt="%s" class="%s" %s />',
								$attachment_url,
								$attachment_title,
								get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
								$attachment_attr['class'],
								image_hwstring( $sizes['width'], $sizes['height'] )
							);
						}

						echo sprintf('<a href="%s" title="%s" class="%s" data-lightbox="mfp" data-mfp="image" >%s</a>',
							$attachment_url,
							$attachment_title,
							'zn-Slider-link',
							$img
						);
					}

					elseif ($source == 'pb'){

						// Add complex page builder element
						echo znb_get_column_container(array(
							'cssClasses' => 'row zn-Slider-itemRow'
						));

							if ( empty( $this->data['content'][$i] ) ) {
								$column = ZNB()->frontend->addModuleToLayout( 'ZnColumn', array() , array(), 'col-sm-12' );
								$this->data['content'][$i] = array ( $column );
							}

							if ( !empty( $this->data['content'][$i] ) ) {
								ZNB()->frontend->renderContent( $this->data['content'][$i] );
							}

						echo '</div>'; // end znSmartCarousel-container
					}

					echo '</div>';
					echo '</div>';
				}

			echo '</div>';

			echo $bottom_nav_output;

		echo '</div>';

	}

	function filter_empty($v){
		return $v !== '';
	}

	function check_option($val = '', $default = '', $bool = false){
		if( $val == $default ){
			return '';
		}
		if( $bool ){
			$val = $this->is_true($val);
		}
		return $val;
	}

	function is_true($v){
		return ($v == 'yes' || $v == 'true' || $v == '1') ? true : false;
	}

	function css(){

		$uid = $this->data['uid'];
		$css = '';

		// Margins
		$margins = array();
		if($this->opt('margin_lg', '' )) $margins['lg'] = $this->opt('margin_lg');
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

		if( ($nav_offset = $this->opt('arrows_offset', '0')) && $nav_offset != 0 ){
			$css .= '.'.$uid.' .znSlickNav-prev {margin-right:'. $nav_offset .'px}';
			$css .= '.'.$uid.' .znSlickNav-next {margin-left:'. $nav_offset .'px}';
		}


		$nav_vertical_offset = $this->opt( 'arrows_vertical_offset','50' );
		if ( ! empty( $nav_vertical_offset ) ) {
			$css .= '.'.$uid.' .zn-SliderNav.zn-SliderNav--style2.zn-SliderNav--pos-middle .znSlickNav-arr, .' .$uid . ' .zn-SliderNav.zn-SliderNav--style3.zn-SliderNav--pos-middle .znSlickNav-arr{top: calc('.$nav_vertical_offset.'% - 1.3em);}';
		}

		return $css;
	}

	function scripts() {
		wp_enqueue_script( 'slick', get_template_directory_uri() . '/addons/slick/slick.min.js', array ( 'jquery' ), true, true );
	}
}

