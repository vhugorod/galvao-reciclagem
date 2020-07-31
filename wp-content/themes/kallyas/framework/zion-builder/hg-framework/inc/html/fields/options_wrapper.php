<?php

class ZnHgFw_Html_Options_Wrapper extends ZnHgFw_BaseFieldType{

	var $type = 'options_wrapper';

	function render( $options ){
		$output = '';
		if( ! empty( $options['option_file'] ) ){
			ob_start();
			include( $options['option_file'] );
			$output = ob_get_clean();
		}
		return $output;
	}
}
