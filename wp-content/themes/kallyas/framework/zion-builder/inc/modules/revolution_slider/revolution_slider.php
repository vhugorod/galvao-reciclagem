<?php if(! defined('ABSPATH')){ return; }

/**
 * Class ZNB_RevolutionSlider
 *
 * Create and display a Revolution Slider element
 *
 * @package  Zion builder
 * @author   Team Hogash
 * @since    1.0.0
 */
class ZNB_RevolutionSlider extends ZionElement
{

	function canLoad(){
		return class_exists('UniteBaseClassRev');
	}

	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];

		// Don't show anything if a slider wasn't selected
		if( empty( $options['revslider_id'] ) ){ return; }

		$classes=array();
		$classes[] = $this->data['uid'];
		$classes[] = zn_get_element_classes($options);

		?>
		<div class="znb-revolution-slider <?php echo implode(' ', $classes); ?>" <?php zn_the_element_attributes($options); ?>>

			<?php
				if(isset($options['revslider_id']) && ! empty($options['revslider_id']) ){
					echo do_shortcode( '[rev_slider alias="' . $options['revslider_id'] . '"]' );
				}
			?>

		</div>
		<?php
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		global $wpdb;
		$revslider_options = array(''=> 'No slider');
		if ( class_exists('RevSliderFront') ) {
			// Table name
			$table_name = $wpdb->prefix . "revslider_sliders";
			// Get sliders
			$rev_sliders = $wpdb->get_results( "SELECT title,alias FROM $table_name" );
			// Iterate over the sliders
			if(! empty($rev_sliders)) {
				foreach ($rev_sliders as $key => $item) {
					if (isset($item->alias) && isset($item->title)) {
						$revslider_options[$item->alias] = $item->title;
					}
				}
			}
		}

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(
					array (
						"name"        => __( "Select slider", 'zn_framework' ),
						"description" => __( "Select the desired slider you want to use. Please note that the slider can be created
									from inside the Revolution Slider options page.", 'zn_framework' ),
						"id"          => "revslider_id",
						"std"         => "",
						"type"        => "select",
						"options"     => $revslider_options
					),
				),
			),


			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				// 'docs'    => 'https://my.hogash.com/documentation/revolution-slider/',
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}

ZNB()->elements_manager->registerElement( new ZNB_RevolutionSlider(array(
	'id' => 'Znb_RevolutionSlider',
	'name' => __('Revolution slider', 'zn_framework'),
	'description' => __('This element will display a Revolution Slider.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content, Media',
	'legacy' => false,
	'keywords' => array(),
)));
