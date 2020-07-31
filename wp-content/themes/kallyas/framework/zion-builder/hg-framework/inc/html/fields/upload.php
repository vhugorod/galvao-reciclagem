<?php

class ZnHgFw_Html_Upload extends ZnHgFw_BaseFieldType{

	var $type = 'upload';

	function init(){
		add_action( 'wp_ajax_znhg_upload_icon_package', array( $this, 'upload_icon_package' ) );
		add_action( 'wp_ajax_znhg_remove_icon_font', array( $this, 'remove_icons' ) );
		add_action( 'wp_ajax_znhg_get_download_icon_font_url', array( $this, 'get_download_font_url' ) );
		add_action( 'wp_ajax_znhg_download_icon_font', array( $this, 'download_font' ) );
	}

	/**
	 * DELETES THE ICONS
	 */
	public function remove_icons() {

		// Perform AJax checks and requirements
		$nonce_verification = check_ajax_referer( 'zn_framework', 'security', false );
		if( ! $nonce_verification ){
			wp_send_json_error(
				array(
					'message' => esc_html( __( 'Security check not passed!', 'zn_framework' ) )
				)
			);
		}

		// Check to see if the font name is received
		if ( ! isset( $_POST[ 'font_name' ] ) ) {
			wp_send_json_error( array( 'message' => esc_html( __( 'Invalid request. Missing font name', 'zn_framework' ) ) ) );
		}

		// Strip all tags
		$font_name = wp_strip_all_tags( $_POST[ 'font_name' ] );
		if ( empty( $font_name ) ) {
			wp_send_json_error( array( 'message' => esc_html( __( 'Invalid request. Missing font name', 'zn_framework' ) ) ) );
		}

		// Delete the font
		$deleted_font = ZNHGFW()->getComponent('icon_manager')->delete_font( $font_name );

		if( is_wp_error( $deleted_font ) ){
			wp_send_json_error(
				array(
					'message' => esc_html( $deleted_font->get_error_message() )
				)
			);
		}

		$return[ 'message' ] = esc_html( sprintf( __( ' The %s font was successfully deleted', 'zn_framework' ), $font_name ) );
		wp_send_json_success( $return );

	}


	/**
	 * Will return the download URL for a specific font
	 */
	public function get_download_font_url(){

		// Perform AJax checks and requirements
		$nonce_verification = check_ajax_referer( 'zn_framework', 'security', false );
		if( ! $nonce_verification ){
			wp_send_json_error(
				array(
					'message' => esc_html( __( 'Security check not passed!', 'zn_framework' ) )
				)
			);
		}

		// Check to see if the font name is received
		if ( ! isset( $_POST[ 'font_name' ] ) ) {
			wp_send_json_error( array( 'message' => esc_html( __( 'Invalid request. Missing font name', 'zn_framework' ) ) ) );
		}

		// Strip all tags
		$font_name = wp_strip_all_tags( $_POST[ 'font_name' ] );
		if ( empty( $font_name ) ) {
			wp_send_json_error( array( 'message' => esc_html( __( 'Invalid request. Missing font name', 'zn_framework' ) ) ) );
		}

		$font_archive = ZNHGFW()->getComponent('icon_manager')->create_font_archive( $font_name );
		if( is_wp_error( $font_archive ) ){
			wp_send_json_error(
				array(
					'message' => esc_html( $font_archive->get_error_message() )
				)
			);
		}

		// Create a nonce for security
		$nonce = wp_create_nonce( 'znhg-icon-font-download' );
		wp_send_json_success( array(
			'archive_url' => admin_url( 'admin-ajax.php' ) . "?action=znhg_download_icon_font&nonce={$nonce}&font_name={$font_name}"
		));

	}


