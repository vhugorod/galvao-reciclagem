<?php

class ZnHgFw_Html_Zn_Radio extends ZnHgFw_BaseFieldType{

	var $type = 'zn_radio';

	function render( $value ) {

		if ( isset ( $value['rel_id'] ) ) {
			$rel = $value['rel_id'];
		} else {
			$rel = $value['id'];
		}

		$std = $value['std'];

		// Tabs mode: Always use first as active
		if(isset($value['tabs']) && $value['tabs']){
			if( is_array($value['options']) && !empty($value['options'])){
				foreach ($value['options'] as $k => $v) {
					$std = $k;
					break;
				}
			}
		}

		$output = '';
		$output .= '<div id="' . $value['id'] . '" class="zn_radio">';
		$i = 0;
		foreach ( $value['options'] as $option => $name ) {
			$i ++;
			$label = zn_uid();

			$tooltip = '';
			$label_name = $name;

			if(is_array($name) && !empty($name)){
				if(isset($name['title'])){
					$label_name = $name['title'];
				}
				if(isset($name['tip'])){
					$tooltip = 'data-tooltip="'.$name['tip'].'"';
				}
			}

			$output .= '<input rel="' . $rel . '" id="' . $label . $i . '" name="' . $value['id'] . '" type="radio" class="zn_radio_check" value="' . $option . '" '. checked( $std, $option, false ) .' />';
			$output .= '<label for="' . $label . $i . '" '.$tooltip.'>' . $label_name . '</label>';
		}
		$output .= '<div class="clearfix"></div>';
		$output .= '</div>';
		return $output;
	}

}
