<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_heading
 *
 * @usage [znhg_alternative_header heading_type="h1"] Content [/znhg_alternative_header]
 */
class znhg_alternative_header extends HG_Shortcode
{

	/**
	 * Retrieve the information about this shortcode
	 * @see hg-framework/assets/src/js/admin/shortcodes/shortcodes.js
	 * @return array
	 */
	public function getInfo()
	{
		return array(
			'id' => $this->getTag(),
			'name' => __( 'Alternative heading', 'zn_framework' ),
			'section' => __( 'Typography', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Heading type', 'zn_framework' ),
					'id' => 'heading_type',
					'description' => __( 'Choose what alternative heading type you want to use', 'zn_framework' ),
					'type' => 'select',
					'value' => 'h1',
					'options' => array(
						'h1' => 'h1',
						'h2' => 'h2',
						'h3' => 'h3',
						'h4' => 'h4',
						'h5' => 'h5',
						'h6' => 'h6',
					),
				),
				array(
					'name' => __( 'Heading text', 'zn_framework' ),
					'id' => 'content',
					'description' => __( 'Please enter the heading text you want to use', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => 'heading text',
				),
			),
		);
	}


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'znhg_alternative_header';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$atts = shortcode_atts(
			array(
				'heading_type' => 'h1'
			), $atts, 'bartag' );

		$shortcode_function = 'zn_shortcode_'.$atts['heading_type'] .'a';
		if( function_exists( $shortcode_function ) ){
			return call_user_func($shortcode_function, $atts, $content );
		}
		return false;
	}

}