<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_two_columns
 *
 * @usage [one_half_column] Content [/one_half_column]
 */
class zn_shortcode_two_columns extends HG_Shortcode
{

	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'one_half_column';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [one_half_column] Content [/one_half_column]
		return '<div class="col-sm-6">' . do_shortcode( $content ) . '</div>';
	}

}