<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_three_fourth_columns
 *
 * @usage [three_fourth_column] Content [/three_fourth_column]
 */
class zn_shortcode_three_fourth_columns extends HG_Shortcode
{


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'three_fourth_column';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [three_fourth_column] Content [/three_fourth_column]
		return '<div class="col-sm-9">' . do_shortcode( $content ) . '</div>';
	}

}