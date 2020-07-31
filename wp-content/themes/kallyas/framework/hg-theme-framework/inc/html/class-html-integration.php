<?php

class ZnHgTFw_HtmlIntegration{
	function __construct(){
		// Only do stuff if the HTML FW is needed
		add_action( 'znhgfw_html_init', array( $this, 'addHtmlFeatures' ) );
	}

	function addHtmlFeatures( $htmlManager ){

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		require dirname(__FILE__) . '/fields/theme_import_export.php';
		$htmlManager->registerOptionType( new ZnHgFw_Html_Theme_Import_Export( $htmlManager ) );
	}

	function enqueue_scripts(){
		wp_enqueue_script( 'zn-theme-options-export', ZNHGTFW()->getFwUrl( 'assets/js/html.js' ), array( 'jquery' ), ZNHGTFW()->getVersion(), true );
	}
}
return new ZnHgTFw_HtmlIntegration();
