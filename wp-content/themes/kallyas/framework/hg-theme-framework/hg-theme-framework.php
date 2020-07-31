<?php

/**
 * Class ZnHgTFw_ThemeFramework
 */
class ZnHgTFw_ThemeFramework {

	/**
	 * Holds the theme configuration
	 * @var array
	 */
	public static $instance      = null;
	private $registeredComponent = array();

	/**
	 * Holds the current Theme Version
	 * @var string
	 */
	private $_version;

	/**
	 * Holds the current Theme Name
	 * @var string
	 */
	private $_theme_name;

	/**
	 * Holds the name of the parent theme's directory
	 * @var string
	 */
	private $_parentThemeDir = '';

	/**
	 * Holds the current Framework path
	 * @var string
	 */
	private $_fwPath;

	/**
	 * Holds the current Theme path
	 * @var string
	 */
	private $_themePath;

	/**
	 * Holds the current Theme URI
	 * @var string
	 */
	private $_themeUri;

	/**
	 * Holds the current Framework URL
	 * @var string
	 */
	private $_fwUrl;

	/**
	 * Holds the Theme options id
	 * @see get_option()
	 * @var string
	 */
	private $theme_db_id;

	/**
	 * Holds internal theme id
	 * @var string
	 */
	private $theme_id;

	/**
	 * Holds the theme server URL
	 * The URL is usually used as an API
	 * @var string
	 */
	private $server_url;

	/**
	 * Holds the API version to use when contacting the Dash API
	 * @see ZnHgTFw_ThemeFramework::$server_url
	 * @var string
	 */
	private $api_version = '1';

	/**
	 * Holds the theme logo url
	 * The logo is usually used in admin pages
	 * @var string
	 */
	private $themeLogoUrl = false;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function __construct() {

		// Set FW vars
		$this->initVars();
		// Load theme config
		$this->initFwConfig();
		// Register all FW components
		$this->_registerComponents();

		// Load all helper functions
		$this->initHelpers();

		// Main class init
		add_action( 'init', array( $this, 'initFw' ), 1 );
	}

