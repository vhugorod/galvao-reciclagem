<?php

class ZnHgFw_Html_Date_Picker extends ZnHgFw_BaseFieldType{

	var $type = 'date_picker';

	function render ( $value ){

		// Check for url
		if ( isset($value['std']['date']) )
		{
			$date_val = stripslashes($value['std']['date']);
		}
		else {
			$date_val = '';
		}

		// Check for url text
		if ( isset($value['std']['time']) )
		{
			$time_val = stripslashes($value['std']['time']);
		}
		else {
			$time_val = '';
		}

		$output = '<label for="'. $value['id'].'[date]">Date:</label><input class="zn_input zn_date_picker" name="'.$value['id'].'[date]" id="'. $value['id'].'[date]" value="'. $date_val .'" type="text" /><label for="'. $value['id'].'[time]">Time :</label><input id="'. $value['id'].'[time]" name="'. $value['id'].'[time]" value="'. $time_val .'" type="text" class="zn_input zn_time_picker" />';

		return $output;
	}


}
