<?php

class ZnHgFw_Html_Date_Format extends ZnHgFw_BaseFieldType{

	var $type = 'date_format';

	function render($option) {

		$t_value = esc_html(stripslashes($option['std']));

		// Disable the element if it has value
		$output = '<input class="zn_input zn_input_text zn_date_format_input" type="text" name="'.$option['id'].'" value="'.$t_value.'"  placeholder="'.$option['placeholder'].'"><span class="zn_date_format_example">Output</span><span class="example">' . date_i18n( $t_value ) . '</span> <span class="spinner"></span>';

		return $output;
	}

}
