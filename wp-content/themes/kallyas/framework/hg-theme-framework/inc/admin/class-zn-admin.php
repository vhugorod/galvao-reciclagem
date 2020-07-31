<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// This will add the theme options panel if the theme has this support

/*
*	TO DO :
*	Separate theme page css from HTML class css
*
*/

/**
 * Holds the HTTP path to the import directory
 */
define( 'DEMO_IMPORT_DIR_URL', ZNHGTFW()->getFwUrl( 'inc/admin/importer' ) );

class ZnAdmin {
	public $theme_pages = array();
	public $data        = array();

	function __construct() {
		$this->load_files();

		add_action( 'admin_menu', array( $this, 'zn_add_admin_pages' ) );
		add_action( 'admin_menu', array( $this, 'edit_admin_menus' ) );
		add_action( 'current_screen', array( $this, 'remove_actions' ) );
		add_action( 'current_screen', array( $this, 'initHtml' ) );

		// AJAX actions
		add_action( 'wp_ajax_zn_server_check', array( $this, 'ajax_check_server_connection' ) );
		add_action( 'wp_ajax_zn_theme_registration', array( $this, 'ajax_theme_registration_hook' ) );
		add_action( 'wp_ajax_zn_theme_unlink', array( $this, 'ajax_theme_unlink_hook' ) );
		add_action( 'wp_ajax_zn_refresh_theme_demos', array( $this, 'ajax_refresh_theme_demos' ) );
		add_action( 'wp_ajax_zn_refresh_plugins_list', array( $this, 'ajax_refresh_plugins_list' ) );

		// TODO : This loads on all pages... we need to only target the theme dashboard page
		add_action( 'admin_enqueue_scripts', array( $this, 'zn_print_scripts' ) );
		add_action( 'admin_footer', array( $this, 'print_request_filesystem_credentials' ) );
	}

	function print_request_filesystem_credentials() {
		wp_print_request_filesystem_credentials_modal();
	}

	function ajax_check_server_connection() {
		if ( ! isset( $_POST[ 'zn_nonce' ] ) || ! wp_verify_nonce( sanitize_text_field( $_POST[ 'zn_nonce' ] ), 'zn_framework' ) ) {
			wp_send_json_error( 'Sorry, your nonce did not verify.' );
		}

		if ( ! ZN_HogashDashboard::checkConnection() ) {
			$server_fix_documentation_url = 'https://my.hogash.com/documentation/how-to-resolve-server-connection/';
			wp_send_json_error(
				sprintf( '%s <a href="%s" target="_blank">%s</a>',
					esc_html__( 'Unfortunately your web host does not allow you to connect to our domains. Please take a look at the following documentation article to resolve the problem ', 'zn_framework' ),
					$server_fix_documentation_url,
					esc_html__( 'How to resolve', 'zn_framework' )
				)
			);
		}
		wp_send_json_success( '1' );
	}

