<?php

class ZnHgFw_Html_Toggle2 extends ZnHgFw_BaseFieldType{

	var $type = 'toggle2';

	function render ( $value ) {
		$new_id = $value['id'] . zn_uid();

		$output = '<div class="zn_toggle2">';
			$output .= '<input type="hidden" name="'.$value['id'].'" checked="checked" value="zn_dummy_value" />';
			$output .= '<input type="checkbox" name="'.$value['id'].'" id="' . $new_id .'" '. checked( $value['value'] , $value['std'], false ) .' value="'. $value['value'].'">';
			$output .= '<label class="slider-v3" for="' . $new_id .'"></label>';
		$output .= '</div>';

		return $output;

	}


}
