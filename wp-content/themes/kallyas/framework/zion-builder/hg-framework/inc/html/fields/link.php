<?php

class ZnHgFw_Html_Link extends ZnHgFw_BaseFieldType{

	var $type = 'link';

	function render( $config ){

		if ( empty( $config['std']['url'] ) ){ $url = ''; } else { $url = trim($config['std']['url']); }
		if ( empty( $config['std']['title'] ) ){ $title = ''; } else { $title = $config['std']['title']; }
		if ( empty( $config['std']['target'] ) ){ $target = ''; } else { $target = $config['std']['target']; }

		$title = esc_html(stripslashes($title));

		// URL , TARGET , TITLE
		$output = '';
		$output .= '<div class="wp-core-ui-button zn_internal_button_trigger">'.__( 'Add internal link', 'zn_framework' ).'</div>';
		$output .= '<div class="wp-core-ui-button zn_media_link_button_trigger"
						data-button="Insert"
						data-title="Upload Image"
						data-target-url="'.$config['id'].'_url"
						data-target-title="'.$config['id'].'_title">'.__( 'Select image', 'zn_framework' ).'</div>';

		$output .= '<div class="zn_class_link-flexWrapper">';

		$output .= '<label for="'.$config['id'].'_url" class="zn-form--sym dashicons dashicons-admin-links"></label>';
		$output .= '<input type="text" id="'.$config['id'].'_url" class="zn_input zn_input_text zn-form--url" name="'.$config['id'].'[url]" value="'.$url.'" placeholder="URL" >';

		if( !empty( $config['options'] ) ) {
			$output .= '<select name="'.$config['id'].'[target]" class="zn_input zn_input_select zn-form--url-target">';

				foreach ($config['options'] as $key => $value ) {
					$output .= '<option '.selected($target , $key,false).' value="'.$key.'">'.$value.'</option>';
				}

			$output .= '</select>';
		}
		else{
			$output .= '<select name="'.$config['id'].'[target]" class="zn_input zn_input_select zn-form--url-target">';
				$output .= '<option '.selected($target , '_self',false).' value="_self">Same window</option>';
				$output .= '<option '.selected($target , '_blank',false).' value="_blank">New window</option>';
			$output .= '</select>';
		}

		$output .= '<textarea
						class="zn_input zn_input_textarea zn-form--url-title"
						id="'.$config['id'].'_title"
						name="'.$config['id'].'[title]"
						placeholder="Title" >'.$title.'</textarea>';
		$output .= '</div>';

		return $output;

	}
}
