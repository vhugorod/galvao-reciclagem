<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_code
 *
 * @usage [code] BUTTON TEXT [/code]
 */
class zn_shortcode_code extends HG_Shortcode
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
			'name' => __( 'Code', 'zn_framework' ),
			'section' => __( 'Typography', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Code content', 'zn_framework' ),
					'id' => 'content',
					'description' => __( 'Enter the desired code you want to display.', 'zn_framework' ),
					'type' => 'textarea',
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
		return 'code';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [code] BUTTON TEXT [/code]
		$content = str_ireplace( array('<br />', '<p>', '</p>'), '', $content );
		return '<pre class="prettyprint linenums">' . htmlentities( $content ) . '</pre>';
	}

}
