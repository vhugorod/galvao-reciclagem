<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_one_fourth_columns
 *
 * @usage [one_fourth_column] Content [/one_fourth_column]
 */
class zn_shortcode_one_fourth_columns extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'one_fourth_column';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [one_fourth_column] Content [/one_fourth_column]
		return '<div class="col-sm-3">' . do_shortcode( $content ) . '</div>';
	}

}