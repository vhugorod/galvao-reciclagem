<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_column
 *
 * @usage [znhg_column size="col-sm-6" class="custom-class"] Content [/znhg_column]
 */
class zn_shortcode_column extends HG_Shortcode
{

	/**
	 * Retrieve the information about this shortcode
	 * @see hg-framework/assets/src/js/admin/shortcodes/shortcodes.js
	 * @return array
	 */
	public function getInfo(){
		return array(
			'id' => $this->getTag(),
			'name' => __( 'Column', 'zn_framework' ),
			'section' => __( 'Layout', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Column size', 'zn_framework' ),
					'id' => 'size',
					'description' => __( 'Choose the desired column size.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'col-sm-6',
					'options' => array(
						'col-sm-6' => __( '1/2', 'zn_framework'),
						'col-sm-4' => __( '1/3', 'zn_framework'),
						'col-sm-3' => __( '1/4', 'zn_framework'),
						'col-sm-8' => __( '2/3', 'zn_framework'),
						'col-sm-9' => __( '3/4', 'zn_framework'),
					),
				),
				array(
					'name' => __( 'Css Class', 'zn_framework' ),
					'id' => 'css_class',
					'description' => __( 'Enter the desired css class name that will be applied to this row.', 'zn_framework' ),
					'type' => 'text',
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
		return 'znhg_column';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$atts = shortcode_atts( array(
			"size" => 'col-sm-6',
			"css_class" => '',
		), $atts );
		return '<div class="'.esc_attr($atts['size']).' '.esc_attr($atts['css_class']).'">' . do_shortcode( $content ) . '</div>';
	}

}