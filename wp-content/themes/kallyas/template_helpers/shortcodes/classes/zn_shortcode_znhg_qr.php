<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_znhg_qr
 *
 * @usage [znhg_qr align="right" url="YOUR QR CODE URL"]
 */
class zn_shortcode_znhg_qr extends HG_Shortcode
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
			'name' => __( 'Qr Code', 'zn_framework' ),
			'section' => __( 'Marketing', 'zn_framework' ),
			'hasContent' => false,
			'params' => array(
				array(
					'name' => __( 'QR code URL', 'zn_framework' ),
					'id' => 'url',
					'description' => sprintf(__( 'Enter the QR code url generated from <a target="_blank" href="%s">QR code generator</a>', 'zn_framework' ), esc_url('http://goqr.me/')),
					'type' => 'text',
					'placeholder' => 'QR code URL',
				),
				array(
					'name' => __( 'Alignment', 'zn_framework' ),
					'id' => 'align',
					'description' => __( 'Choose the quote alignment.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'left',
					'options' => array(
						'left' => __( 'Left', 'zn_framework'),
						'right' => __( 'Right', 'zn_framework'),
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
		return 'znhg_qr';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [znhg_qr align="right" url="YOUR QR CODE URL"]
		extract( shortcode_atts( array(
			"align" => 'right',
			"url" => '',
		), $atts ) );
		$data = urlencode( trim( $content ) );
		$return = '<div class="qrCode align' . esc_attr($align) . '" >';
		$return .= '<h6>' . __( 'Scan me!', 'zn_framework' ) . '</h6>';
		$return .= '<img src="'.$url.'" alt="' . __( "Scan this QR Code!", 'zn_framework' ) . '" class="img-polaroid" />';
		$return .= '</div>';
		return $return;
	}

}