	/**
	 * Will download a font and delete the font archive
	 */
	function download_font(){

		if( empty( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'znhg-icon-font-download' ) || empty( $_GET[ 'font_name' ] ) ){
			die( 'Security check' ); ;
		}

		$font_name = wp_strip_all_tags( $_GET[ 'font_name' ] );
		if ( empty( $font_name ) ) {
			die( 'Invalid icon font' );
		}

		$file_system = ZNHGFW()->getComponent( 'utility' )->getFileSystem();
		$font_archive_path = ZNHGFW()->getComponent('icon_manager')->get_font_archive_path( $font_name );

		if( is_wp_error( $font_archive_path ) ){
			die( $font_archive_path->get_error_message() );
		}

		if ( $file_system->is_file( $font_archive_path ) ) {
			$archive_file_name = basename( $font_archive_path );
			header( "Content-type: application/zip" );
			header( "Content-Disposition: attachment; filename=$archive_file_name" );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );
			readfile( $font_archive_path );
			$file_system->delete( $font_archive_path );
			die();
			exit;
		}

	}

	public function upload_icon_package(){

		// Validate request
		check_ajax_referer( 'zn_framework', 'security' );
		if ( ! isset( $_POST[ 'attachment' ] ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html( __( 'Invalid request. Missing attachment', 'zn_framework' ) )
				)
			);
		}

		$attachment = $_POST[ 'attachment' ];
		if ( empty( $attachment ) || ! is_array( $attachment ) || ! isset( $attachment[ 'id' ] ) || ! isset( $attachment[ 'title' ] ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html( __( 'Invalid request. Missing attachment', 'zn_framework' ) )
				)
			);
		}


		$path = get_attached_file( (int)$attachment[ 'id' ] );
		$icon_install = ZNHGFW()->getComponent('icon_manager')->install_icon_package( $path );
		if( is_wp_error( $icon_install ) ){
			wp_send_json_error(
				array(
					'message' => esc_html( $icon_install->get_error_message() )
				)
			);
		}

		wp_send_json_success( array(
			'message' => sprintf( __( ' The %s font was successfully added', 'zn_framework' ), $icon_install ),
			'html' => $this->render_single_font( $icon_install )
		));

	}

	function render( $value ) {

		// ONLY ALLOW SUPER ADMINS TO UPLOAD NEW ICONS
		if ( !current_user_can( 'update_plugins' ) ){
			return 'You need super admin capabilities to use this option!';
		}

		// GET/SET DEFAULTS
		$supports = $value['supports'];
		$output = '';

		// CHECK TO SEE IF THE FILE TYPE IS ALLOWED
		// CHECK ON MULTISITE
		if ( is_multisite() && strpos( get_site_option( 'upload_filetypes' ), $supports['file_extension'] ) === false )
		{
			return 'It seems that the '.$supports['file_extension'].' file type is not allowed on your multisite enable network. Please go to <a title="Network settings page" href="'.network_admin_url('settings.php').'"">Network settings page</a> and add the '.$supports['file_extension'].' file extension to the list of "Upload file types"';
		}

		$output .= '<div class="zn_file_upload zn_admin_button" data-file_type="'.$supports['file_type'].'" data-button="Upload" data-title="Upload File">Select file</div>';
		$output .= '<div class="uploads_container">';

			$fonts = ZNHGFW()->getComponent('icon_manager')->get_custom_fonts();

			if( ! empty( $fonts ) ) {

				foreach ( $fonts as $key => $font ) {

					$output .= $this->render_single_font( $key );

				}

			}

		$output .= '</div>';

		return $output;

	}


	/**
	 * Returns the HTML markup for a single font
	 */
	function render_single_font( $font_key ){
		$output = '';

		$output .= '<div class="znhg_icon_font_container js-znhg_icon_font_container">';
			$output .= '<span class="znhg_icon_font-title">'.$font_key.'<span class="spinner"></span></span>';
			$output .= '';
			$output .= '<a data-font_name="'.$font_key.'" class="zn_font_button js-zn-remove-icon-font">&#215;</a>';
			$output .= '<a data-font_name="'.$font_key.'" class="zn_font_button js-zn-download-icon-font"><span class="dashicons dashicons-download"></span></a>';
		$output .= '</div>';

		return $output;
	}

}


function foo( int $i ){

}