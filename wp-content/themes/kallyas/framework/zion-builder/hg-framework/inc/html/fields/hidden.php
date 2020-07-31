<?php

class ZnHgFw_Html_Hidden extends ZnHgFw_BaseFieldType{

	var $type = 'hidden';

	function render($option) {
		return '<input type="hidden" name="'.$option['id'].'" value="'.$option['std'].'">';
	}

}
