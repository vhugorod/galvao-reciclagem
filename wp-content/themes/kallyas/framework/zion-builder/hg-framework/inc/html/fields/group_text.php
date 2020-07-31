<?php

class ZnHgFw_Html_Group_Text extends ZnHgFw_BaseFieldType{

	var $type = 'group_text';

	function render( $option ){
		$output = '';
		$id_base = $option['id'];
		$config = isset( $option['config'] ) ? (array)$option['config'] : array();

		if( empty($config) ) { return 'The element\'s options are not properly configured'; }

		$output = '<div class="zn_row">';

			$size = isset( $config['size'] ) ? $config['size'] : 'zn_span4';
			$all_saved_values = ! empty( $option['std'] ) ? $option['std'] : array();

			if( is_array( $config['options'] ) ){
				foreach ($config['options'] as $key => $value) {
					$output .= '<div class="'.$size.'">';
					$output .= '<h4>'.$value['name'].'</h4>';

					$saved_value = isset( $all_saved_values[$value['id']] ) ? $all_saved_values[$value['id']] : '';

					$output .= '<input id="'.$id_base.'_'.$value['id'].'" class="zn_input zn_input_text" placeholder="'.$value['placeholder'].'" name="'.$id_base.'['.$value['id'].']" value="'.$saved_value.'" >';
					$output .= '</div>';
				}
			}

		$output .= '</div>';

		return $output;
	}
}
