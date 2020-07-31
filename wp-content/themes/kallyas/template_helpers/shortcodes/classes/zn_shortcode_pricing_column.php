<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_pricing_column
 *
 * @usage [pricing_column name="" target="_self" highlight="no" price="" price_value="" button_link="" button_text=""] PRICING COLUMNS [/pricing_column]
 */
class zn_shortcode_pricing_column extends HG_Shortcode
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
			'name' => __( 'Pricing column', 'zn_framework' ),
			'section' => __( 'Marketing', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Highlight', 'zn_framework' ),
					'id' => 'highlight',
					'description' => __( 'Choose whether or not to highlight this column', 'zn_framework' ),
					'type' => 'select',
					'value' => 'no',
					'options' => array(
						'no' => __( 'No', 'zn_framework' ),
						'yes' => __( 'Yes', 'zn_framework' ),
					),
				),
				array(
					'name' => __( 'Price', 'zn_framework' ),
					'id' => 'price',
					'description' => __( 'Specify the price', 'zn_framework' ),
					'type' => 'text',
					'value' => '',
					'placeholder' => '$15',
				),
				array(
					'name' => __( 'Price value', 'zn_framework' ),
					'id' => 'price_value',
					'description' => __( 'Specify the currency???', 'zn_framework' ),
					'type' => 'text',
					'value' => '',
					'placeholder' => 'whatever',
				),
				array(
					'name' => __( 'Link text', 'zn_framework' ),
					'id' => 'button_text',
					'description' => __( 'Enter the desired link content text.', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => __( 'Content', 'zn_framework' ),
				),
				array(
					'name' => __( 'URL', 'zn_framework' ),
					'id' => 'button_link',
					'description' => __( 'Enter the desired link url.', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => __( 'URL', 'zn_framework' ),
				),
				array(
					'name' => __( 'Target', 'zn_framework' ),
					'id' => 'target',
					'description' => __( 'Select the desired target for the link.', 'zn_framework' ),
					'type' => 'select',
					'value' => '_self',
					'options' => array(
						'_self' => __( 'Same window', 'zn_framework'),
						'_blank' => __( 'New window', 'zn_framework'),
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
		return 'pricing_column';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$highlight = $name = $price = $price_value = $target = $button_link = $button_text = '';
		// [pricing_column name="" target="_self" highlight="no" price="" price_value="" button_link="" button_text=""] PRICING COLUMNS [/pricing_column]
		extract( shortcode_atts( array( "name" => '',
			"highlight" => false,
			"price" => '',
			"price_value" => '',
			"button_link" => '',
			"button_text" => '',
			"target" => '_self',
		), $atts ) );

		global $hgShPT_columns, $hgShPT_color;
		if( empty($hgShPT_columns) || $hgShPT_columns > 12 ){
			$hgShPT_columns = 4;
		}
		$span = 12 / $hgShPT_columns;

		$is_highlight = ( $highlight == 'no' ? '': 'highlight');

		$pricing = '';
		$pricing .= '<div class="col-sm-' . esc_attr($span) . '">';
		$pricing .= '<div class="pr_table_col ' . esc_html($is_highlight) . '" data-color="' . esc_attr($hgShPT_color) . '">';
		$pricing .= '<div class="tb_header">';
		$pricing .= '<h4 class="ttitle kl-font-alt">' . $name . '</h4>';
		$pricing .= '<div class="price kl-font-alt"><p>' . $price . '<span>' . $price_value . '</span></p></div>';
		$pricing .= '</div>';
		$pricing .= do_shortcode( str_replace( '<ul', '<ul class="tb_content"', $content ) );
		$pricing .= '<div class="signin"><a class="btn" target="' . esc_attr($target) . '" href="' . esc_url($button_link) . '">' .
			$button_text . '</a></div>';
		$pricing .= '</div><!-- end pricing table column -->';
		$pricing .= '</div>';
		return $pricing;
	}
}
