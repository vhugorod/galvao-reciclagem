<?php

/**
 * Plugin Name: ZNPB Counter Element
 * Plugin URI: http://hogash.com
 * Description: This plugin will generate an animated number counter for Kallyas Page builder
 * Version: 1.0.2
 * Author: Balasa Sorin Stefan
 * Author URI: http://themefuzz.com
 * License: GPL2
 */


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class ZnPbCounterElement{

	/**
	 * Holds the plugin current version
	 * @var string
	 */
	var $version = '1.0.2';

	/**
	 * Holds the plugin url
	 * @var string
	 */
	var $url = '';

	/**
	 * Holds the plugin path
	 * @var string
	 */
	var $path = '';


	/**
	 * Holds a refference of the class instance
	 */
	static $instance = null;


	/**
	 * Returns an instance of the classs
	 * @return [type] [description]
	 */
	static public function get_instance(){
		if( null === self::$instance ){
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Main class constructor
	 */
	function __construct(){

		// Set plugin paths
		$this->url     = plugin_dir_url( __FILE__ );
		$this->path    = plugin_dir_path( __FILE__ );

		add_action( 'after_setup_theme', array( $this, 'init_plugin' ) );
		add_filter(  'zn_pb_dirs', array( $this, 'register_elements_dir' ) );
		load_plugin_textdomain('znpb-counter-element', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}


	/**
	 * Fire up the plugin or show a notice in case Kallyas theme is not installed
	 */
	function init_plugin(){
		if( ! function_exists( 'ZNPB' ) ){
			add_action( 'admin_notices', array( $this, 'show_admin_notice' ) );
		}
	}


	/**
	 * Shows an admin notice telling you that the Kallyas theme is not installed.
	 */
	function show_admin_notice(){

		$class = 'notice notice-error is-dismissible';
		$buy_kallyas_url = 'https://themeforest.net/item/kallyas-responsive-multipurpose-wordpress-theme/4091658';
		$buy_link = sprintf( '<a class="button button button-primary" href="%1$s" target="_blank">%2$s</a>', $buy_kallyas_url, __( 'Get Kallyas theme from here', 'znpb-counter-element' ) );
		$message = __( 'Kallyas theme is not installed! Counter Element only works with Kallyas theme.', 'znpb-counter-element' );

		printf( '<div class="%1$s"><p>%2$s</p><p>%3$s</p></div>', $class, $message, $buy_link );

	}


	/**
	 * Add the path to our elements folder
	 * @param  array $dirs the folders that were already loaded
	 * @return array       the complete folders list
	 */
	function register_elements_dir( $dirs ){

		$dirs[] = array(
			'url' => trailingslashit( $this->url .'elements' ),
			'path' => trailingslashit( $this->path .'elements' ),
		);

		return $dirs;
	}

}
ZnPbCounterElement::get_instance();