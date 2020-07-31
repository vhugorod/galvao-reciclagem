<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionPageBuilderExportHelper
 *
 * This class provides utility methods to import/export elements or templates
 */
class ZionPageBuilderExportHelper {

	/**
	 * Holds the ZipArchive object
	 * @var array
	 */
	var $zip;

	/**
	 * Holds the wp_upload_dir() paths
	 * @var array
	 */
	var $upload_dir_config;

	/**
	 * Holds the uploads directory url
	 * @var string
	 */
	var $upload_dir_url;

	/**
	 * Holds the uploads directory url without WWW
	 * @var string
	 */
	var $upload_dir_url_no_www;

	/**
	 * Holds the uploads directory path
	 * @var string
	 */
	var $upload_dir_path;

	/**
	 * Holds the uploads placeholder used for replacing the uploads urls
	 * @var string
	 */
	var $site_url_placeholder = 'ZNBP_SITE_URL_PLACEHOLDER';

	/**
	 * Holds the allowed file types on export
	 * @var array
	 */
	var $allowed_file_types = array(
		'jpg',
		'png',
		'gif',
		'svg',
		'jpeg',
		'txt',
	);

	/**
	 * Holds a refference to the media files that were already uploaded
	 * @var array
	 */
	var $uploaded_media = array();


	function __construct() {
		$this->upload_dir_config     = wp_upload_dir();
		$this->upload_dir_path       = $this->upload_dir_config[ 'basedir' ];
		$this->upload_dir_url        = $this->upload_dir_config[ 'baseurl' ];
		$this->upload_dir_url_no_www = str_replace( 'www.', '', $this->upload_dir_url );

		// Add ajax requests actions
		add_action( 'wp_ajax_znpb_download_export', array( $this, 'downloadExportArchive' ) );
		add_action( 'wp_ajax_zn_export_template', array( $this, 'exportTemplate' ) );
		add_action( 'wp_ajax_znpb_export_indv_template', array( $this, 'exportIndividualTemplate' ) );

		// Import actions
		add_action( 'wp_ajax_zn_import_template', array( $this, 'importTemplate' ) );
	}

	function importTemplate() {
		check_ajax_referer( 'zn_framework', 'security' );
		define( 'ZN_PB_AJAX', true );
		$return = array();

		// Check zip file
		switch ( $_FILES[ 'file' ][ 'error' ] ) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				$return[ 'message' ] = 'No file sent';
				wp_send_json_error( $return );
				die();
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$return[ 'message' ] = 'Maximum file upload limit exceeded';
				wp_send_json_error( $return );
				die();
			default:
				break;
		}

		$filePath = $_FILES[ 'file' ][ 'tmp_name' ];

		// Extract it's contents
		WP_Filesystem();
		global $wp_filesystem;

		$temp_path = $this->upload_dir_path . '/znpbtemp/';
		$unzipfile = unzip_file( $filePath, $temp_path );

		// prepare the template data
		if ( is_wp_error( $unzipfile ) ) {
			$return[ 'message' ] = 'There was a problem extracting the template files: ' . $unzipfile->get_error_message();
			zn_delete_folder( $temp_path );
			wp_send_json_error( $return );
			die();
		}

		// Read the template_export config
		$temp_path = trailingslashit( $temp_path );
		$content   = ( $wp_filesystem->exists( $temp_path . 'template_export.txt' ) ) ? $wp_filesystem->get_contents( $temp_path . 'template_export.txt' ) : '';
		if ( '' == $content ) {
			$return[ 'message' ] = "The zip file doesn't contain a template configuration file.";
			zn_delete_folder( $temp_path );
			wp_send_json_error( $return );
			die();
		}

		// Replace the placeholder URL with the current uploads url
		$content_arr = json_decode( $content, true );

		if ( empty( $content_arr[ 'template' ] ) ) {
			$return[ 'message' ] = 'There were no elements found in the template. Skipping import.';
			zn_delete_folder( $temp_path );
			wp_send_json_error( $return );
			die();
		}

