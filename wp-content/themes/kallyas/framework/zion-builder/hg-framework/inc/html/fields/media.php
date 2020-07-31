<?php

class ZnHgFw_Html_Media extends ZnHgFw_BaseFieldType{

	var $type = 'media';

	function render ( $option ) {
		$output =	'';

		// This is just for Kallyas
		if(is_array($option['std'])){
			if(isset($option['std']['image'])){
				$option['std'] = $option['std']['image'];
			}
		}

		$data = $option['supports'] == 'id' ? ' data-id="true" ': '';
		$image = $option['supports'] == 'id' ? wp_get_attachment_url( $option['std'] ) : $option['std'];

		$output .= '<input class="logo_upload_input" id="'.$option['id'].'" type="hidden" '.$data.' name="'.$option['id'].'" value="'.$option['std'].'" />';
		$output .= '<div class="zn_upload_image_button" data-multiple="false" data-button="Insert" data-title="Upload Logo">Select Image</div>';

		if( !empty( $image ) )
		{
			$output .= '<div class="attachment-preview zn-image-holder"><button title="Close (Esc)" type="button" class="zn-remove-image">&#215;</button><img src=" '.$image.' "></div>';
		}
		else
		{
			$output .= '<div class="zn-image-holder">Nothing selected...</div>';
		}

		return $output;
	}
}
