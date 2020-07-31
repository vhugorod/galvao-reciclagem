<?php

class ZnHgFw_BaseFormType{

	var $options;
	var $id;


	function __construct( $id, $args ){
		// Main class constructor
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		// Set the HTML manager
		$this->id = $id;
	}

	private function _render(){
		// Renderer
	}

	function render(){
		// Will output the option content
		return $this->_render();
	}

	function save(){
		// Do an action so that anyone can use the saved values
		do_action( 'znhgfw_form_save_'. $this->id );
	}

	function scripts(){
		// Will add additionall css/js scripts
	}
}