		// Get the template map
		$template_name = $content_arr[ 'template_name' ];
		$template      = ! empty( $content_arr[ 'template' ] ) ? $content_arr[ 'template' ] : array();
		$template      = $this->parseRecursiveImport( $template );
		$page_options  = ! empty( $content_arr[ 'page_options' ] ) ? $content_arr[ 'page_options' ] : array();
		$custom_css    = ! empty( $page_options[ 'zn_page_custom_css' ] ) ? $page_options[ 'zn_page_custom_css' ] : '';
		$custom_js     = ! empty( $page_options[ 'zn_page_custom_js' ] ) ? $page_options[ 'zn_page_custom_js' ] : '';
		$level         = ! empty( $content_arr[ 'level' ] ) ? $content_arr[ 'level' ] : '1';
		$content       = array(
			'name'       => '{{{' . $template_name . '}}}',
			'template'   => $template,
			'custom_css' => $custom_css,
			'custom_js'  => $custom_js,
			'level'      => $level,
		);

		$template_type       = isset( $_POST[ 'isSingle' ] ) && filter_var($_POST[ 'isSingle' ], FILTER_VALIDATE_BOOLEAN) ? 'zn_pb_el_templates' : 'zn_pb_templates';
		$post_id             = ZNB()->templates->getPostID( $template_type );
		$template_new_name   = ZNB()->templates->generateKey( $template_name );
		$template_name_check = ! empty( $level ) ? ZNB()->templates->getPageBuilderTemplates( 'zn_pb_el_templates', $template_new_name, '=' ) : ZNB()->templates->getPageBuilderTemplates( 'zn_pb_templates', $template_new_name, '=' );

		// If the template already exists
		if ( ! empty( $template_name_check ) ) {
			$new_template_name = $template_name . zn_uid();
			$template_new_name = ZNB()->templates->generateKey( $new_template_name );
			$content[ 'name' ] = '{{{' . $new_template_name . '}}}';
		}

		$result = update_post_meta( $post_id, $template_new_name, $content );
		zn_delete_folder( $temp_path );

