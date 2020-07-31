<?php if(! defined('ABSPATH')) {return; }

/**
 * This class provides utility methods to use when importing a demo
 */
class ZN_DemoImportHelper
{
	const WP_SETTINGS_FILE = 'wp_settings.json';
	const WIDGETS_FILE = 'widgets.json';
	const THEME_OPTIONS_FILE = 'theme_options.json';
	const IMAGES_ARCHIVE = 'images.zip';
	const IMAGES_MAP_FILE = 'images.json';
	const CUSTOM_FONTS_FILE = 'custom_fonts.zip';
	const WP_OPTIONS_FILE = 'global_options.json';
	const DEMO_CONFIG_FILE = 'demo_config.json';
	const WP_CONTENT_FILE = 'content.json';
	const WP_TERMS_FILE = 'terms.json';
	const WP_TERMS_META_FILE = 'terms_meta.json';
	const WP_MENUS_FILE = 'menus.json';
	const PLUGINS_FILE = 'plugins.json';
	const REV_SLIDER_DIR = 'revslider';


	// Install process state
	const STATE_NONE = 0;
	const STATE_WAIT = 1;
	const STATE_DONE = 2;
	const STATE_FAIL = 3;
	const STATE_ABORT = 4;
	const STATE_COMPLETE = 5;
	const STATE_UNKNOWN = 6;


	// Stores the temporary data we use throughout the import process
	const TEMP_DATA_TRANS = 'kallyas_demo_import_temp_data';
	const TEMP_POST_ID_TRANS = 'kallyas_demo_import_post_id';
	const TEMP_MENU_ID_TRANS = 'kallyas_demo_import_menu_id';
	const TEMP_IMAGE_HASH_TRANS = 'kallyas_demo_import_image_hash';
	// Whether or not the terms were processed
	const TEMP_TERMS_DONE_TRANS = 'kallyas_demo_import_terms_processed';
	const TEMP_TERMS_META_DONE_TRANS = 'kallyas_demo_import_terms_meta_processed';

	public static $attachmentIDs = array();

	/**
	 * Special PB elements
	 * @var array
	 */
	public static $_pbe = array(
		'TH_BlogArchive', 'TH_CustomMenu', 'TH_LatestPosts', 'TH_LatestPosts2', 'TH_LatestPosts3', 'TH_LatestPosts4',
		'TH_PortfolioArchive', 'TH_RecentWork', 'TH_RecentWork2', 'TH_RecentWork3', 'TH_ShopLimitedOffers',
		'TH_ShopProductsPresentation', 'ZnPbCustomTempalte'
	);
	/**
	 * Special widgets
	 * @var array
	 */
	private static $_pbw = array(
		'WP_Widget_Pages', 'WP_Nav_Menu_Widget',
	);

	public static $demoConfig = array();
	public static $imagesMap = array();

//<editor-fold desc="::: GETTERS">


	/**
	 * Retrieve the demo config options from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getDemoConfig( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::DEMO_CONFIG_FILE;
		$data = null;
		if( $fs->is_file($filePath)){
			if( $fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Demo config file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the images map from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getImagesMap( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::IMAGES_MAP_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Images map file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the global options from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getGlobalOptions( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::WP_OPTIONS_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Global options file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the WP Settings from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getWpSettings( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::WP_SETTINGS_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('WP Settings file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the theme options from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getThemeOptions( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::THEME_OPTIONS_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Theme options file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the menus from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getMenus( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::WP_MENUS_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Menus file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the plugins options from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getPluginsOptions( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::PLUGINS_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Plugins file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the global options from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getWidgets( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::WIDGETS_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Widgets file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the demo content from file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getContent( $demoDirPath )
	{
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::WP_CONTENT_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Global options file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the terms from the demo export file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getTerms( $demoDirPath ){
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::WP_TERMS_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Terms file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve the terms metadata from the demo export file
	 * @param string $demoDirPath
	 * @return array|bool|mixed|null|object
	 */
	public static function getTermsMeta( $demoDirPath ){
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$filePath = trailingslashit($demoDirPath).self::WP_TERMS_META_FILE;
		$data = null;
		if($fs->is_file($filePath)){
			if($fs->is_readable($filePath)) {
				$data = json_decode( trim( $fs->get_contents( $filePath ) ), true );
				if ( empty( $data ) ) {
					// maybe no data
					return null;
				}
			}
			else {
				self::log(sprintf (
					esc_html__('Terms meta file is not accessible (%s). ', 'zn_framework'),
					$filePath
				));
				return false;
			}
		}
		return $data;
	}

	/**
	 * Retrieve all the revolution sliders from the export directory
	 * @param string $demoDirPath
	 * @return bool
	 */
	public static function hasRevSliders( $demoDirPath )
	{
		$revSlidersDirPath = trailingslashit($demoDirPath).self::REV_SLIDER_DIR;
		if( is_dir($revSlidersDirPath) )
		{
			$files = glob($revSlidersDirPath.'/*.zip');
			if( empty($files)){
				return false;
			}
			return true;
		}
		return false;
	}

//</editor-fold desc="::: GETTERS">

//<editor-fold desc="::: UTILITY FUNCTIONS">

	/**
	 * Log an error to the log file
	 *
	 * @param string $error
	 * @param null   $className
	 */
	public static function log( $error = '', $className = null )
	{
		if(! empty($error))
		{
			if (empty($className)){
				$className = get_class();
			}
			$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
			$fs->put_contents(ABSPATH.'/import.log', '['.$className.']: '.$error.PHP_EOL, FILE_APPEND);
		}
	}


	/**
	 * Clears the import log file
	 * @return void
	 */
	public static function clearLogFile(){
		$fs = ZNHGTFW()->getComponent( 'utility' )->getFileSystem();
		$fs->delete( trailingslashit( ABSPATH ) . 'import.log' );
	}
//</editor-fold desc="::: UTILITY FUNCTIONS">


//<editor-fold desc="::: IMPORT FUNCTIONS">

