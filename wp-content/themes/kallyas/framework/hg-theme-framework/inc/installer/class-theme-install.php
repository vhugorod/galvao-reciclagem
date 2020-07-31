<?php

class ZnHgTFw_Theme_Installer{

	/**
	 * Holds the status of the theme. If the theme is first activate it will return true
	 * @var boolean
	 */
	private $_is_setup = false;

	/**
	 * Holds the theme db status id.
	 * @see $this->_is_setup
	 * @var string
	 */
	private $_theme_db_status_id = '';

	/**
	 * Holds the theme version string that will be added to DB
	 * @see: self::setupThemeDefaults
	 * @var string
	 */
	private $_theme_version_string = '';

	/**
	 * Holds the theme version number
	 * @see: ZNHGTFW()->getVersion()
	 * @var string
	 */
	private $_theme_version = '';

	/**
	 * Holds a refference to DB updates ( updates that needs more processing power )
	 * @var array
	 */
	private $_db_updates = array();

	/**
	 * Holds a refference to normal updates ( updates that can run on a single page reload )
	 * @var array
	 */
	private $_normal_updates = array();


	function __construct(){

		// Theme activation
		add_action( 'after_switch_theme', array( $this, 'redirectToThemeDashboard' ) );

		// Theme Updater
		add_action( 'admin_init', array( $this, 'check_version' ), 5 );
		add_action( 'admin_init', array( $this, 'needs_update_redirect' ) );
		add_action( 'admin_menu', array( $this, 'add_update_menu_item') );
		add_action( 'admin_enqueue_scripts', array( $this, 'zn_print_scripts') );

		// Ajax actions
		add_action( 'wp_ajax_znhgkl_process_theme_updater', array( $this, 'process_theme_update' ) );

		// Setup vars
		$this->_theme_db_status_id   = 'znhgtfw_first_install_' . ZNHGTFW()->getThemeId();
		$this->_theme_version_string = ZNHGTFW()->getThemeId().'_version';
		$this->_theme_version        = ZNHGTFW()->getVersion();
	}


	/**
	 * Checks if this a theme update or a new installation
	 * @return type
	 */
	public function check_version(){

		// Check if we need to load an updater script
		$current_theme_version	= get_option( $this->_theme_version_string );
		$saved_options 			= get_option( ZNHGTFW()->getThemeDbId() );

		// Check if the theme has an update
		// This runs if the user made a manual update
		if( empty( $saved_options ) ){
			$this->themeActivate();
		}
		elseif( ! empty( $saved_options ) && $current_theme_version != $this->_theme_version ){

			// Load update config
			$this->load_update_config();

			// Check if we need to perform a DB update
			foreach ( $this->_db_updates as $version => $updater ) {
				if ( version_compare( $current_theme_version, $version, '<' ) ) {
					update_option( 'zn_theme_needs_update', $current_theme_version, false );
				}
			}

			// Perform the normal updates
			foreach ( $this->_normal_updates as $version => $updater ) {

				if ( version_compare( $current_theme_version, $version, '<' ) ) {
					if( function_exists( $updater['function'] ) ){
						call_user_func( $updater['function'] );
					}
				}

			}

			// Call a general update action
			if ( version_compare( $current_theme_version, $this->_theme_version, '<' ) ) {
				// TODO : Move this elsewhere
				if( function_exists( 'ZNHGFW' ) ){
					ZNHGFW()->getComponent('scripts-manager')->deleteDynamicCss();
				}
				do_action( 'zn_theme_updated', $current_theme_version, $this->_theme_version );
			}

			update_option( $this->_theme_version_string, $this->_theme_version, false );


		}

	}


	function load_update_config(){
		$update_config = ZNHGTFW()->getThemePath( '/template_helpers/update/update_config.php' );
		if( file_exists( $update_config ) ){
			require_once( $update_config );
			$this->_db_updates = apply_filters( 'zn_theme_update_scripts', array() );
			$this->_normal_updates = apply_filters( 'zn_theme_normal_update_scripts', array() );
		}
	}


