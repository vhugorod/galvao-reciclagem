<?php

class ZnHgFw_Html_Font extends ZnHgFw_BaseFieldType{

	var $type = 'font';

	/* Select option group */
	function render($option) {

		if ( empty( $option['supports'] ) ) {
			return 'Please make sure the option has the "supports" key set.';
		}

		$output = '<div class="zn_row">';

			if ( isset( $option['std']['font-family'] ) ) {
				$font_family = $option['std']['font-family'];
			}
			else {
				$font_family = '';
			}

		// If supported font
		if ( in_array( 'font', $option['supports'] ) ){

			$normal_fonts = ZNHGFW()->getComponent('font-manager')->get_fonts_array();
			/*
			 * Allow themes to hook into this list and add more fonts
			 * @see Kallyas (Typekit integration)
			 */
			$normal_fonts = apply_filters('znb_field_font_families', $normal_fonts);

			$output .= '<div class="zn_span4">';
			$output .= '<h4>Font Family</h4>';
			$output .= '<select id="'.$option['id'].'_font" class="zn_input zn_input_select" name="'.$option['id'].'[font-family]" data-live-input="1" data-live-font-property="font-family">';
				$output .= '<option disabled>Font Family</option>';
				$output .= '<option value="">-- select --</option>';

				foreach ($normal_fonts as $key => $font) {
					$output .= '<option value="'.$key.'" ' . selected( $font_family , $key, false) . '>'.$font.'</option>';
				}

			$output .= '</select>';

			$output .= '</div>';

		}

		// If supported font size
		if ( in_array( 'size', $option['supports'] ) ) {

			if ( isset( $option['std']['font-size'] ) ) {
				$size = $option['std']['font-size'];
			}
			else {
				$size = '';
			}

			$output .= '<div class="zn_span4">';
			$output .= '<h4 data-tooltip="Drag up or down, or use top/down arrow keys">Font Size</h4>';
			$output .= '<input type="text" id="'.$option['id'].'_size" class="zn_input zn_input_text js-dragkeyfield" name="'.$option['id'].'[font-size]" data-live-input="1" data-live-font-property="font-size" data-disable-negative="yes" value="'.$size.'" placeholder="eg: 13px">';

			$output .= '</div>';
		}

		// If supported line height
		if ( in_array( 'line', $option['supports'] ) ) {

			if ( isset( $option['std']['line-height'] ) ) {
				$line = $option['std']['line-height'];
			}
			else {
				$line = '';
			}

			$output .= '<div class="zn_span4">';
			$output .= '<h4 data-tooltip="Drag up or down, or use top/down arrow keys">Line Height</h4>';
			$output .= '<input type="text" id="'.$option['id'].'_line" class="zn_input zn_input_text js-dragkeyfield" name="'.$option['id'].'[line-height]" data-live-input="1" data-live-font-property="line-height" data-disable-negative="yes" value="'.$line.'" placeholder="eg: 20px">';

			$output .= '</div>';
		}

		// If supported font weight
		if ( in_array( 'weight', $option['supports'] ) ) {

			if ( isset( $option['std']['font-weight'] ) ) {
				$saved_weight = $option['std']['font-weight'];
			}
			else {
				$saved_weight = '';
			}

			$font_weight = array(
					 '400' => '400 (normal) ' , '700' =>'700 (bold)' , '100' => '100' , '200' => '200' , '300' => '300' , '500' => '500', '600' => '600', '800' => '800' , '900' => '900'
				);

			$output .= '<div class="zn_span4">';
			$output .= '<h4>Font Weight</h4>';
			$output .= '<select id="'.$option['id'].'_weight" class="zn_input zn_input_select" name="'.$option['id'].'[font-weight]" data-live-input="1" data-live-font-property="font-weight">';
			$output .= '<option value="">-- select --</option>';

					foreach ( $font_weight as $key => $weight ){
							$output .= '<option value="'. $key .'" ' . selected( $saved_weight , $key, false) . '>'. $weight .'</option>';
					}

				$output .= '</select>';

			$output .= '</div>';
		}

		// If supports font color
		if ( in_array( 'color', $option['supports'] ) ) {

			if ( isset( $option['std']['color'] ) ) {
				$saved_color = $option['std']['color'];
			}
			else {
				$saved_color = '';
			}

			$output .= '<div class="zn_span4">';
			$output .= '<h4>Font color</h4>';
			$output .= '<input type="text" class="zn_colorpicker" data-alpha="true" data-default-color="'.$saved_color.'" name="'.$option['id'].'[color]" value="'.$saved_color.'" data-live-input="1" data-live-font-property="color">';
			$output .= '</div>';
		}

		// If supported font style
		if ( in_array( 'style', $option['supports'] ) ) {

			if ( isset( $option['std']['font-style'] ) ) {
				$saved_style = $option['std']['font-style'];
			}
			else {
				$saved_style = '';
			}

			$font_style = array(
					'normal' , 'italic'
				);

			$output .= '<div class="zn_span4">';
			$output .= '<h4>Font Style</h4>';
			$output .= '<select id="'.$option['id'].'_style" class="zn_input zn_input_select" name="'.$option['id'].'[font-style]" data-live-input="1" data-live-font-property="font-style">';
			$output .= '<option value="">-- select --</option>';

					foreach ( $font_style as $style ){
							$output .= '<option value="'. $style .'" ' . selected( $saved_style , $style, false) . '>'. $style .'</option>';
					}

				$output .= '</select>';

			$output .= '</div>';
		}


		// If supported letter-spacing
		if ( in_array( 'spacing', $option['supports'] ) ) {

			if ( isset( $option['std']['letter-spacing'] ) ) {
				$spacing = $option['std']['letter-spacing'];
			}
			else {
				$spacing = '';
			}

			$output .= '<div class="zn_span4">';
			$output .= '<h4 data-tooltip="Drag up or down, or use top/down arrow keys">Letter Spacing</h4>';
			$output .= '<input type="text" id="'.$option['id'].'_spacing" class="zn_input zn_input_text js-dragkeyfield" name="'.$option['id'].'[letter-spacing]" data-live-input="1" data-live-font-property="letter-spacing" value="'.$spacing.'" placeholder="0px" data-dragkey-min="-50" data-dragkey-max="50">';

			$output .= '</div>';
		}


		// If supported text case
		if ( in_array( 'case', $option['supports'] ) ) {

			if ( isset( $option['std']['text-transform'] ) ) {
				$saved_style = $option['std']['text-transform'];
			}
			else {
				$saved_style = '';
			}

			$text_transform = array(
					'uppercase', 'capitalize' , 'lowercase',
				);

			$output .= '<div class="zn_span4">';
			$output .= '<h4>Text Transform</h4>';
			$output .= '<select id="'.$option['id'].'_case" class="zn_input zn_input_select" name="'.$option['id'].'[text-transform]" data-live-input="1" data-live-font-property="text-transform">';
			$output .= '<option value="">-- select --</option>';

					foreach ( $text_transform as $style ){
							$output .= '<option value="'. $style .'" ' . selected( $saved_style , $style, false) . '>'. $style .'</option>';
					}

				$output .= '</select>';

			$output .= '</div>';
		}

		// If supported text shadow
		if ( in_array( 'shadow', $option['supports'] ) ) {

			if ( isset( $option['std']['text-shadow'] ) ) {
				$saved_style = $option['std']['text-shadow'];
			}
			else {
				$saved_style = '';
			}

			$text_shadow = array(
				'1px 1px 50px rgba(0,0,0,.4)' => 'Deep Dark glow',
				'1px 1px 50px rgba(255,255,255,.4)' => 'Deep Light glow',
				'1px 1px 20px rgba(0,0,0,.4)' => 'Simple Dark glow',
				'1px 1px 20px rgba(255,255,255,.4)' => 'Simple Light glow',
				'1px 1px 0 rgba(0,0,0,.5)' => '1px Edge Dark shadow',
				'1px 1px 0 rgba(255,255,255,.5)' => '1px Edge Light Shadow',
			);

			$output .= '<div class="zn_span4">';
			$output .= '<h4>Text Shadow</h4>';
			$output .= '<select id="'.$option['id'].'_case" class="zn_input zn_input_select" name="'.$option['id'].'[text-shadow]" data-live-input="1" data-live-font-property="text-shadow">';
			$output .= '<option value="">-- select --</option>';

					foreach ( $text_shadow as $t => $style ){
							$output .= '<option value="'. $t .'" ' . selected( $saved_style , $t, false) . '>'. $style .'</option>';
					}

				$output .= '</select>';

			$output .= '</div>';
		}


		// If supported margin-bottom
		if ( in_array( 'mb', $option['supports'] ) ) {

			if ( isset( $option['std']['margin-bottom'] ) ) {
				$mb = $option['std']['margin-bottom'];
			}
			else {
				$mb = '';
			}

			$output .= '<div class="zn_span4">';
			$output .= '<h4 data-tooltip="Drag up or down, or use top/down arrow keys">Margin Bottom</h4>';
			$output .= '<input type="text" id="'.$option['id'].'_mb" class="zn_input zn_input_text js-dragkeyfield" name="'.$option['id'].'[margin-bottom]" data-live-input="1" data-live-font-property="margin-bottom" value="'.$mb.'" placeholder="0px">';
			$output .= '</div>';
		}

		// If supported alignment
		if ( in_array( 'align', $option['supports'] ) ) {

			if ( isset( $option['std']['text-align'] ) ) {
				$align = $option['std']['text-align'];
			}
			else {
				$align = '';
			}

			$alignment_opts = array(
				'' => 'Inherit',
				'left' => 'Left',
				'center' => 'Center',
				'right' => 'Right',
				'justify' => 'Justify',
				'start' => 'Start',
				'end' => 'End',
			);

			$output .= '<div class="zn_span4">';
			$output .= '<h4>Alignment</h4>';
			$output .= '<select id="'.$option['id'].'_alignment" class="zn_input zn_input_select" name="'.$option['id'].'[text-align]" data-live-input="1" data-live-font-property="text-align">';
			$output .= '<option value="">-- select --</option>';

					foreach ( $alignment_opts as $t => $style ){
							$output .= '<option value="'. $t .'" ' . selected( $align , $t, false) . '>'. $style .'</option>';
					}

				$output .= '</select>';

			$output .= '</div>';
		}

		// If supported (text-)decoration
		if ( in_array( 'decoration', $option['supports'] ) ) {

			if ( isset( $option['std']['text-decoration'] ) ) {
				$saved_value = $option['std']['text-decoration'];
			}
			else {
				$saved_value = '';
			}

			$values = array(
				'none', 'underline', 'overline', 'line-through'
			);

			$output .= '<div class="zn_span4">';
			$output .= '<h4>Text Decoration</h4>';
			$output .= '<select id="'.$option['id'].'_style" class="zn_input zn_input_select" name="'.$option['id'].'[text-decoration]" data-live-input="1" data-live-font-property="text-decoration">';
			$output .= '<option value="">-- select --</option>';

			foreach ( $values as $v ){
				$output .= '<option value="'. $v .'" ' . selected( $saved_value , $v, false) . '>'. $v .'</option>';
			}

			$output .= '</select>';

			$output .= '</div>';
		}


		$output .= '</div>';

		return $output;
	}

}
