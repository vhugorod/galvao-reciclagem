<?php

class ZnHgFw_Html_Group_Select extends ZnHgFw_BaseFieldType{

	var $type = 'group_select';

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

				$is_live = isset($option['live']) ? 'data-live-input="1"' : '';

				$output .= '<select id="'.$id_base.'_'.$value['id'].'" class="zn_input zn_input_select" name="'.$id_base.'['.$value['id'].']" '.$is_live.'>';
					// $output .= '<option disabled>'.$value['name'].'</option>';
					$saved_value = isset( $all_saved_values[$value['id']] ) ? $all_saved_values[$value['id']] : '';

					foreach ( $value['options'] as $opt_id => $opt_name ) {
						$output .= '<option value="'.$opt_id.'" ' . selected( $saved_value , $opt_id, false) . '>'.$opt_name.'</option>';
					}

				$output .= '</select>';
				$output .= '</div>';
			}
		}

	$output .= '</div>';

	return $output;
	}
}
