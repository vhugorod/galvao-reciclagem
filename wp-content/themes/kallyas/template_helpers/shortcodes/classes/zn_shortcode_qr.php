<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_qr
 *
 * This class is deprecated, here only for backwards compatibility
 *
 * @usage [qr align="right" size="140"] data [/qr]
 */
class zn_shortcode_qr extends HG_Shortcode
{

	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'qr';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [qr align="right" size="140"] data [/qr]
		$align = $size = '';
		extract( shortcode_atts( array(
			"align" => 'right',
			"size" => '140',
		), $atts ) );
		$data = urlencode( trim( $content ) );
		$return = '<div class="qrCode align' . esc_attr($align) . '" >';
		$return .= '<h6>' . __( 'Scan me!', 'zn_framework' ) . '</h6>';
		$return .= '<img src="http://api.qrserver.com/v1/create-qr-code/?data=' . $data . '&amp;size=' . esc_attr($size) . 'x' .
			esc_attr($size) . '" alt="' . __( "Scan this QR Code!", 'zn_framework' ) . '" class="img-polaroid" />';
		$return .= '</div><!-- end QR Code -->';
		return $return;
	}

}