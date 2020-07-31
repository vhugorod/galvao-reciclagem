<?php

class Znb_Integration_Manager {

	/**
	 * Holds the integrations that needs to be loaded
	 *
	 * @var array
	 *
	 * @since  1.0.0
	 */
	private $registered_integrations = array();

	/**
	 * Holds integrations that where already loaded
	 *
	 * @var array
	 *
	 * @since  1.0.0
	 */
	public $loaded_integrations = array();


	/**
	 * Main class constructor
	 *
	 * @since  1.0.0
	 */
	function __construct() {

		// Load the base integration class
		include( dirname( __FILE__) . '/class-base-integration.php' );

		// Load default integrations
		$this->load_default_integrations();

		// Allow other to load integrations
		do_action( 'znb_integrations_init', $this );

		// Try to initialise integrations
		$this->init_integrations();
	}


	/**
	 * Will register the default integrations provided by the plugin
	 *
	 * @since  1.0.0
	 */
	function load_default_integrations() {
		//#! Load integration files
		$integrationsDir = trailingslashit( dirname( __FILE__) ) . 'integrations/';

		include( $integrationsDir . 'class_yoast_integration.php' );
		$this->register_integration( 'yoast', 'Znb_Yoast_Integration' );

		include( $integrationsDir . 'class_polylang_integration.php' );
		$this->register_integration( 'polylang', 'Znb_Polylang_Integration' );

		include( $integrationsDir . 'class_nextgengallery_integration.php' );
		$this->register_integration( 'nextgengallery', 'Znb_NextGenGallery_Integration' );

		include( $integrationsDir . 'class_wordpress_revisions.php' );
		$this->register_integration( 'wordpress_revisions', 'Znb_WordPress_Revisions' );

		include( $integrationsDir . 'class_woocommerce_integration.php' );
		$this->register_integration( 'woocommerce_integration', 'Znb_WooCommerce_Integration' );

		include( $integrationsDir . 'class_paid_membership_integration.php' );
		$this->register_integration( 'paid_membership', 'Znb_PaidMembership_Integration' );

		// Fixes text widget not showing options after WP 4.8
		include( $integrationsDir . 'class_wordpress_widgets.php' );
		$this->register_integration( 'wordpress_widgets', 'Znb_WordPressWidgets_Integration' );

		// Gutenberg integration
		include( $integrationsDir . 'class_gutenberg_integration.php' );
		$this->register_integration( 'gutenberg', 'Znb_Gutenberg_Integration' );
	}


	/**
	 * Will try to load the integrations if can_load permits it
	 *
	 * @since  1.0.0
	 */
	function init_integrations() {
		foreach ( $this->registered_integrations as $integration_name => $integration_class) {
			if ( call_user_func( array($integration_class, 'can_load' )) ) {
				$this->loaded_integrations[$integration_name] = new $integration_class();
			}
		}
		// Remove the registered integrations
		$this->registered_integrations = null;
	}


	/**
	 * Will register a new integration
	 *
	 * @param string $integration_name  The integration nice name
	 * @param string $integration_class Integration class name
	 *
	 * @since  1.0.0
	 */
	function register_integration( $integration_name, $integration_class ) {
		// Only add if the integration extends our base integration class
		if ( is_subclass_of( $integration_class, 'Znb_Integration' ) ) {
			$this->registered_integrations[$integration_name] = $integration_class;
		}
	}


	/**
	 * Will unregister an integration
	 *
	 * @param string $integration_name The integration name you want to remove
	 *
	 * @return [type] [description]
	 */
	function unregister_integration( $integration_name ) {
		unset( $this->registered_integrations[$integration_name] );
	}


	function get_integration( $integration_name ) {
		return isset( $this->loaded_integrations[$integration_name] ) ? $this->loaded_integrations[$integration_name] : false;
	}
}

//return new Znb_Integration_Manager();
