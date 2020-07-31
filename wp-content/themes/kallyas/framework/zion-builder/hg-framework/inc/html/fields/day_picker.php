<?php

class ZnHgFw_Html_Day_Picker extends ZnHgFw_BaseFieldType{

	var $type = 'day_picker';

	function render( $value ){
		if ( empty( $value['options'] ) ) {
			$value['options'] = array(
					__('Sunday', 'zn_framework') => __('Sunday', 'zn_framework'),
					__('Monday', 'zn_framework') => __('Monday', 'zn_framework'),
					__('Tuesday', 'zn_framework') => __('Tuesday', 'zn_framework'),
					__('Wednesday', 'zn_framework') => __('Wednesday', 'zn_framework'),
					__('Thursday', 'zn_framework') => __('Thursday', 'zn_framework'),
					__('Friday', 'zn_framework') => __('Friday', 'zn_framework'),
					__('Saturday', 'zn_framework') => __('Saturday', 'zn_framework'),
			);
		}

		if(empty($value['std'])){
			$value['std'] = __('Sunday', 'zn_framework');
		}

		$out = '<select class="select zn_input zn_input_select" name="'.$value['id'].'" id="'. $value['id'] .'">';
		if(! empty($value['options'])) {
			foreach ( $value['options'] as $select_ID => $option ) {
				$out .= '<option  value="' . $select_ID . '" ' . selected( $value['std'], $select_ID, false ) . '>' . $option . '</option>';
			}
		}
		$out .= '</select>';

		return $out;
	}

}
