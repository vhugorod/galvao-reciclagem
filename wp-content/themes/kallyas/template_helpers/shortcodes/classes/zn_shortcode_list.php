<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_list
 *
 * @usage [list type="list-style1"] Content [/list]
 */
class zn_shortcode_list extends HG_Shortcode
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
			'name' => __( 'List', 'zn_framework' ),
			'section' => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'List style', 'zn_framework' ),
					'id' => 'type',
					'description' => __( 'Choose the desired list style you want to use.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'list-style1',
					'options' => array(
						'list-style1' => __( 'Arrow list', 'zn_framework'),
						'list-style2' => __( 'Check list', 'zn_framework'),
					),
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
		return 'list';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// TYPE : list-style1 , list-style2
		// [list type="list-style1"] Content [/list]
		$type = '';
		extract( shortcode_atts( array( "type" => 'list-style1' ), $atts ) );
		return str_replace( '<ul', '<ul class="' . $type . '"', do_shortcode( $content ) );
	}

}