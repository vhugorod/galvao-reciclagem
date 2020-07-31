<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_buttons
 *
 * @usage [button style="btn-primary" url="" size="" block="false" target="_self"] BUTTON TEXT [/button]
 */
class zn_shortcode_buttons extends HG_Shortcode
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
			'name' => __( 'Button', 'zn_framework' ),
			'section' => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Style', 'zn_framework' ),
					'id' => 'style',
					'description' => __( 'Select the desired style you want to use for this button.', 'zn_framework' ),
					'type' => 'select',
					'value' => '',
					'options' => array(
						'' => __( 'Default', 'zn_framework'),
						'btn-primary' => __( 'Primary', 'zn_framework'),
						'btn-info' => __( 'Info', 'zn_framework'),
						'btn-success' => __( 'Success', 'zn_framework'),
						'btn-warning' => __( 'Warning', 'zn_framework'),
						'btn-danger' => __( 'Danger', 'zn_framework'),
						'btn-inverse' => __( 'Inverse', 'zn_framework'),
					),
				),
				array(
					'name' => __( 'Button content', 'zn_framework' ),
					'id' => 'content',
					'description' => __( 'Enter the desired button content text.', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => __( 'Content', 'zn_framework' ),
				),
				array(
					'name' => __( 'URL', 'zn_framework' ),
					'id' => 'url',
					'description' => __( 'Enter the desired button url.', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => __( 'URL', 'zn_framework' ),
				),
				array(
					'name' => __( 'Target', 'zn_framework' ),
					'id' => 'target',
					'description' => __( 'Select the desired target for this button.', 'zn_framework' ),
					'type' => 'select',
					'value' => '_self',
					'options' => array(
						'_self' => __( 'Same window', 'zn_framework'),
						'_blank' => __( 'New window', 'zn_framework'),
					),
				),
				array(
					'name' => __( 'Size', 'zn_framework' ),
					'id' => 'size',
					'description' => __( 'Select the desired szie for this button.', 'zn_framework' ),
					'type' => 'select',
					'value' => '',
					'options' => array(
						'' => __( 'Default', 'zn_framework'),
						'btn-lg' => __( 'Large', 'zn_framework'),
						'btn-md' => __( 'Medium', 'zn_framework'),
						'btn-sm' => __( 'Small', 'zn_framework'),
						'btn-xs' => __( 'Extra small', 'zn_framework'),
					),
				),
				array(
					'name' => __( 'Block ?', 'zn_framework' ),
					'id' => 'block',
					'description' => __( 'Select if you want to display the button as block or not.', 'zn_framework' ),
					'type' => 'select',
					'value' => '',
					'options' => array(
						'' => __( 'Normal', 'zn_framework'),
						'btn-block' => __( 'Block', 'zn_framework'),
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
		return 'button';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$url = $size = $style = $block = $target = '';
		// [button style="btn-primary" url="" size="" block="false" target="_self"] BUTTON TEXT [/button]
		extract( shortcode_atts( array( "style" => '',
			"size" => '',
			"block" => '',
			"url" => '',
			"target" => '' ), $atts ) );
		return ' <a href="' . esc_url($url) . '" class="btn ' . esc_attr($size) . ' ' . esc_attr($style) . ' ' . esc_attr($block) . '" target="' . esc_attr($target) . '">' . $content . '</a>';
	}
}