	/**
	 * Handle updates
	 * TODO : Move all ajax code inside this file
	 */
	public function process_theme_update( $step = 0,  $data = false ) {

		// Load update config
		$this->load_update_config();

		$step = isset ( $_POST[ 'step' ] ) ? sanitize_text_field( $_POST[ 'step' ] ) : '0';
		$data = isset ( $_POST[ 'data' ] ) ? $_POST[ 'data' ] : false;

		if( $step == '0' ){
			$this->set_data();

			if( !empty( $this->update_data['current_update'] ) ){
				$response = array(
					'status' => 'ok',
					'step'	=> 'process_update',
					'data'	=> array(
						'update_version' => $this->update_data['current_update'],
					),
					'response_text' => 'Starting update for version '. $this->update_data['current_update'],
				);
				$this->zn_send_json( $response );
			}
			else{
				$this->do_update_end();
			}

		}
		elseif( $step == 'process_update' ){

			// Load the version update file
			if( empty( $this->_db_updates[$data['update_version']] ) ){
				// TODO : Investigate if we should close here.. it may be a javascript issue ?
				$this->do_update_end();
			}

			// We need to call the updater script for the current version
			include( $this->_db_updates[$data['update_version']]['file'] );
			$current_version_function = $this->_db_updates[$data['update_version']]['function'];

			call_user_func_array( $current_version_function, array( $step, $data ) );

		}
		elseif( $step == 'version_done' ){
			// Here we need to set the current_db_version to the current script version
			if( empty( $this->_db_updates[$data['update_version']] ) ){
				// TODO : Investigate if we should close here.. it may be a javascript issue ?
				$this->do_update_end();
			}

			update_option( 'zn_theme_needs_update', $data['update_version'] );

			$this->set_data();

			if( !empty( $this->update_data['current_update'] ) ){
				$response = array(
					'status' => 'ok',
					'step'	=> 'process_update',
					'data'	=> array(
						'update_version' => $this->update_data['current_update'],
					),
					'response_text' => 'Starting update for version '. $this->update_data['current_update'],
				);
				$this->zn_send_json( $response );
			}
			else{
				$this->do_update_end();
			}


		}
		elseif( $step == 'done' ){
			$this->do_update_end();
		}
		return;
	}

	function do_update_end(){
		delete_option( 'zn_theme_needs_update' );
		$response = array(
				'status' => 'done',
				'response_text' => 'Theme update finished'
			);
		$this->zn_send_json( $response );
	}

	function zn_send_json( $response ) {

		while (ob_get_level()) {
			ob_end_clean();
		}

		header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		$response = json_encode( $response );
		echo '<div class="zn_json_response">';
			echo ''.$response;
		echo '</div>';
		die();
	}

	function set_data(){

		$current_db_version = get_option( 'zn_theme_needs_update' );
		$updates_remaining  = array();

		foreach ( $this->_db_updates as $version => $updater ) {

			if ( version_compare( $current_db_version, $version, '<' ) ) {
				$updates_remaining[] = $version;
			}

		}

		$current_update = false;
		if( !empty( $updates_remaining ) && is_array( $updates_remaining ) ){
			$current_update = reset( $updates_remaining );
		}

		$this->update_data = array(
			'updates_remaining' => $updates_remaining,
			'current_update' => $current_update,
		);

		set_transient( 'zn_update_process', $this->update_data, 12 * HOUR_IN_SECONDS );
	}

	function get_data(){
		$this->update_data = get_transient( 'zn_update_process' );
	}

