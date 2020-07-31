<?php

class ZnHgFw_Html_Slider extends ZnHgFw_BaseFieldType{

	var $type = 'slider';

	function render($option) {

		$step = !empty($option['helpers']['step']) ? $option['helpers']['step'] : '';

		$output  = '<div class="zn_slider clearfix">';
		$output .= '<input type="number" class="zn_input zn_input_text wp-slider-input" name="'.$option['id'].'" value="'.floatval( $option['std'] ).'" min="'.$option['helpers']['min'].'" step="'.$step.'" max="'.$option['helpers']['max'].'" >';
		$output .= '<div class="wp-slider slider-range-max" data-min="'.$option['helpers']['min'].'" data-step="'.$step.'" data-max="'.$option['helpers']['max'].'" data-value="'.floatval($option['std']).'"></div>';
		$output .= '</div>';

		return $output;
	}

}
