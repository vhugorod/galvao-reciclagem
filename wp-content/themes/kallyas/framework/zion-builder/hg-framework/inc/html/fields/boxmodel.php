<?php

class ZnHgFw_Html_Boxmodel extends ZnHgFw_BaseFieldType{

	var $type = 'boxmodel';

	function render($option) {

		$output = '<div class="zn_row zn_row_gutter5 zn-boxmodel js-boxmodel-field">';

			$disable_top = $disable_right = $disable_bottom = $disable_left = '';
			if ( isset( $option['disable'] ) ) {
				if(in_array('top', $option['disable'])){
					$disable_top = 'disabled="disabled"';
				}
				if(in_array('right', $option['disable'])){
					$disable_right = 'disabled="disabled"';
				}
				if(in_array('bottom', $option['disable'])){
					$disable_bottom = 'disabled="disabled"';
				}
				if(in_array('left', $option['disable'])){
					$disable_left = 'disabled="disabled"';
				}
			}

			// specify if non-negative
			$non_negative = isset($option['allow-negative']) && $option['allow-negative'] === false ? 'data-disable-negative="yes"' : '';

			// TOP
			if ( isset( $option['std']['top'] ) ) {
				$bm_top = $option['std']['top'];
			}
			else {
				$bm_top = '';
			}
			$output .= '<div class="zn_span2 zn-boxmodel-field">';
				$output .= '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'[top]" data-live-input="1" data-side="top" id="'.$option['id'].'_top" value="'.$bm_top.'"  placeholder="'.$option['placeholder'].'" '.$disable_top.' '.$non_negative.'>';
				$output .= '<label for="'.$option['id'].'_top">TOP</label>';
			$output .= '</div>';

			// RIGHT
			if ( isset( $option['std']['right'] ) ) {
				$bm_right = $option['std']['right'];
			}
			else {
				$bm_right = '';
			}
			$output .= '<div class="zn_span2 zn-boxmodel-field">';
				$output .= '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'[right]" data-live-input="1" data-side="right" id="'.$option['id'].'_right" value="'.$bm_right.'"  placeholder="'.$option['placeholder'].'" '.$disable_right.' '.$non_negative.'>';
				$output .= '<label for="'.$option['id'].'_right">RIGHT</label>';
			$output .= '</div>';

			// BOTTOM
			if ( isset( $option['std']['bottom'] ) ) {
				$bm_bottom = $option['std']['bottom'];
			}
			else {
				$bm_bottom = '';
			}
			$output .= '<div class="zn_span2 zn-boxmodel-field">';
				$output .= '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'[bottom]" data-live-input="1" data-side="bottom" id="'.$option['id'].'_bottom" value="'.$bm_bottom.'"  placeholder="'.$option['placeholder'].'" '.$disable_bottom.' '.$non_negative.'>';
				$output .= '<label for="'.$option['id'].'_bottom">BOTTOM</label>';
			$output .= '</div>';

			// LEFT
			if ( isset( $option['std']['left'] ) ) {
				$bm_left = $option['std']['left'];
			}
			else {
				$bm_left = '';
			}
			$output .= '<div class="zn_span2 zn-boxmodel-field">';
				$output .= '<input class="zn_input zn_input_text" type="text" name="'.$option['id'].'[left]" data-live-input="1" data-side="left" id="'.$option['id'].'_left" value="'.$bm_left.'"  placeholder="'.$option['placeholder'].'" '.$disable_left.' '.$non_negative.'>';
				$output .= '<label for="'.$option['id'].'_left">LEFT</label>';
			$output .= '</div>';

			// Linked checkbox
			$output .= '<div class="zn_span4">';

				$checked = '';
				if ( isset( $option['std']['linked'] ) && $option['std']['linked'] == 1 ) {
					$checked = 'checked="checked"';
				}
				$output .= '<input type="checkbox" class="zn_input zn_input_checkbox zn-boxmodel-linked" name="'.$option['id'].'[linked]" id="'.$option['id'].'_linked" value="1" ' . $checked . ' ><label for="'.$option['id'].'_linked" data-tooltip="Linked?"><span class="dashicons dashicons-unlock"></span></label>';
			$output .= '</div>';

		$output .= '</div>';

		return $output;
	}


}
