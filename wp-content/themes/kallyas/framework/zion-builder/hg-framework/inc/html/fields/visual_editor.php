<?php

class ZnHgFw_Html_Visual_Editor extends ZnHgFw_BaseFieldType{

	var $type = 'visual_editor';

	function render($option) {

		ob_start();

		$id  = preg_replace('![^a-zA-Z]!', "", $option['id']) .''.zn_uid();

		$args = array(
			'editor_class' => 'zn_tinymce',
			'default_editor' => 'tmce',
			'textarea_name' => $option['id'],
			'textarea_rows' => 5,
		);

		wp_editor( stripslashes($option['std']) , $id, $args );
		$output = ob_get_clean();
		return $output;
	}

}
