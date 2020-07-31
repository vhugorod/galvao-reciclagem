<?php if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZnThemeImportExport
 *
 * Import/Export theme options
 */
class ZnThemeImportExport
{
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
	var $site_url_placeholder = 'ZN_SITE_URL_PLACEHOLDER';

	/**
	 * Holds the name of the export archive
	 * @var string
	 */
	var $export_file_name = 'theme_options_export.zip';

	// Holds the list of images to get for export
	private $_imagesList = array();


	public function __construct()
	{
		$this->upload_dir_config = wp_upload_dir();
		$this->upload_dir_path = $this->upload_dir_config['basedir'];
		$this->upload_dir_url = $this->upload_dir_config['baseurl'];
		$this->upload_dir_url_no_www = str_replace('www.', '', $this->upload_dir_url );

		add_action( 'wp_ajax_zn_theme_export', array($this, 'ajax_theme_options_export') );
		add_action( 'wp_ajax_zn_theme_export_download', array($this, 'zn_download_theme_options_archive') );
		add_action( 'wp_ajax_zn_theme_options_import', array($this, 'ajax_theme_options_import') );
	}



	/**
	 * This function is used by the theme export to replace the site url with a placeholder
	 * @internal
	 * @param mixed $arrayValue
	 * @param null  $arrayKeyIndex
	 */
	public function _replaceUrlWithPlaceholder( &$arrayValue, $arrayKeyIndex = null)
	{
		$uploadUrl = $this->upload_dir_url;
		if($uploadUrl){
			if (false !== stristr($arrayValue, $uploadUrl)){
				$t = $arrayValue = str_ireplace($uploadUrl, $this->site_url_placeholder, $arrayValue);
				array_push($this->_imagesList, $t);
			}
		}
	}

	/**
	 * This function is used by the theme import to replace the placeholder with the site url
	 * @internal
	 * @param mixed $arrayValue
	 * @param null  $arrayKeyIndex
	 */
	public function _replacePlaceholderWithUrl( &$arrayValue, $arrayKeyIndex = null)
	{
		$uploadUrl = $this->upload_dir_url;
		if($uploadUrl){
			if (false !== stristr($arrayValue, $this->site_url_placeholder)){
				$arrayValue = str_ireplace($this->site_url_placeholder, $uploadUrl, $arrayValue);
			}
		}
	}


	function ajax_theme_options_export()
	{
		check_ajax_referer( 'zn_framework', 'zn_ajax_nonce' );

		$exportImages = false;
		if(isset($_POST['data']['export_images']) && ('true' == $_POST['data']['export_images'])){
			$exportImages = true;
		}

		if(! class_exists('ZipArchive')){
			wp_send_json_error( esc_html__( 'Error: ZipArchive class is not installed on your server.', 'zn_framework' ) );
		}

		// Create the export archive

		// Set the location where we'll save the export file
		$export_path = trailingslashit( $this->upload_dir_path ) . $this->export_file_name;

		// Create and open the archive
		$this->zip = new ZipArchive();
		$success = $this->zip->open( $export_path, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);

		if( $success !== true ) {
			wp_send_json_error( esc_html__( 'Error: Could not create the export archive.', 'zn_framework' ) );
		}

		// Add the db options
		$dbOptions = get_option( ZNHGTFW()->getThemeDbId() );

		// If we need to export images, then we need to set the placeholder
		if($exportImages){
			array_walk_recursive( $dbOptions, array($this, '_replaceUrlWithPlaceholder') );

			// Check for images
			if(! empty($this->_imagesList))
			{
				foreach($this->_imagesList as $imagePath)
				{
					// Remove the placeholder
					$imagePath = str_replace($this->site_url_placeholder, '', $imagePath);

					// Normalize path
					$imagePath = preg_replace('!\\+!', '/', $imagePath);

					// Add the image to archive
					$this->zip->addFile( $this->upload_dir_path.$imagePath, 'images'.$imagePath );
				}
			}
		}

		// Export fonts as well
		$fontsDir = ZNHGFW()->getComponent('icon_manager')->get_custom_fonts();
		if(! empty($fontsDir))
		{
			$this->zip->addEmptyDir('zn_fonts');
			$fontArchives = array();
			foreach($fontsDir as $dirName => $dirInfo)
			{
				// Create an archive out of a font dir
				$fontDirPath = $dirInfo['filepath'].$dirName;
				if(is_dir($fontDirPath)){
					$fontArchivePath = $fontDirPath.'.zip';
					$files = scandir($fontDirPath);
					if(! empty($files)){
						$z = new ZipArchive();
						$z->open($fontArchivePath, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);
						foreach($files as $filePath){
							if($filePath != '.' && $filePath != '..'){
								$z->addFile($fontDirPath.'/'.$filePath, $filePath);
							}
						}
						$z->close();
						if(is_file($fontArchivePath)) {
							$this->zip->addFile( $fontArchivePath, 'zn_fonts/'. basename($dirName).'.zip' );
							array_push($fontArchives, $fontArchivePath);
						}
					}
				}
			}
		}

		// Add the database options to the archive
		$this->zip->addFromString( 'db_options.info', serialize($dbOptions) );

		// Close the archive
		$this->zip->close();

		// Cleanup
		if(! empty($fontArchives)){
			foreach($fontArchives as $path){
				if(is_file($path)) {
					unlink( $path );
				}
			}
		}

		// Send response
		wp_send_json_success(esc_html__('Done', 'zn_framework'));
	}

