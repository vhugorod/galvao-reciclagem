<?php

class ZnHgFw_Html_Sidebar extends ZnHgFw_BaseFieldType{

	var $type = 'sidebar';

	public $_sidebars = array();

	function init(){
		$this->_getSidebars();
	}

	private function _getSidebars(){
		$sidebars = array();

		// Add the unlimited sidebars
		global $wp_registered_sidebars;
		foreach ($wp_registered_sidebars as $key => $sidebar) {
			$sidebars[$sidebar['id']] = $sidebar['name'];
		}

		$this->_sidebars = $sidebars;
	}

	function render( $value ) {

		$sidebars = array();

		if( ! empty( $value['supports']['sidebars_options'] ) ) {
			$sidebars = array_merge( $value['supports']['sidebars_options'], $sidebars );
		}

		if( ! empty( $value['supports']['default_sidebar'] ) ){
			$sidebars = array_merge( $sidebars, array( $value['supports']['default_sidebar'] => 'Default Sidebar' ) );
		}
		else{
			$sidebars = array_merge( array( 'default_sidebar' => 'Default Sidebar' ), $sidebars );
		}

		if( is_array( $this->_sidebars ) ){
			$sidebars = array_merge( $sidebars, $this->_sidebars );
		}

		// Override default sidebar options
		if( !empty( $value['supports']['sidebar_options'] ) ){
			$sidebar_options = $value['supports']['sidebar_options'];
		}
		else{
			$sidebar_options = array( 'sidebar_right' => 'Right sidebar' , 'sidebar_left' => 'Left sidebar' , 'no_sidebar' => 'No sidebar' );
		}

		if ( !is_array( $value['std'] ) ) { $value['std'] = array(); }
		if ( !isset ( $value['std']['layout'] ) ) { $value['std']['layout'] = ''; }
		if ( !isset ( $value['std']['sidebar'] ) || empty( $value['std']['sidebar'] ) ) { $value['std']['sidebar'] = ''; }

		$output = '';
		$output .= '<div class="zn_row">';

		// Sidebar layout
		$output .= '<div class="zn_span4">';
		$output .= '<label for="'. $value['id'] .'_layout">Sidebar layout</label><select class="select zn_input zn_input_select" name="'.$value['id'].'[layout]" id="'. $value['id'] .'_layout">';
		foreach ( $sidebar_options as $select_ID => $option ) {
			$output .= '<option id="' . $select_ID . '" value="'.$select_ID.'" ' . selected( $value['std']['layout'], $select_ID, false) . ' >'.$option.'</option>';
		}
		$output .= '</select>';
		$output .= '</div>';

		// Sidebar select
		$output .= '<div class="zn_span4">';
		$output .= '<label for="'. $value['id'] .'_sidebar">Sidebar select</label><select class="select zn_input zn_input_select" name="'.$value['id'].'[sidebar]" id="'. $value['id'] .'_sidebar">';
		foreach ( $sidebars as $select_ID => $option ) {
			$output .= '<option id="' . $select_ID . '" value="'.$select_ID.'" ' . selected($value['std']['sidebar'], $select_ID, false) . ' >'.$option.'</option>';
		}
		$output .= '</select>';
		$output .= '</div>';

		$output .= '</div>';

		return $output;

	}

}
