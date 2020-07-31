<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_pricing_caption
 *
 * @usage [pricing_caption name=""] PRICING COLUMNS [/pricing_caption]
 */
class zn_shortcode_pricing_caption extends HG_Shortcode
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
			'name' => __( 'Pricing table caption', 'zn_framework' ),
			'section' => __( 'Marketing', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Name', 'zn_framework' ),
					'id' => 'name',
					'description' => __( 'Enter the desired pricing caption name', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => __( 'Column name', 'zn_framework' ),
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
		return 'pricing_caption';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$name = '';

		// [pricing_caption name=""] PRICING COLUMNS [/pricing_caption]
		extract( shortcode_atts( array( "name" => '' ), $atts ) );

		global $hgShPT_columns, $hgShPT_color;
		if( empty($hgShPT_columns) || $hgShPT_columns > 12 ){
			$hgShPT_columns = 4;
		}
		$span = 12 / $hgShPT_columns;

		$pricing = '';
		$pricing .= '<div class="col-sm-' . esc_attr($span) . '">';
		$pricing .= '<div class="pr_table_col caption_column" data-color="' . esc_attr($hgShPT_color) . '">';
		$pricing .= '<div class="tb_header">';
		$pricing .= $name;
		$pricing .= '</div>';
		$pricing .= do_shortcode( str_replace( '<ul', '<ul class="tb_content"', $content ) );
		$pricing .= '</div><!-- end pricing table column -->';
		$pricing .= '</div>';
		return $pricing;
	}
}
