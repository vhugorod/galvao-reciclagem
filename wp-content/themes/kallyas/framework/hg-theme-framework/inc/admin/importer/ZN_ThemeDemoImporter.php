<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ZN_ThemeDemoImporter
 */
class ZN_ThemeDemoImporter
{
	const NONCE_ACTION = 'ZN_DEMO_IMPORT';

	const DEMO_TEMP_FOLDER = 'znDummyTemp';

	// Whether or not a demo is currently installing
	const DEMO_INSTALLING_TRANSIENT = 'zn_demo_installing';

	// Installation steps

	// [INTERNAL] SPECIAL STEPS
	const STEP_DO_GET_DEMO        = 'zn_get_demo';
	const STEP_DO_GLOBAL_SETTINGS = 'zn_global_settings';
	const STEP_DO_CUSTOM_ICONS    = 'zn_custom_icons';
	const STEP_DO_CUSTOM_FONTS    = 'zn_custom_fonts';
	const STEP_DO_IMPORT_IMAGES   = 'zn_import_images';
	const STEP_DO_IMPORT_MENUS    = 'zn_import_menus';
	const STEP_DO_CLEANUP         = 'zn_import_cleanup';
	const STEP_DO_REV_SLIDERS     = 'zn_import_rev_sliders';
	const STEP_DO_POST_PROCESSING = 'zn_import_post_processing';

	//@see: importer/inc/importer-tmpl.php
	const STEP_INSTALL_PLUGINS = 'zn_install_plugins';
	const STEP_INSTALL_THEME_OPTIONS = 'zn_install_theme_options';
	const STEP_INSTALL_WIDGETS = 'zn_install_widgets';
	const STEP_INSTALL_CONTENT = 'zn_install_content';

	// Caches the uploads Dir URL
	static $uploadsDirUrl = '';

	/**
	 * User's required capability to view or interact with this page
	 */
	const USER_CAP = 'manage_options';


	public static $tempTermId = false;
	public static $dummyTermId = false;



	public function __construct(){
		// delete_transient( self::DEMO_INSTALLING_TRANSIENT );
		// Add ajax actions
		add_action( 'wp_ajax_install_demo', array( $this, 'ajaxInstallDemo') );

		// Load resources
		add_action( 'admin_enqueue_scripts', array($this, 'loadScripts') );
	}

	public function loadScripts( $hook )
	{
		if( false !== ($pos = strpos($hook, 'zn-about')))
		{
			// Styles
			wp_enqueue_style( 'zn-about-style', ZNHGTFW()->getFwUrl( 'inc/admin/assets/css/zn_about.css' ), array(), ZNHGTFW()->getVersion() );
			wp_enqueue_style( 'zn-demo-import-styles', ZNHGTFW()->getFwUrl( 'inc/admin/importer/assets/zn-demo-import.css' ) );

			// Scripts
			wp_register_script( 'znde-manager', ZNHGTFW()->getFwUrl( 'inc/admin/importer/assets/znde-manager.js' ), array('jquery') );
			wp_localize_script( 'znde-manager', 'ZN_THEME_DEMO', array(
				'nonce' => wp_create_nonce( self::NONCE_ACTION ),
				'status_waiting' => esc_html__('Waiting', 'zn_framework'),
				'status_in_progress' => esc_html__('In progress', 'zn_framework'),
				'status_completed' => esc_html__('Done', 'zn_framework'),
				'status_failed' => esc_html__('Failed', 'zn_framework'),
				'status_none' => esc_html__('No', 'zn_framework'),
				'status_error' => esc_html__('Error', 'zn_framework'),

				// Messages
				'msg_select_option' => esc_html__('Please select at least one option.', 'zn_framework'),
				'msg_invalid_markup' => esc_html__('Invalid markup. Please contact the theme developers.', 'zn_framework'),
				'msg_get_demo' => esc_html__('Retrieving demo:', 'zn_framework'),
				'msg_install_plugins' => esc_html__('Install plugins:', 'zn_framework'),
				'msg_install_theme_options' => esc_html__('Install theme options:', 'zn_framework'),
				'msg_install_widgets' => esc_html__('Install widgets:', 'zn_framework'),
				'msg_install_content' => esc_html__('Install demo content:', 'zn_framework'),
				'msg_install_global_opt' => esc_html__('Install global options:', 'zn_framework'),
				'msg_install_custom_icons' => esc_html__('Install custom icons:', 'zn_framework'),
				'msg_install_custom_fonts' => esc_html__('Install custom fonts:', 'zn_framework'),
				'msg_import_images' => esc_html__('Import images:', 'zn_framework'),
				'msg_import_menus' => esc_html__('Install menus:', 'zn_framework'),
				'msg_import_rev_sliders' => esc_html__('Install Revolution Sliders:', 'zn_framework'),
				'msg_import_post_processing' => esc_html__('Post processing:', 'zn_framework'),
				'msg_import_cleanup' => esc_html__('Cleanup:', 'zn_framework'),
				'msg_install_complete' => esc_html__('Success! Install complete.', 'zn_framework'),
				'msg_install_failed_retries' => esc_html__('Maximum failed retries reached. Unfortunately, your server cannot install this demo.', 'zn_framework'),
				'msg_install_abort' => esc_html__('Installation failed.', 'zn_framework'),
				'msg_install_failed_invalid_response' => esc_html__('Invalid response from server.', 'zn_framework'),
				'msg_install_configure' => esc_html__('Please configure your installation and try again.', 'zn_framework'),

				// Modal window
				'modal_title' => esc_html__('Demo Install', 'zn_framework'),
				'modal_close' => esc_html__('Close', 'zn_framework'),

				// Install steps
				'state_none' => ZN_DemoImportHelper::STATE_NONE,
				'state_wait' => ZN_DemoImportHelper::STATE_WAIT,
				'state_done' => ZN_DemoImportHelper::STATE_DONE,
				'state_fail' => ZN_DemoImportHelper::STATE_FAIL,
				'state_abort' => ZN_DemoImportHelper::STATE_ABORT,
				'state_complete' => ZN_DemoImportHelper::STATE_COMPLETE,
				'state_unknown' => ZN_DemoImportHelper::STATE_UNKNOWN,
			));
			wp_enqueue_script( 'znde-manager' );
			wp_enqueue_script( 'znde-init', ZNHGTFW()->getFwUrl( 'inc/admin/importer/assets/znde-init.js' ),array( 'znde-manager' ) );
		}
	}

