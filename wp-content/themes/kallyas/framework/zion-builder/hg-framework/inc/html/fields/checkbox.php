<?php

class ZnHgFw_Html_Checkbox extends ZnHgFw_BaseFieldType{

	var $type = 'checkbox';

	function render ( $value ) {

		$output = '';

		if ( empty($value['options']) || !is_array($value['options']) ) {
			return;
		}

		// Determine if radio-type toggle button group
		$is_radio = '';
		if ( isset( $value['supports']) && !empty( $value['supports'] ) ) {
			if ( in_array( 'zn_radio', $value['supports'] ) ){
				$is_radio = 'zn_radio';
			}
		}

		$output .= '<div class="zn_checkbox_wrapper '.$is_radio.'">';

		foreach ( $value['options'] as $select_ID => $option) {

			if ( !empty($value['std']) && in_array($select_ID, $value['std'] ) ) {
				$checked = 'checked="checked"';
			}
			else {
				$checked = '';
			}


			$output .= '<input type="checkbox" class="zn_input zn_input_checkbox zn_radio_check" name="'.$value['id'].'[]" id="' . $value['id'] .'_'. $select_ID . '" value="'.$select_ID.'" ' . $checked . ' ><label for="' . $value['id'] .'_'. $select_ID . '"> '.$option.'</label>';
			if(empty($is_radio)){
				$output .=  '<br/>';
			}
		}

		$output .= '</div>';


		return $output;

	}

}
