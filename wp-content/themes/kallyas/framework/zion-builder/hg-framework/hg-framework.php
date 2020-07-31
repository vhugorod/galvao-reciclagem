<?php
/**
 * Plugin Name: Hogash Framework
 * Plugin URI: http://hogash.com
 * Description: This plugin contains the framework for Hogash Themes.
 * Version: 1.0.7
 * Author: Balasa Sorin Stefan
 * Author URI: http://hogash.com
 * License: GPL2
 * Text Domain: zn_framework
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}
// Load the Framework registrar
if ( !function_exists( 'znhgfw_register_framework' ) ) {
	require_once( dirname( __FILE__ ) . '/inc/helpers/loader.php' );
}
// Register current framework
znhgfw_register_framework( __FILE__ );
