<?php

class ZnHgFw_Html_Colorpicker extends ZnHgFw_BaseFieldType{

	var $type = 'colorpicker';

	function render($option) {

		$alpha = !empty( $option['alpha'] ) ? 'data-alpha="true"' : false;

		$output  = '<div class="input-append color">';
		$output .= '<input type="text" class="zn_colorpicker" data-default-color="'.$option['std'].'" name="'.$option['id'].'" '.$alpha.' value="'.$option['std'].'" >';
		$output .= '</div>';

		return $output;
	}


}
