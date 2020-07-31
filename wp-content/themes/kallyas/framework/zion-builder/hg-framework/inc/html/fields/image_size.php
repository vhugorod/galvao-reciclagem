<?php

class ZnHgFw_Html_Image_Size extends ZnHgFw_BaseFieldType{

	var $type = 'image_size';

	function render( $value ) {
		$output = '';

		if(! is_array($value) || !isset($value['id'])){
			return $output;
		}

		$image_size = $value['std'];

		if( empty($image_size) || is_scalar($image_size) ) {
			$image_size = array();
		}

		if ( empty( $image_size['width'] ) ) {
			$image_size['width'] = '';
		}

		if ( empty( $image_size['height'] ) ) {
			$image_size['height'] = '';
		}

		$output .= '<div class="zn_image_size">';
		$output .= '<div>';
		$output .= '<label>' . __( 'Width', 'zn_framework' ) . '</label>';
		$output .= '<input type="text" value="' . $image_size['width'] . '" id="' . $value['id'] . '_width" name="' . $value['id'] . '[width]" class="zn_input zn_input_text">';
		$output .= '</div>';
		$output .= '<div class="separator">' . __( 'X', 'zn_framework' ) . '</div>';
		$output .= '<div>';
		$output .= '<label>' . __( 'Height', 'zn_framework' ) . '</label>';
		$output .= '<input type="text" value="' . $image_size['height'] . '" id="' . $value['id'] . '_height" name="' . $value['id'] . '[height]" class="zn_input zn_input_text">';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

}
