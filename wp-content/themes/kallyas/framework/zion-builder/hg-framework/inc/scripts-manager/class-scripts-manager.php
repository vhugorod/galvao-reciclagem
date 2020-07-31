<?php

/**
 * Will handle dynamic css and JS content
 */
class ZnHgFw_ScriptsManager{

	/**
	 * Holds a refference to the registered inline javascript
	 * @var string
	 */
	var $inline_js = array();

	/**
	 * Holds a refference to the registered inline css
	 * @var string
	 */
	var $inline_css = '';

	/**
	 * Holds the name of the dynamic css filename
	 * @var string
	 */
	var $_dynamicCssFileName = 'zn_dynamic.css';

	/**
	 * Holds a reference to the utility class
	 * @var object
	 */
	var $_utility;

	/**
	 * Main class constructor
	 */
	function __construct(){

		// Generated css file - The options needs to be saved in order to generate new file
		$uploads = wp_upload_dir();

		// Dynamic css url
		$dynamic_css_file_url = trailingslashit( $uploads[ 'baseurl' ] ) . $this->_dynamicCssFileName;
		$dynamic_css_file_url = trailingslashit( $uploads[ 'baseurl' ] ) . $this->_dynamicCssFileName;

		// Dynamic css file path
		$this->_dynamicCssFilePath = trailingslashit( $uploads['basedir'] ) . $this->_dynamicCssFileName;
		$this->_dynamicCssFileUrl = zn_fix_insecure_content( $dynamic_css_file_url );

		// Add Utility shortcut
		$this->_utility = ZNHGFW()->getComponent('utility');

		// Add actions
		add_action( 'wp_footer', array( $this, 'output_inline_js' ), 25 );
		add_action( 'wp_head', array( $this, 'output_inline_css' ), 25 );
		add_action( 'after_switch_theme', array( $this, 'deleteDynamicCss' ) );
		add_action( 'activated_plugin', array( $this, 'deleteDynamicCss' ) );
		add_action( 'znhgfw_domain_change', 'deleteDynamicCss' );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dynamic_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	/**
	 * Will delete the dynamic css upon theme switch
	 * @return void
	 */
	function deleteDynamicCss(){
		ZNHGFW()->getComponent('utility')->getFileSystem()->delete( $this->_dynamicCssFilePath );
	}

	/**
	 * Frontend: Load theme's dynamic CSS
	 * @hooked to wp_enqueue_scripts.
	 */
	function enqueue_dynamic_style() {

		// Make sure that the dynamic css is generated
		if( ! $this->_utility->getFileSystem()->is_file( $this->_dynamicCssFilePath ) ){
			$this->generateDynamicCss();
		}

		// Get save date microtime so that the cache gets invalidated
		$saved_date = filemtime( $this->_dynamicCssFilePath );
		wp_enqueue_style( 'th-theme-options-styles', $this->_dynamicCssFileUrl, array(), $saved_date );

	}


	function enqueue_scripts(){
		// Register Isotope script
		// It can be used by themes or plugins
		wp_register_script( 'isotope', ZNHGFW()->getFwUrl('assets/dist/js/jquery.isotope.min.js'), 'jquery', '', true );
	}


	/**
	 * Generates the dynamic css
	 * @param string $css Css code that can be passed
	 * @return void
	 */
	function generateDynamicCss( $css = '' ){

		$css = apply_filters('zn_dynamic_css', $css);
		$css = zn_minimify( $css );

		$fs = ZNHGFW()->getComponent( 'utility' )->getFileSystem();

		/** Write to zn_dynamic.css file **/
		$fs->put_contents( $this->_dynamicCssFilePath, $css, 0644 );
	}

	/**
	 * @param string $code The code that you want to add to inline js
	 * @param bool|false $echo should we echo or return the code ?
	 */
	public function add_inline_js( $code, $echo = false ) {

		if ( $echo ) {

			$code = $code[ key( $code ) ];

			echo '<!-- Generated inline javascript -->';
			echo '<script type="text/javascript">';
				echo '(function($){';
					echo $code;
				echo '})(jQuery);';
			echo '</script>';

			return;
		}

		$this->inline_js[ key( $code ) ] = "\n" . $code[ key( $code ) ] . "\n";
	}


	/**
	 * @param string $code
	 * @param bool|false $echo
	 */
	public function add_inline_css( $code, $echo = false ) {

		if ( $echo ) {

			echo '<!-- Generated inline styles -->';
			echo '<style type="text/css">';
				echo $code;
			echo '</style>';

			return;
		}

		$this->inline_css .= $code;

	}

	/**
	 * Output the inline js
	 */
	public function output_inline_js() {

		if ( ! empty( $this->inline_js ) && is_array( $this->inline_js ) ) {

			echo '<!-- Zn Framework inline JavaScript-->';
			echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function($) {';
				foreach ( $this->inline_js as $key => $code ) {
					echo $code;
				}
				echo '});';
			echo '</script>';

		}
	}

	/**
	 * Output the inline css
	 */
	public function output_inline_css() {
		if ( $this->inline_css ) {
			echo '<!-- Generated inline styles -->';
			echo "<style type='text/css' id='zn-inline-styles'>";
				echo $this->inline_css;
			echo '</style>';
		}
	}
}

return new ZnHgFw_ScriptsManager();
