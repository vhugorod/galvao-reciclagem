<?php

class ZnHgFw_Html_Textarea extends ZnHgFw_BaseFieldType{

	var $type = 'textarea';

	function render($option) {
		$t_value = esc_html(stripslashes($option['std']));
		$output = sprintf('<textarea class="zn_input zn_input_textarea" id="%1$s" name="%1$s" rows="4">%2$s</textarea>',$option['id'],$t_value);
		return $output;
	}

}
