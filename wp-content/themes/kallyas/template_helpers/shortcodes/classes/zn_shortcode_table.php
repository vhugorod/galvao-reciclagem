<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_table
 *
 * @usage [table type="table-striped"] Your table HTML here [/table]
 */
class zn_shortcode_table extends HG_Shortcode
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
			'name' => __( 'Table', 'zn_framework' ),
			'section' => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Style', 'zn_framework' ),
					'id' => 'style',
					'description' => __( 'Choose the desired style you want to use.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'table-striped',
					'options' => array(
						'table-striped' => __( 'Striped', 'zn_framework'),
						'table-bordered' => __( 'Bordered', 'zn_framework'),
						'table-hover' => __( 'Hover', 'zn_framework'),
						'table-condensed' => __( 'Condensed', 'zn_framework'),
					),
				),
				array(
					'name' => __( 'Table content', 'zn_framework' ),
					'id' => 'content',
					'description' => sprintf(
						__( 'Enter the table html here. <br/>Ex: %s', 'zn_framework' ),
						esc_html('<table><thead>etc...</thead></table>')),
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
		return 'table';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// TYPE : table , table-striped , table-bordered , table-hover , table-condensed
		// [table type="table-striped"] Content [/table]
		$type = '';
		extract( shortcode_atts( array( "type" => 'table-striped' ), $atts ) );
		return do_shortcode( str_replace( '<table', '<table class="table ' . esc_attr($type) . '"', $content ) );
	}
}