	/**
	 * Install the global options
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	public static function importGlobalOptions( $demoDirPath )
	{

		$options = self::getGlobalOptions( $demoDirPath );
		if( empty($options) ){
			return true;
		}

		$refreshPermalinks = false;

		// Install the global options
		foreach( $options as $optionName => $optionValue ){
			if(!$refreshPermalinks && (false !== strpos($optionName, 'permalink'))){
				$refreshPermalinks = true;
			}
			update_option( $optionName, $optionValue );
		}


		// Update WP Reading Settings
		$wpSettings = self::getWpSettings( $demoDirPath );
		$tempData = get_transient( self:: TEMP_DATA_TRANS );
		if(! empty($wpSettings) && (isset($tempData['posts']) && !empty($tempData['posts'])))
		{
			// Set default settings - they will be updated later on
			//@see __cleanup
			if(isset($wpSettings['show_on_front'])){
				update_option('show_on_front', $wpSettings['show_on_front']);
			}
			if(isset($wpSettings['page_on_front'])){
				update_option('page_on_front', $wpSettings['page_on_front']);
			}
			if(isset($wpSettings['page_for_posts'])){
				update_option('page_for_posts', $wpSettings['page_for_posts']);
			}
		}

		if($refreshPermalinks){
			flush_rewrite_rules();
		}

		return true;
	}

	/**
	 * Install widgets
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	public static function importWidgets( $demoDirPath )
	{

		$_widgets = self::getWidgets( $demoDirPath );
		if( empty( $_widgets )){
			return true;
		}

		global $wp_registered_sidebars;

		$available_widgets = self::__getAvailableWidgets();

		// Get all existing widget instances
		$widget_instances = array();
		if($available_widgets) {
			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
			}
		}

		// Loop and import widgets into sidebars
		foreach ( $_widgets as $sidebar_id => $widgets )
		{
			// Check if sidebar is available on this site, otherwise add widgets to an inactive state
			$use_sidebar_id = (isset( $wp_registered_sidebars[$sidebar_id] ) ? $sidebar_id : 'wp_inactive_widgets');

			// Loop through widgets
			foreach ( $widgets as $widget_instance_id => $widget )
			{
				$fail = false;

				// Get id_base (remove -# from end) and instance ID number
				$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				// Does site support this widget?
				if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
					$fail = true;
				}

				// Does widget with identical settings already exist in same sidebar?
				if ( ! $fail && isset( $widget_instances[$id_base] ) )
				{
					// Get existing widgets in this sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					// check Inactive if that's where will go
					$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array();

					// Loop widgets with ID base
					$single_widget_instances = array();
					if(isset($widget_instances[$id_base]) && !empty($widget_instances[$id_base])){
						$single_widget_instances = $widget_instances[$id_base];
					}
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						// Is widget in same sidebar and has identical settings?
						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {
							$fail = true;
							break;
						}
					}
				}

				// No failure
				if ( ! $fail )
				{
					// Add widget instance
					$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
					$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
					$single_widget_instances[] = (array) $widget; // add it

					// Get the key it was given
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					// If key is 0, make it 1
					// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number = 1;
						$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					// Move _multi-widget to end of array for uniformity
					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					// Update option with new widget
					update_option( 'widget_' . $id_base, $single_widget_instances );

					// Assign widget instance to sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
					$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
					$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
					update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data
				}
			}
		}
		return true;
	}

	/**
	 * Internal method to retrieve all widgets
	 * @return array
	 */
	private static function __getAvailableWidgets()
	{
		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;
		$available_widgets = array();
		foreach ( $widget_controls as $widget ) {
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) {
				$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
				$available_widgets[$widget['id_base']]['name'] = $widget['name'];
			}
		}
		return $available_widgets;
	}

	/**
	 * Install custom icons
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	public static function importCustomIcons( $demoDirPath )
	{

		$fontsDir = $demoDirPath.'/custom_icons/';

		// Check to see whether or not there are any fonts to import
		if(! ZN_ThemeDemoImporter::canAccessDir($fontsDir)) {
			return true;
		}

		$files = scandir($fontsDir);
		if($files)
		{
			// Try to create the fonts directory
			@wp_mkdir_p( ZN_ThemeDemoImporter::getUploadsDirPath().'zn_fonts' );

			// Set the list of special directories to ignore
			$ignore = array('.', '..');
			foreach($files as $entry) {
				if(in_array($entry, $ignore)){
					continue;
				}
				// Import all fonts found

				$fontArchivePath  = trailingslashit($fontsDir) . $entry;
				if(is_file($fontArchivePath) && is_readable($fontArchivePath)) {
					$fontArchiveTitle = basename( $entry, '.zip' );
					ZNHGFW()->getComponent('icon_manager')->install_icon_package( $fontArchivePath );
				}
				else {
					self::log('[Error] Archive: ('.$fontArchivePath.') was either not found or not readable.');
				}

			}
		}
		return true;
	}

	/**
	 * Install custom fonts
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	public static function importCustomFonts( $demoDirPath )
	{

		// Check to see whether or not we have the custom fonts archive
		$archivePath = trailingslashit( $demoDirPath ) . self::CUSTOM_FONTS_FILE;
		$archivePath = str_replace("\\","/",$archivePath);
		if(! is_file($archivePath))
		{
			// No custom fonts
			return true;
		}

		// Extract and copy fonts to uploads dir
		$uploadsDir = ZN_ThemeDemoImporter::getUploadsDirPath();
		WP_Filesystem();
		$extracted = unzip_file( $archivePath ,$uploadsDir );

		if(! $extracted){
			self::log( sprintf (
				esc_html__('Error extracting the custom fonts from (%s). ', 'zn_framework'), $archivePath )
			);
			return false;
		}

		return true;
	}

	/**
	 * Install theme options
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	public static function importThemeOptions( $demoDirPath )
	{

		$themeOptions = self::getThemeOptions( $demoDirPath );
		// No theme options to import
		if(empty($themeOptions)){
			return true;
		}

		// Get mapped images so we can replace the placeholders
		$images = self::getImagesMap( $demoDirPath );

		// No images - save as is
		if(empty($images))
		{
			// If we have data, import theme options
			if(! empty($themeOptions)){
				// get the name of the option
				$optionName = ZNHGTFW()->getThemeDbId();
				update_option( $optionName, $themeOptions);
			}
			return true;
		}

		// Get demo config so can retrieve the placeholders in use
		$demoConfig = self::getDemoConfig( $demoDirPath );
		$configPH = (isset($demoConfig['placeholders']) ? $demoConfig['placeholders'] : array());

		// Get the transient that stores the imported images
		$tempData = get_transient( self:: TEMP_DATA_TRANS );

		// Loop through all theme options and replace any occurrence of the placeholders
		foreach( $images as $imgPlaceholder => $imageEntry)
		{
			if(isset($tempData['images'][ $imageEntry['path'] ]))
			{
				$attachmentID = $tempData['images'][ $imageEntry['path'] ];
				$attLinkPage  = get_attachment_link( $attachmentID );
				$imageLink    = ZN_ThemeDemoImporter::getUploadsDirUrl() . $imageEntry['path'];
				$imageExt     = substr( $imageEntry['path'], strrpos( $imageEntry['path'], '.' ) );
				$imageLinkBaseName = substr( $imageLink, 0, strrpos( $imageLink, '.' ) );

				$searches = array(
					$imgPlaceholder . $configPH['ATT_LINK_PAGE'],
					$imgPlaceholder . $configPH['ATT_IMAGE_ID'],
					$imgPlaceholder . $configPH['IMAGE_LINK'],
					$imgPlaceholder . $configPH['IMAGE_EXT'],
					$imgPlaceholder . $configPH['IMAGE_LINK_BASE_NAME']
				);

				$replacements = array(
					$attLinkPage,
					$attachmentID,
					$imageLink,
					$imageExt,
					$imageLinkBaseName
				);
				$themeOptions = self::__replaceInArray( $themeOptions, $searches, $replacements );
			}
		}

		// Update special cases
		if(isset($themeOptions['general_options']['hidden_panel_pb_template'])){
			$oldPID = intval($themeOptions['general_options']['hidden_panel_pb_template']);
			if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
				$themeOptions['general_options']['hidden_panel_pb_template'] = $tempData['posts'][$oldPID];
			}
		}
		if(isset($themeOptions['coming_soon_options']['cs_page_template'])){
			$oldPID = intval($themeOptions['coming_soon_options']['cs_page_template']);
			if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
				$themeOptions['coming_soon_options']['cs_page_template'] = $tempData['posts'][$oldPID];
			}
		}
		if(isset($themeOptions['zn_404_options']['404_smart_area'])){
			$oldPID = intval($themeOptions['zn_404_options']['404_smart_area']);
			if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
				$themeOptions['zn_404_options']['404_smart_area'] = $tempData['posts'][$oldPID];
			}
		}

		// Update smart areas if any
		if( isset( $themeOptions['pb_layouts'] ) && !empty($themeOptions['pb_layouts']))
		{
			// Smart areas
			if(isset($themeOptions['pb_layouts']))
			{
				if(isset($themeOptions['pb_layouts']['pbtmpl_general']['subheader_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_general']['subheader_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_general']['subheader_template'] = $tempData['posts'][$oldPID];
					}
				}
				if(isset($themeOptions['pb_layouts']['pbtmpl_general']['footer_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_general']['footer_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_general']['footer_template'] = $tempData['posts'][$oldPID];
					}
				}

				if(isset($themeOptions['pb_layouts']['pbtmpl_post']['subheader_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_post']['subheader_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_post']['subheader_template'] = $tempData['posts'][$oldPID];
					}
				}
				if(isset($themeOptions['pb_layouts']['pbtmpl_post']['footer_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_post']['footer_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_post']['footer_template'] = $tempData['posts'][$oldPID];
					}
				}

				if(isset($themeOptions['pb_layouts']['pbtmpl_portfolio']['subheader_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_portfolio']['subheader_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_portfolio']['subheader_template'] = $tempData['posts'][$oldPID];
					}
				}
				if(isset($themeOptions['pb_layouts']['pbtmpl_portfolio']['footer_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_portfolio']['footer_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_portfolio']['footer_template'] = $tempData['posts'][$oldPID];
					}
				}

				if(isset($themeOptions['pb_layouts']['pbtmpl_product']['subheader_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_product']['subheader_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_product']['subheader_template'] = $tempData['posts'][$oldPID];
					}
				}
				if(isset($themeOptions['pb_layouts']['pbtmpl_product']['footer_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_product']['footer_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_product']['footer_template'] = $tempData['posts'][$oldPID];
					}
				}

				if(isset($themeOptions['pb_layouts']['pbtmpl_category']['subheader_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_category']['subheader_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_category']['subheader_template'] = $tempData['posts'][$oldPID];
					}
				}
				if(isset($themeOptions['pb_layouts']['pbtmpl_category']['footer_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_category']['footer_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_category']['footer_template'] = $tempData['posts'][$oldPID];
					}
				}

				if(isset($themeOptions['pb_layouts']['pbtmpl_product_cat']['subheader_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_product_cat']['subheader_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_product_cat']['subheader_template'] = $tempData['posts'][$oldPID];
					}
				}
				if(isset($themeOptions['pb_layouts']['pbtmpl_product_cat']['footer_template'])){
					$oldPID = intval($themeOptions['pb_layouts']['pbtmpl_product_cat']['footer_template']);
					if(! empty($oldPID) && isset($tempData['posts'][$oldPID])){
						$themeOptions['pb_layouts']['pbtmpl_product_cat']['footer_template'] = $tempData['posts'][$oldPID];
					}
				}
			}
		}


		// Check for Custom Fonts Options
		if(isset($themeOptions['google_font_options']) &&
		   isset($themeOptions['google_font_options']['zn_custom_fonts']) &&
			! empty($themeOptions['google_font_options']['zn_custom_fonts']))
		{
			// Replace uploads dir path
			$uploadsDirPath = trailingslashit( ZN_ThemeDemoImporter::getUploadsDirUrl() );
			foreach( $themeOptions['google_font_options']['zn_custom_fonts'] as &$entry )
			{
				if(isset($entry['cf_woff']) && !empty($entry['cf_woff'])) {
					$entry['cf_woff'] = str_replace($demoConfig['UPLOADS_DIR_URI'], $uploadsDirPath, $entry['cf_woff']);
				}
				if(isset($entry['cf_ttf']) && !empty($entry['cf_ttf'])) {
					$entry['cf_ttf'] = str_replace($demoConfig['UPLOADS_DIR_URI'], $uploadsDirPath, $entry['cf_ttf']);
				}
				if(isset($entry['cf_svg']) && !empty($entry['cf_svg'])) {
					$entry['cf_svg'] = str_replace($demoConfig['UPLOADS_DIR_URI'], $uploadsDirPath, $entry['cf_svg']);
				}
				if(isset($entry['cf_eot']) && !empty($entry['cf_eot'])) {
					$entry['cf_eot'] = str_replace($demoConfig['UPLOADS_DIR_URI'], $uploadsDirPath, $entry['cf_eot']);
				}
			}
		}

		// Update theme options
		$optionName = ZNHGTFW()->getThemeDbId();
		update_option( $optionName, $themeOptions);

		// Update cache
		set_transient( self::TEMP_DATA_TRANS, $tempData);
		return true;
	}


	/**
	 * Install demo content
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	public static function importContent( $demoDirPath )
	{
		//#! Import terms here
		self::__importTerms( $demoDirPath );
		//#! Import terms metadata here
		self::__importTermsMeta( $demoDirPath );

		$posts = self::getContent( $demoDirPath );

		// No content to import
		if(empty($posts)){
			return true;
		}

		//
		$processedIndex = get_transient( self:: TEMP_POST_ID_TRANS);
		if(empty($processedIndex)){
			$processedIndex = 0;
		}

		// Get mapped images so we can build the search placeholders
		$images = self::getImagesMap( $demoDirPath );
		$replaceImages = (! empty($images));
		$attachedImages = array();
		$uploadsDir = trailingslashit(ZN_ThemeDemoImporter::getUploadsDirPath());

		// Get temp data
		$tempData = get_transient( self:: TEMP_DATA_TRANS );

		if(empty($tempData)){
			$tempData = array();
		}

		// Holds the list of processed posts
		if(! isset($tempData['posts'])){
			$tempData['posts'] = array();
		}
		// Holds the list of posts that will need update in the case they use post_parent
		if(! isset($tempData['posts_review'])){
			$tempData['posts_review'] = array();
		}

		// Images
		if(! isset($tempData['images'])){
			$tempData['images'] = array();
		}

		// If the demo contains images
		if( $replaceImages )
		{
			// Insert and cache attachments
			foreach( $images as $imgPlaceholder => $entry ) {
				if ( isset( $entry['path'] ) )
				{
					// Check to see whether or not the image has been imported already
					if( ! isset( $tempData['images'][ $entry['path'] ] ))
					{
						$attID = self::__importImage( $uploadsDir.$entry['path'], $entry['path'], $entry['id'] );
						$tempData['images'][ $entry['path'] ] = $attID;
					}
					$attachedImages[$imgPlaceholder] = $tempData['images'][ $entry['path'] ];
				}
			}

			// Get placeholders
			$demoConfig = self::getDemoConfig( $demoDirPath );
			$configPH   = ( isset( $demoConfig['placeholders'] ) ? $demoConfig['placeholders'] : array() );

			// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
			if(! function_exists('getimagesize')) {
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			// In case there are custom links to pages/posts/etc
			$crtSteUrl = esc_url(home_url());

			// Loop through each post
			// [*] insert post
			// [*] parse post content and replace all occurrences of $search strings + special strings
			// [*] update post content
			// [*] parse and insert meta
			// [*] parse and insert taxonomies
			// [*] set featured image if any was set
			$crtIndex = 0;
			foreach( $posts as $oldPostID => $postInfo )
			{
				// [*] Get post data
				$post_db = ( isset( $postInfo['db'] ) && ! empty( $postInfo['db'] ) ? $postInfo['db'] : null );
				if( empty($post_db) || !is_array($post_db) ){
					// no data here
					$crtIndex++;
					continue;
				}

				// Get post meta
				$post_meta = ( isset( $postInfo['pm'] ) && ! empty( $postInfo['pm'] ) ? $postInfo['pm'] : null );
				$hasPostMeta = ( ! empty($post_meta) && is_array($post_meta) );

				//#! Check if the post has a parent
				$hasParentID = (isset($post_db['post_parent']) && ! empty($post_db['post_parent']));
				$currentPostParentUpdated = false;
				if( $hasParentID )
				{
					//#! Update the new parent id if it has been processed
					if( isset($tempData['posts'][ $post_db['post_parent'] ]) ){
						$post_db['post_parent'] = (int)$tempData['posts'][ $post_db['post_parent'] ];
						$currentPostParentUpdated = true;
					}
				}

				//#! 1. Check to see whether or not we've already processed this post
				if(isset($tempData['posts'][$oldPostID])){
					if(empty($processedIndex)){
						$processedIndex = $crtIndex;
						set_transient(self::TEMP_POST_ID_TRANS, $processedIndex);
					}
					$crtIndex++;
					continue;
				}

				//#! 2. Check to see whether or not this post has been already processed
				if($processedIndex && $crtIndex <= $processedIndex && isset($tempData['posts'][$oldPostID])){
					$crtIndex++;
					$processedIndex = $crtIndex;
					set_transient(self::TEMP_POST_ID_TRANS, $processedIndex);
					continue;
				}

				// Check to see whether or not the post exists in the database
				$newPostID = 0;
				if( isset( $post_db['post_slug'] ) ) {
					$newPostID = post_exists($post_db['post_slug']);
				}
				if( ! empty($newPostID)){
					// Update cache and continue
					$tempData['posts'][$oldPostID] = $newPostID;

					//#! If the post has a parent but that post_parent hasn't been processed yet
					if( $hasParentID )
					{
						$tempData['posts_review'][$newPostID] = (int)$post_db['post_parent'];
					}

					set_transient( self::TEMP_DATA_TRANS, $tempData );
					$crtIndex++;
					$processedIndex = $crtIndex;
					set_transient(self::TEMP_POST_ID_TRANS, $processedIndex);
					continue;
				}

				// [*] Else, Insert the post as is
				$newPostID = self::__insertPost( $post_db, $configPH );

				//#! If the post has a parent but that post_parent hasn't been processed yet
				if( $hasParentID && !$currentPostParentUpdated)
				{
					$tempData['posts_review'][$newPostID] = (int)$post_db['post_parent'];
				}

				// Post thumbnail ( see below )
				$post_th = ( isset( $postInfo['th'] ) && ! empty( $postInfo['th'] ) ? $postInfo['th'] : null );
				$thSet = false;

				// Update the post author
				$post_db['post_author'] = get_current_user_id();

				// Get the post content (unaltered)
				$postContent = $post_db['post_content'];
				$postContentUpdated = $postContent;

				// Loop through each image and replace every occurrence of the placeholders in:
				// [*] post content
				// [*] post meta
				foreach( $attachedImages as $imgPlaceholder => $attachmentID )
				{
					$imageEntry = $images[$imgPlaceholder];

					// Get image details
					$attLinkPage  = self::getAttachmentLink( $attachmentID );
					$imageLink    = ZN_ThemeDemoImporter::getUploadsDirUrl() . $imageEntry['path'];
					$imageExt     = substr( $imageEntry['path'], strrpos( $imageEntry['path'], '.' ) );
					$imageLinkBaseName = substr( $imageLink, 0, strrpos( $imageLink, '.' ) );


					// Set post thumbnail
					if( ! $thSet && ! empty($post_th) && !empty($attachmentID))
					{
						if( isset($attachedImages[$imgPlaceholder]) && $images[$imgPlaceholder]['id'] == $post_th )
						{
							set_post_thumbnail( $newPostID, $attachmentID );
							$thSet = true;
						}
					}

					// Post Content
					if(! empty($postContentUpdated))
					{
						// Replace all occurrences of the placeholders
						if( false !== ($pos = strpos($postContentUpdated, $imgPlaceholder . $configPH['ATT_LINK_PAGE']))){
							$postContentUpdated = str_replace( $imgPlaceholder . $configPH['ATT_LINK_PAGE'], $attLinkPage, $postContentUpdated);
						}
						if( false !== ($pos = strpos($postContentUpdated, $imgPlaceholder . $configPH['ATT_IMAGE_ID']))){
							$postContentUpdated = str_replace( $imgPlaceholder . $configPH['ATT_IMAGE_ID'], $attachmentID, $postContentUpdated);
						}
						if( false !== ($pos = strpos($postContentUpdated, $imgPlaceholder . $configPH['IMAGE_LINK']))){
							$postContentUpdated = str_replace( $imgPlaceholder . $configPH['IMAGE_LINK'], $imageLink, $postContentUpdated);
						}
						if( false !== ($pos = strpos($postContentUpdated, $imgPlaceholder . $configPH['IMAGE_EXT']))){
							$postContentUpdated = str_replace( $imgPlaceholder . $configPH['IMAGE_EXT'], $imageExt, $postContentUpdated);
						}
						if( false !== ($pos = strpos($postContentUpdated, $imgPlaceholder . $configPH['IMAGE_LINK_BASE_NAME']))){
							$postContentUpdated = str_replace( $imgPlaceholder . $configPH['IMAGE_LINK_BASE_NAME'], $imageLinkBaseName, $postContentUpdated);
						}
					}
				}

				// POST META PROCESSING IMPROVED
				if( $hasPostMeta ){
					foreach( $post_meta as $key => &$value ) {

						if ( '_edit_last' == $key || '_edit_lock' == $key ) {
							continue;
						}

						foreach( $attachedImages as $imgPlaceholder => $attachmentID ) {

							$imageEntry = $images[$imgPlaceholder];

							// Get image details
							$attLinkPage  = self::getAttachmentLink( $attachmentID );
							$imageLink    = ZN_ThemeDemoImporter::getUploadsDirUrl() . $imageEntry['path'];

							// Set the search query
							$find_image_link = $imgPlaceholder . $configPH['IMAGE_LINK'];
							$find_image_link_page = $imgPlaceholder . $configPH['ATT_LINK_PAGE'];

							$searches = array(
								$find_image_link,
								$find_image_link_page,
								$configPH['SITE_URL']
							);
							$replacements = array(
								$imageLink,
								$attLinkPage,
								$crtSteUrl
							);

							$value = self::__replaceInArray( $value, $searches, $replacements );
						}

						// UPDATE ONLY ONCE PER POST META
						add_post_meta( $newPostID, $key, $value );
					}
				}
				// End foreach $images

				// Update site url
				$postContentUpdated = str_replace( $configPH['SITE_URL'], $crtSteUrl, $postContentUpdated);

				// Update the post content if modified
				if( $postContent != $postContentUpdated )
				{
					// Update post content
					$postUpdate = wp_update_post( array(
						'ID' => $newPostID,
						'post_content' => $postContentUpdated,
					), true );
					if ( is_wp_error( $postUpdate ) ) {
						self::log( '[' . $newPostID . '] Error updating post content: ' . var_export($postUpdate->get_error_messages(),1 ) );
					}
				}

				$tempData['posts'][$oldPostID] = $newPostID;
				set_transient( self::TEMP_DATA_TRANS, $tempData );

				$crtIndex++;
				$processedIndex = $crtIndex;
				set_transient(self::TEMP_POST_ID_TRANS, $processedIndex);
				set_transient( self::TEMP_DATA_TRANS, $tempData );

				// [*] Import taxonomies
				$post_tx = ( isset( $postInfo['tx'] ) && ! empty( $postInfo['tx'] ) ? $postInfo['tx'] : null );
				if( ! empty($post_tx))
				{
					if(! isset($tempData['terms'])){
						$tempData['terms'] = array();
					}

					foreach( $post_tx as $taxonomy => $terms )
					{
						if( !taxonomy_exists($taxonomy) || empty($terms)){ continue; }

						$termIds = array();
						foreach( $terms as &$term )
						{
							// Map old id => new id
							$oldTermID = intval($term['term_id']);

							// Check to see whether or not the term exists in the database
							$termID = term_exists( $term['name'], $taxonomy );
							if(empty($termID))
							{
								// Set the active term id so it can be used by tryToKeepTermIdOnImport
								ZN_ThemeDemoImporter::$tempTermId = $oldTermID;
								$termID = wp_insert_term( $term['name'], $taxonomy );
								ZN_ThemeDemoImporter::$tempTermId = false;
								if ( is_wp_error( $termID ) ) {
									self::log( 'Could not add term['.$term['name'].'] TAX['.$taxonomy.']: ' . var_export( $termID->get_error_messages(), 1 ) );
									$termID = 0;
								}
							}

							if(is_array($termID) && isset($termID['term_id'])) {
								$termID = $termID['term_id'];
							}
							else { $termID = 0; }

							// Add the term to the internal list
							if(! empty($termID)) {
								array_push( $termIds, (int) $termID );
								$tempData['terms'][$oldTermID] = $termID;
							}
						}

						// Set the terms for this post
						if( ! empty($termIds)){
							$termIds = array_map('intval', $termIds);
							$result = wp_set_object_terms( $newPostID, $termIds, $taxonomy );
							if(is_wp_error($result)){
								self::log('Could not set post terms: '.var_export($result->get_error_messages(),1));
							}
						}
					}
				}

				// [**] New structure for post taxonomies
				$post_tx = ( isset( $postInfo['taxonomies'][$oldPostID] ) && ! empty( $postInfo['taxonomies'][$oldPostID] ) ? $postInfo['taxonomies'][$oldPostID] : null );
				if( ! empty($post_tx) ) {
					$termIds = array();
					foreach( $post_tx as $_taxonomy => $oldTermsIDS ){
						foreach($oldTermsIDS as $_oldTermID) {
							if ( isset( $tempData['terms'][$_oldTermID] ) ) {
								array_push( $termIds, $tempData['terms'][$_oldTermID] );
							}
						}
						if(! empty($termIds)) {
							wp_set_object_terms( $newPostID, $termIds, $_taxonomy );
						}
					}
				}
				// End processing post taxonomies
			}
			// END foreach $posts
		}

		// Update cache
		if(! empty($tempData['posts'])) {
			set_transient(self::TEMP_DATA_TRANS, $tempData);
		}
		return true;
	}


	/**
	 * Will add a dummy term to the database so that the term_id gets a higher starting value
	 */
	public static function addDummyTerm(){
		ZN_ThemeDemoImporter::$tempTermId = 500;
		add_filter( 'wp_insert_term_data', array( 'ZN_ThemeDemoImporter', 'tryToKeepTermIdOnImport' ), 10, 3 );
		$result = wp_insert_term( 'dummyTerm', 'category' );
		if( ! is_wp_error( $result ) ){
			if( ! empty( $result['term_id'] ) ){
				ZN_ThemeDemoImporter::$dummyTermId = $result['term_id'];
			}
 		}
		ZN_ThemeDemoImporter::$tempTermId = false;
	}

	public static function removeDummyterm(){
		if( ! empty( ZN_ThemeDemoImporter::$dummyTermId ) ){
			wp_delete_term( ZN_ThemeDemoImporter::$dummyTermId, 'category' );
		}
	}

	public static function getAttachmentLink( $attachmentID ){
		if( empty( self::$attachmentIDs[$attachmentID] ) ){
			self::$attachmentIDs[$attachmentID] = get_attachment_link( $attachmentID );
		}
		return self::$attachmentIDs[$attachmentID];
	}

	/**
	 * Install menus
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	public static function importMenus( $demoDirPath )
	{
		$menuData = self::getMenus( $demoDirPath );
		if(empty($menuData)){
			// no menus
			return true;
		}

		// Get the last id processed
		$processedMenuItemsIDS = get_transient(self::TEMP_MENU_ID_TRANS);
		if(empty($processedMenuItemsIDS)){
			$processedMenuItemsIDS = array();
		}


		// Get placeholders
		$demoConfig = self::getDemoConfig( $demoDirPath );
		$configPH   = ( isset( $demoConfig['placeholders'] ) ? $demoConfig['placeholders'] : array() );

		$themeLocations = get_option( 'theme_mods_'.get_option('stylesheet') );
		if(empty($themeLocations)){
			$themeLocations = array('nav_menu_locations' => array());
		}
		elseif( ! isset($themeLocations['nav_menu_locations'])){
			$themeLocations['nav_menu_locations'] = array();
		}
		else {
			$themeLocations = $themeLocations['nav_menu_locations'];
		}

		// Get saved temp data
		$tempData = get_transient( self::TEMP_DATA_TRANS );
		// Keep a local cache of processed posts
		$processedPosts = &$tempData['posts'];
		$processedCategories = &$tempData['terms'];

		if(! isset($tempData['menus'])){
			$tempData['menus'] = array();
		}
		if(! isset($tempData['menu_items'])){
			$tempData['menu_items'] = array();
		}
		$processedMenus = &$tempData['menus'];
		$processedMenuItems = &$tempData['menu_items'];

		$crtSiteUrl = esc_url(home_url());

		// Check for menus [registered locations]
		if(isset($menuData['locations']) && ! empty($menuData['locations']))
		{
			foreach( $menuData['locations'] as $location => $menuItemData )
			{
				// Check to see whether or not the menu exists
				$menuExists = term_exists( $menuItemData['name'], $menuItemData['taxonomy']);
				$menuTermID = 0;
				if(is_null($menuExists))
				{

					ZN_ThemeDemoImporter::$tempTermId = $menuItemData['term_id'];
					$result = wp_insert_term( $menuItemData['name'], $menuItemData['taxonomy'], array('parent' => $menuItemData['parent']));
					ZN_ThemeDemoImporter::$tempTermId = false;
					if(is_wp_error($result)){
						self::log('[MENU] Could not add term: '.$menuItemData['name'].': '.var_export($result->get_error_messages() ,1));
						return false;
					}
					else {
						if(isset($result['term_id'])){
							$menuTermID = $result['term_id'];
						}
					}
				}
				else {
					if(isset($result['term_id'])){
						$menuTermID = $menuExists['term_id'];
					}
				}

				// Update location if not there already
				if( ! empty($menuTermID)) {
					$processedMenus[$menuItemData['term_id']] = $menuTermID;
					$themeLocations[ $location ] = $menuTermID;
				}
			}
			// Save menu locations
			set_theme_mod( 'nav_menu_locations', $themeLocations );
		}

		// Check for unregistered menus [single menu items]
		if(isset($menuData['menus']) && !empty($menuData['menus']))
		{
			// Process each menu
			foreach( $menuData['menus'] as $menuName => $menuItems )
			{
				// Add menu if it doesn't exist
				$menuExists = term_exists( $menuName, 'nav_menu' );
				$menuID = 0;
				if(is_null($menuExists))
				{
					$result = wp_insert_term( $menuName, 'nav_menu' );
					if(is_wp_error($result)){
						self::log('[MENU] Could not add term: '.$menuName.': '.var_export($result->get_error_messages() ,1));
						return false;
					}
					else {
						if(isset($result['term_id'])){
							$menuID = $result['term_id'];
						}
					}
				}
				else {
					if(isset($result['term_id'])){
						$menuID = $menuExists['term_id'];
					}
				}

				if(! isset($processedMenuItemsIDS[$menuID])){
					$processedMenuItemsIDS[$menuID] = array();
				}

				// Process submenus
				foreach( $menuItems as $menuItem )
				{
					$menuItem = (array)$menuItem;

					// Get post info
					$menuItemType = $menuItem['type'];
					$oldPostID = $menuItem['ID'];

					// Skip already processed menu items
					if(isset($processedMenuItems[ $oldPostID ])){
						if(! isset($processedMenuItemsIDS[$menuID][$oldPostID])){
							array_push($processedMenuItemsIDS[$menuID], $oldPostID);
							set_transient(self::TEMP_MENU_ID_TRANS, $processedMenuItemsIDS);
						}
						continue;
					}

					// Check if we've already processed this menu item
					if(in_array($oldPostID, $processedMenuItemsIDS[$menuID])){
						continue;
					}

					// Remove data we don't need (it will be added by WP automatically)
					unset( $menuItem['ID'], $menuItem['guid'], $menuItem['db_id'], $menuItem['post_author'] );

					// Get data that we need to update
					$oldMenuItemParentID = $menuItem['menu_item_parent'];
					$oldMenuItemObjectID = $menuItem['object_id'];

					// Update item with the new values and add it to db

					// Get the current menu item parent
					$newMenuItemParentID = intval($menuItem['menu_item_parent']);

					// if post/page/whatever
					if( 'post_type' == $menuItemType && isset($processedPosts[$oldMenuItemObjectID]) )
					{
						$menuItem['object_id'] = $processedPosts[$oldMenuItemObjectID];
					}
					// if category, tag/whatever
					elseif( 'taxonomy' == $menuItemType && isset($processedCategories[$oldMenuItemObjectID]))
					{
						// we should have mapped it already (see __importContent())
						$menuItem['object_id'] = $processedCategories[$oldMenuItemObjectID];
					}
					// custom
					elseif(isset($processedMenuItems[$oldMenuItemParentID])){
						$menuItem['object_id'] = $processedMenuItems[$oldMenuItemParentID];
					}

					if (isset($processedMenuItems[ $oldMenuItemParentID ])) {
						$newMenuItemParentID = $processedMenuItems[ $oldMenuItemParentID ];
					}

					$_menu_item_classes = maybe_unserialize( $menuItem['classes'] );
					if ( is_array( $_menu_item_classes ) ) {
						$_menu_item_classes = implode( ' ', $_menu_item_classes );
					}

					// Update values
					$menuItem['menu_item_parent'] = $newMenuItemParentID;

					$_newMenuItemID = wp_insert_post( $menuItem, true );
					if(is_wp_error($_newMenuItemID)){
						self::log( 'Could not add menu item post: '.var_export($_newMenuItemID->get_error_messages(),1) );
					}

					//#! Set menu tem data for DB
					$menuItemData = array(
						'menu-item-object-id'   => $menuItem['object_id'],
						'menu-item-object'      => $menuItem['object'],
						'menu-item-parent-id'   => $newMenuItemParentID,
						'menu-item-position'    => $menuItem['menu_order'],
						'menu-item-type'        => $menuItemType,
						'menu-item-title'       => $menuItem['title'],
						'menu-item-url'         => str_replace( $configPH['SITE_URL'], $crtSiteUrl, $menuItem['url']),
						'menu-item-description' => $menuItem['description'],
						'menu-item-attr-title'  => $menuItem['attr_title'],
						'menu-item-target'      => $menuItem['target'],
						'menu-item-classes'     => $_menu_item_classes,
						'menu-item-xfn'         => $menuItem['xfn'],
						'menu-item-status'      => 'publish',
						'menu-item-post-parent' => $menuItem['post_parent'],
					);

					// Set menu item (insert/update)
					$_newMenuItemID = wp_update_nav_menu_item( $menuID, $_newMenuItemID, $menuItemData );
					if ( $_newMenuItemID && ! is_wp_error( $_newMenuItemID ) )
					{
						// Set special metadata
						if(isset($menuItem['_menu_item_zn_mega_menu_enable'])){
							update_post_meta( $_newMenuItemID, '_menu_item_zn_mega_menu_enable', esc_sql($menuItem['_menu_item_zn_mega_menu_enable']));
						}
						if(isset($menuItem['_menu_item_zn_mega_menu_label'])){
							update_post_meta( $_newMenuItemID, '_menu_item_zn_mega_menu_label', esc_sql($menuItem['_menu_item_zn_mega_menu_label']));
						}
						if(isset($menuItem['_menu_item_zn_mega_menu_headers'])){
							update_post_meta( $_newMenuItemID, '_menu_item_zn_mega_menu_headers', esc_sql($menuItem['_menu_item_zn_mega_menu_headers']));
						}
						if(isset($menuItem['_menu_item_zn_mega_menu_smart_area'])){
							$smID = (int)$menuItem['_menu_item_zn_mega_menu_smart_area'];
							if( ! empty($smID) && isset($processedPosts["$smID"])){
								update_post_meta( $_newMenuItemID, '_menu_item_zn_mega_menu_smart_area', $processedPosts["$smID"] );
							}
						}

						// Cache the processed menu item
						$processedMenuItems[ $oldPostID ] = $_newMenuItemID;
						array_push($processedMenuItemsIDS[$menuID], $oldPostID);
						set_transient(self::TEMP_MENU_ID_TRANS, $processedMenuItemsIDS);
					}
					else {
						self::log( 'Could not add menu item: '.var_export($_newMenuItemID->get_error_messages(),1) );
					}
				}
				// END foreach submenus
			}
			// END foreach menus
		}

		// Update cache
		set_transient( self::TEMP_DATA_TRANS, $tempData );
		return true;
	}

	/**
	 * Install plugins and their options
	 * @param string $demoDirPath
	 * @return bool
	 */
	public static function installDependentPlugins( $demoDirPath )
	{

		// Add a dummy term id in case a plugin adds it's own term id's
		// This is usefull because we can try to import the content term using their own ids
		self::addDummyTerm();

		// Get the reference to the instance of the ZnAddonsManager() class
		$znAddonsMgr = ZNHGTFW()->getComponent('addons_manager');

		// Install mandatory plugins
		$pluginsRequirements = ZNHGTFW()->getComponent('dependency_manager')->getPluginRequirements();

		foreach ( $pluginsRequirements as $slug => $pluginConfig ) {
			if( ! ZNHGTFW()->getComponent( 'addons_manager' )->isPluginActive( $pluginConfig['slug'] ) ){
				$result = $znAddonsMgr->do_plugin_install( $pluginConfig['slug'] );
			}
		}

		//#! Get the dependent plugins
		$pluginsOptions = self::getPluginsOptions( $demoDirPath );
		$hasPluginOptions = ( ! empty($pluginsOptions) );

		//#! Get the demo's plugins
		$demoName = basename( $demoDirPath );

		if( ! empty($demoName) )
		{
			$demoInfo = ZN_HogashDashboard::__get_demo_install_info( $demoName );
			if( ! empty($demoInfo) )
			{
				$demoPlugins = (isset($demoInfo['plugins']) ? $demoInfo['plugins'] : null );

				//#! if the demo requires no plugins there is nothing we can do more here
				if( empty($demoPlugins) ){
					return true;
				}

				/*
				 * Loop through the plugins and Install/Activate them
				 */
				foreach( $demoPlugins as $pluginSlug )
				{
					$result = null;

					// Check to see if the plugin is already installed
					if( $znAddonsMgr->is_plugin_installed( $pluginSlug ) ){
						if( ! $znAddonsMgr->isPluginActive( $pluginSlug ) ){
							// Try to activate the plugin
							$result = $znAddonsMgr->do_plugin_activate( $pluginSlug );
						}
					}
					// Install the plugin and activate it
					else {
						$result = $znAddonsMgr->do_plugin_install( $pluginSlug );
					}

					// Log potential errors
					if ( is_array( $result ) && isset( $result['error'] ) ) {
						self::log(__METHOD__."() Error installing/activating plugin [{$pluginSlug}]: ". $result['error'] );
						continue;
					}

					//#! Install options if they are provided
					if( $hasPluginOptions  && isset($pluginsOptions["$pluginSlug"]) && !empty($pluginsOptions["$pluginSlug"]) )
					{
						//#! ignore Revolution slider since the sliders will be imported in installRevolutionSliders()
						if( 'revslider' == $pluginSlug ) {
							continue;
						}
						elseif( 'woocommerce' == $pluginSlug )
						{
							foreach( $pluginsOptions['woocommerce'] as $row )
							{
								$optionName = $row['option_name'];
								$optionValue = $row['option_value'];
								$autoload = $row['autoload'];

								//#! Check for pages & update their values if there is one provided
								if( 'woocommerce_cart_page_id' == $optionName && !empty($optionValue) ){ update_option( $optionName, $optionValue, $autoload ); }
								elseif( 'woocommerce_checkout_page_id' == $optionName && !empty($optionValue) ){ update_option( $optionName, $optionValue, $autoload ); }
								elseif( 'woocommerce_myaccount_page_id' == $optionName && !empty($optionValue) ){ update_option( $optionName, $optionValue, $autoload ); }
								elseif( 'woocommerce_shop_page_id' == $optionName && !empty($optionValue) ){ update_option( $optionName, $optionValue, $autoload ); }
								elseif( 'woocommerce_terms_page_id' == $optionName && !empty($optionValue) ){ update_option( $optionName, $optionValue, $autoload ); }
								//#! Any other option
								else { update_option( $optionName, $optionValue, $autoload ); }
							}
						}
					}
				}
				// Invalidate WP plugins cache so our new installed plugins will be included in it when WP will regenerate the cache
				wp_cache_set('plugins', array(), 'plugins');
			}
		}

		// Remove dummy term id
		self::removeDummyterm();

		return true;
	}

	/**
	 * Install revolution sliders
	 * @param $demoDirPath
	 * @return bool
	 */
	public static function installRevolutionSliders( $demoDirPath )
	{
		//#! Import revolution sliders if any
		if(! empty($demoDirPath))
		{
			$allPluginsOptions = self::getPluginsOptions( $demoDirPath );
			if( ! empty($allPluginsOptions))
			{
				// Install all sliders if any
				if( ! empty($allPluginsOptions['revslider']) ){
					foreach( $allPluginsOptions['revslider'] as $sliderName ) {
						self::__importRevSlider( $demoDirPath, $sliderName );
					}
				}
			}
		}
		return true;
	}

	/**
	 * Insert the post in the database
	 *
	 * @param array $postData
	 * @param array $configPH
	 *
	 * @return int The new post ID on success or 0 on error
	 */
	private static function __insertPost( array $postData, array $configPH )
	{
		if(empty($configPH) || ! is_array($configPH)) { return 0; }

		// Remove the old ID and author before inserting the post so the current values will be used
		if(isset($postData['ID'])) { unset($postData['ID']); }
		if(isset($postData['post_author'])) { unset($postData['post_author']); }
		if(isset($postData['guid'])) { unset($postData['guid']); }

		$resultInsert = wp_insert_post($postData, true);
		if( is_wp_error($resultInsert) ){
			self::log('Error inserting post: '.var_export($resultInsert->get_error_messages(),1));
			return 0;
		}
		return $resultInsert;
	}

	/**
	 * Import terms
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	private static function __importTerms( $demoDirPath )
	{
		//#! Check if we've finished this step already
		$processed = get_transient( self::TEMP_TERMS_DONE_TRANS );
		if( false !== $processed || ( 'done' == $processed ) ){
			return true;
		}

		$terms = self::getTerms( $demoDirPath );

		// No content to import
		if(empty($terms)){
			return true;
		}

		// Get temp data
		$tempData = get_transient( self:: TEMP_DATA_TRANS );

		if(empty($tempData)){
			$tempData = array();
		}
		if( ! isset($tempData['terms'])){
			$tempData['terms'] = array();
		}

		// #! Process parent terms first, so children can find the referenced parents
		$children = array();

		foreach( $terms as $term )
		{
			$termID = $term['term_id'];
			$termParentID = (int)$term['parent'];
			if ( ! empty( $termParentID ) ) {
				array_push( $children, $term );
				continue;
			}

			//#! Process the parent term

			//#! Check if the term exists and update the cache
			$theTerm = get_term( $term['name'], $term['taxonomy'] );
			//#! Not found - add the term
			if ( ! is_wp_error( $theTerm ) ) {
				$termArray = array();
				if ( isset( $term['description'] ) ) {
					$termArray['description'] = $term['description'];
				}
				// = 0 since this is a parent
				$termArray['parent'] = 0;
				if ( isset( $term['slug'] ) ) {
					$termArray['slug'] = $term['slug'];
				}
				if ( isset( $term['count'] ) ) {
					$termArray['count'] = $term['count'];
				}
				if ( isset( $term['term_group'] ) ) {
					$termArray['term_group'] = $term['term_group'];
				}

				ZN_ThemeDemoImporter::$tempTermId = $termID;
				$resultInsert = wp_insert_term( $term['name'], $term['taxonomy'], $termArray );
				ZN_ThemeDemoImporter::$tempTermId = false;
				if ( is_wp_error( $resultInsert ) ) {
//						error_log( '[Demo Importer] Error adding the term: ' . $term['name'] . ': ' . $resultInsert->get_error_message() );
				}
				elseif ( is_array( $resultInsert ) && isset( $resultInsert['term_id'] ) && isset( $resultInsert['term_taxonomy_id'] ) ) {
					//#! Update cache
					$tempData['terms'][$termID] = $resultInsert['term_id'];
				}
			}

			//#! Term exists - update cache
			if ( isset( $theTerm->term_id ) ) {
				$tempData['terms'][$termID] = $theTerm->term_id;
			}
		}

		//#! Process children
		if ( ! empty( $children ) ) {
			foreach ( $children as $term ) {
				$termID = $term['term_id'];
				$oldTermParentID = $term['parent'];

				//#! Get from cache - parent already exists there
				$newTermParentID = $tempData['terms'][$oldTermParentID];

				//#! Check if the term exists
				$theTerm = get_term( $term['name'], $term['taxonomy'] );

				//#! Not found - add the term
				if ( ! is_wp_error( $theTerm ) ) {
					$termArray = array();

					if ( isset( $term['description'] ) ) {
						$termArray['description'] = $term['description'];
					}
					$termArray['parent'] = $newTermParentID;
					if ( isset( $term['slug'] ) ) {
						$termArray['slug'] = $term['slug'];
					}
					if ( isset( $term['count'] ) ) {
						$termArray['count'] = $term['count'];
					}
					if ( isset( $term['term_group'] ) ) {
						$termArray['term_group'] = $term['term_group'];
					}
					ZN_ThemeDemoImporter::$tempTermId = $termID;
					$resultInsert = wp_insert_term( $term['name'], $term['taxonomy'], $termArray );
					ZN_ThemeDemoImporter::$tempTermId = false;
					if ( is_wp_error( $resultInsert ) ) {
//							error_log( '[DEMO IMPORTER] Error adding the term: ' . $term['name'] . ': ' . $resultInsert->get_error_message() );
					}
					elseif ( is_array( $resultInsert ) && isset( $resultInsert['term_id'] ) && isset( $resultInsert['term_taxonomy_id'] ) ) {
						//#! Update cache
						$tempData['terms'][$termID] = $resultInsert['term_id'];
					}
				}

				//#! Term exists - update cache
				if ( isset( $theTerm->term_id ) ) {
					$tempData['terms'][$termID] = $theTerm->term_id;
				}
			}
		}
		// Update cache
		set_transient( self::TEMP_DATA_TRANS, $tempData );
		set_transient( self::TEMP_TERMS_DONE_TRANS, 'done' );
		return true;
	}

	/**
	 * Import terms metadata
	 * @param string $demoDirPath The path to the demo directory
	 * @return bool
	 */
	private static function __importTermsMeta( $demoDirPath )
	{
		//#! Check if we've finished this step already
		$processed = get_transient( self::TEMP_TERMS_META_DONE_TRANS );
		if( false !== $processed || ( 'done' == $processed ) ){
			return true;
		}

		$termsMeta = self::getTermsMeta( $demoDirPath );

		// No content to import
		if(empty($termsMeta)){
			return true;
		}

		// Get temp data
		$tempData = get_transient( self:: TEMP_DATA_TRANS );

		if(empty($tempData)){
			$tempData = array();
		}
		if( ! isset($tempData['terms'])){
			$tempData['terms'] = array();
		}

		//#! At this point the term has already been processed
		foreach( $termsMeta as $oldTermID => $data )
		{
			$newTermID = (isset($tempData['terms']["$oldTermID"]) ? $tempData['terms']["$oldTermID"] : 0 );
			if( ! empty($newTermID) && is_array($data) && ! empty($data) ) {
				foreach( $data as $metaKey => $metaValue ) {
					update_term_meta( $newTermID, $metaKey, $metaValue );
				}
			}
		}
		set_transient( self::TEMP_TERMS_META_DONE_TRANS, 'done' );
		return true;
	}


	/**
	 * Recursive search and replace in an array
	 * @param array $array The list where to search for $find
	 * @param mixed $find What to find
	 * @param mixed $replacement The replacement to use when $find was found
	 * @return array|mixed
	 */
	 public static function __replaceInArray( $array, $find, $replacement  )
	 {
		 if( is_array( $array ) ){
			 $array = json_decode(str_replace($find, $replacement, json_encode($array) ), true);
		 }
		 else{
			 $array = str_replace( $find, $replacement, $array );
		 }

		 return $array;
	 }


	/**
	 * Extract demo images from archive into the uploads directory
	 * @param $demoDirPath
	 * @return bool
	 */
	public static function importDemoImages( $demoDirPath )
	{

		// Extract and copy images to uploads dir
		$wpUploads = wp_get_upload_dir();
		$uploadsDir = $wpUploads['basedir'];
		$demoDirPath = str_replace("\\","/",$demoDirPath);
		$imagesArchive = trailingslashit($demoDirPath).self::IMAGES_ARCHIVE;

		if(! is_file($imagesArchive)){
			return true;
		}

		WP_Filesystem();
		$extracted = unzip_file( $imagesArchive, $uploadsDir );

		if( ! $extracted ){
			self::log(sprintf (
				esc_html__('Error extracting the demo images from (%s). ', 'zn_framework'),
				$imagesArchive
			));
			return false;
		}

		// Load the images map file and see what images we need to import
		$imagesMap = self::getImagesMap( $demoDirPath );
		if(empty( $imagesMap )){
			self::log('images.json file is empty. We cannot proceed with importing images.');
			return false;
		}

		$tempData = get_transient( self:: TEMP_DATA_TRANS );
		if( ! isset($tempData['images'])) {
			$tempData['images'] = array();
		}

		//
		$processedImages = get_transient( self:: TEMP_IMAGE_HASH_TRANS);
		if(empty($processedImages)){
			$processedImages = array();
		}


		// Needed to import the images below
		if(! defined('ABSPATH')) { require_once( ABSPATH . 'wp-load.php' ); }
		if(! function_exists('wp_crop_image')) { require_once( ABSPATH . 'wp-admin/includes/image.php' ); }

		// Loop through all images and import them into WordPress
		foreach($imagesMap as $placeholder => $info )
		{
			$path = $info['path'];


			// Skip the processed ones
			if(isset($tempData['images'][$path])){
				if(! isset($processedImages["$placeholder"])){
					array_push($processedImages, "$placeholder");
					set_transient(self::TEMP_IMAGE_HASH_TRANS, $processedImages);
				}
				continue;
			}

			if(isset($processedImages["$placeholder"])){
				continue;
			}

			// Check to see if the images exists, and skip it if it does
			$imageURL = ZN_ThemeDemoImporter::getUploadsDirUrl() . $path;
			$imageID = attachment_url_to_postid( $imageURL );
			if(! empty($imageID)){
				$tempData['images'][$path] = $imageID;
				set_transient( self::TEMP_DATA_TRANS, $tempData );
				continue;
			}

			$fullPath = trailingslashit($uploadsDir).$path;

			// Process image
			if( false === self::__importImage( $fullPath, $path, $info['id'] ) ){
				self::log('Image '.$fullPath.' could not be imported');
			}
			else {
				// Update transient
				array_push($processedImages, "{$placeholder}");
				set_transient(self::TEMP_IMAGE_HASH_TRANS, $processedImages);
			}
		}
		return true;
	}

	/**
	 * Import media into WordPress
	 * @param string $filePath The full system path to the image file
	 * @param string $internalPath The partial system path to the image file starting from the uploads directory
	 * @param int $imageId The image id that should be used to insert the image to DB
	 * @return bool True on success, false on failure
	 */
	private static function __importImage( $filePath, $internalPath, $imageId = false )
	{
		if(empty($filePath) || !is_file($filePath)){
			self::log('File not found: '.$filePath);
			return false;
		}

		$fileInfo = @getimagesize($filePath);
		$fileName = basename( $filePath );

		// Create an array of attachment data to insert into wp_posts table
		$postData = array(
			'post_author' => get_current_user_id(),
			'post_date' => current_time('mysql'),
			'post_date_gmt' => current_time('mysql'),
			'post_title' => $fileName,
			'post_status' => 'publish',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_name' => sanitize_title_with_dashes(str_replace("_", "-", $fileName)),
			'post_modified' => current_time('mysql'),
			'post_modified_gmt' => current_time('mysql'),
			'post_type' => 'attachment',
			'guid' => ZN_ThemeDemoImporter::getUploadsDirUrl().$internalPath,
			'post_mime_type' => $fileInfo['mime'],
		);

		// Set the image id if it exists
		if( $imageId ){
			$postData['import_id'] = $imageId;
		}

		// Insert the database record
		$attach_id = wp_insert_attachment( $postData, $filePath );

		// Generate metadata and thumbnails
		if ($attach_data = wp_generate_attachment_metadata( $attach_id, $filePath)) {
			wp_update_attachment_metadata( $attach_id, $attach_data );
		}

		// Store the imported image in a transient so we can reference its ID later on
		$tempData = get_transient( self:: TEMP_DATA_TRANS );
		if( ! isset($tempData['images'])) {
			$tempData['images'] = array();
		}
		$tempData['images'][$internalPath] = $attach_id;
		set_transient( self::TEMP_DATA_TRANS, $tempData );
		return $attach_id;
	}

	/**
	 * Import all Revolution Sliders into WordPress. If the pecified slider $sliderName already exists, it wil be skipped.
	 *
	 * @param string $demoDirPath The path to the demo directory
	 * @param string $sliderName The name of the slider to import
	 * @return bool True on success, false on failure
	 */
	private static function __importRevSlider( $demoDirPath, $sliderName = '' )
	{
		if( ! class_exists('RevSliderSlider')){
			self::log('['.__METHOD__.'] Class RevSliderSlider not found.');
			return false;
		}

		if( empty($sliderName)){
			self::log("No slider name provided. Skipping.");
			return false;
		}

		// Check to see whether or not the $sliderName already exists
		if(self::__sliderExists($sliderName)){
			self::log("Slider $sliderName already exists. Skipping.");
			return true;
		}


		$output = array();

		// Check for slider
		$sliderPath = trailingslashit($demoDirPath).self::REV_SLIDER_DIR.'/'.$sliderName.'.zip';

		if( is_file($sliderPath) && is_readable($sliderPath) )
		{
			$slider = new RevSliderSlider();

			// Use revolution slider and import all sliders
			try {
				// Since it doesn't provide a method for importing sliders directly, trick it into believing this would be a slider upload
				if(! isset($_POST)){
					$_POST = array();
				}
				$_POST['sliderid'] = 0;

				if(! isset($_FILES)){
					$_FILES = array();
				}
				$_FILES['import_file']['error'] = '';
				$_FILES["import_file"]["tmp_name"] = $sliderPath;
				//#!---

				$result = $slider->importSliderFromPost(true, true, $sliderPath);
			}
			catch( Exception $e ){
				$output["$sliderName"] = array(
					'success' => false,
					'error' => $e->getMessage(),
					'path' => $sliderPath
				);
				self::log('SLIDER IMPORT: '.var_export($output,1));
			}
		}
		return true;
	}


	/**
	 * Checks if a revolution slider is already installed
	 * @param  string $sliderName The slider name to check
	 * @return bool             True/false if slider exists or not
	 */
	private static function __sliderExists($sliderName = ''){
		if(empty($sliderName)){
			return false;
		}
		$revSlider = new RevSliderSlider();
		$sliders = $revSlider->getAllSliderAliases();
		return in_array($sliderName, $sliders);
	}


	/**
	 * Post processing before cleanup
	 * @param string $demoDirPath
	 * @return bool
	 */
	public static function  postProcessing( $demoDirPath )
	{
		//#! Update theme options that require a special attention
		$tempData = get_transient( self::TEMP_DATA_TRANS );

		if(! empty($tempData))
		{
			$hasProcessedPosts = false;

			// Check if there are posts that need to be re-checked with page builder enabled, and if there are any,
			// loop through the PB data and search for specific elements and update values
			if (isset($tempData['posts']) && ! empty($tempData['posts']))
			{
				$hasProcessedPosts = true;

				//#! Get old pages / they will be updated if found
				$oldShopPageID = get_option('woocommerce_shop_page_id');
				$oldShopCartPageID = get_option('woocommerce_cart_page_id');
				$oldShopCheckoutPageID = get_option('woocommerce_checkout_page_id');
				//#! New
				$oldMyAccountPageID = get_option('woocommerce_myaccount_page_id');
				$oldTermsPageID = get_option('woocommerce_terms_page_id');

				foreach($tempData['posts'] as $oldID => $newID)
				{
					// Check page builder post meta
					$pageBuilderMeta = get_post_meta($newID, 'zn_page_builder_els', true );
					if(! empty($pageBuilderMeta) && is_array($pageBuilderMeta))
					{
						// Update
						$pageBuilderMeta = self::__processPostMetaArray( $pageBuilderMeta, $tempData );
						update_post_meta( $newID, 'zn_page_builder_els', $pageBuilderMeta );
					}

					// Check special post meta (from post options meta box)
					$postCustomLayout = get_post_meta( $newID, 'zn-custom-layout', true );
					if(! empty($postCustomLayout) && isset($tempData['posts'][$postCustomLayout]))
					{
						update_post_meta( $newID, 'zn-custom-layout', $tempData['posts'][$postCustomLayout]);
					}
				}

				// Update reading settings
				$pageOnFront =  get_option( 'page_on_front' );
				$pageForPosts = get_option( 'page_for_posts' );
				if(! empty($pageOnFront) &&isset($tempData['posts'][$pageOnFront])){
					update_option( 'page_on_front', $tempData['posts'][$pageOnFront] );
				}
				if(! empty($pageOnFront) &&isset($tempData['posts'][$pageForPosts])){
					update_option( 'page_for_posts', $tempData['posts'][$pageForPosts] );
				}

				// Update the Shop Pages if woocommerce exists
				if( ! empty($oldShopPageID) && isset($tempData['posts'][$oldShopPageID]) ) {
					update_option( 'woocommerce_shop_page_id', $tempData['posts'][$oldShopPageID]);
				}
				if( ! empty($oldShopCartPageID) && isset($tempData['posts'][$oldShopCartPageID]) ) {
					update_option( 'woocommerce_cart_page_id', $tempData['posts'][$oldShopCartPageID]);
				}
				if( ! empty($oldShopCheckoutPageID) && isset($tempData['posts'][$oldShopCheckoutPageID]) ) {
					update_option( 'woocommerce_checkout_page_id', $tempData['posts'][$oldShopCheckoutPageID]);
				}
				if( ! empty($oldMyAccountPageID) && isset($tempData['posts'][$oldMyAccountPageID]) ) {
					update_option( 'woocommerce_myaccount_page_id', $tempData['posts'][$oldMyAccountPageID]);
				}
				if( ! empty($oldTermsPageID) && isset($tempData['posts'][$oldTermsPageID]) ) {
					update_option( 'woocommerce_terms_page_id', $tempData['posts'][$oldTermsPageID]);
				}

			}

			// Check to see whether or not we need to update any post_parent entries
			if( $hasProcessedPosts && isset($tempData['posts_review']) && !empty($tempData['posts_review']))
			{
				foreach( $tempData['posts_review'] as $newPostID => $oldParentID )
				{
					if(isset($tempData['posts'][$oldParentID]))
					{
						// Update post parent
						$postUpdate = wp_update_post( array(
							'ID' => $newPostID,
							'post_parent' => (int)$tempData['posts'][$oldParentID],
						), true );
						if ( is_wp_error( $postUpdate ) ) {
							self::log( '[' . $newPostID . '] Error updating post parent: ' . var_export($postUpdate->get_error_messages(),1 ) );
						}
					}
				}
			}
		}

		// Update theme options
		$optionName = ZNHGTFW()->getThemeDbId();
		$themeOptions = get_option( $optionName );

		// Replace SITE URL
		$demoConfig = self::getDemoConfig( $demoDirPath );
		$configPH = (isset($demoConfig['placeholders']) ? $demoConfig['placeholders'] : array());
		$siteUrl = esc_url(home_url());
		$themeOptions = self::__replaceInArray( $themeOptions, $configPH['SITE_URL'], $siteUrl );

		if ( isset( $themeOptions['advanced']['custom_css'] ) ) {
			$custom_css = $themeOptions['advanced']['custom_css'];
			update_option( 'zn_'. ZNHGTFW()->getThemeId() .'_custom_css', $custom_css, false );

			// Remove custom css from the main options field
			unset( $themeOptions['advanced']['custom_css'] );
		}

		if ( isset( $themeOptions['advanced']['custom_js'] ) ) {
			$custom_js = $themeOptions['advanced']['custom_js'];
			update_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_js', $custom_js, false );

			// Remove custom js from the main options field
			unset( $themeOptions['advanced']['custom_js'] );
		}

		// Regenerate custom css & js
		$saved_options = apply_filters( 'zn_options_to_save', $themeOptions );
		if( function_exists( 'ZNHGFW' ) ){
			ZNHGFW()->getComponent('scripts-manager')->deleteDynamicCss();
		}
		// If installed, clear builder cache as well
		if( function_exists( 'ZNB' ) ){
			ZNB()->scripts_manager->deleteAllCache();
			ZNB()->scripts_manager->compileElementsCss( true );
		}
		// Update options
		update_option( $optionName, $themeOptions);

		wp_schedule_single_event( time(), 'znhgtfw_flush_rewrite_rules' );

		return true;
	}