	/**
	 * Will check and save the user credentials needed for automatic theme updates. This works ony for single sites
	 * @return string A json formatted value
	 */
	function ajax_theme_registration_hook() {
		if ( ! isset( $_POST[ 'zn_nonce' ] ) || ! wp_verify_nonce( sanitize_text_field( $_POST[ 'zn_nonce' ] ), 'zn_theme_registration' ) ) {
			wp_send_json_error( array( 'error' => 'Sorry, your nonce did not verify.' ) );
		}

		$apiKey = isset( $_POST[ 'dash_api_key' ] ) ? esc_attr( $_POST[ 'dash_api_key' ] ) : '';

		if ( empty( $apiKey ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'An error occurred. The API key is missing.', 'zn_framework' ) ) );
		} elseif ( ! ZN_HogashDashboard::isValidApiKeyFormat( $apiKey ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'An error occurred. The API format is not valid.', 'zn_framework' ) ) );
		}

		//#! Theme registration
		$response = ZN_HogashDashboard::connectTheme( $apiKey );
		if ( isset($response['code']) && ZN_HogashDashboard::E_SUCCESS == $response['code'] ) {
			//#! Save a new request to our server if theme is connected
			ZN_HogashDashboard::updateApiKey( $apiKey );
			ZN_HogashDashboard::setThemeCheckOption('1x');
			wp_send_json_success( array( 'message' => $response[ 'message' ] ) );
		}

		ZN_HogashDashboard::setThemeCheckOption('0x');
		wp_send_json_error( array( 'error' => (isset($response[ 'message' ]) ? $response[ 'message' ] : esc_html__( 'An error occurred.', 'zn_framework' ) ) ) );
	}

	/**
	 * Unlink domain
	 */
	function ajax_theme_unlink_hook() {
		if ( defined('DOING_CRON') && DOING_CRON ) {
			return;
		}

		if ( ! defined('DOING_AJAX') || ! DOING_AJAX ) {
			return;
		}

		if ( ! isset( $_POST[ 'zn_nonce' ] ) || ! wp_verify_nonce( sanitize_text_field( $_POST[ 'zn_nonce' ] ), 'zn_theme_registration' ) ) {
			wp_send_json_error( array( 'error' => 'Sorry, your nonce did not verify.' ) );
		}

		$response = ZN_HogashDashboard::unlinkTheme();
		if ( isset($response['code']) && ZN_HogashDashboard::E_SUCCESS == $response['code'] ) {
			wp_send_json_success( array( 'message' => $response[ 'message' ] ) );
		} elseif ( isset($response[ 'message' ]) ) {
			wp_send_json_error( array( 'error' => $response[ 'message' ] ) );
		} else {
			wp_send_json_error( array( 'error' => 'An error occurred.' ) );
		}
	}


	function ajax_refresh_theme_demos() {

		if ( ! isset( $_POST[ 'zn_nonce' ] ) || ! wp_verify_nonce( sanitize_text_field( $_POST[ 'zn_nonce' ] ), 'refresh_demos_list' ) ) {
			wp_send_json_error( array( 'error' => 'Sorry, your nonce did not verify.' ) );
		}

		//#! Disable cache if this is GoDaddy hosting
		if ( ZN_HogashDashboard::isGoDaddy() ) {
			wp_using_ext_object_cache( false );
		}


		if ( ! ZN_HogashDashboard::isConnected() ) {
			do_action('dash_clear_cached_data');
			wp_send_json_error( array( 'error' => 'Sorry, your need to register your theme before using this functionality.' ) );
		}

		//#! All good
		$status = ZN_HogashDashboard::getAllDemos();
		if ( is_wp_error( $status ) ) {
			wp_send_json_error( array( 'error' => $status->get_error_message() ) );
		}
		ZN_HogashDashboard::clearDemosList();
		wp_send_json_success( '1' );
	}

	function ajax_refresh_plugins_list() {
		if ( ! isset( $_POST[ 'zn_nonce' ] ) || ! wp_verify_nonce( sanitize_text_field( $_POST[ 'zn_nonce' ] ), 'refresh_plugins_list' ) ) {
			wp_send_json_error( array( 'error' => 'Sorry, your nonce did not verify.' ) );
		}

		//#! Disable cache if this is GoDaddy hosting
		if ( ZN_HogashDashboard::isGoDaddy() ) {
			wp_using_ext_object_cache( false );
		}

		if ( ! ZN_HogashDashboard::isConnected() ) {
			do_action('dash_clear_cached_data');
			wp_send_json_error( array( 'error' => 'Sorry, your need to register your theme before using this functionality.' ) );
		}

		//#! All good
		$status = ZN_HogashDashboard::getAllPlugins();
		if ( is_array( $status ) && isset( $status[ 'error' ] ) ) {
			wp_send_json_error( array( 'error' => $status[ 'error' ] ) );
		}
		ZN_HogashDashboard::clearPluginsList();

		wp_send_json_success( '1' );
	}

	function zn_print_scripts( $hook ) {

		/* Set default theme pages where the js and css should be loaded */
		$this->theme_pages[] = 'widgets.php';
		$this->theme_pages   = apply_filters( 'zn_theme_admin_pages_slug', $this->theme_pages );

		// Load about page scripts
		if ( 'appearance_page_zn-about' === $hook || 'toplevel_page_zn-about' === $hook ) {
			wp_enqueue_style( 'zn-about-style', ZNHGTFW()->getFwUrl( 'inc/admin/assets/css/zn_about.css' ), array(), ZNHGTFW()->getVersion() );
			// TODO: Remove this. It is only needed for the modals
			wp_enqueue_style( 'zn-html-css', ZNHGTFW()->getFwUrl('assets/css/zn_modal.css') );

			// Theme Check
			wp_register_script( 'znkl-theme-check', ZNHGTFW()->getFwUrl('inc/admin/assets/js/zn_theme_check.js' ), array( 'jquery' ), ZNHGTFW()->getVersion() );
			wp_localize_script( 'znkl-theme-check', 'ZnAjaxThemeCheck', array(
				'ajaxurl'  => admin_url( 'admin-ajax.php', 'relative' ),
				'security' => wp_create_nonce( 'zn_framework' ),
			));
			wp_enqueue_script( 'znkl-theme-check' );

			wp_enqueue_script( 'zn-modal', ZNHGTFW()->getFwUrl( 'assets/js/zn_modal.js'), array( 'jquery', 'jquery-ui-draggable' ), ZNHGTFW()->getVersion(), true );
			wp_enqueue_script( 'zn-about_script', ZNHGTFW()->getFwUrl( 'inc/admin/assets/js/zn_about.js' ), array( 'jquery', 'jquery-ui-tooltip' ), ZNHGTFW()->getVersion() );
		}

		if ( ! in_array( $hook, $this->theme_pages ) ) {
			return;
		}

		// LOAD CUSTOM SCRIPTS
		wp_enqueue_script( 'zn-theme-ajax-callback', ZNHGTFW()->getFwUrl( 'assets/js/zn_theme_ajax_callback.js' ), 'jquery', '', true );
	}

	/**
	 * Load the necessarry extra files
	 * @return null Nothing
	 */
	function load_files() {
		// Load theme Import/Export settings class
		include ZNHGTFW()->getFwPath( 'inc/options-exporter/ZnThemeImportExport.php' );
		// Import classes
		include ZNHGTFW()->getFwPath( 'inc/admin/importer/ZN_ThemeDemoImporter.php' );
		include ZNHGTFW()->getFwPath( 'inc/admin/importer/ZN_DemoImportHelper.php' );
		
	}




	/**
	 * Add all framework admin pages
	 * @return null
	 */
	function zn_add_admin_pages() {

		// Add the main page
		$this->data[ 'theme_pages' ] = ZNHGTFW()->getComponent('utility')->get_theme_options_pages();
		$icon                        = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB3aWR0aD0iNzhweCIgaGVpZ2h0PSI3OHB4IiB2aWV3Qm94PSIwIDAgNzggNzgiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sbnM6c2tldGNoPSJodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2gvbnMiPiAgICAgICAgPHRpdGxlPmthbGx5YXNfbG9nbzwvdGl0bGU+ICAgIDxkZXNjPkNyZWF0ZWQgd2l0aCBTa2V0Y2guPC9kZXNjPiAgICA8ZGVmcz4gICAgICAgIDxsaW5lYXJHcmFkaWVudCB4MT0iNTAlIiB5MT0iMCUiIHgyPSI1MCUiIHkyPSI5Ny4wODAyNzc0JSIgaWQ9ImxpbmVhckdyYWRpZW50LTEiPiAgICAgICAgICAgIDxzdG9wIHN0b3AtY29sb3I9IiMzQzNDM0MiIG9mZnNldD0iMCUiPjwvc3RvcD4gICAgICAgICAgICA8c3RvcCBzdG9wLWNvbG9yPSIjODQyRTJGIiBvZmZzZXQ9IjQ5LjQ5NjY3NjUlIj48L3N0b3A+ICAgICAgICAgICAgPHN0b3Agc3RvcC1jb2xvcj0iI0NEMjEyMiIgb2Zmc2V0PSIxMDAlIj48L3N0b3A+ICAgICAgICA8L2xpbmVhckdyYWRpZW50PiAgICA8L2RlZnM+ICAgIDxnIGlkPSJQYWdlLTEiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSIjOTk5IiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHNrZXRjaDp0eXBlPSJNU1BhZ2UiPiAgICAgICAgPGcgaWQ9ImthbGx5YXNfbG9nbyIgc2tldGNoOnR5cGU9Ik1TQXJ0Ym9hcmRHcm91cCIgZmlsbD0iIzk5OSI+ICAgICAgICAgICAgPHBhdGggZD0iTTM5LDc2IEMxOC41NjYsNzYgMiw1OS40MzUgMiwzOSBDMiwxOC41NjUgMTguNTY2LDIgMzksMiBDNTkuNDM1LDIgNzYsMTguNTY1IDc2LDM5IEM3Niw1OS40MzUgNTkuNDM1LDc2IDM5LDc2IEwzOSw3NiBaIE02Ni43NSwzOSBDNjYuNzUsMzUuODQxIDY2LjE5NywzMi44MTcgNjUuMjI0LDI5Ljk4NyBMNTQuMjQ1LDQxLjk3NCBDNTMuNjY5LDQyLjYxNyA1My4wMzUsNDMuMTg2IDUyLjM0NCw0My42OCBDNTEuNjUyLDQ0LjE3NSA1MC45MzIsNDQuNjA3IDUwLjE4Myw0NC45NzggQzUwLjc4OCw0NS4zOTkgNTEuMzEzLDQ1Ljg4NyA1MS43Niw0Ni40NDMgQzUyLjIwNyw0Ni45OTkgNTIuNjAzLDQ3LjYzNiA1Mi45NDksNDguMzUzIEw1OC4xNjksNTkuMDM0IEM2My40NDcsNTMuOTgyIDY2Ljc1LDQ2Ljg4MyA2Ni43NSwzOSBMNjYuNzUsMzkgWiBNNDYuOTYxLDY1LjU3NyBDNDYuNzc2LDY1LjM1NyA0Ni42MDcsNjUuMTExIDQ2LjQ2Niw2NC44MTkgTDQwLjI0Miw1MS4yMDggQzM5LjkyNSw1MC41OTEgMzkuNTg3LDUwLjE4OSAzOS4yMjcsNTAuMDAzIEMzOC44NjYsNDkuODE4IDM4LjI4Myw0OS43MjUgMzcuNDc2LDQ5LjcyNSBMMzYuMDkzLDQ5LjcyNSBMMzMuNzQ2LDY2LjIzOCBDMzUuNDQ4LDY2LjU2NSAzNy4yMDIsNjYuNzUgMzksNjYuNzUgQzQxLjc2OSw2Ni43NSA0NC40MzgsNjYuMzMyIDQ2Ljk2MSw2NS41NzcgTDQ2Ljk2MSw2NS41NzcgWiBNMTEuMjUsMzkgQzExLjI1LDQ3LjYyNCAxNS4xODQsNTUuMzI4IDIxLjM1NSw2MC40MTggTDI4LjA3NywxMy40ODkgQzE4LjE4MywxNy43MzEgMTEuMjUsMjcuNTU0IDExLjI1LDM5IEwxMS4yNSwzOSBaIE00MS42MDQsMTEuMzgyIEwzNy4xNzQsNDIuMzQ1IEwzOC4wODEsNDIuMzQ1IEMzOC44Myw0Mi4zNDUgMzkuNDM1LDQyLjI0NiAzOS44OTcsNDIuMDQ4IEM0MC4zNTcsNDEuODUxIDQwLjgxOCw0MS40OTIgNDEuMjgsNDAuOTczIEw1MC41MjksMzAuMTQ0IEM1MS4wNzYsMjkuNTAxIDUxLjY4MSwyOS4wMzEgNTIuMzQ0LDI4LjczNSBDNTMuMDA2LDI4LjQzOCA1My44MTMsMjguMjg5IDU0Ljc2NCwyOC4yODkgTDY0LjYwMywyOC4yODkgQzYwLjczMSwxOS4wNDUgNTIsMTIuMzUgNDEuNjA0LDExLjM4MiBMNDEuNjA0LDExLjM4MiBaIiBpZD0iU2hhcGUiIHNrZXRjaDp0eXBlPSJNU1NoYXBlR3JvdXAiPjwvcGF0aD4gICAgICAgIDwvZz4gICAgPC9nPjwvc3ZnPg==';

		// Drop icon if whitelabeled
		if ( has_filter('zn_theme_config') ) {
			$icon = '';
		}

		do_action('znhgtfw_register_admin_pages', $this, $icon );
		if ( true !== apply_filters( 'znhgtfw_use_register_admin_pages', true ) ) {
			return;
		}

		add_theme_page( ZNHGTFW()->getThemeName() . ' Theme', ZNHGTFW()->getThemeName() . ' Theme Dashboard', 'manage_options', 'zn-about', array( $this, 'about_screen' ) );

		// If the dependencies are not met don't show the admin pages
		if ( ! ZNHGTFW()->getComponent( 'dependency_manager' )->checkDependencies() ) {
			return;
		}

		// Add all subpages
		foreach ( $this->data[ 'theme_pages' ] as $key => $value ) {
			/* CREATE THE SUBPAGES */
			$this->theme_pages[] = add_theme_page(
				$value[ 'title' ],
				$value[ 'title' ],
				'manage_options',
				'zn_tp_' . $key,
				array( $this, 'zn_render_page' )
			);
		}
	}

	/**
	 * Replace the first menu title quick setup / update screen / dashboard
	 */
	function edit_admin_menus() {
		global $submenu;

		$menu_name = 'Dashboard';
		if ( ZNHGTFW()->getComponent('installer')->isThemeSetup() ) {
			$menu_name = 'Quick setup';
		}

		if ( current_user_can( 'manage_options' ) ) {
			if ( ! empty( $submenu[ 'zn-about' ] ) ) {
				$submenu[ 'zn-about' ][ 0 ][ 0 ] = $menu_name;
			}
		}
	}

	/**
	 * Removes all WP actions so we can have a clean page
	 * @return null
	 */
	function remove_actions() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->theme_pages ) ) {
			remove_all_actions( 'admin_notices' );
		}

		return false;
	}



	private function _formatOptions( $options ) {
		$newOptions = array();

		foreach ( $options as $key => $option ) {
			if ( isset($option['parent']) && isset($option['slug']) ) {
				$newOptions[$option['parent']][$option['slug']][] = $option;
			}
		}

		return $newOptions;
	}

	function initHtml( $current_screen ) {
		if ( in_array( $current_screen ->id, $this->theme_pages ) ) {

			// Get all options
			$options          = ZNHGTFW()->getComponent('utility')->get_theme_options();
			$formattedOptions = $this->_formatOptions( $options );

			// Check current options page
			$slug = sanitize_text_field( $_GET['page'] );
			$slug = str_replace( 'zn_tp_', '', $slug );

			// Theme's options form config
			$formConfig = array(
				'version' => ZNHGTFW()->getVersion(),
				'options' => ! empty( $formattedOptions[$slug] ) ? $formattedOptions[$slug] : array(),
				'slug'    => $slug,
				'pages'	  => ZNHGTFW()->getComponent('utility')->get_theme_options_pages(),
				'logo'    => ZNHGTFW()->getLogoUrl(),
			);
			ZNHGFW()->getComponent('html')->addForm( new ZnHgFw_Html_ThemeForm( 'theme-options', $formConfig ) );
		}
	}

	function zn_render_page() {
		//#! Disable cache if this is GoDaddy hosting
		if ( ZN_HogashDashboard::isGoDaddy() ) {
			wp_using_ext_object_cache( false );
		}

		// Will register the theme options form
		// TODO : Replace form id with actual form id from theme
		echo ZNHGFW()->getComponent('html')->renderForm( 'theme-options' );
	}

	/**
	 * Renders the admin pages
	 */
	function about_screen() {
		include dirname( __FILE__ ) . '/tmpl/header-tmpl.php';
		include dirname( __FILE__ ) . '/tmpl/content-tmpl.php';
		include dirname( __FILE__ ) . '/tmpl/footer-tmpl.php';
	}
}
return new ZnAdmin();
