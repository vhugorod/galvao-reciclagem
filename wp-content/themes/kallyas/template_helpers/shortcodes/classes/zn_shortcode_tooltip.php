<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_tooltip
 *
 * @usage [tooltip placement="" border="yes" title="My title"] Content [/tooltip]
 */
class zn_shortcode_tooltip extends HG_Shortcode
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
			'name' => __( 'Tooltip', 'zn_framework' ),
			'section' => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Tooltip content', 'zn_framework' ),
					'id' => 'content',
					'description' => __( 'Enter the desired tooltip anchor text.', 'zn_framework' ),
					'type' => 'textarea',
				),
				array(
					'name' => __( 'Tooltip title', 'zn_framework' ),
					'id' => 'title',
					'description' => __( 'Enter the desired tooltip content.', 'zn_framework' ),
					'type' => 'text',
				),
				array(
					'name' => __( 'Tooltip placement', 'zn_framework' ),
					'id' => 'placement',
					'description' => __( 'Choose the desired tooltip placement.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'top',
					'options' => array(
						'top' => __( 'Top', 'zn_framework'),
						'right' => __( 'Right', 'zn_framework'),
						'bottom' => __( 'Bottom', 'zn_framework'),
						'left' => __( 'Left', 'zn_framework'),
					),
				),
				array(
					'name' => __( 'Use border ?', 'zn_framework' ),
					'id' => 'border',
					'description' => __( 'Choose yes if you want to add a border around the tooltip.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'false',
					'options' => array(
						'yes' => __( 'Yes', 'zn_framework'),
						'no' => __( 'No', 'zn_framework'),
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
		return 'tooltip';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$title = $placement = $border = '';

		// [tooltip placement="" border="yes" title="My title"] Content [/tooltip]
		extract( shortcode_atts( array(
			"placement" => 'top',
			"border" => 'yes',
			"title" => '',
		), $atts ) );
		if ( empty ( $placement ) ) {
			$placement = 'top';
		}

		$border = ( $border == 'yes' ? 'stronger' : '');
		return '<span class="' . esc_attr($border) . '" data-rel="tooltip" data-placement="' . esc_attr($placement) . '" title="' . esc_attr($title) . '" data-animation="true">' . $content . '</span>';
	}

}