	/**
	 * Mark the demo as installing
	 */
	private static function __setDemoInstalling(){

		//#! Disable cache if this is GoDaddy hosting
		if( ZN_HogashDashboard::isGoDaddy() ){
			wp_using_ext_object_cache( false );
		}

		set_transient( self::DEMO_INSTALLING_TRANSIENT, true, 5*60);
	}

	/**
	 * Check to see whether or not there is a demo installing
	 * @return bool
	 */
	public static function isDemoInstalling(){
		return false;
		//#! Disable cache if this is GoDaddy hosting
		if( ZN_HogashDashboard::isGoDaddy() ){
			wp_using_ext_object_cache( false );
		}

		$data = get_transient( self::DEMO_INSTALLING_TRANSIENT );
		return (!empty($data));
	}

	private static function __preValidateRequest()
	{
		if( ! defined('DOING_AJAX')){
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_ABORT,
				'msg' => esc_html__('Fatal Error: DOING_AJAX not defined', 'zn_framework')
			));
		}
		if( ! DOING_AJAX){
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_ABORT,
				'msg' => esc_html__('Fatal Error: Not doing ajax', 'zn_framework')
			));
		}

		if( ! self::__validateUser()){
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_ABORT,
				'msg' => esc_html__('Fatal Error: You are not allowed to perform this action.', 'zn_framework')
			));
		}

		if(! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), self::NONCE_ACTION)){
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_ABORT,
				'msg' => esc_html__('Fatal Error: Nonce is not valid', 'zn_framework')
			));
		}
	}

	public static function canAccessDir( $dirPath )
	{
		return ( is_dir($dirPath) && is_readable($dirPath) );
	}

	/**
	 * Recursive value search/replace in array
	 *
	 * @param array $array The target array where to search in
	 * @param mixed $find The target value to search for
	 * @param mixed $replace The value to replace $find with
	 *
	 * @return array
	 */
	public static function recursiveArrayReplace( &$array, $find, $replace){
		if(is_array($array)){
			foreach($array as $key => &$value) {
				if(empty($value)){
					continue;
				}
				$serialized = is_serialized( $value );
				if($serialized){
					$value = unserialize( $value );
					if(is_array($value)){
						$value = self::recursiveArrayReplace($value, $find, $replace);
					}
					$value = serialize($value);
				}
				elseif( is_array($value) ){
					$value = self::recursiveArrayReplace($value, $find, $replace);
				}
				else {
					$value = str_replace($find, $replace, $value);
				}
			}
		}
		return $array;
	}

	/**
	 * Retrieve the path to the demo directory
	 * @param string $demoName
	 * @return int|string
	 */
	public static function getDemoDirPath($demoName = ''){
		if(empty($demoName)) {
			if (!isset($_POST['demo_name']) || empty($_POST['demo_name'])) {
				return 0;
			}
			$demoName = esc_attr( wp_strip_all_tags($_POST['demo_name']) );
		}
		return self::getTempFolder() . '/' . $demoName;
	}

	public static function getTempFolder(){
		$demoTmpPath = self::getUploadsDirPath() . self::DEMO_TEMP_FOLDER;
		return $demoTmpPath;
	}

	/**
	 * Utility method to use for the provided installing steps
	 */
	public function ajaxInstallDemo()
	{
		self::__preValidateRequest();

		add_filter( 'wp_insert_term_data', array( get_class(), 'tryToKeepTermIdOnImport' ), 10, 3 );


		global $wp_filesystem;

		// Check to see if we need to instantiate the filesystem with credentials
		ob_start();
		$credentials = request_filesystem_credentials( false );
		$data = ob_get_clean();

		// If the credentials are not ok
		if ( ! empty( $data ) ) {
			$status = array();
			$status[ 'error' ] = 'Invalid credentials';
			$status[ 'error_code' ] = 'invalid_ftp_credentials';
			self::__respond( $status );
		}

		if( ! $wp_filesystem ){
			WP_Filesystem( $credentials );
		}

		// Try to increase the time limit
		@set_time_limit( 0 );

		// The installation step field is required
		if(! isset($_POST['step']) || empty($_POST['step'])){
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_ABORT,
				'msg' => esc_html__('Invalid request. The installation step is missing', 'zn_framework')
			));
		}
		// The demo_name field is required
		if(! isset($_POST['demo_name']) || empty($_POST['demo_name'])){
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_ABORT,
				'msg' => esc_html__('Invalid request. "demo_name" not found in post data.', 'zn_framework')
			));
		}

		//#! Disable cache if this is GoDaddy hosting
		if( ZN_HogashDashboard::isGoDaddy() ){
			wp_using_ext_object_cache( false );
		}

		$demoName = ZN_DemoImportHelper::sanitizeString( $_POST['demo_name'] );
		$step = ZN_DemoImportHelper::sanitizeString( $_POST['step'] );
		$demoDirPath = self::getDemoDirPath($demoName);

		// Get the demo
		if( self::STEP_DO_GET_DEMO == $step ) {
			self::__setDemoInstalling();

			// Demo already retrieved and extracted
			if(self::canAccessDir($demoDirPath))
			{
				//#! Check if there is any content
				$cfgFile = trailingslashit($demoDirPath).ZN_DemoImportHelper::DEMO_CONFIG_FILE;
				if( ! is_file($cfgFile) || ! is_readable($cfgFile)){
					self::__respond( array(
						'state' => ZN_DemoImportHelper::STATE_ABORT,
						'msg' => esc_html__('An error occurred while retrieving the demo. Please try again in a few minutes.', 'zn_framework')
					));
				}

				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			// else, get the demo
			else {
				$demoDirPath = self::__getDemo( $demoName );
				if(empty($demoDirPath) || ! is_dir( $demoDirPath)){
					self::__respond( array(
						'state' => ZN_DemoImportHelper::STATE_ABORT,
						'msg' => esc_html__('Fatal Error: Could not retrieve the demo, aborting.', 'zn_framework')
					));
				}

				//#! Check if there is any content
				$cfgFile = trailingslashit($demoDirPath).ZN_DemoImportHelper::DEMO_CONFIG_FILE;
				if( ! is_file($cfgFile) || ! is_readable($cfgFile)){
					self::__respond( array(
						'state' => ZN_DemoImportHelper::STATE_ABORT,
						'msg' => esc_html__('An error occurred while retrieving the demo. Please try again in a few minutes.', 'zn_framework')
					));
				}

				// so far so good
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
		}

		// IMPORT IMAGES
		elseif( self::STEP_DO_IMPORT_IMAGES == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::importDemoImages( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL GLOBAL SETTINGS (wp global options, etc...)
		elseif( self::STEP_DO_GLOBAL_SETTINGS == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::importGlobalOptions( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL PLUGINS
		elseif( self::STEP_INSTALL_PLUGINS == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::installDependentPlugins( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL REVOLUTION SLIDERS
		elseif( self::STEP_DO_REV_SLIDERS == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::installRevolutionSliders( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL THEME OPTIONS
		elseif( self::STEP_INSTALL_THEME_OPTIONS == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::importThemeOptions( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL WIDGETS
		elseif( self::STEP_INSTALL_WIDGETS == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::importWidgets( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL CUSTOM ICONS
		elseif( self::STEP_DO_CUSTOM_ICONS == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::importCustomIcons( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL CUSTOM FONTS
		elseif( self::STEP_DO_CUSTOM_FONTS == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::importCustomFonts( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL CONTENT
		elseif( self::STEP_INSTALL_CONTENT == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::importContent( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// INSTALL MENUS
		elseif( self::STEP_DO_IMPORT_MENUS == $step )
		{
			self::__setDemoInstalling();

			if(ZN_DemoImportHelper::importMenus( $demoDirPath )){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// POST-PROCESSING
		elseif( self::STEP_DO_POST_PROCESSING == $step )
		{
			self::__setDemoInstalling();

			if( ZN_DemoImportHelper::postProcessing( $demoDirPath ) ){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// CLEANUP
		elseif( self::STEP_DO_CLEANUP == $step )
		{
			self::__setDemoInstalling();

			if( ZN_DemoImportHelper::__cleanup() ){
				self::__respond( array(
					'state' => ZN_DemoImportHelper::STATE_DONE,
					'msg' => sprintf(esc_html__('Installation step "%s" completed.', 'zn_framework'), $step)
				));
			}
			self::__respond( array(
				'state' => ZN_DemoImportHelper::STATE_FAIL,
				'msg' => sprintf(esc_html__('Installation step "%s" failed to complete.', 'zn_framework'), $step)
			));
		}

		// Not good
		delete_transient( self::DEMO_INSTALLING_TRANSIENT );
		self::__respond( array(
			'state' => ZN_DemoImportHelper::STATE_UNKNOWN,
			'msg' => esc_html__('Fatal Error: Invalid installation state.', 'zn_framework')
		));
	}

//<editor-fold desc=">>> PRIVATE METHODS">

	/**
	 * TODO: IMPLEMENT VALIDATION AND RESPONSE, SANITIZE DIR PATH, ETC
	 *
	 * Retrieve the demo from our server
	 *
	 * @requires valid user
	 * @param string $demoName
	 * @return bool|string
	 */
	private static function __getDemo( $demoName ) {
		$result = '';
		global $wp_filesystem;

		$tmpDirPath = self::getTempFolder();
		if( ! $wp_filesystem->is_dir($tmpDirPath)){
			wp_mkdir_p( $tmpDirPath );
		}

		// Set save path
		$demoArchivePath = trailingslashit($tmpDirPath) . $demoName.'.zip';

		//#!
		$savePath = ZN_HogashDashboard::getDemo( $demoName, $demoArchivePath );
		if(false === $savePath){
			return $result;
		}
		elseif( $wp_filesystem->is_file($savePath) ) {
			$saveDirPath = self::getTempFolder().'/'.$demoName.'/';

			if( ! $wp_filesystem->is_dir($saveDirPath)){
				wp_mkdir_p( $saveDirPath );
			}

			unzip_file( $savePath, $saveDirPath );

			return $saveDirPath;
		}
		return $result;
	}

	/**
	 * Respond to ajax requests
	 * @param array $data The data to send as a response
	 */
	private static function __respond( $data = array( 'state' => '', 'msg' => '') ){
		remove_filter( 'wp_insert_term_data', array( get_class(), 'tryToKeepTermIdOnImport' ), 10, 3 );
		delete_transient( ZN_ThemeDemoImporter::DEMO_INSTALLING_TRANSIENT );
		@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		echo wp_json_encode( $data );
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ){ wp_die(); }
		exit;
	}

	public static function getUploadsDirPath()
	{
		$wp_uploadsDir = wp_upload_dir();
		$dirPath = '';
		if(is_array($wp_uploadsDir))
		{
			if(isset($wp_uploadsDir['basedir'])) {
				return trailingslashit( $wp_uploadsDir['basedir'] );
			}
		}
		return $dirPath;
	}
	public static function getUploadsDirUrl()
	{

		if( empty( self::$uploadsDirUrl ) ){
			$wp_uploadsDir = wp_upload_dir();
			if(is_array($wp_uploadsDir))
			{
				if(isset($wp_uploadsDir['baseurl'])) {
					self::$uploadsDirUrl = trailingslashit($wp_uploadsDir['baseurl']);
				}
			}
		}
		return self::$uploadsDirUrl;
	}

	/**
	 * Will try to insert the term id as received from exporter
	 */
	public static function tryToKeepTermIdOnImport( $data, $taxonomy, $args ){
		if( ! empty( self::$tempTermId ) ){
			global $wpdb;
			// Check to see if this term id is taken
			$termExistsCheck = get_term( self::$tempTermId );
			// If the term id doesn't exist, force our id
			if( null == $termExistsCheck || is_wp_error( $termExistsCheck ) ){
				$data['term_id'] = self::$tempTermId;
			}
			else{
				// error_log( '[IMPORTER] Term exists id check: ' . var_export( self::$tempTermId, 1 ) );
				// error_log( '[IMPORTER] Term exists response: ' . var_export( $termExistsCheck, 1 ) );
			}
		}

		return $data;
	}
//</editor-fold desc=">>> PRIVATE METHODS">

//<editor-fold desc="::: INTERNALS">
	/**
	 * Check to see whether or not the current user is logged in and has the "manage_options" capability (administrator)
	 * @return bool
	 */
	 private static function __validateUser(){
		return (is_user_logged_in() && current_user_can(self::USER_CAP));
	}
//</editor-fold desc="::: INTERNALS">

}
new ZN_ThemeDemoImporter();