	function ajax_theme_options_import()
	{
		check_ajax_referer( 'zn_framework', 'zn_ajax_nonce' );

		if( ! isset( $_POST['attachment'] ) ){
			wp_send_json_error(esc_html__('Error: Invalid Request.', 'zn_framework'));
		}
		if( ! isset($_POST['attachment']) || empty($_POST['attachment'])){
			wp_send_json_error(esc_html__('Error: Invalid Request. Attachment info is missing.', 'zn_framework'));
		}

		// Get the uploads directory path
		$uploadDir = trailingslashit( $this->upload_dir_path );

		// Copy the archive to the uploads directory
		$attachment = $_POST['attachment'];
		$zipPath = get_attached_file( (int)$attachment[ 'id' ] );
		$tempPath = trailingslashit( $uploadDir . 'themeOptionsTemp' );
		$tempdir2 = zn_create_folder( $tempPath, false );
		WP_Filesystem();
		$extracted = unzip_file( $zipPath, $tempPath );

		// Return the error in case the archive couldn't be extracted
		if( is_wp_error( $extracted ) ){
			wp_send_json_error(	$extracted->get_error_message() );
		}

		// Setup paths
		$imagesDir = $tempPath.'images/';
		$fontsDir = $tempPath.'zn_fonts/';
		$dbOptionsFile = $tempPath.'db_options.info';
		$files = null;

		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();

		// Copy images if any
		if($fs->is_dir($imagesDir))
		{
			copy_dir($imagesDir, $this->upload_dir_path);
		}

		// Copy fonts if any
		if($fs->is_dir($fontsDir))
		{
			$files = scandir($fontsDir);
			if($files)
			{
				// Set the list of special directories to ignore
				$ignore = array('.', '..');
				foreach($files as $entry)
				{
					if(in_array($entry, $ignore)){
						continue;
					}
					// Import all fonts found
					$fontArchivePath = $fontsDir.$entry;
					$fontArchiveTitle = basename($entry, '.zip');
					ZNHGFW()->getComponent('icon_manager')->install_icon_package($fontArchivePath);
				}
			}
		}

		$dbOptionName = ZNHGTFW()->getThemeDbId();

		// Update options
		$dbOptions = $fs->get_contents($dbOptionsFile);
		if(! empty($dbOptions)) {
			$data = maybe_unserialize($dbOptions);
			if(! empty($data) && is_array($data)) {
				// Replace placeholder with the site url
				array_walk_recursive( $data, array($this, '_replacePlaceholderWithUrl') );
				// Save option in database
				delete_option( $dbOptionName );
				add_option( $dbOptionName, $data );
			}
		}

		//#!++ Cleanup
		// Delete the temp directory and its subdirectories/files
		$fs->delete( $tempPath, true );

		//#!-- Cleanup
		wp_send_json_success(esc_html__('Theme options imported successfully.', 'zn_framework'));
	}

	/**
	 * This function will start the download for the exported archive
	 * @internal
	 */
	public function zn_download_theme_options_archive()
	{
		check_ajax_referer( 'zn_framework', 'nonce' );

		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();

		$export_file = trailingslashit( $this->upload_dir_path ) . $this->export_file_name;
		if(! $fs->is_file($export_file)){
			wp_send_json_error(esc_html__('Error: Could not locate the export archive', 'zn_framework'));
		}
		if(! headers_sent()) {
			header( "Content-type: application/zip" );
			header( "Content-Disposition: attachment; filename=" . $this->export_file_name );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );
		}

		echo ''.$fs->get_contents($export_file);

		// Delete file after sending it to user
		$fs->delete( $export_file );

		wp_send_json_success(esc_html__('Done', 'zn_framework'));
	}

}
new ZnThemeImportExport();
