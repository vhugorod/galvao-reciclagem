<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
/**
 * Plugin Name: Zion Builder
 * Plugin URI: http://zionbuilder.net
 * Description: This plugin allows you to build beautiful websites in minutes.
 * Version: 1.0.28
 * Author: Balasa Sorin Stefan
 * Author URI: http://themefuzz.com
 * License: GPL2
 * TextDomain: zn_framework
 */


/**
 * Class ZionBuilder
 *
 * Standard singleton. This is the plugin's base class.
 */
class ZionBuilder {
	/**
	 * Holds the reference to the instance of the ZnTemplateSystem class
	 *
	 * @see __construct()
	 *
	 * @var ZnTemplateSystem
	 */
	public $templates = null;
	/**
	 * Holds the reference to the instance of the ZionUtility class
	 *
	 * @see __construct()
	 *
	 * @var ZionUtility
	 */
	public $utility = null;
	/**
	 * Holds the reference to the instance of the Znb_Integration_Manager class
	 *
	 * @see __construct()
	 *
	 * @var Znb_Integration_Manager
	 */
	public $integrations = null;
	/**
	 * Holds the reference to the instance of the ZionElementsManager class
	 *
	 * @see __construct()
	 *
	 * @var ZionElementsManager
	 */
	public $elements_manager = null;
	/**
	 * Holds the reference to the instance of the ZionScriptsManager class
	 *
	 * @see __construct()
	 *
	 * @var ZionScriptsManager
	 */
	public $scripts_manager = null;
	/**
	 * Holds the reference to the instance of the ZionEditor class
	 *
	 * @see __construct()
	 *
	 * @var ZionEditor
	 */
	public $builder = null;
	/**
	 * Holds the reference to the instance of the ZionPageBuilderFrontend class
	 *
	 * @see __construct()
	 *
	 * @var ZionPageBuilderFrontend
	 */
	public $frontend = null;
	/**
	 * Holds the reference to the instance of the ZionSmartArea class
	 *
	 * @see __construct()
	 *
	 * @var ZionSmartArea
	 */
	public $smart_area = null;
	/**
	 * Holds the reference to the instance of the ZionPageBuilderAdmin class
	 *
	 * @see __construct()
	 *
	 * @var ZionPageBuilderAdmin
	 */
	public $admin = null;


	/**
	 * Holds the system path to the plugin's install directory
	 *
	 * @see __construct()
	 *
	 * @var string
	 */
	public $plugin_dir = '';

	/**
	 * Holds the http path to the plugin's install directory
	 *
	 * @see __construct()
	 *
	 * @var string
	 */
	public $plugin_url = '';

	/**
	 * Holds the current version of the plugin
	 *
	 * @see __construct()
	 * @see getVersion()
	 *
	 * @var string
	 */
	public $version = '';

	/**
	 * Holds the information about this plugin
	 *
	 * @see getPluginInfo()
	 *
	 * @var array
	 */
	private $_pluginData = array();

	/**
	 * Holds the reference to the instance of this class
	 *
	 * @see __construct()
	 *
	 * @var null|ZionBuilder
	 */
	private static $_instance = null;

	/**
	 * Get the reference to the instance of this class
	 *
	 * @return ZionBuilder
	 */
	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Class constructor
	 */
	private function __construct() {

		// Setup default class vars like paths and version
		$this->initVars();

		//#! Enable the plugin to be translatable.
		load_plugin_textdomain( 'zn_framework', false, basename( dirname( __FILE__ ) ) . '/languages' );

		add_action( 'after_setup_theme', array( $this, 'init' ) );

		//#! Load framework
		include( $this->getFwPath( 'hg-framework/hg-framework.php' ) );
	}

	/**
	 * Setup version, builder path and URL
	 */
	function initVars() {
		$this->version = $this->getVersion();

		// Get the file path
		$zionPath = wp_normalize_path( dirname( __FILE__ ) );

		// Get theme path
		$theme_base           = get_template_directory();
		$theme_base           = str_replace( '//', '/', $theme_base );
		$theme_base_for_regex = str_replace( '#', '\#', wp_normalize_path( $theme_base ) );

		// Check if this file is inside a theme or a plugin
		$is_theme = ( preg_match( '#' . wp_normalize_path( $theme_base_for_regex ) . '#', wp_normalize_path( $zionPath ) ) ) ? true : false;

		// get the directory URI
		$directory_uri = ( $is_theme ) ? get_template_directory_uri() : WP_PLUGIN_URL;
		// Get the directory path
		$directory_path = ( $is_theme ) ? $theme_base : WP_PLUGIN_DIR;
		$fw_basename    = str_replace( wp_normalize_path( $directory_path ), '', $zionPath );

		// Setup plugin path and url
		$this->plugin_dir = trailingslashit( $zionPath );
		$this->plugin_url = $directory_uri . $fw_basename;
	}