	/**
	 *	Load theme config
	 */
	function initFwConfig() {
		// Get the theme config
		$config = apply_filters('znhgtfw_config', array());
		if ( empty($config) ) {
			trigger_error( 'Please configure the theme framework (see: znhgtfw_config)', E_USER_ERROR );
		}

		// Setup vars
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $config[ $key ] ) ) {
				$this->$key = $config[ $key ];
			}
		}
	}

	/**
	 * Retrieve the theme configuration
	 * @return array
	 */
	public function getThemeConfig() {
		return apply_filters('znhgtfw_config', array());
	}

	/**
	 * Main Framework init
	 * @see WordPress init action
	 * @return void
	 */
	public function initFw() {

		// Load mega menu component
		$this->_loadComponent( 'mega-menu' );
		$this->_loadComponent( 'scripts-manager' );
		$this->_loadComponent( 'html_integration' );

		/*
		 * since v4.15.10
		 * Load the updater for wp-cron,
		 *
		 * $this->isRequest('cron') = in case the automatic theme update option is enabled
		 * is_admin()      = for the deafault manual theme update from WP Dashboard > Updates screen
		 */
		if ( $this->isRequest('cron') || is_admin() ) {
			// Load Hogash API class
			$this->_loadComponent( 'hogash_api' );
			// Load Updater class
			$this->_loadComponent( 'updater' );
		}

		// Load admin stuff
		if ( is_admin() ) {

			// Load add-ons manager class
			$this->_loadComponent( 'addons_manager' );
			// Load theme Installer
			$this->_loadComponent( 'installer' );
			// TODO: REORGANIZE THE ADMIN CLASS
			$this->_loadComponent( 'admin' );
		} else {
			$this->_loadComponent( 'dependency_manager' );
		}
	}

	/**
	 * Checks if the Hogash framework is installeds
	 * @return boolean [description]
	 */
	function isFwInstalled() {
		return class_exists( 'ZnHg_Framework' );
	}

	/**
	 * Will load all helper functions
	 * @return void
	 */
	function initHelpers() {

		// Load integrations
		require $this->getFwPath( 'inc/integrations/znhgfw_integration.php' );
		// Adds the theme options to the admin bar
		require $this->getFwPath( 'inc/integrations/admin-bar.php' );
		require $this->getFwPath( 'inc/integrations/rev-slider-integration.php' );
		require $this->getFwPath( '/inc/components/index.php' );

		// Backend functions
		require $this->getFwPath( 'inc/helpers/theme_ajax.php' );
		// Backend functions
		require $this->getFwPath( 'inc/helpers/functions-helper.php' );
		// Backend functions
		require $this->getFwPath( 'inc/helpers/functions-backend.php' );
		// Image resize helper functions
		require $this->getFwPath( 'inc/helpers/functions-image-helpers.php' );

		if ( ! $this->isRequest('admin') || $this->isRequest('ajax') ) {
			require $this->getFwPath( 'inc/helpers/functions-frontend.php' );
		}
	}

	/**
	 * Sets framework/theme vars
	 * @return void
	 */
	function initVars() {
		// Get active theme version even if it is a child theme
		$active_theme          = wp_get_theme();
		$this->_version        = $active_theme->parent() ? $active_theme->parent()->get('Version') : $active_theme->get('Version');
		$this->_theme_name     = $active_theme->parent() ? $active_theme->parent()->get('Name') : $active_theme->get('Name');
		$this->_parentThemeDir = $active_theme->get_template();

		// FW PATHS
		$theme_base           = get_template_directory();
		$this->childthemePath = get_stylesheet_directory();

		// Set the path to the theme's directory
		//#! For when ABSPATH is "/"
		$this->_themePath = str_replace( '//', '/', $theme_base );

		// FW URLS
		$this->_themeUri     = esc_url( get_template_directory_uri() );
		$this->childthemeUri = esc_url( get_stylesheet_directory_uri() );

		// FW PATHS
		$this->_fwPath = wp_normalize_path( dirname( __FILE__ ) );
		$fw_basename   = str_replace( wp_normalize_path( $this->_themePath ), '', $this->_fwPath );
		$this->_fwUrl  = $this->_themeUri . $fw_basename;
	}

	/**
	 * What type of request is this?
	 * @var string $type ajax, frontend or admin
	 * @return bool
	 * @param mixed $type
	 */
	public function isRequest( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ! is_admin();
		}

		return false;
	}

	public function getVersion() {
		return $this->_version;
	}

	public function getThemeName() {
		return $this->_theme_name;
	}

	public function getParentThemeDir() {
		return $this->_parentThemeDir;
	}

	public function getFwPath( $path = '' ) {
		return trailingslashit( $this->_fwPath ) . $path;
	}

	public function getFwUrl( $path = '' ) {
		return trailingslashit( $this->_fwUrl ) . $path;
	}

	public function getLogoUrl() {
		return $this->themeLogoUrl;
	}

	/**
	 * Returns the path to the current master theme
	 * @param  string $path the path that will be added to the theme path
	 * @return string The requested path based on current master theme path
	 */
	public function getThemePath( $path = '' ) {
		return trailingslashit( $this->_themePath ) . $path;
	}

	/**
	 * Returns the url to the current master theme
	 * @param  string $path the url that will be added to the theme path
	 * @return string The requested url based on current master theme url
	 */
	public function getThemeUrl( $path = '' ) {
		return trailingslashit( $this->_themeUri ) . $path;
	}

	public function getThemeDbId() {
		return $this->theme_db_id;
	}

	public function getThemeId() {
		return $this->theme_id;
	}

	public function getThemeServerUrl() {
		return $this->server_url;
	}

	public function getApiVersion() {
		return $this->api_version;
	}

	/**
	 * Will register all components by name
	 */
	private function _registerComponents() {
		$this->registerComponent( 'html_integration', $this->getFwPath( 'inc/html/class-html-integration.php' ) );
		$this->registerComponent( 'hogash_api', $this->getFwPath( 'inc/api/ZN_HogashDashboard.php' ) );
		$this->registerComponent( 'addons_manager', $this->getFwPath( 'inc/addons_manager/class-addons-manager.php' ) );
		$this->registerComponent( 'dependency_manager', $this->getFwPath( 'inc/dependency-management/class-dependency-management.php' ) );
		$this->registerComponent( 'utility', $this->getFwPath( 'inc/utility/class-utility.php' ) );
		$this->registerComponent( 'mega-menu', $this->getFwPath( 'inc/mega-menu/class-mega-menu.php' ) );
		$this->registerComponent( 'admin', $this->getFwPath( 'inc/admin/class-zn-admin.php' ) );
		$this->registerComponent( 'installer', $this->getFwPath( 'inc/installer/class-theme-install.php' ) );
		$this->registerComponent( 'updater', $this->getFwPath( 'inc/updater/class-theme-updater.php' ) );
		$this->registerComponent( 'scripts-manager', $this->getFwPath( 'inc/scripts-manager/class-scripts-manager.php' ) );
	}

	public function registerComponent( $componentName, $path ) {
		$this->registeredComponent[ $componentName ] = $path;
	}

	private function _loadComponent( $component_name ) {
		$this->components[ $component_name ] = require_once $this->registeredComponent[ $component_name ];
	}

	public function getComponent( $component_name ) {
		if ( empty( $this->components[ $component_name ] ) ) {
			$this->_loadComponent( $component_name );
		}
		return $this->components[ $component_name ];
	}
}

function ZNHGTFW() {
	return ZnHgTFw_ThemeFramework::getInstance();
}

ZNHGTFW();