		if ( $result ) {
			$return[ 'isSingle' ] = false;
			$return[ 'name' ]     = $template_name;
			$return[ 'level' ]    = $level;
			$return[ 'message' ]  = 'Template succesfully saved.';
			wp_send_json_success( $return );
		} else {
			$return[ 'message' ] = 'There was a problem saving the template.';
			wp_send_json_error( $return );
		}
	}

	/**
	 * Handles template export for already saved templates
	 * @return string The Json encoded response
	 */
	function exportIndividualTemplate() {
		check_ajax_referer( 'zn_framework', 'security' );

		define( 'ZN_PB_AJAX', true );

		$return = array();

		// Don't do anything if we don't have a proper template name
		if ( empty( $_POST[ 'template_name' ] ) ) {
			$return[ 'message' ] = 'A template name was not provided';
			wp_send_json_error( $return );
		}

		$template_type = isset( $_POST[ 'isSingle' ] ) && filter_var($_POST[ 'isSingle' ], FILTER_VALIDATE_BOOLEAN) ? 'zn_pb_el_templates' : 'zn_pb_templates';

		$post_id       = ZNB()->templates->getPostID();
		$templateName  = ZNB()->templates->generateKey($_POST['template_name']);
		$template_data = ZNB()->templates->getPageBuilderTemplates( $template_type, $templateName, '=' );

		// if we didn't received a proper template data from DB return an error message
		if ( empty( $template_data[ 0 ] ) ) {
			$return[ 'message' ] = 'The template data couldn\'t be returned from DB';
			wp_send_json_error( $return );
		}

		$template_data = maybe_unserialize( $template_data[ 0 ] );
		$pb_template   = $template_data[ 'template' ];

		$name = str_replace( array( '{{{', '}}}' ), '', $template_data[ 'name' ]);

		// Get the template map
		$export_config = array(
			'template_name' => $name[ 1 ],
			'template'      => $pb_template,
			'page_options'  => array(),
			'level'         => isset( $_POST[ 'level' ] ) ? $_POST[ 'level' ] : '',
		);

		$export_location = $this->prepareExportTemplate( $export_config );

		// Return an error message if the export failed
		if ( is_wp_error( $export_location ) ) {
			wp_send_json_error( array(
				'message' => $export_location->get_error_message(),
			) );
		}

		// Send the response
		wp_send_json_success();
	}

	/**
	 * Handles the template export
	 * @return string The Json encoded response
	 */
	function exportTemplate() {
		check_ajax_referer( 'zn_framework', 'security' );
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( 'You don\'t have permission to export' );
		}
		define( 'ZN_PB_AJAX', true );

		// Get the template map
		$export_config = array(
			'template_name' => $_POST[ 'template_name' ],
			'template'      => json_decode( stripslashes( $_POST[ 'template' ] ), true ),
			'page_options'  => isset( $_POST[ 'page_options' ] ) ? $_POST[ 'page_options' ] : '',
			'level'         => isset( $_POST[ 'level' ] ) ? $_POST[ 'level' ] : '',
		);

		// Type 1 of export
		$export_location = $this->prepareExportTemplate( $export_config );

		// Return an error message if the export failed
		if ( is_wp_error( $export_location ) ) {
			$return[ 'message' ] = $export_location->get_error_message();
			wp_send_json_error( $return );
		}

		// Send the response
		wp_send_json_success();
	}


	/**
	 * Get export path
	 *
	 * Will sanitize the filename and return the full path to the file
	 *
	 * @param string $file_name The filname that needs to be sanitized
	 * @return string The sanitized file name
	 */
	public function get_export_path( $file_name ) {
		$file_name = sanitize_file_name( $file_name ) . '.zip';
		return trailingslashit( $this->upload_dir_path ) . $file_name;
	}


	/**
	 * In this export type we will only export a configuration file containing the json data
	 * @param  boolean $name [description]
	 * @param  array $export_config [description]
	 * @return [type]                 [description]
	 */
	public function prepareExportTemplate( $export_config = array() ) {
		if ( ! isset( $_POST['template_name'] ) ) {
			return new WP_Error( 'ZNPB export failed', 'Template name is missing.' );
		}

		// Set the location where we'll save the export file
		$export_path = $this->get_export_path( $_POST['template_name'] );

		if ( class_exists( 'ZipArchive' ) ) {
			$this->zip = new ZipArchive;
			$success   = $this->zip->open( $export_path, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE );

			if ( true !== $success ) {
				return new WP_Error( 'ZNPB export failed', 'Could not create the export file in ' . $export_path );
			}

			// Get all images in template
			if ( is_array( $export_config ) ) {
				$export_config = $this->parseRecursiveExport( $export_config );
			}

			// Add the files in zip
			$this->zip->addFromString( 'template_export.txt', json_encode( $export_config ) );
			$this->zip->close();
		} else {
			// THROW AN ERROR HERE SO THAT THE CLIENT KNOWS WHAT TO DO
			return new WP_Error( 'ZNPB export failed', 'ZipArchive class is not installed on your server.' );
		}

		return $export_path;
	}

	function parseRecursiveExport( &$export_config ) {
		if ( empty( $export_config ) ) {
			return $export_config;
		}

		if ( is_array( $export_config ) ) {
			foreach ( $export_config as $key => &$value ) {
				if ( empty( $value ) ) {
					continue;
				}

				if ( is_array( $value ) ) {
					$this->parseRecursiveExport( $value );
				} else {
					// Check if this is exportable media
					$value = $this->extractImage( $value );
				}
			}
		} else {
			// This should be a string
			$export_config = $this->extractImage( $export_config );
		}

		return $export_config;
	}

	/**
	 * This function checks to see if we have an exportable media and if so, it will add it to the zip file
	 * @param  [type] $url [description]
	 * @param  [type] $zip [description]
	 * @return [type]      [description]
	 */
	private function extractImage( $url ) {
		$file_types = implode( '|', $this->allowed_file_types );
		$pattern    = "#https?://[^/\s]+/\S+\.($file_types)#";
		$url        = preg_replace_callback( $pattern, array(
			$this,
			'mediaCallback',
		), $url );

		return $url;
	}

	/**
	 * This function adds the media file to the zip archive and replaces the uploads directory path with a placeholder path that we cann replace on import
	 * @param  array $file The preg_replace match
	 * @return string The modified file url
	 */
	function mediaCallback( $file ) {

		// Check to see if this is a local file or not
		if ( false !== strpos( $file[ 0 ], $this->upload_dir_url ) || false !== strpos( $file[ 0 ], $this->upload_dir_url_no_www ) ) {
			// Get the media file path relative to the uploads folder
			$file_path = str_replace( array(
				$this->upload_dir_url,
				$this->upload_dir_url_no_www,
			), '', $file[ 0 ] );

			$file = $this->upload_dir_path . $file_path;
			if ( is_file( $file ) ) {
				$this->zip->addFile( $file, 'images' . $file_path );
			}
			return $this->site_url_placeholder . $file_path;
		}
		return $file[ 0 ];
	}

	function parseRecursiveImport( &$template ) {
		if ( empty( $template ) ) {
			return $template;
		}

		if ( is_array( $template ) ) {
			foreach ( $template as $key => &$value ) {
				if ( empty( $value ) ) {
					continue;
				}

				if ( is_array( $value ) ) {
					$this->parseRecursiveImport( $value );
				} else {
					// Check if this is exportable media
					$value = $this->importMedia( $value );
				}
			}
		} else {
			// This should be a string
			$template = $this->importMedia( $template );
		}

		return $template;
	}

	/**
	 * This function checks to see if we have an exportable media and if so, it will add it to the zip file
	 * @param  [type] $url [description]
	 * @param  [type] $zip [description]
	 * @return string
	 */
	private function importMedia( $url ) {
		$file_types = implode( '|', $this->allowed_file_types );
		$pattern    = "#{$this->site_url_placeholder}\/\S+\.($file_types)#";
		$url        = preg_replace_callback( $pattern, array(
			$this,
			'mediaImportCallback',
		), $url );

		return $url;
	}

	/**
	 * This function adds the media file to the zip archive and replaces the uploads directory path with a placeholder path that we can replace on import
	 * @param  array $file The preg_replace match
	 * @return string The modified file url
	 */
	function mediaImportCallback( $file ) {
		// Get the media file path relative to the uploads folder
		$file_path = str_replace( $this->site_url_placeholder, '', $file[ 0 ] );
		$this->upload_media( $file_path );
		// Upload the file
		return $this->upload_dir_url . $file_path;
	}

	function upload_media( $file_path ) {
		// Don't upload media if it was already uploaded
		if ( in_array( $file_path, $this->uploaded_media ) ) {
			return;
		}

		$save_file_name = basename( $file_path );
		$path           = str_replace( $save_file_name, '', $file_path );
		wp_mkdir_p( $this->upload_dir_path . $path, 0777, true );

		$save_path     = $this->upload_dir_path . $file_path;
		$new_file_path = $this->upload_dir_path . '/znpbtemp/images' . $file_path;

		// Don't upload the images that already exists
		if ( is_file( $save_path ) ) {
			// error_log( 'IMAGE ALREADY EXISTS: '. $save_path );
			return;
		}

		copy( $new_file_path, $save_path );

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $save_path ), null );

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $this->upload_dir_url . '/' . basename( $save_path ),
			'post_mime_type' => $filetype[ 'type' ],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $save_path ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $save_path );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $save_path );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		$this->uploaded_media[] = $file_path;
	}

	/**
	 * This function will start the download for the exported template
	 * @return [type] [description]
	 */
	function downloadExportArchive() {
		check_ajax_referer( 'zn_framework', 'nonce' );
		if ( ! current_user_can( 'edit_posts' ) ) {
			return false;
		}

		if ( ! isset( $_GET[ 'file_name' ] ) || empty( $_GET[ 'file_name' ] ) ) {
			return false;
		}

		$file_system = ZNHGFW()->getComponent( 'utility' )->getFileSystem();
		$file_path   = $this->get_export_path( $_GET['file_name'] );

		if ( $file_system->is_file( $file_path ) ) {
			$archive_file_name = basename( $file_path );
			ob_start();
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename=' . basename( $file_path ) );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );
			header( 'Content-Length: ' . filesize( $file_path ) );
			while ( ob_get_level() ) {
				ob_end_clean();
			}
			echo '' . $file_system->get_contents($file_path);
			$file_system->delete( $file_path );
			exit();
		}
	}
}

new ZionPageBuilderExportHelper();
