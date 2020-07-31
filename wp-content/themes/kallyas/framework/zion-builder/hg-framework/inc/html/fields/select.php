<?php

class ZnHgFw_Html_Select extends ZnHgFw_BaseFieldType{

	var $type = 'select';

	function render( $config ){

		if ( empty( $config['options'] ) ) { $config['options'] = array(); }

		if( isset( $config['multiple'] ) && $config['multiple'] ) {
			$output = '<select class="select zn_input zn_input_select" multiple name="'.$config['id'].'[]" id="'. $config['id'] .'">';
			foreach ($config['options'] as $select_ID => $option) {

				$checked = '';
				if(is_array($config['std'])) {
					if(in_array($select_ID,$config['std'])) { $checked = 'selected="selected"'; } else { $checked = ''; }
				}
				/* id="' . $select_ID . '" */
				$output .= '<option value="'.$select_ID.'" '.$checked.' >'.$option.'</option>';
			}
			$output .= '</select>';
		}
		else {
			$output = '<select class="select zn_input zn_input_select" name="'.$config['id'].'" id="'. $config['id'] .'">';
			foreach ($config['options'] as $select_ID => $option) {
				$output .= '<option  value="'.$select_ID.'" ' . selected($config['std'], $select_ID, false) . ' >'.$option.'</option>';
			}
			$output .= '</select>';
		}
		return $output;

	}
}
