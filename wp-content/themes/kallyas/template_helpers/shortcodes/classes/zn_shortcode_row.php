<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_row
 *
 * @usage [row no_margin] Content [/row]
 */
class zn_shortcode_row extends HG_Shortcode
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
			'name' => __( 'Row', 'zn_framework' ),
			'section' => __( 'Layout', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
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
		return 'row';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [row no_margin] Content [/row]
		$class = '';
		if ( isset( $atts[0] ) && trim( $atts[0] ) ) {
			$class = trim( $atts[0] );
		}
		elseif( !empty( $atts['css_class'] ) ){
			$class = $atts['css_class'];
		}
		return '<div class="row ' . esc_attr($class) . '">' . do_shortcode( $content ) . '</div>';
	}

}