	/**
	 * Will redirect the user to the update page if the theme requires a DB update
	 * @return void
	 */
	function needs_update_redirect(){

		if( get_option( 'zn_theme_needs_update' ) && ( !isset( $_GET['page'] ) || isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) != 'zn-update' ) && ! ZNHGTFW()->isRequest('ajax') ) {
			wp_redirect( admin_url( 'themes.php?page=zn-update' ) );
			exit();
		}

	}

	/**
	 * Will add the update and about menu items to WP admin menu
	 */
	function add_update_menu_item(){
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		$about_page_name  = sprintf( esc_html__( 'Update %s', 'zn_framework' ), ZNHGTFW()->getThemeName() );
		$welcome_page_title = sprintf ( esc_html__( 'Welcome to %s', 'zn_framework' ), ZNHGTFW()->getThemeName() );

		switch ( sanitize_text_field( $_GET['page'] ) ) {

			case 'zn-update' :
				// Remove all admin notices
				if( get_option( 'zn_theme_needs_update' ) ){
					remove_all_actions('admin_notices', 10 );
					add_theme_page( $welcome_page_title, $about_page_name, 'manage_options', 'zn-update', array( $this, 'update_screen_html' ) );
				}

			break;
		}
	}

	/**
	 * Renders the about page html
	 * @return string The HTML for the about page
	 */
	function about_screen_html(){

		$about_screen_html = apply_filters( 'znhgtfw_about_screen_template', ZNHGTFW()->getFwPath( 'inc/installer/ui/html-page-about.php' ) );
		if( file_exists( $about_screen_html ) ){
			require( $about_screen_html );
		}
		else{
			echo 'It seems that the template files are missing for this view. See "znhgtfw_update_screen_template" WordPress filter';
		}

	}


	/**
	 * Renders the update page html
	 * @return string The HTML for the update page
	 */
	function update_screen_html(){

		$updater_screen_html = apply_filters( 'znhgtfw_update_screen_template', ZNHGTFW()->getFwPath( 'inc/installer/ui/html-page-update.php' ) );
		if( file_exists( $updater_screen_html ) ){
			require( $updater_screen_html );
		}
		else{
			echo 'It seems that the template files are missing for this view. See "znhgtfw_update_screen_template" WordPress filter';
		}
	}

	/**
	 * Will load the HTML scrips needed for the update page
	 * @TODO: separate js functionality from the main FW scripts
	 * @param  string $hook The current page hook
	 * @return void
	 */
	function zn_print_scripts( $hook ){

		/* Set default theme pages where the js and css should be loaded */
		$about_pages = array(
			'appearance_page_zn-update'
		);

		if ( !in_array( $hook, $about_pages ) ) {
			return;
		}

		// Load the framework assets
		ZNHGFW()->getComponent('html')->enqueue_scripts();
	}


	/**
	 * Runs after the theme is activated
	 * Will add default theme options to DB if the theme is installed for the first time
	 * Will Check to see if we need to perform an script update
	 * @return void
	 */
	function themeActivate(){

		// Check if this is the first time the theme was activated
		$savedOptions = get_option( ZNHGTFW()->getThemeDbId() );

		// If this is the first time the theme was activated
		if( empty( $savedOptions ) ){
			// Don't proceed if the dependencies are not met
			if( ! ZNHGTFW()->getComponent('dependency_manager')->checkDependencies() ){
				return;
			}
			$this->doThemeInstall();
		}
	}

	/**
	 * Returns true if this is the first time the user installed the theme
	 * @return bool Whatever the theme is first installed or not
	 */
	public function isThemeSetup(){

		if( ! empty( $this->_is_setup ) ){
			// Checks if this is the first time the user installed this theme
			$this->_is_setup = get_option( $this->_theme_db_status_id, false );
			delete_option( $this->_theme_db_status_id );
		}

		return $this->_is_setup;
	}

	/**
	 * Will do the theme activation
	 * @return void
	 */
	function doThemeInstall(){

		// Set-up theme defaults
		$this->setupThemeDefaults();

		// Sets a flag so that we know that this is the first time the user installed the theme
		update_option( $this->_theme_db_status_id, true, false );

		// Redirect the user to theme Dashboard
		$this->redirectToThemeDashboard();
		exit;
	}

	/**
	 * Will redirect the user to theme dashboard
	 */
	function redirectToThemeDashboard(){
		// Holds the url to which the user is redirected when first installing the theme
		$installUrl = apply_filters( 'znhgtfw_install_url_redirect', ZNHGTFW()->getComponent('utility')->get_options_page_url() );
		// Redirect the user to the Theme Dashboard
		wp_redirect( $installUrl );
	}

	/**
	 * Will import theme default options and settings
	 * @return void
	 */
	function setupThemeDefaults(){

		// Get theme options defaults
		$optionsConfig = ZNHGTFW()->getComponent('utility')->get_theme_options();
		$defaultOptionsValues = array();

		foreach ( $optionsConfig as $key => $optionConfig ) {

			if( !empty( $optionConfig['std'] ) )
			{
				$defaultOptionsValues[$optionConfig['parent']][$optionConfig['id']] = $optionConfig['std'];
			}

		}

		update_option( ZNHGTFW()->getThemeDbId(), $defaultOptionsValues );
		if( function_exists( 'ZNHGFW' ) ){
			ZNHGFW()->getComponent('scripts-manager')->deleteDynamicCss();
		}

		// If installed, clear builder cache as well
		if( function_exists( 'ZNB' ) ){
			ZNB()->scripts_manager->deleteAllCache();
			ZNB()->scripts_manager->compileElementsCss( true );
		}

		update_option( $this->_theme_version_string, $this->_theme_version, false );
	}
}

return new ZnHgTFw_Theme_Installer();
