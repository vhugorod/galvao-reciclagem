<?php

class ZnHgFw_Html_Zn_Ajax_Call extends ZnHgFw_BaseFieldType{

	var $type = 'zn_ajax_call';

	function render($option) {

		$output = '<div class="'.$option['ajax_call_setup']['action'].'_btn zn_admin_button">'.$option['ajax_call_setup']['button_text'].'</div>';
		$output .= '<div class="'.$option['ajax_call_setup']['action'].'_msg_container"></div>';

		return $output;
	}
}
