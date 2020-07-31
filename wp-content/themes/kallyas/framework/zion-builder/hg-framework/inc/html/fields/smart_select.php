<?php

class ZnHgFw_Html_Smart_Select extends ZnHgFw_BaseFieldType{

	var $type = 'smart_select';

	function render($option) {

			$output = '';
			$list_output = '';
			$item_output = '';

			$output .= '<div class="zn-smartselect clearfix">';

			$def_label = __( '-- Select --', 'zn_framework' );

			$i = 0;
			foreach ( $option['options'] as $key => $soption ) {

				$s_value = '';
				if(isset($soption['value'])){
					$s_value = $soption['value'];
				}

				if($option['std'] == $s_value){
					$def_label = $soption['name'];
				}

				// if( !empty($s_value) ){
					$list_output .= '<option value="'.$soption['value'].'" '.selected($option['std'], $soption['value'], false).'>'.$soption['name'].'</option>';
				// }

				$active = $option['std'] == $s_value ? 'is-active' : '';


				$img = isset($soption['image']) ? 'data-img="'. esc_attr($soption['image']) .'"':'';

				$item_output .= '<div class="zn-smartselect-item '.$active.'" data-value="'.$s_value.'" '.$img.'>';

					$item_output .= '<div class="zn-smartselect-item-title">' . $soption['name'].'</div>';

					if(isset($soption['desc'])){
						$item_output .= '<div class="zn-smartselect-item-desc">'.$soption['desc'].'</div>';
					}

					// if(isset($soption['image'])){
					// 	$item_output .= '<div class="zn-smartselect-item-img"><img src="'.$soption['image'].'"/></div>';
					// }

				$item_output .= '</div>';
				$i++;
			}

			$output .= '<select class="zn-smartselect-selectlist" name="'.$option['id'].'" id="'.$option['id'].'">';
			$output .= $list_output;
			$output .= '</select>';

			$output .= '<div class="zn-smartselect-label">'.$def_label .'</div>';
			$output .= '<div class="zn-smartselect-dropdown clearfix">';
			$output .= $item_output;
			$output .= '</div>';

			$output .= '<div class="zn-smartselect-hoverImg"><img></div>';

			$output .= '</div>';
			return $output;
		}


}
