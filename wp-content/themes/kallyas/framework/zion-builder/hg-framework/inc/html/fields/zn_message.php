<?php

class ZnHgFw_Html_Zn_Message extends ZnHgFw_BaseFieldType{

	var $type = 'zn_message';

	function render($option) {

		$message_type = ! empty( $option['supports'] ) ? $option['supports'] : 'info';
		$output = '<div class="znhtml_message znhtml_message_'.$message_type.'">';
			$output .= '<h4><span class="dashicons dashicons-'.$message_type.'"></span> <span>'.$option['name'].'</span></h4>';
			$output .= '<p>'.$option['description'].'</p>';
		$output .= '</div>';

		return $output;
	}
}
