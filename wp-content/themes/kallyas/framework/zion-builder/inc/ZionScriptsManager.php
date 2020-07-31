<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionScriptsManager
 *
 * TODO: What does this class do?
 */
class ZionScriptsManager {
	/**
	 * Holds the name of the directory to use by default for assets config
	 *
	 * @see getAssetConfig()
	 */
	const DIR_TYPE_CACHE = 'cache';

	/**
	 * Holds the reference to the instance of the ZionUtility class
	 *
	 * @var ZionUtility
	 *
	 * @see __construct()
	 */
	private $_utility = null;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->_utility = ZNB()->utility;
		add_action( 'delete_post', array( $this, 'deleteAssetCache' ) );
		add_action( 'znpb_save_page', array( $this, 'deleteAssetCache' ) );
		add_action( 'after_switch_theme', array( $this, 'deleteAllCache' ) );
		add_action( 'activated_plugin', array( $this, 'deleteAllCache' ) );
		add_action( 'zn_save_theme_options', array( $this, 'deleteAllCache' ) );

		// Clear cache upon pressing clear zion cache
		add_action( 'init', array( $this, 'clearBuilderCache') );
	}

	/**
	 *	Perform the cache clean
	 */
	function clearBuilderCache() {
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			if ( isset( $_GET['zion-clear-cache'] ) && $_GET['zion-clear-cache'] ) {
				$this->deleteAllCache();
				$this->compileElementsCss( true );
			}
		}
	}


	/**
	 * Will return the cache directory path and url
	 *
	 * @param string $type
	 *
	 * @return array the path and url to the cache folder
	 */
	public function getAssetConfig( $type = self::DIR_TYPE_CACHE ) {
		$type                = ( ! empty( $type) ? wp_strip_all_tags( $type ) : self::DIR_TYPE_CACHE );
		$allowed_asset_types = array( self::DIR_TYPE_CACHE );

		// Don't proceed if we don't allow this type of asset directory
		if ( ! in_array( $type, $allowed_asset_types ) ) {
			return false;
		}

		$wp_upload_dir = wp_upload_dir();
		$dir_name      = ZNB()->getPluginDirName();

		$asset_config = array(
			'path' => $wp_upload_dir['basedir'] . '/' . $dir_name . '/' . $type . '/',
			'url'  => $wp_upload_dir['baseurl'] . '/' . $dir_name . '/' . $type . '/',
		);

		// Create the file if it doesn't exists
		$this->_utility->createFolder( $asset_config['path'], true );

		//#! TODO: What does this filter do?
		$asset_config = apply_filters( "znb:{$type}_paths_config", $asset_config );

		return $asset_config;
	}


	/**
	 * Will combine all elements css code into one big file
	 *
	 * @param bool $recompile If we need to recompile the CSS or not
	 *
	 * @return void Nothing
	 */
	function compileElementsCss( $recompile = false ) {
		$fs = ZNHGFW()->getComponent( 'utility' )->getFileSystem();

		//  Will contain all compiled css
		$css = '';

		foreach ( ZNB()->elements_manager->getElements() as $class ) {
			// Check if the style.css file exists
			if ( $fs->is_file( $class->getPath( 'style.css' ) ) ) {
				$css .= $fs->get_contents( $class->getPath( 'style.css' ) );
			}

			// Check to see if we have an style.php file
			if ( $fs->is_file( $class->getPath( '/style.php' ) ) ) {
				ob_start();
				include $class->getPath( '/style.php' );
				$css .= ob_get_clean();
			}
		}

		$css_code     = apply_filters( 'znb_compiled_css', zn_minimify( $css ) );
		$assetsConfig = $this->getAssetConfig();
		$fs->put_contents( $assetsConfig['path'] . 'znb_compiled_css.css', $css_code, 0644 );
		add_option( 'znb_css_compiled', true);
	}

	/**
	 * Will enqueue the compiled css from all elements
	 *
	 * @return void Nothing
	 */
	function enqueueCompiledCss() {
		$assetsConfig = $this->getAssetConfig();

		if ( ! is_file( $assetsConfig['path'] . 'znb_compiled_css.css') ) {
			$this->compileElementsCss();
		}

		wp_enqueue_style( 'znb_compiled_css', $assetsConfig['url'] . 'znb_compiled_css.css' );
	}


	/**
	 * Will delete all cached files for a post
	 *
	 * @param string $post_id The post id for wich we need to clear the cache
	 */
	public function deleteAssetCache( $post_id = null ) {
		// Will delete the cache for a post id
		$post_id      = $post_id ? $post_id : $this->_utility->getPostID();
		$cache_config = $this->getAssetConfig();
		$css_file_names = array( $post_id . '-layout-loggedin', $post_id . '-layout');
		$files = array();
		foreach ( $css_file_names as $key => $filename ) {
			$files[] = $cache_config['path'] . $filename . '.css';
			$files[] = $cache_config['path'] . $filename . '.js';
		}

		// if smart area, delete all cache
		if ( 'znpb_template_mngr' == get_post_type( $post_id ) ) {
			$this->deleteAllCache();
			return;
		}

		foreach ( $files as $file_path ) {
			$this->_utility->deleteFile( $file_path );
		}
	}

	public function deleteAllCache() {
		// Will delete the cache for a post id
		$cache_config = $this->getAssetConfig();
		$css_files    = glob( $cache_config['path'] . '*.css' );
		$js_files     = glob( $cache_config['path'] . '*.js' );

		$files = array();
		if ( ! empty( $css_files) && is_array( $css_files) ) {
			$files = $css_files;
		}
		if ( ! empty( $js_files) && is_array( $js_files) ) {
			$files = array_merge( $files, $js_files );
		}
		if ( ! empty( $files) ) {
			array_map( array( $this->_utility, 'deleteFile' ), $files );
		}
	}

	/**
	 * TODO: VERIFY $js_file USAGE !!
	 * Enqueue the stylesheets and scripts registered to the provided $post_id
	 *
	 * @param null $post_id
	 */
	public function enqueueCachedAssets( $post_id = null ) {
		// Will enqueue both css and js
		$post_id       = $post_id ? $post_id : $this->_utility->getPostID();
		$cache_path    = $this->getAssetConfig();
		$css_file_name = is_user_logged_in() ? $post_id . '-layout-loggedin.css' : $post_id . '-layout.css';
		$css_file_path = $cache_path['path'] . $css_file_name;
		$css_file_url  = $cache_path['url'] . $css_file_name;
		$js_file       = $cache_path['url'] . $post_id . '-layout.js';
		$version       = $this->getAssetVersion( $post_id );

		if ( $post_id ) {
			if ( ! is_file( $css_file_path ) || ZNB()->isDebug() ) {
				$this->createAssetCss( $post_id );
			}

			wp_enqueue_style( $css_file_name, $this->_utility->fixInsecureContent( $css_file_url ), array(), $version );
		} else {
			// We may have a smart area set
			$registered_smart_areas = ZNB()->smart_area->getRegisteredSmartAreas();

			if ( ! empty( $registered_smart_areas ) ) {
				foreach ( $registered_smart_areas as $key => $area_id ) {
					$css_file_name = $area_id . '-smart-layout.css';
					$css_file_path = $cache_path['path'] . $css_file_name;
					$css_file_url  = $cache_path['url'] . $css_file_name;
					$js_file       = $cache_path['url'] . $area_id . '-smart-layout.js';

					if ( ! is_file( $css_file_path ) || ZNB()->isDebug() ) {
						$this->createSmartAreaCss( $area_id );
					}

					wp_enqueue_style( $css_file_name, $this->_utility->fixInsecureContent( $css_file_url ), array(), $version );
				}
			}
		}
	}

	/**
	 * Will return an asset version as an md5'd string
	 *
	 * @param string $post_id The current post id
	 *
	 * @return string
	 */
	public function getAssetVersion( $post_id = null ) {
		$post_id = $post_id ? $post_id : $this->_utility->getPostID();

		if ( $this->_utility->isActiveEditor() ) {
			return md5( uniqid() );
		} else {
			return md5( get_post_modified_time( 'U', false, $post_id ) );
		}
	}

	/**
	 * @param null $post_id
	 *
	 * @return bool
	 */
	public function createAssetCss( $post_id = null ) {

		// Will create the css file asset
		$post_id = $post_id ? $post_id : $this->_utility->getPostID();

		if ( empty( $post_id ) ) {
			return false;
		}

		$fs             = ZNHGFW()->getComponent( 'utility' )->getFileSystem();
		$current_layout = ZNB()->frontend->getLayoutModules();
		$cache_config   = $this->getAssetConfig();
		$loaded_assets  = array();
		$css            = '';
		foreach ( $current_layout as $key => $element_instance ) {
			if ( ! isset( $loaded_assets[$element_instance->data['object']] ) ) {
				// Add the style.css file if present
				$filePath = $element_instance->getPath( 'style.css');
				if ( $fs->is_file( $filePath ) && $fs->is_readable( $filePath ) ) {
					$css .= $fs->get_contents( $filePath );
				}

				// Add the style.php file if present
				$filePath = $element_instance->getPath( 'style.php');
				if ( $fs->is_file( $filePath ) && $fs->is_readable( $filePath ) ) {
					ob_start();
					include $filePath;
					$css .= ob_get_clean();
				}
			}

			// Add inline styles for the element
			if ( method_exists( $element_instance, 'css' ) ) {
				$css .= $element_instance->css();
			}

			$loaded_assets[$element_instance->data['object']] = true;
		}

		// Minify the css if it's not in debug mode
		if ( ! ZNB()->isDebug() ) {
			$css = $this->_utility->minifyCss( $css );
		}

		$css_file_name = is_user_logged_in() ? $post_id . '-layout-loggedin.css' : $post_id . '-layout.css';
		$filePath      = $cache_config['path'] . $css_file_name;
		return $fs->put_contents( $filePath, $css, 0644 );
	}

	/*
	 * TODO: IMPLEMENT THIS METHOD
	 */
	public function createAssetJs( $post_id = null ) {
		// Will create the js file asset
	}

	/*
	 * TODO: IMPLEMENT THIS METHOD
	 */
	public function createSmartAreaCss( $post_id ) {
		// Will create the css file asset
		$post_id = $post_id ? $post_id : $this->_utility->getPostID();

		if ( empty( $post_id ) ) {
			return false;
		}

		$area_layout = get_post_meta( $post_id, 'zn_page_builder_els', true );

		$cache_config  = $this->getAssetConfig();
		$loaded_assets = array();
		$css           = '';

		// Save the old instantiated modules
		$old_instances = ZNB()->frontend->instantiated_modules;

		// Instantiate area modules
		ZNB()->frontend->setupElements( $area_layout );

		$fs = ZNHGFW()->getComponent( 'utility' )->getFileSystem();

		$current_layout = ZNB()->frontend->getLayoutModules();
		foreach ( $current_layout as $key => $element_instance ) {
			if ( ! isset( $loaded_assets[$element_instance->data['object']] ) ) {
				// Add the style.css file if present
				$filePath = $element_instance->getPath( 'style.css');
				if ( $fs->is_file( $filePath ) && $fs->is_readable( $filePath ) ) {
					$css .= $fs->get_contents( $filePath );
				}

				// Add the style.php file if present
				$filePath = $element_instance->getPath( 'style.php');
				if ( $fs->is_file( $filePath ) && $fs->is_readable( $filePath ) ) {
					ob_start();
					include $filePath;
					$css .= ob_get_clean();
				}
			}

			// Add inline styles for the element
			if ( method_exists( $element_instance, 'css' ) ) {
				$css .= $element_instance->css();
			}
			$loaded_assets[$element_instance->data['object']] = true;
		}

		// Restore the old instances
		ZNB()->frontend->instantiated_modules = $old_instances;

		// Minify the css if it's not in debug mode
		if ( ! ZNB()->isDebug() ) {
			$css = $this->_utility->minifyCss( $css );
		}

		$filePath = $cache_config['path'] . $post_id . '-smart-layout.css';
		$fs->delete( $filePath );
		return $fs->put_contents( $filePath, $css, 0644 );
	}
}
