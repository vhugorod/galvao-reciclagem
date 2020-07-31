<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_two_third_columns
 *
 * @usage [two_third_column] Content [/two_third_column]
 */
class zn_shortcode_two_third_columns extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'two_third_column';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [two_third_column] Content [/two_third_column]
		return '<div class="col-sm-8">' . do_shortcode( $content ) . '</div>';
	}

}