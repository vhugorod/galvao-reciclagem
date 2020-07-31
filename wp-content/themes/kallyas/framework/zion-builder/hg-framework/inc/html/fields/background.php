<?php

class ZnHgFw_Html_Background extends ZnHgFw_BaseFieldType{

	var $type = 'background';

	function render ( $value ){
		$output =	'';

		if( !isset ( $value['std']['image'] ) || empty( $value['std']['image'] ) )
		{
			$value['std'] = array();
			$value['std']['image'] = '';
		}


		$output .= '<input class="logo_upload_input zn_input" id="'.$value['id'].'" type="hidden" name="'.$value['id'].'[image]" value="'.$value['std']['image'].'" />';
		$output .= '<div class="zn_upload_image_button button button-hero" data-multiple="false" data-button="Insert" data-title="Upload Logo">Select Image</div>';

		if(  !empty( $value['std']['image'] ) )
		{
			$output .= '<div class="attachment-preview zn-image-holder"><button title="Close (Esc)" type="button" class=" zn-remove-image">&#215;</button><img src="'.$value['std']['image'].'"></div>';
		}
		else
		{
			$output .= '<div class="zn-image-holder">Nothing selected...</div>';
		}


		$output .= '<div class="clearfix zn_margin20"></div>';
		$output .= '<div class="zn_row zn_image_properties">';

		if ( isset( $value['options']['repeat'] ) || !empty( $value['std']['repeat'] ) )
		{

			if( !isset ( $value['std']['repeat'] ) || empty( $value['std']['repeat'] ) )
			{
				$value['std']['repeat'] = '';
			}

			$output .= '<div class="cf zn_span6">';
			$output .= '<label>Background repeat</label>';
			$output .= '<select class="zn_input zn_input_select" name="'.$value['id'].'[repeat]" id="' . $value['id'] . '_repeat'  . '">';
			$repeats = array ('no-repeat', 'repeat' ,'repeat-x' ,'repeat-y');

			foreach ($repeats as $repeat) {
				$output .= '<option value="' . $repeat . '" ' . selected( $value['std']['repeat'], $repeat, false ) . '>'. $repeat . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
		}

		if ( isset( $value['options']['attachment'] ) )
		{

			if( !isset ( $value['std']['attachment'] ) || empty( $value['std']['attachment'] ) )
			{
				$value['std']['attachment'] = '';
			}

			$output .= '<div class=" zn_span6">';
			$output .= '<label>Background attachment</label>';
			$output .= '<select class="select zn_input zn_input_select" name="'.$value['id'].'[attachment]" id="' . $value['id'] . '_attachment'  . '">';
			$attachments = array ('scroll' ,'fixed' );

			foreach ($attachments as  $attachment) {
				$output .= '<option value="' . $attachment . '" ' . selected( $value['std']['attachment'], $attachment, false ) . '>'. $attachment . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
		}

		if ( isset( $value['options']['position'] ) )
		{

			if( !isset ( $value['std']['position']['x'] ) || empty( $value['std']['position']['x'] ) )
			{
				$value['std']['position']['x'] = '';
			}

			if( !isset ( $value['std']['position']['y'] ) || empty( $value['std']['position']['y'] ) )
			{
				$value['std']['position']['y'] = '';
			}

			// Position - X
			$output .= '<div class="cf zn_span6">';
			$output .= '<label>Background position-x</label>';
			$output .= '<select class="select zn_input zn_input_select" name="'.$value['id'].'[position][x]" id="' . $value['id'] . '_position-x'  . '">';
			$positionxs = array ('center', 'left' ,'right');

			foreach ($positionxs as  $positionx) {
				$output .= '<option value="' . $positionx . '" ' . selected( $value['std']['position']['x'], $positionx, false ) . '>'. $positionx . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';

			// Position - Y
			$output .= '<div class=" zn_span6">';
			$output .= '<label>Background position-y</label>';
			$output .= '<select class="select zn_input zn_input_select" name="'.$value['id'].'[position][y]" id="' . $value['id'] . '_position-y'  . '">';
			$positionys = array ('center', 'top' ,'bottom');

			foreach ($positionys as  $positiony) {
				$output .= '<option value="' . $positiony . '" ' . selected( $value['std']['position']['y'], $positiony, false ) . '>'. $positiony . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
		}

		if ( isset( $value['options']['size'] ) || !empty( $value['std']['size'] ) )
		{

			if( !isset ( $value['std']['size'] ) || empty( $value['std']['size'] ) )
			{
				$value['std']['size'] = '';
			}

			$output .= '<div class="cf zn_span6">';
			$output .= '<label>Background size</label>';
			$output .= '<select class="zn_input zn_input_select" name="'.$value['id'].'[size]" id="' . $value['id'] . '_size'  . '">';
			$sizes = array ('cover', 'auto' ,'contain');

			foreach ($sizes as $size) {
				$output .= '<option value="' . $size . '" ' . selected( $value['std']['size'], $size, false ) . '>'. $size . '</option>';
			}
			$output .= '</select>';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
	}

}
