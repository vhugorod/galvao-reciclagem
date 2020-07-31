<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}
//??? usage??
$hgShPT_columns = '';
$hgShPT_color = '';

/**
 * Class zn_shortcode_pricing_table
 *
 * @usage [pricing_table color="red" columns="4" space="no" rounded="no"] PRICING COLUMNS [/pricing_table]
 */
class zn_shortcode_pricing_table extends HG_Shortcode
{

	/**
	 * Internal flag to know whether or not the scripts were loaded
	 * @var bool
	 */
	private $_scriptsLoaded = false;

	/**
	 * Retrieve the information about this shortcode
	 * @see hg-framework/assets/src/js/admin/shortcodes/shortcodes.js
	 * @return array
	 */
	public function getInfo()
	{
		return array(
			'id' => $this->getTag(),
			'name' => __( 'Pricing table container', 'zn_framework' ),
			'section' => __( 'Marketing', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Color', 'zn_framework' ),
					'id' => 'color',
					'description' => __( 'Choose the desired pricing table color', 'zn_framework' ),
					'type' => 'select',
					'value' => 'red',
					'options' => array(
						'red' => __( 'Red', 'zn_framework' ),
						'blue' => __( 'Blue', 'zn_framework' ),
						'style3' => __( 'Style 3', 'zn_framework' ),
						'turquoise' => __( 'Turquoise', 'zn_framework' ),
						'orange' => __( 'Orange', 'zn_framework' ),
						'purple' => __( 'Purple', 'zn_framework' ),
						'yellow' => __( 'Yellow', 'zn_framework' ),
						'green_lemon' => __( 'Green lemon', 'zn_framework' ),
						'dark' => __( 'Dark', 'zn_framework' ),
						'light' => __( 'Light', 'zn_framework' ),
					),
				),
				array(
					'name' => __( 'Columns', 'zn_framework' ),
					'id' => 'columns',
					'description' => __( 'Choose how many columns you want to use for this table', 'zn_framework' ),
					'type' => 'select',
					'value' => '4',
					'options' => array(
						'1' => __( '1 Column', 'zn_framework' ),
						'2' => __( '2 Columns', 'zn_framework' ),
						'3' => __( '3 Columns', 'zn_framework' ),
						'4' => __( '4 Columns', 'zn_framework' ),
						'6' => __( '6 Columns', 'zn_framework' ),
					),
				),
				array(
					'name' => __( 'Use rounded corners ?', 'zn_framework' ),
					'id' => 'rounded',
					'description' => __( 'Choose if you want to use rounded corners or not', 'zn_framework' ),
					'type' => 'select',
					'value' => 'no',
					'options' => array(
						'no' => __( 'No', 'zn_framework' ),
						'yes' => __( 'Yes', 'zn_framework' ),
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
		return 'pricing_table';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		global $hgShPT_columns, $hgShPT_color;
		$space = $rounded = '';
		// Colors : red , blue , green , turquoise , orange , purple , yellow , green_lemon , dark , light
		// Space : no-space
		// [pricing_table color="red" columns="4" space="no" rounded="no"] PRICING COLUMNS [/pricing_table]

		//#! Set defaults
		$atts = shortcode_atts( array(
			"columns" => '4',
			"color" => 'red',
			"rounded" => 'no',
			"space" => false ), $atts
		);
		extract( $atts );

		$hgShPT_columns = intval($atts['columns']);
		$hgShPT_color = esc_attr($color);
		if ( $space == 'no' ) {
			$space = 'no-space';
		}
		if ( $rounded == 'yes' ) {
			$rounded = 'rounded-corners';
		}
		$pricing = '<div class="row pricing_table ' . esc_attr($space) . ' ' . esc_attr($rounded) . '">';
		$pricing .= do_shortcode( $content );
		$pricing .= '</div>';
		return $pricing;
	}

	public function scripts()
	{
		// Load scripts
		wp_enqueue_style('pricing_table-css', THEME_BASE_URI . '/css/shortcodes/pricing_table.css', array('kallyas-styles'), ZN_FW_VERSION);
	}
}
