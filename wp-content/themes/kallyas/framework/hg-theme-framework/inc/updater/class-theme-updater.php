<?php if ( !defined( 'ABSPATH' ) )
{
	return;
}

/**
 * This class handles the theme update functionality
 */
class ZN_ThemeUpdater
{
	private $_isAutomaticThemeUpdateEnabled = false;

	public function __construct() {

		add_filter( "pre_set_site_transient_update_themes", array( $this, "checkForUpdates" ), 800 );
		/*
		 * This filter is triggered right before the actual theme update process and will update the theme's download url correctly
		 * @since 4.11.1
		 */
		add_filter( 'upgrader_package_options', array( $this, 'update_theme_download_url' ), 999 );

		/*
		 * @since v.15.10
		 * Automatic theme update
		 * Hooks into WordPress default theme update system and let it handle this process
		 */
		add_filter( 'zn_theme_pages', array( $this, 'addPageOptions' ) );
		add_filter( 'zn_theme_options', array( $this, 'addThemeOptions' ) );

		//#! Setup vars
		$this->_isAutomaticThemeUpdateEnabled = ( 'yes' == zget_option( 'theme_auto_update', 'advanced_options', false, 'no' ) );

	}


	/**
	 * Retrieve and update the theme's download url
	 * @see init()
	 * @see add_filter( 'upgrader_package_options', array( get_class(), 'update_theme_download_url' ), 999 );
	 * @param array $options
	 * @return mixed
	 */
	public function update_theme_download_url( $options = array() ) {
		if( ! is_admin() || ! ZN_HogashDashboard::isConnected() )
		{
			return $options;
		}

		//#! Check $options['package'] for our custom URL
		$packageURL = (isset($options['package']) && !empty($options['package']) ? $options['package'] : null);

		if( empty($packageURL)){
			return $options;
		}

		//#! custom query vars: hogash_deferred_download, item_id
		if ( false !== strrpos( $packageURL, 'hogash_deferred_download' ) && false !== strrpos( $packageURL, 'item_id' ) ) {
			parse_str( parse_url( $packageURL, PHP_URL_QUERY ), $vars );
			if ( $vars['item_id'] ) {
				$data = ZN_HogashDashboard::getThemeDownloadUrl();
				if( is_array($data) && isset($data['url']) && ! empty($data['url']) ) {
					$options[ 'package' ] = $data['url'];
				}
			}
		}
		return $options;
	}

	/**
	 * Check to see if there is an update available for the theme and inject this info in WordPress' updates list
	 * @param array $updatesAvailable
	 * @return mixed
	 */
	public function checkForUpdates( $updatesAvailable )
	{
		//#! Get the theme info from Dashboard
		$dashThemeInfo = ZN_HogashDashboard::getThemeInfo();

		if ( empty( $dashThemeInfo ) || !isset( $dashThemeInfo[ 'url' ] ) )
		{
			return $updatesAvailable;
		}
		if( !isset( $dashThemeInfo[ 'new_version' ] ) || empty($dashThemeInfo[ 'new_version' ])){
			return $updatesAvailable;
		}

		if( !isset( $dashThemeInfo[ 'package' ] ) ){
			return $updatesAvailable;
		}
		if( !isset( $dashThemeInfo[ 'theme_id' ] ) || empty($dashThemeInfo[ 'theme_id' ]) ){
			return $updatesAvailable;
		}
		//#! Check if the theme needs an update
		if ( !version_compare( ZNHGTFW()->getVersion(), $dashThemeInfo[ 'new_version' ], '<' ) )
		{
			return $updatesAvailable;
		}

		/*
		 * @since v4.15.10
		 * @Automatic theme update
		 */
		if( $this->_isAutomaticThemeUpdateEnabled && ZNHGTFW()->isRequest('cron') ) {
			$info = ZN_HogashDashboard::getThemeDownloadUrl();
			if( ! empty($info) && isset($info['url']) && ! empty($info['url'])){
				$dashThemeInfo['package'] = $info['url'];
			}
			//#! for WP theme update
			$dashThemeInfo['theme'] = ZNHGTFW()->getThemeId();
		}
		/*
		 * Add our custom query arg and provide a custom URL, not the actual download URL
		 * This URL will be updated with the correct theme download URL by the "upgrader_package_options" filter.
		 * This will prevent clients from reaching the requests limit set by the Envato API for this action.
		 * @see init()
		 * @since 4.11.2
		 */
		else {
			$dashThemeInfo['package'] = add_query_arg( array(
				'hogash_deferred_download' => true,
				'item_id' => $dashThemeInfo[ 'theme_id' ],
			), ZNHGTFW()->getComponent('utility')->get_options_page_url());
		}

		//#! Update and return the list
		$updatesAvailable->response[ ZNHGTFW()->getParentThemeDir() ] = $dashThemeInfo;
		return $updatesAvailable;
	}

	/**
	 * Load the options slugs and hook to Kallyas theme options
	 *
	 * @param array $admin_pages Existing slugs
	 *
	 * @return array
	 */
	public function addPageOptions( $admin_pages )
	{
		$_page_options = array(
			'slug' => 'theme_auto_update',
			'title' => esc_html__( 'Theme Auto Update', 'zn_framework' ),
		);
		if ( isset( $admin_pages[ 'advanced_options' ][ 'submenus' ] ) ) {
			$admin_pages[ 'advanced_options' ][ 'submenus' ][] = $_page_options;
		}
		return $admin_pages;
	}

	/**
	 * Load the options that are displayed in Theme options > General options > Advanced options
	 *
	 * @param array $admin_options Existing options
	 *
	 * @return array The updated theme options
	 */
	public function addThemeOptions( $admin_options = array() )
	{
		include( ZNHGTFW()->getFwPath( 'inc/updater/inc/options.php' ) );
		return $admin_options;
	}
}

return new ZN_ThemeUpdater();
