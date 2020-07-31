<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

class ZnHg_Framework {

	//Will hold the current FW version
	private       $_version = '1.0.0';
	private       $_fwUrl;
	private       $_fwPath;
	public static $instance      = null;
	private $registeredComponent = array();
	private $_isDomainChanged    = false;

	/**
	 * Holds the reference to the instance of each loaded component
	 *
	 * @var array
	 */
	private       $components = array();

	public static function getInstance( $fwData ) {
		if ( null === self::$instance && empty( $fwData ) ) {
			trigger_error( 'ZnHg_Framework needs to be instantiated before use.', E_USER_ERROR );
		}
		if ( null === self::$instance ) {
			self::$instance = new self( $fwData );
		}
		return self::$instance;
	}

	private function __construct( $fw_data ) {
		// set FW version
		$this->_version = $fw_data['version'];
		// Set Framework paths
		$this->_setPaths();
		// Let other know we're ready
		$this->_registerComponents();

		// Load helper functions
		require( $this->getFwPath( 'inc/helpers/functions-helpers.php' ) );
		require( $this->getFwPath( 'inc/helpers/functions-color-helpers.php' ) );

		// Main class init
		add_action( 'init', array( $this, 'initFw' ), 1 );
	}

	/**
	 * What type of request is this?
	 *
	 * @var string $type ajax, frontend or admin
	 *
	 * @param mixed $type
	 *
	 * @return bool
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

	/**
	 * Main Framework init
	 *
	 * @see WordPress init action
	 *
	 * @return void
	 */
	public function initFw() {

		// LOAD HELPER COMPONENTS
		// Init the icon manager
		$this->_loadComponent( 'icon_manager' );
		// Initialize the Image resizer class
		$this->_loadComponent( 'image-resizer' );
		// Initialize fonts manager
		$this->_loadComponent( 'font-manager' );

		// Initialize typekit client
		$this->_loadComponent( 'typekit' );

		// Load Ajax handlers
		// TODO : Find a better way to do this
		if ( defined( 'DOING_AJAX') && DOING_AJAX ) {
			// Init HTML manager. There are ajax calls that need HTML
			$this->_loadComponent( 'html');
		}

		if ( is_admin() ) {
			//#! Initialize the MetaBox class
			$this->_loadComponent( 'metabox' );
			//! Initialize the TermMeta class
			$this->_loadComponent( 'termmeta' );
			// Load permalinks framework
			$this->_loadComponent( 'permalinks' );

			// Check domain change
			$this->checkDomainChange();
		}
		// Init frontend functionality
		else {
		}

		// Init scripts manager. Manages inline scripts
		$this->_loadComponent( 'scripts-manager' );

		// Initialize the Shortcodes classs
		$this->_loadComponent( 'shortcode_manager' );

		// Allow others to hook into FW
		do_action( 'znhgtfw_init', $this );
	}

	/**
	 *    Set FW URL AND path
	 */
	private function _setPaths() {
		// Set FW Path
		$fwPath = wp_normalize_path( dirname( __FILE__ ) );
		// Set FW URI
		$theme_base = get_template_directory();
		// Fixes problem with baename(__FILE__) returning two slashes
		$theme_base           = str_replace( '//', '/', $theme_base );
		$theme_base_for_regex = str_replace( '#', '\#', wp_normalize_path( $theme_base ) );

		$is_theme       = ( preg_match( '#' . $theme_base_for_regex . '#', wp_normalize_path( $fwPath ) ) ) ? true : false;
		$directory_uri  = ( $is_theme ) ? get_template_directory_uri() : WP_PLUGIN_URL;
		$directory_path = ( $is_theme ) ? $theme_base : WP_PLUGIN_DIR;
		$fw_basename    = str_replace( wp_normalize_path( $directory_path ), '', $fwPath );
		$this->_fwUrl   = $directory_uri . $fw_basename;
		$this->_fwPath  = $directory_path . $fw_basename;
	}

	public function getVersion() {
		return $this->_version;
	}

	public function getFwPath( $path = '' ) {
		return trailingslashit( $this->_fwPath ) . $path;
	}

	public function getFwUrl( $path = '' ) {
		return trailingslashit( $this->_fwUrl ) . $path;
	}

	public function debugVars() {
		var_dump( $this );
	}

	public function isDebug() {
		return defined( 'ZNHGFW_DEBUG' ) && ZNHGFW_DEBUG == true;
	}

	/**
	 * Returns true if the home url has changed
	 *
	 * @return boolean the status of current domain
	 */
	public function isDomainChanged() {
		return $this->_isDomainChanged;
	}

	/**
	 * Will register all components by name
	 */
	private function _registerComponents() {
		$this->registerComponent( 'utility', $this->getFwPath( 'inc/utility/class-utility.php' ) );
		$this->registerComponent( 'font-manager', $this->getFwPath( 'inc/font-manager/class-font-manager.php' ) );
		$this->registerComponent( 'typekit', $this->getFwPath( 'inc/font-manager/ZnTypekitClient.php' ) );
		$this->registerComponent( 'html', $this->getFwPath( 'inc/html/class-html.php' ) );
		$this->registerComponent( 'metabox', $this->getFwPath( 'inc/metaboxes/class-metabox.php' ) );
		$this->registerComponent( 'termmeta', $this->getFwPath( 'inc/taxonomies/class-termmeta.php' ) );
		$this->registerComponent( 'icon_manager', $this->getFwPath( 'inc/icon-manager/class-icon-manager.php' ) );
		$this->registerComponent( 'shortcode_manager', $this->getFwPath( 'inc/shortcodes/class-shortcodes-manager.php' ) );
		$this->registerComponent( 'image-resizer', $this->getFwPath( 'inc/image-resizer/class-image-resize.php' ) );
		$this->registerComponent( 'permalinks', $this->getFwPath( 'inc/permalinks/class-permalinks.php' ) );
		$this->registerComponent( 'scripts-manager', $this->getFwPath( 'inc/scripts-manager/class-scripts-manager.php' ) );
	}

	public function registerComponent( $componentName, $path ) {
		$this->registeredComponent[$componentName] = $path;
	}

	private function _loadComponent( $component_name ) {
		$this->components[$component_name] = require_once( $this->registeredComponent[$component_name] );
	}

	public function getComponent( $component_name ) {
		if ( empty( $this->components[$component_name] ) ) {
			$this->_loadComponent( $component_name );
		}
		return $this->components[$component_name];
	}

	/**
	 * Checks if the domain has changed
	 * Triggers the znhgfw_domain_change action
	 *
	 * @return void
	 */
	public function checkDomainChange() {
		$currentDomain = home_url();
		// Get the saved domain from DB. Note that the domain URI is reversed at this point
		$savedDomain = get_option( 'znhgfw_current_domain' );

		// Reverse back to the original saved domain uri
		$savedDomain = strrev( $savedDomain );

		// Check if this is a domain change
		if ( empty( $savedDomain ) ) {
			update_option( 'znhgfw_current_domain', strrev( $currentDomain ), false );
		} elseif ( $savedDomain !== $currentDomain ) {
			update_option( 'znhgfw_current_domain', strrev( $currentDomain ), false );
			do_action( 'znhgfw_domain_change', $currentDomain, $savedDomain );
			$this->_isDomainChanged = true;
		}
	}
}

function ZNHGFW( $fwData = array() ) {
	return ZnHg_Framework::getInstance( $fwData );
}
