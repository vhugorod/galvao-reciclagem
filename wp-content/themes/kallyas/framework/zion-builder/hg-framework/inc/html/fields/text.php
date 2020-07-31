<?php

class ZnHgFw_Html_Text extends ZnHgFw_BaseFieldType{

	var $type = 'text';

	function render($option) {

		$t_value = esc_html(stripslashes($option['std']));

		// Input Class
		$input_class = isset($option['input_class']) && $option['input_class'] ? $option['input_class'] : '';

		// Disable the element if it has value
		if ( !empty( $option['std'] ) && !empty ( $option['supports'] ) && $option['supports'] == 'block' ) {
			$output = '<input type="hidden" name="'.$option['id'].'" value="'.$t_value.'" placeholder="'.$option['placeholder'].'">';
			$output .= '<div class="button disabled">'.$t_value.'</div>';
		}
		else {
			$attrs = 'type="text"';
			if(isset($option['numeric']) && $option['numeric']){
				$attrs = 'type="number"';
				if(isset($option['helpers']) && !empty($option['helpers']) ){
					$attrs .= !empty($option['helpers']['step']) ? ' step="'.$option['helpers']['step'].'"' : '';
					$attrs .= !empty($option['helpers']['min']) ? ' min="'.$option['helpers']['min'].'"' : '';
					$attrs .= !empty($option['helpers']['max']) ? ' max="'.$option['helpers']['max'].'"' : '';
				}
			}

			if(isset($option['dragup'])){
				$input_class .= ' js-dragkeyfield';
				if(is_array($option['dragup']) && !empty($option['dragup']) ){
					$attrs .= isset($option['dragup']['unit']) && $option['dragup']['unit'] ? ' data-dragkey-unit="'.$option['dragup']['unit'].'"' : 'data-no-unit';
					$attrs .= isset($option['dragup']['min']) && !empty($option['dragup']['min']) ? ' data-dragkey-min="'.$option['dragup']['min'].'"' : '';
					$attrs .= isset($option['dragup']['max']) && !empty($option['dragup']['max']) ? ' data-dragkey-max="'.$option['dragup']['max'].'"' : '';
				}
			}

			$output = '<input class="zn_input zn_input_text '.$input_class.'" '.$attrs.' name="'.$option['id'].'" value="'.$t_value.'"  placeholder="'.$option['placeholder'].'">';
		}


		return $output;
	}
}