	/**
	 * Initialize the class' main functionality
	 */
	public function init() {
		// include helpers
		include( $this->plugin_dir . 'inc/helpers/helpers-help.php' );
		include( $this->plugin_dir . 'inc/helpers/helpers-frontend.php' );
		include( $this->plugin_dir . 'inc/ZionPageBuilderCustomCode.php' );

		// Load Template system
		include( $this->plugin_dir . 'inc/editor/ZionTemplateSystem.php' );
		$this->templates = new ZionTemplateSystem();

		// Load the utility class
		include( $this->plugin_dir . 'inc/utility/ZionUtility.php' );
		$this->utility = new ZionUtility();

		// Load integration manager
		include( $this->plugin_dir . 'inc/integration_manager/class-integration-manager.php' );
		$this->integrations = new Znb_Integration_Manager();

		// Elements manager class
		include( $this->plugin_dir . 'inc/ZionElementsManager.php' );
		$this->elements_manager = new ZionElementsManager();

		// Load scripts manager
		include( $this->plugin_dir . 'inc/ZionScriptsManager.php' );
		$this->scripts_manager = new ZionScriptsManager();

		// Main builder
		include( $this->plugin_dir . 'inc/editor/ZionEditor.php' );
		$this->builder = new ZionEditor();

		// Frontend
		include( $this->plugin_dir . 'inc/ZionPageBuilderFrontend.php' );
		$this->frontend = new ZionPageBuilderFrontend();

		// Load Smart Areas
		include( $this->plugin_dir . 'inc/smart_areas/ZionSmartArea.php' );
		$this->smart_area = new ZionSmartArea();

		if ( is_admin() ) {
			include( $this->plugin_dir . 'inc/admin/ZionPageBuilderAdmin.php' );
			$this->admin = new ZionPageBuilderAdmin;
		}

		do_action( 'znb:init', $this );
	}

	/**
	 * Returns the path to a given assets location. With trailing slash!
	 *
	 * @param string $path The path relative to the assets folder
	 *
	 * @return string The url to the requested asset path
	 */
	public function assetsUrl( $path ) {
		return trailingslashit( $this->plugin_url ) . 'assets/' . trailingslashit( $path );
	}


	/**
	 * Checks to see if we are in debug mode or not
	 *
	 * @return boolean whatever we are on debug or not
	 */
	public function isDebug() {
		return ( defined( 'ZION_DEBUG' ) && ZION_DEBUG );
	}

	/**
	 * Retrieve the information about this plugin and store it into the local variable
	 *
	 * @return array
	 */
	public function getPluginInfo() {
		if ( ! empty( $this->_pluginData) ) {
			return $this->_pluginData;
		}
		if ( ! function_exists( 'get_plugin_data') ) {
			include_once( trailingslashit( ABSPATH) . 'wp-admin/includes/plugin.php' );
		}
		$this->_pluginData = get_plugin_data( __FILE__, false );
		return $this->_pluginData;
	}

	/**
	 * Retrieve the current version of the plugin
	 *
	 * @return mixed
	 */
	public function getVersion() {
		if ( empty( $this->_pluginData) ) {
			$this->getPluginInfo();
		}
		return $this->_pluginData['Version'];
	}

	public function getFwPath( $path = '' ) {
		return trailingslashit( $this->plugin_dir ) . $path;
	}

	public function getFwUrl( $path = '' ) {
		return trailingslashit( $this->plugin_url ) . $path;
	}

	/**
	 * Retrieve the plugin's directory name
	 *
	 * @return string
	 */
	public function getPluginDirName() {
		return basename( $this->plugin_dir );
	}
}


if ( ! function_exists(  'ZNB' ) ) {
	/**
	 * Utility method to retrieve the reference to the instance of the ZionBuilder class
	 *
	 * @return ZionBuilder
	 */
	function ZNB() {
		return ZionBuilder::getInstance();
	}
}

//#! Initialize the plugin
ZNB();
