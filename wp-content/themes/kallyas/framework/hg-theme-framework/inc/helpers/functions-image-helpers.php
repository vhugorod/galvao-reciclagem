<?php

if( ! function_exists( 'ZngetAttachmentIdFromUrl' ) ){
	/**
	 * Retrieve the media ID from the given URL
	 * @param string $attachment_url
	 * @return bool|null|string
	 */
	function ZngetAttachmentIdFromUrl($attachment_url = ''){
		global $wpdb;
		$attachment_id = false;
		// If there is no url, return.
		if ( empty( $attachment_url ) ) {
			return $attachment_id;
		}

		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();

		// Make sure the upload path base directory exists in the attachment URL
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

			// Run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $wpdb->get_var(
				$wpdb->prepare( "
			SELECT wposts.ID
				FROM {$wpdb->posts} AS wposts
					INNER JOIN {$wpdb->postmeta} AS wpostmeta
						ON wposts.ID = wpostmeta.post_id
					WHERE wpostmeta.meta_key = '_wp_attached_file'
						AND wpostmeta.meta_value = '%s'
						AND wposts.post_type = 'attachment'", $attachment_url ) );
		}
		return $attachment_id;
	}
}


if( ! function_exists( 'ZngetImageAltFromUrl' ) ){
	/**
	 * Retrieve the alt value from the Given Image URL
	 * @param string $attachment_url
	 */
	function ZngetImageAltFromUrl($attachment_url = '', $with_attr = false){

		$img_id = ZngetImageDataFromUrl($attachment_url);
		$alt = !empty($img_id['alt']) ? $img_id['alt'] : '';

		if($with_attr){
			$alt = ' alt="'.$alt.'"';
		}
		return $alt;
	}
}


if( ! function_exists( 'ZngetImageTitleFromUrl' ) ){
	/**
	 * Retrieve the image title value from the Given Image URL
	 * @param string $attachment_url
	 */
	function ZngetImageTitleFromUrl($attachment_url = '', $with_attr = false){

		$img_id = ZngetImageDataFromUrl($attachment_url);
		$title = !empty($img_id['title']) ? $img_id['title'] : '';

		if($with_attr){
			$title = ' title="'.$title.'"';
		}

		return $title;
	}
}


if( ! function_exists( 'ZngetImageIdFromUrl' ) ){
	/**
	 * Retrieve the image title value from the Given Image URL
	 * @param string $attachment_url
	 */
	function ZngetImageIdFromUrl( $attachment_url = '' ){

		$img_data = ZngetImageDataFromUrl($attachment_url);
		return !empty($img_data['id']) ? $img_data['id'] : '';

	}
}

if( ! function_exists( 'ZngetImageSizesFromUrl' ) ){
	/**
	 * Retrieve the image width and height from the Given Image URL
	 * @param string $attachment_url
	 */
	function ZngetImageSizesFromUrl($attachment_url = '', $with_attr = false){

		$sizes = array();

		$img_data = ZngetImageDataFromUrl($attachment_url);
		$sizes['width'] = ! empty($img_data['width']) ? $img_data['width'] : '';
		$sizes['height'] = ! empty($img_data['height']) ? $img_data['height'] : '';

		if($with_attr){
			$attr_sizes = '';
			if( ! empty( $sizes['width'] ) ) {
				$attr_sizes .= ' width="'.$sizes['width'].'"';
			}
			if( ! empty( $sizes['height'] ) ) {
				$attr_sizes .= ' height="'.$sizes['height'].'"';
			}
			$sizes = $attr_sizes;
			// $sizes = ' width="'.$sizes['width'].'" height="'.$sizes['height'].'"';
		}

		return $sizes;
	}
}

if( ! function_exists( 'ZngetImageDataFromUrl' ) ){
	function ZngetImageDataFromUrl( $attachment_url = '' ) {

		// Allow users to disable this data as it can be slow on some big DB's
		if( false === apply_filters( 'znklfw_enable_image_data_from_url', true) ){
			return false;
		}

		global $wpdb, $zn_images_data;
		$attachment_data = false;
		// If there is no url, return.
		if ( empty( $attachment_url ) ) {
			return $attachment_data;
		}
		$saved_attachment_url = $attachment_url;

		if(is_array($zn_images_data) && is_string($attachment_url) && isset($zn_images_data[ $attachment_url ]) && !empty($zn_images_data[ $attachment_url ]))
		{
			return $zn_images_data[ $attachment_url ];
		}

		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();

		//#! @wpk: Fixes PHP notice when $attachment_url is an array
		//#! @since v4.1.1
		if(is_array($attachment_url) && isset($attachment_url['image']) && !empty($attachment_url['image'])){
			$saved_attachment_url = $attachment_url = $attachment_url['image'];
		}
		//#!

		$attachment = '';

		// Make sure the upload path base directory exists in the attachment URL
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

			// Run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_data = $wpdb->get_results(
				$wpdb->prepare( "
			SELECT wposts.ID, wposts.post_title
				FROM {$wpdb->posts} AS wposts
					INNER JOIN {$wpdb->postmeta} AS wpostmeta
						ON wposts.ID = wpostmeta.post_id
					WHERE wpostmeta.meta_key = '_wp_attached_file'
						AND wpostmeta.meta_value = '%s'", $attachment_url ) );

			if( ! empty( $attachment_data[0] ) ){
				$attachment = $attachment_data[0];
			}

			// Check for WPML data
			if(function_exists('icl_object_id') && defined('ICL_LANGUAGE_CODE')) {
				foreach($attachment_data as $k => $att){
					$icl_id = icl_object_id( $att->ID, 'page', true, ICL_LANGUAGE_CODE );
					if($att->ID == $icl_id){
						$attachment = $attachment_data[$k];
					}
				}
			}

			if( $attachment ){
				// $alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true);
				$attachment_data = $zn_images_data[ $saved_attachment_url ] = array(
					'title' => $attachment->post_title,
					'id' 	=> $attachment->ID,
					'alt' 	=> get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true),
				);

				// Get the image width and height
				$image_data = get_post_meta( $attachment->ID, '_wp_attachment_metadata', true);
				if( is_array( $image_data ) && isset( $image_data['width'] ) && isset( $image_data['height'] ) ){
					$attachment_data['width'] = absint( $image_data['width'] );
					$attachment_data['height'] = absint( $image_data['height'] );
				}
			}
		}
		return $attachment_data;
	}
}
