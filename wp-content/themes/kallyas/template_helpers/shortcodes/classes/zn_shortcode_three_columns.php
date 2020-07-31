<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_three_columns
 *
 * @usage [one_third_column] Content [/one_third_column]
 */
class zn_shortcode_three_columns extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'one_third_column';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [one_third_column] Content [/one_third_column]
		return '<div class="col-sm-4">' . do_shortcode( $content ) . '</div>';
	}

}