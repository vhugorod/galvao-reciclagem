<?php

class ZnHgFw_Html_Toggle extends ZnHgFw_BaseFieldType{

	var $type = 'toggle';

	function render ( $value ) {

		$output = $checked = '';

		$output = '<div class="onoffswitch">';
			$output .= '<input type="checkbox" name="'.$value['id'].'" class="onoffswitch-checkbox" id="' . $value['id'] .'" '. checked( $value['value'] , $value['std'], false ) .' value="'. $value['std'].'">';
			$output .= '<label class="onoffswitch-label" for="' . $value['id'] .'">';
				$output .= '<div class="onoffswitch-inner"></div>';
				$output .= '<div class="onoffswitch-switch"></div>';
			$output .= '</label>';
		$output .= '</div>';

		return $output;

	}

}
