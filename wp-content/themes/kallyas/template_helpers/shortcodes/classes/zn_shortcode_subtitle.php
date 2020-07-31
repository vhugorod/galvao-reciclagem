<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_subtitle
 *
 * @usage [subtitle]CONTENT-HERE[/subtitle]
 */
class zn_shortcode_subtitle extends HG_Shortcode
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
			'name' => __( 'Subtitle', 'zn_framework' ),
			'section' => __( 'Typography', 'zn_framework' ),
			'hasContent' => false,
			'params' => array(
				array(
					'name' => __( 'Content', 'zn_framework' ),
					'id' => 'content',
					'description' => __( 'Enter the subtitle text', 'zn_framework' ),
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
		return 'subtitle';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$output = '';
		if( ! empty($content)) {
			$output = '<h2 class="subtitle page-subtitle">' . do_shortcode( $content ) . '</h2>';
		}
		return $output;
	}

}