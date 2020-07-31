<?php

class ZnHgFw_Html_Radio_Image extends ZnHgFw_BaseFieldType{

	var $type = 'radio_image';

	function render($option) {

			$output = '';
			$list_output = '';
			$item_output = '';

			$output .= '<div class="zn-radio-image-container clearfix">';

			$i = 0;
			foreach ( $option['options'] as $key => $soption ) {

				$s_value = '';
				if(isset($soption['value'])){
					$s_value = $soption['value'];
				}

				if( !empty($s_value) ){
					$list_output .= '<option value="'.$soption['value'].'" '.selected($option['std'], $soption['value'], false).'>'.$soption['name'].'</option>';
				}

				$active = !empty($s_value) && $option['std'] == $s_value ? 'active' : '';

				$dummy = '';
				if(empty($s_value) && isset($soption['dummy']) && $soption['dummy'] == true )	{
					$dummy =  'data-dummy="1"';
				}

				$item_output .= '<div class="zn-radio-image-box '.$active.'" '.$dummy.'>';

					$desc = '';
					if(isset($soption['desc'])){
						$desc = '<div class="zn-radio-image-desc">'.$soption['desc'].'</div>';
					}

					$item_output .= '<div class="zn-radio-button '.$active.'" '.$dummy.' data-value="'.$s_value.'"><img src="'.$soption['image'].'"/>'.$desc.'</div>';

					$item_output .= '<span class="zn-radio-img-title">' . $soption['name'].'</span>';
				$item_output .= '</div>';
				$i++;
			}

			$output .= '<select class="zn-radio-image-select" name="'.$option['id'].'" id="'.$option['id'].'">';
			$output .= $list_output;
			$output .= '</select>';
			$output .= $item_output;

			$output .= '</div>';
			return $output;
		}

}
