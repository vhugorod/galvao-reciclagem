<?php

class ZnHgFw_Html_Gallery extends ZnHgFw_BaseFieldType{

	var $type = 'gallery';

	function render ( $option ) {
		// FOR GALLERY
		$defaults = array(
			'media_type' => 'image_gallery',
			'insert_title' => 'Insert gallery', // The text that will appear on the inser button from the media manager
			'button_title' => 'Add / Edit gallery', // The text that will appear as the main option button for adding images
			'title' => 'Add / Edit gallery', // The text that will appear as the main option button for adding images
			'type' => 'image', // The media type : image, video, etc
			'value_type' => 'id', // What to return - url, id
			'state' => 'gallery-library', // The media manager state
			'frame' => 'post', // The media manager frame
			'class' => 'zn-media-gallery media-frame', // The media manager state
		);
		// Set the data
		$option['data'] = !empty( $option['data'] ) ? wp_parse_args( $option['data'], $defaults ) : $defaults;
		$option['preview_holder'] = 'No image selected';
		if ( !empty( $option['std'] ) ) {
			$saved_images = !empty( $option['std'] ) ? explode( ',', $option['std'] ) : array();
			$option['preview_holder'] = self::get_media_preview( $saved_images );
		}
		return $this->zn_media( $option );
	}
	// Returns the HTML needed for the gallery type option
	static function get_media_preview( $images ) {
		$images_holder = '';
		foreach ( $images as $image ) {
			$image_url = wp_get_attachment_image_src( $image, 'thumbnail' );
			$images_holder .= '<span class="zn-media-gallery-preview-image"><img src="'.$image_url[0].'" /></span>';
		}
		return $images_holder;
	}
}
