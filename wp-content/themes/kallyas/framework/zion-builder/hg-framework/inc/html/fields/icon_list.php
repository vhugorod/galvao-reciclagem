<?php

class ZnHgFw_Html_Icon_List extends ZnHgFw_BaseFieldType{

	var $type = 'icon_list';

	public $_icons = array();

	function init(){
		$this->_getIcons();
	}

	private function _getIcons(){

		$all_icon_option = array();
		$all_icons = ZNHGFW()->getComponent('icon_manager')->get_icons();

		foreach ( $all_icons as $name => $icon_data ) {
			foreach ( $icon_data as $icon ) {
				$unicode = ZNHGFW()->getComponent('icon_manager')->get_icon( $icon );
				$all_icon_option[$name][$icon] = $unicode;
			}
		}

		$this->_icons = $all_icon_option;

	}

	function render($option) {

		if( !is_array( $option['std'] ) ) {
			$std = array( 'family' => '' , 'unicode' => '' );
		}
		else {
			$std = $option['std'];
		}

		$uid = $class = $output = '';

		if ( !empty( $option['modal'] ) && $option['modal'] == true ){
			$uid = zn_uid();
			$class = 'zn-modal-form zn_hidden';
			$output .= '<a class="zn_admin_button zn_modal_trigger no-scroll" href="#'.$uid.'" data-modal_title="Select icon">Select icon</a>';
		}

		if ( !empty( $option['compact'] ) && $option['compact'] == true ){
			$uid = zn_uid();
			$class = '';

			$keyword = 'browse icons';
			if(isset($option['std']['unicode']) && !empty($option['std']['unicode']) ){
				$keyword = 'change icon';
				// print_z($option['std']);
				$output .= '<span class="zn_icon zn_icon_op_label_icon" '.zn_generate_icon( $option['std'] ).'"></span>';
			}

			$output .= '<input type="checkbox" id="zn_icon_op_checkbox_'.$uid.'" class="zn_icon_op_checkbox">';
			$output .= '<label for="zn_icon_op_checkbox_'.$uid.'" class="zn_icon_op_label">Click to '.$keyword.'</label>';
		}

		$output .= '<div class="zn_icon_op_container '.$class.'" id="'.$uid.'">';

			$output .= '<input type="hidden" class="zn_icon_family" name="'.$option['id'].'[family]" value="'.$std['family'].'">';
			$output .= '<input type="hidden" class="zn_icon_unicode" name="'.$option['id'].'[unicode]" value="'.$std['unicode'].'">';
			$output .= '<div class="zn_icon_container">';

				foreach ( $this->_icons as $name => $icon_data ) {

					$output .= '<div class="zn_font_name">Font : '.$name.'</div>';

					foreach ( $icon_data as $icon => $unicode ) {
						$class = '';

						if ( $std['unicode'] == $icon && $std['family'] == $name ) {
							$class = 'zicon_active';
						}

						$output .= '<span class="'.$class.' zn_icon" data-unicode="'.$icon.'" data-zniconfam="'.$name.'" data-zn_icon="'.$unicode.'"></span>';
					}

				}

			$output .= '</div>';
			$output .= '<div class="clearfix"></div>';
			$output .= '<a href="'.admin_url( 'admin.php?page=zn_tp_advanced_options' ).'" target="_blank" class="zn-upload-more-btn"><span class="dashicons dashicons-download"></span> Upload more icons</a>';
		$output .= '</div>';


		return $output;
	}


}
