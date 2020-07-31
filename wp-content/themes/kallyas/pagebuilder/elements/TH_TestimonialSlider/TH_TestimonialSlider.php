<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Testimonial Slider
 Description: Create and display a Testimonial Slider element
 Class: TH_TestimonialSlider
 Category: content
 Level: 3
 Scripts: true
*/

/**
 * Class TH_TestimonialSlider
 *
 * Create and display a Testimonial Slider element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_TestimonialSlider extends ZnElements
{
	public static function getName(){
		return __( "Testimonial Slider", 'zn_framework' );
	}

	/**
	 * Load dependant resources
	 */
	function scripts(){
		wp_enqueue_script( 'slick', THEME_BASE_URI . '/addons/slick/slick.min.js', array ( 'jquery' ), ZN_FW_VERSION, true );
	}

	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];

		$elm_classes=array();
		$elm_classes[] = $uid = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);

		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$elm_classes[] = 'tstsld--'.$color_scheme;
		$elm_classes[] = 'element-scheme--'.$color_scheme;

		echo '<div class="testimonials-carousel tst-carousel '.implode(' ', $elm_classes).'" '.$attributes.'>';

		if ( ! empty ( $options['tf_title'] ) ) {
			echo '<h3 class="m_title m_title_ext text-custom tst-carousel-elm-title" '.WpkPageHelper::zn_schema_markup('title').'>' . $options['tf_title'] . '</h3>';
		}

		echo '<div class="tst-carousel-controls"></div>';

		if ( ! empty ( $options['testimonials_slider_single'] ) && is_array( $options['testimonials_slider_single'] ) ) {

			$slick_attributes = array(
				"infinite" => true,
				"slidesToShow" => 1,
				"slidesToScroll" => 1,
				"autoplay" => $this->opt('tf_autoplay',1) == 1 ? true : false,
				"autoplaySpeed" => $this->opt('tf_speed', 5000),
				"arrows" => true,
				"appendArrows" => '.'.$uid.' .tst-carousel-controls',
				"dots" => false,
			);

			echo '<ul class="tst-carousel-list js-slick" data-slick=\''.json_encode($slick_attributes).'\' >';

			foreach ( $options['testimonials_slider_single'] as $test ) {
				if ( ! empty ( $test['tf_single_test'] ) ) {
					echo '<li class="tst-carousel-item u-slick-show1stOnly">';

					echo '<blockquote class="tst-carousel-bqt">' . do_shortcode( $test['tf_single_test'] ) . '</blockquote>';

					echo '<div class="testimonial-author tst-carousel-author">';
						if ( isset($test['ts_author_photo']) && !empty($test['ts_author_photo'])) {
							echo '<div class="testimonial-author--photo tst-carousel-photo">';
							$image = vt_resize( '', $test['ts_author_photo'], '40', '40', true );
							echo '<img class="tst-carousel-img" src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="'. ZngetImageAltFromUrl( $test['ts_author_photo'] ) .'" title="'.ZngetImageTitleFromUrl( $test['ts_author_photo'] ).'">';
							echo '</div>';
						}
						echo '<h5 class="tst-carousel-title">' . $test['tf_single_author'] . '</h5>';
					echo '</div>';

					echo '</li>';
				}
			}
			echo '</ul>';
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
			"name"           => __( "Testimonials", 'zn_framework' ),
			"description"    => __( "Here you can add your testimonials.", 'zn_framework' ),
			"id"             => "testimonials_slider_single",
			"std"            => "",
			"type"           => "group",
			"add_text"       => __( "Testimonial", 'zn_framework' ),
			"remove_text"    => __( "Testimonial", 'zn_framework' ),
			"group_sortable" => true,
			"element_title" => "tf_single_test",
			"subelements"    => array (
				array (
					"name"        => __( "Testimonial", 'zn_framework' ),
					"description" => __( "Please enter the desired testimonial.", 'zn_framework' ),
					"id"          => "tf_single_test",
					"std"         => "",
					"type"        => "textarea"
				),
				array (
					"name"        => __( "Author Photo", 'zn_framework' ),
					"description" => __( "Please select a photo for this author.", 'zn_framework' ),
					"id"          => "ts_author_photo",
					"std"         => "",
					"type"        => "media",
				),
				array (
					"name"        => __( "Testimonial author", 'zn_framework' ),
					"description" => __( "Please enter the desired author for this
											testimonial.", 'zn_framework' ),
					"id"          => "tf_single_author",
					"std"         => "",
					"type"        => "text"
				)
			)
		);

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(
					array (
						"name"        => __( "Title", 'zn_framework' ),
						"description" => __( "Please enter the Testimonials Fader title", 'zn_framework' ),
						"id"          => "tf_title",
						"std"         => "",
						"type"        => "text",
					),
					array (
						"name"        => __( "Auto-play?", 'zn_framework' ),
						"description" => __( "Please select wether you want the carousel to auto-play itself.", 'zn_framework' ),
						"id"          => "tf_autoplay",
						"std"         => "1",
						"value"         => "1",
						"type"        => "toggle2",
					),
					array (
						"name"        => __( "Transition Speed", 'zn_framework' ),
						"description" => __( "Please enter a numeric value for the transition speed. Default is 2500", 'zn_framework' ),
						"id"          => "tf_speed",
						"std"         => "2500",
						"type"        => "text",
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
							'multiple' => array(
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'tstsld--',
								),
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'element-scheme--',
								),
							)
						)
					),
					$extra_options,
				),
			),

			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#oDsttutS1c8') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/testimonial-slider/') ),
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
