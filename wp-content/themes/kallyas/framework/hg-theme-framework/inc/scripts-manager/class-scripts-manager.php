<?php

class ZnHgTFw_ScriptsManager{

	function __construct(){
		add_filter( 'zn_dynamic_css', array( $this, 'addThemeDynamicCss' ) );
		add_filter( 'zn_dynamic_css', array( $this, 'add_custom_css' ), 100 );
		add_action( 'wp', array( $this, 'zn_fw_custom_js' ) );

		add_filter( 'zn_options_to_save', array( $this, 'saveCustomCode' ) );

		// Add styles for menu separators
		add_action('admin_enqueue_scripts', array( $this, 'zn_load_kallyas_admin_menus_css' ) );
		add_action('wp_enqueue_scripts', array( $this, 'zn_load_kallyas_admin_menus_css' ));

	}

	public function addThemeDynamicCss( $css ){
		/** Define some vars **/
		$uploads = wp_upload_dir();
		$css_dir = apply_filters( 'zn_dynamic_css_location', ZNHGTFW()->getThemePath( 'css/' ));
		$dynamic_css_file = $css_dir . 'dynamic_css.php';

		// Bail if the theme doesn't have a dynamic css file
		if( ! is_file( $dynamic_css_file ) ){
			return;
		}

		/** Capture CSS output **/
		ob_start();
		require($css_dir . 'dynamic_css.php');
		$dynamicCss = ob_get_clean();
		return $css . $dynamicCss;
	}

	/**
	 * Load Menu & Admin Bar menu CSS to separate items
	 */
	function zn_load_kallyas_admin_menus_css(){
		if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
			wp_enqueue_style( 'znhgtfw-admin-menu-css', ZNHGTFW()->getFwUrl( 'assets/css/admin_menu_separators.css' ), false, ZNHGTFW()->getVersion() );
		}
	}

	/**
	 * Adds custom css to dynamic css file
	 * @param string $css The current css code
	 */
	function add_custom_css( $css ){

		$saved_css = get_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_css', '' );
		$new_css = $css  . $saved_css;

		return $new_css;
	}


	/**
	 * Adds the user added javascript code
	 * @return void
	 */
	function zn_fw_custom_js(){

		$custom_js = get_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_js' );

		if( ! empty( $custom_js ) ){
			$custom_js = array( 'theme_custom_js' => $custom_js );
			ZNHGFW()->getComponent('scripts-manager')->add_inline_js( $custom_js );
		}

	}


	// Change the advanced tab to advanced_options. This is needed for the custom css save
	// TODO : Remove this and change the 'advanced_options' to 'advanced'
	function saveCustomCode( $saved_options ){

		// Save the Custom CSS in custom field
		if ( isset( $saved_options['advanced_options']['custom_css'] ) ) {

			$custom_css = $saved_options['advanced_options']['custom_css'];
			update_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_css', $custom_css, false );

			// Remove custom css from the main options field
			unset( $saved_options['advanced_options']['custom_css'] );
		}

		// Save custom JS in a custom field
		if ( isset( $saved_options['advanced_options']['custom_js'] ) ) {
			$custom_js = $saved_options['advanced_options']['custom_js'];
			update_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_js', $custom_js, false );

			// Remove custom js from the main options field
			unset( $saved_options['advanced_options']['custom_js'] );
		}

		return $saved_options;
	}

}

return new ZnHgTFw_ScriptsManager();