//</editor-fold desc="::: IMPORT FUNCTIONS">

	/**
	 * Cleanup after the install process
	 * @return bool
	 */
	public static function __cleanup()
	{

		// Delete temp data transients
		delete_transient( self::TEMP_DATA_TRANS );
		delete_transient( self::TEMP_POST_ID_TRANS );
		delete_transient( self::TEMP_MENU_ID_TRANS );
		delete_transient( self::TEMP_IMAGE_HASH_TRANS );
		delete_transient( self::TEMP_TERMS_DONE_TRANS );
		delete_transient( self::TEMP_TERMS_META_DONE_TRANS );
		delete_transient( ZN_ThemeDemoImporter::DEMO_INSTALLING_TRANSIENT );

		// Delete the temp folder
		$tempDir = ZN_ThemeDemoImporter::getTempFolder();
		if (is_dir($tempDir)) {
			if( function_exists('zn_delete_folder') ){
				zn_delete_folder($tempDir);
			}
		}

		return true;
	}

	/**
	 * Sanitizes the specified string using esc_attr and wp_strip_all_tags
	 * @param string $string The string to sanitize
	 * @return string|void
	 */
	public static function sanitizeString($string){
		return esc_attr( wp_strip_all_tags($string) );
	}

	/**
	 * Utility method that is used to update the PB data with the imported values for those posts that have been
	 * edited with page builder
	 *
	 * @param array $pm
	 * @param array $tempData
	 * @return array
	 */
	private static function __processPostMetaArray( &$pm, $tempData )
	{
		foreach ( $pm as $k => &$value ) {

			if( empty($value)){ continue; }

			if(is_array($value)) {

				if(isset($value['object']))
				{
					if(in_array($value['object'], self::$_pbe) && ! empty($value['options']))
					{
						$value['options'] = self::__updatePostMetaValue_PBE( $value['object'], $value['options'], $tempData );
					}
					elseif( 'ZnWidgetElement' == $value['object'] && !empty($value['options'])){
						if( isset($value['options']['widget']) && in_array($value['options']['widget'], self::$_pbw))
						{
							$value['options'] = self::__updatePostMetaValue_PBW( $value['object'], $value['options'], $tempData );
						}
					}
					else {
						self::__processPostMetaArray( $value, $tempData );
					}
				}
				else {
					self::__processPostMetaArray( $value, $tempData );
				}
			}
		}
		return $pm;
	}

	/**
	 * Helper function that is used to update the values from the special PB elements with the correct ones
	 * @param string $value The name of the PB element
	 * @param array $options
	 * @param array $tempData
	 * @return array
	 */
	private static function __updatePostMetaValue_PBE( $value, array &$options, $tempData )
	{
		$processedPosts = $processedMenus = $processedTerms = null;

		if( isset($tempData['posts']) && ! empty($tempData['posts']) ){
			$processedPosts = $tempData['posts'];
		}
		if( isset($tempData['terms']) && ! empty($tempData['terms']) ){
			$processedTerms = $tempData['terms'];
		}
		if( isset($tempData['menus']) && ! empty($tempData['menus']) ){
			$processedMenus = $tempData['menus'];
		}


		if( 'TH_BlogArchive' == $value && $processedTerms){
			if(isset($options['category']) && ! empty($options['category'])){
				foreach($options['category'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
		}

		elseif( 'TH_CustomMenu' == $value && $processedMenus) {
			if(isset($options['cm_menu']) && ! empty($options['cm_menu'])){
				$oldMenuID = $options['cm_menu'];
				if ( isset( $processedMenus[$oldMenuID] ) ) {
					$options['cm_menu'] = $processedMenus[$oldMenuID];
				}
			}
		}

		elseif( 'ZnPbCustomTempalte' == $value && $processedPosts) {
			if(isset($options['pb_template']) && ! empty($options['pb_template'])){
				$oldSmartAreaID = $options['pb_template'];
				if ( isset( $processedPosts[$oldSmartAreaID] ) ) {
					$options['pb_template'] = $processedPosts[$oldSmartAreaID];
				}
			}
		}

		elseif( in_array($value, array('TH_LatestPosts', 'TH_LatestPosts2', 'TH_LatestPosts3', 'TH_LatestPosts4')) && $processedPosts){
			if(isset($options['lp_blog_categories']) && ! empty($options['lp_blog_categories'])){
				foreach($options['lp_blog_categories'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
		}

		elseif( in_array($value, array('TH_PortfolioArchive', 'TH_RecentWork', 'TH_RecentWork2', 'TH_RecentWork3')) && $processedTerms){
			if(isset($options['portfolio_categories']) && ! empty($options['portfolio_categories'])){
				foreach($options['portfolio_categories'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
		}

		elseif( 'TH_ShopLimitedOffers' == $value && $processedTerms ) {
			if ( isset( $options['woo_categories'] ) && ! empty( $options['woo_categories'] ) ) {
				$terms = array();
				foreach ( $options['woo_categories'] as $oldTermID ) {
					if ( isset( $processedTerms[ $oldTermID ] ) ) {
						array_push( $terms, $processedTerms["$oldTermID"] );
					}
				}
				$options['woo_categories'] = $terms;
			}
		}

		elseif( 'TH_ShopProductsPresentation' == $value && $processedTerms){
			if(isset($options['woo_categories']) && ! empty($options['woo_categories'])){
				foreach($options['woo_categories'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
			if(isset($options['woo_categories_fp']) && ! empty($options['woo_categories_fp'])){
				foreach($options['woo_categories_fp'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
			if(isset($options['woo_categories_lp']) && ! empty($options['woo_categories_lp'])){
				foreach($options['woo_categories_lp'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
			if(isset($options['woo_categories_bs']) && ! empty($options['woo_categories_bs'])){
				foreach($options['woo_categories_bs'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}

			if(isset($options['woo_tags']) && ! empty($options['woo_tags'])){
				foreach($options['woo_tags'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
			if(isset($options['woo_tags_fp']) && ! empty($options['woo_tags_fp'])){
				foreach($options['woo_tags_fp'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
			if(isset($options['woo_tags_lt']) && ! empty($options['woo_tags_lt'])){
				foreach($options['woo_tags_lt'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
			if(isset($options['woo_tags_bs']) && ! empty($options['woo_tags_bs'])){
				foreach($options['woo_tags_bs'] as &$oldTermID) {
					if ( isset( $processedTerms[$oldTermID] ) ) {
						$oldTermID = $processedTerms[$oldTermID];
					}
				}
			}
		}
		return $options;
	}

	/**
	 * Helper function that is used to update the values from the special PB widgets with the correct ones
	 * @param string $value The name of the PB widget
	 * @param array $options
	 * @param array $tempData
	 * @return array
	 */
	private static function __updatePostMetaValue_PBW( $value, array &$options, $tempData )
	{
		$processedPosts = $processedMenus = $processedTerms = null;

		if( isset($tempData['posts']) && ! empty($tempData['posts']) ){
			$processedPosts = $tempData['posts'];
		}
//		if( isset($tempData['terms']) && ! empty($tempData['terms']) ){
//			$processedTerms = $tempData['terms'];
//		}
		if( isset($tempData['menus']) && ! empty($tempData['menus']) ){
			$processedMenus = $tempData['menus'];
		}

		if( 'WP_Widget_Pages' == $value && $processedPosts){
			if( isset($options['widget-pages']) && isset($options['widget-pages']['exclude']) && !empty($options['widget-pages']['exclude'])){
				$tmp = array();
				$posts = explode(',', $options['widget-pages']['exclude']);
				$posts = array_map( 'trim', $posts );
				foreach( $posts as $oldPostID ){
					if(isset($processedPosts[$oldPostID])){
						array_push($tmp, $processedPosts[$oldPostID]);
					}
				}
				$options['widget-pages']['exclude'] = implode(',', $tmp);
				$tmp = null;
			}
		}

		elseif( 'WP_Nav_Menu_Widget' == $value && $processedMenus) {
			if(isset($options['widget-nav_menu']) && isset($options['widget-nav_menu']['nav_menu']) && ! empty($options['widget-nav_menu']['nav_menu'])){
				$oldNavMenu = $options['widget-nav_menu']['nav_menu'];
				if(isset($processedMenus[$oldNavMenu])){
					$options['widget-nav_menu']['nav_menu'] = $processedMenus[$oldNavMenu];
				}
			}
			elseif(isset($options['widget-sbs_nav_menu']) && isset($options['widget-sbs_nav_menu']['sbs_nav_menu']) && ! empty($options['widget-nav_menu']['sbs_nav_menu'])){
				$oldNavMenu = $options['widget-sbs_nav_menu']['sbs_nav_menu'];
				if(isset($processedMenus[$oldNavMenu])){
					$options['widget-sbs_nav_menu']['sbs_nav_menu'] = $processedMenus[$oldNavMenu];
				}
			}
		}
		return $options;
	}

}
