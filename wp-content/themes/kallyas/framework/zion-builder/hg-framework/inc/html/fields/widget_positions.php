<?php

class ZnHgFw_Html_Widget_Positions extends ZnHgFw_BaseFieldType{

	var $type = 'widget_positions';

	function render( $value ) {
			$number_of_columns  = $value['number_of_columns'];
			$columns_variations = $value['columns_positions'];

			$saved_widgets_display = stripslashes( $value['std'] );
			$saved_widgets_array   = json_decode( $saved_widgets_display, true );
			$output                = '<div class="zn_mp">';
			$output .= '<div class="zn_nop">';
			$output .= '<span class="option_title">' . __( 'Columns :', 'zn_framework' ) . '</span>';
			$output .= '<ul class="zn_number_list">';

			for ( $i = 1; $i < $number_of_columns + 1; $i ++ ) {
				$active_class = '';
				if ( $i == key( $saved_widgets_array ) ) {
					$active_class = 'active';
				}
				$output .= '<li class="nof_trigger ' . $active_class . '">' . $i . '</li>';
			}

			$output .= '</ul>';
			$output .= '<div class="clear"></div>';

			$output .= '</div>';

			$alphabet = range('a', 'd');

			$output .= '<div class="zn_positions">';

			$output .= '<div class="zn_positions_display">';

			for ( $i = 1; $i < $number_of_columns + 1; $i ++ ) {
				$css             = '';
				$saved_variation = '';

				if ( $i > key( $saved_widgets_array ) ) {
					$css = 'hidden';
				} else {
					//$saved_variation = $value['columns_positions'][key($saved_widgets_array)][0][$i-1];
					$saved_variation = $saved_widgets_array[ key( $saved_widgets_array ) ][0][ $i - 1 ];
				}
				$output .= '<div class="zn_position zn-grid-' . $saved_variation . ' ' . $css . '"><span>' . $alphabet[ $i - 1 ] . '</span></div>';
			}
			$output .= '</div>';
			$output .= '<div class="clear"></div>';
			$output .= '<div class="zn_position_options">';

			// All position variations
			$output .= '<div class="zn_position_var_options">';

			$output .= '<span class="option_title">' . __( 'Styles :', 'zn_framework' ) . '</span>';
			$output .= '<ul class="zn_number_list">';

			foreach ( $columns_variations[ key( $saved_widgets_array ) ] as $key => $val ) {
				$active_class = '';
				if ( $saved_widgets_array[ key( $saved_widgets_array ) ][0] == $val ) {
					$active_class = 'active';
				}
				$pos_value = $key + 1;
				$output .= '<li class="' . $active_class . '">' . $pos_value . '</li>';
			}

			$output .= '</ul>';

			$output .= '</div>';

			// All position variations
			$output .= '<div class="zn_all_options hidden">';

			$output .= json_encode( $columns_variations );

			$output .= '</div>';

			$output .= '</div>';

			$output .= '<div class="clear"></div>';
			// Positions input
			$output .= '<input class="zn_widgets_positions hidden" data-columns="' . key( $saved_widgets_array ) . '" name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" value="' . htmlspecialchars( $saved_widgets_display ) . '" />';

			$output .= '</div>';
			$output .= '</div>';
			return $output;
		}

}
