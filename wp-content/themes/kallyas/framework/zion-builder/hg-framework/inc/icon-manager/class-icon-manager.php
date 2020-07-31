<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

class ZnHgFw_IconManager {
	public $paths = array();

	/**
	 * The name of the option from the database
	 */
	const OPTION_NAME = 'zn_custom_fonts';

	public $font_name = 'new_font';

	// CONTAINS ALL THE ICONS
	public static $icon_data;
	public static $icons_locations;
	public static $custom_fonts;

	public function __construct() {

		// SET PATHS
		$this->paths = wp_upload_dir();
		$this->paths[ 'fonts' ] = 'zn_fonts';
		$this->paths[ 'temp' ] = trailingslashit( $this->paths[ 'fonts' ] ) . 'zn_temp';
		$this->paths[ 'fontdir' ] = trailingslashit( $this->paths[ 'basedir' ] ) . $this->paths[ 'fonts' ];
		$this->paths[ 'tempdir' ] = trailingslashit( $this->paths[ 'basedir' ] ) . $this->paths[ 'temp' ];
		$this->paths[ 'fonturl' ] = trailingslashit( $this->paths[ 'baseurl' ] ) . $this->paths[ 'fonts' ];
		$this->paths[ 'tempurl' ] = trailingslashit( $this->paths[ 'baseurl' ] ) . trailingslashit( $this->paths[ 'temp' ] );

		// FIlters
		add_filter( 'zn_dynamic_css', array( $this, 'set_css' ) );
		add_filter( 'upload_mimes', array( $this, 'upload_mimes' ), 0 );

		if( is_admin() ) {
			add_action( 'admin_print_styles', array( $this, 'admin_css' ) );
		}
	}

	function upload_mimes( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		$mimes['ttf'] = 'font/ttf';
		$mimes['woff'] = 'application/font-woff';
		$mimes['eot'] = 'application/vnd.ms-fontobject';
		return $mimes;
	}

	function admin_css(){
		echo '<!-- ICON FONTS CSS -->';
		echo '<style type="text/css">';
		echo $this->set_css();
		echo '</style>';
	}

	public function install_icon_package( $zip_file ) {
		$unzipped_package = $this->do_icons_archive( $zip_file );
		if( is_wp_error( $unzipped_package ) ){ return $unzipped_package; }

		// ADD THE FONT INFO TO DB AND CREATE ICON_LIST
		$font_data = $this->create_data();
		if( is_wp_error( $font_data ) ){ return $font_data; }

		// Clear cached css
		if( function_exists( 'ZNHGFW' ) ){
			ZNHGFW()->getComponent('scripts-manager')->deleteDynamicCss();
		}

		return $this->font_name;
	}

	/*
	*	EXTRACTS AN ARCHIVE CONTAINNING ICONS
	*/
	public function do_icons_archive( $zip ) {

		$extensions = array( 'eot', 'svg', 'ttf', 'woff', 'json' );
		$tempdir = zn_create_folder( $this->paths[ 'tempdir' ], false );
		$temp2Folder = $this->paths[ 'tempdir' ] . "2";
		$tempdir2 = zn_create_folder( $temp2Folder, false );

		// Check if the tem dir has been created
		if ( ! $tempdir ) {
			return new WP_Error( 'missing_font_folder', __( 'The temp folder could not be created!', 'zn_framework' ) );
		}

		WP_Filesystem();
		$extracted = unzip_file( $zip, $temp2Folder );
		if ( $extracted ) {
			// We need to remove any unnecessary files and move the allowed file types one folder up
			$elements_files_obj = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $temp2Folder, RecursiveIteratorIterator::CHILD_FIRST ) );
			$elements_files_obj->setMaxDepth( 2 );
			foreach ( $elements_files_obj as $filename => $fileobject ) {
				if ( ! in_array( pathinfo( $fileobject->getFilename(), PATHINFO_EXTENSION ), $extensions ) ) {
					continue;
				}
				copy( $filename, $this->paths[ 'tempdir' ] . '/' . $fileobject->getFilename() );
			}
			zn_delete_folder( $this->paths[ 'tempdir' ] . '2' );
		}
		else {
			return new WP_Error( 'missing_font_folder', __( 'The zip file could not be extracted!', 'zn_framework' ) );
		}

		return true;
	}

	public function create_data() {
		$svg_file = find_file( $this->paths[ 'tempdir' ], '.svg' );
		$return = array();
		if ( empty( $svg_file ) ) {
			zn_delete_folder( $this->paths[ 'tempdir' ] );
			return new WP_Error( 'missing_font_folder', __( 'The zip did not contained any svg files.', 'zn_framework' ) );
		}
		//#! since v4.1.4
		$svgFile = trailingslashit( $this->paths[ 'tempdir' ] ) . $svg_file;
		if ( !is_file( $svgFile ) || !is_readable( $svgFile ) ) {
			zn_delete_folder( $this->paths[ 'tempdir' ] );
			return new WP_Error( 'missing_font_folder', __( 'Could not find or read the svg file.', 'zn_framework' ) );
		}

		$fs = ZNHGFW()->getComponent( 'utility' )->getFileSystem();
		$file_data = $fs->get_contents( $svgFile );

		if ( !is_wp_error( $file_data ) && !empty( $file_data ) ) {
			$xml = simplexml_load_string( $file_data );
			//#! since v4.1.4 - make sure this is a valid font archive
			if ( !is_object( $xml ) || !isset( $xml->defs ) || !isset( $xml->defs->font ) ) {
				zn_delete_folder( $this->paths[ 'tempdir' ] );
				return new WP_Error( 'missing_font_folder', __( 'Could not find or read the svg file.', 'zn_framework' ) );
			}
			$font_attr = $xml->defs->font->attributes();
			$this->font_name = (string)$font_attr[ 'id' ];
			$icon_list = array();
			$glyphs = $xml->defs->font->children();
			$class = '';
			foreach ( $glyphs as $item => $glyph ) {
				if ( $item == 'glyph' ) {
					$attributes = $glyph->attributes();
					$unicode = (string)$attributes[ 'unicode' ];
					$d = (string)$attributes[ 'd' ];
					if ( $class != 'hidden' && !empty( $d ) ) {
						$unicode_key = trim( json_encode( $unicode ), '\\\"' );
						if ( $item == 'glyph' && !empty( $unicode_key ) && trim( $unicode_key ) != '' ) {
							$icon_list[ $this->font_name ][ $unicode_key ] = $unicode_key;
						}
					}
				}
			}
			if ( ! empty( $icon_list ) && ! empty( $this->font_name ) ) {
				$strData = '';
				$icon_list_file = $this->paths[ 'tempdir' ] . '/icon_list.php';
				if ( $icon_list_file ) {
					$strData .= '<?php $icons = array();';
					foreach ( $icon_list[ $this->font_name ] as $unicode ) {
						if ( !empty( $unicode ) ) {
							$delimiter = "'";
							if ( strpos( $unicode, "'" ) !== false ) {
								$delimiter = '"';
							}
							$strData .= "\r\n" . '$icons[\'' . $this->font_name . '\'][' . $delimiter . $unicode . $delimiter . '] = ' . $delimiter . $unicode . $delimiter . ';';
						}
					}
					$fs->put_contents( $icon_list_file, $strData, 0644 );
				}
				else {
					zn_delete_folder( $this->paths[ 'tempdir' ] );
					return new WP_Error( 'missing_font_folder', __( 'There was a problem creating the icon list file', 'zn_framework' ) );
				}

				// RENAME ALL FILES SO WE CAN LOAD THEM BY FONT NAME
				$this->rename_files();
				// RENAME THE FOLDER WITH THE FONT NAME
				$this->rename_folder();
				// ADD FONT DATA TO FONT OPTION
				$this->add_font_data();
				return true;
			}
		}

		return new WP_Error( 'missing_font_folder', __( 'The svg file could not be opened.', 'zn_framework' ) );

	}


	/*
	*	Retrieves all custom fonts from options table
	*/
	public static function get_custom_fonts() {
		if ( !empty( self::$custom_fonts ) ) {
			return self::$custom_fonts;
		}
		$fonts = get_option( self::OPTION_NAME );
		if ( empty( $fonts ) ) {
			$fonts = array();
		}
		// CACHE THE VALUE
		self::$custom_fonts = $fonts;
		return $fonts;

	}


	/**
	 * Set an option containing the icon font url and path
	 */
	public function add_font_data() {
		$fonts = $this->get_custom_fonts();
		if ( empty( $fonts ) ) {
			$fonts = array();
		}
		$url = trailingslashit( $this->paths[ 'fonturl' ] );
		$url = zn_fix_insecure_content( $url ); // SSL friendly URL
		$fonts[ $this->font_name ] = array();
		update_option( self::OPTION_NAME, $fonts );
	}

	public function rename_files() {
		$directory = trailingslashit( $this->paths[ 'tempdir' ] );
		$extensions = array( 'eot', 'svg', 'ttf', 'woff' );
		foreach ( glob( $directory . '*' ) as $file ) {
			$path_parts = pathinfo( $file );
			if ( in_array( $path_parts[ 'extension' ], $extensions ) ) {
				rename( $file, trailingslashit( $path_parts[ 'dirname' ] ) . $this->font_name . '.' . $path_parts[ 'extension' ] );
			}
		}
	}


	/**
	 * Will delete a font
	 *
	 * @param $font_name The font name that will be deleted
	 */
	public function delete_font( $font_name ){

		$dirPath = trailingslashit( $this->paths[ 'fontdir' ] ) . $font_name;
		if ( ! is_dir( $dirPath ) ) {
			return new WP_Error( 'missing_font_folder', __( 'Invalid request. Directory not found', 'zn_framework' ) );
		}

		// Delete the font folder
		zn_delete_folder( $dirPath );
		$fonts = $this->get_custom_fonts();

		// Remove the font from DB
		if ( is_array( $fonts ) && isset( $fonts[ $font_name ] ) ) {
			unset( $fonts[ $font_name ] );
		}
		update_option( self::OPTION_NAME, $fonts );

	}

	/**
	 * Will archive a font a return it's location on the server
	 */
	public function create_font_archive( $font_name ){

		if( ! class_exists( 'ZipArchive' ) ) {
			return new WP_Error( 'zip_archive_not_installed', __( 'ZipArchive not installed. Contact your hosting provider and ask them to enable Zip Archive PHP extension.', 'zn_framework' ) );
		}

		// Create the temp dir
		$tempdir = zn_create_folder( $this->paths[ 'tempdir' ], false );
		if ( ! $tempdir ) {
			return new WP_Error( 'cannot_create_temp_folder', 'Could not create temporarry folder ' . $this->paths[ 'tempdir' ] );
		}

		$export_path = trailingslashit( $this->paths[ 'tempdir' ] ) . $font_name . '.zip';
		$export_url = trailingslashit( $this->paths[ 'tempurl' ] ) . $font_name . '.zip';
		$font_path = trailingslashit( $this->paths[ 'fontdir' ] ) . $font_name;

		$zip = new ZipArchive;
		$success = $zip->open( $export_path, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE );

		if ( $success !== true ) {
			return new WP_Error( 'cannot_create_zip_file', 'Could not create the export file in ' . $export_path );
		}

		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($font_path),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		// Add all font files to zip
		foreach ($files as $name => $file)
		{
			// Skip directories (they would be added automatically)
			if ( ! $file->isDir())
			{
				// Get real and relative path for current file
				$filePath = $file->getRealPath();

				// Add current file to archive
				$zip->addFile($filePath, $file->getFilename());
			}
		}

		// Close the zip
		$zip->close();

		return $export_path;
	}

	public function get_font_archive_path( $font_name ){
		$font_icon_zip = trailingslashit( $this->paths[ 'tempdir' ] ) . $font_name . '.zip';
		if( ! is_file( $font_icon_zip ) ){
			return new WP_Error( 'zip_file_missing', 'The zip file for font is missing ' . $font_icon_zip );
		}

		return $font_icon_zip;
	}


	/*
	*	RENAME THE FOLDER
	*	@param : the font name
	*/
	public function rename_folder() {
		$new_name = trailingslashit( $this->paths[ 'fontdir' ] ) . $this->font_name;
		zn_delete_folder( $new_name );
		rename( $this->paths[ 'tempdir' ], $new_name );
	}



	/*
	*	GET ALL THE ICONS
	*/
	public static function get_icons() {
		if ( !empty( self::$icon_data ) ) {
			return self::$icon_data;
		}
		$icon_locations = self::get_icon_locations();
		$config = array();
		$icons = array();
		foreach ( $icon_locations as $name => $iconData ) {
			if ( file_exists( $iconData[ 'filepath' ] . $name . '/icon_list.php' ) ) {
				include( $iconData[ 'filepath' ] . $name . '/icon_list.php' );
			}
			$config = array_merge( $config, $icons );

		}
		self::$icon_data = $config;
		return $config;
	}

	/*
	*	GET ALL ICONS LOCATIONS ( DEFAULT AND CUSTOM )
	*/
	public static function get_icon_locations() {
		if ( !empty( self::$icons_locations ) ) {
			return self::$icons_locations;
		}

		// Get all icons
		$icons_locations = apply_filters( 'znhgfw_icons_locations', self::get_custom_fonts() );

		// Set proper filepaths and url
		$wp_upload_dir = wp_upload_dir();
		// If the filepaths are missing, consider them uploaded icons
		foreach ($icons_locations as $iconId => &$iconConfig) {
			// Dynamically add the icon url and path
			if( empty( $iconConfig['url'] ) ) {
				$iconConfig['url'] = trailingslashit($wp_upload_dir[ 'baseurl' ]) . trailingslashit('zn_fonts');
				$iconConfig['url'] = zn_fix_insecure_content($iconConfig['url']);
			}
			// dynamically add the file path
			if( empty( $iconConfig['filepath'] ) ) {
				$iconConfig['filepath'] = trailingslashit($wp_upload_dir[ 'basedir' ]) . trailingslashit('zn_fonts');
			}
		}

		self::$icons_locations = $icons_locations;
		return $icons_locations;

	}

	public function get_icon( $icon ) {
		if ( strpos( $icon, 'u' ) === 0 ) {
			$icon = json_decode( '"\\' . $icon . '"' );
		}
		return $icon;
	}

	public function set_css( $output = '' ) {
		$icons_locations = self::get_icon_locations();
		//$output .= '<style type="text/css">';
		foreach ( $icons_locations as $name => $font_data ) {
			$icon_file = $font_data[ 'url' ] . $name . '/' . $name;
			$output .= "
				@font-face {
					font-family: '{$name}'; font-weight: normal; font-style: normal;
					src: url('{$icon_file}.eot');
					src: url('{$icon_file}.eot#iefix') format('embedded-opentype'),
					url('{$icon_file}.woff') format('woff'),
					url('{$icon_file}.ttf') format('truetype'),
					url('{$icon_file}.svg#{$name}') format('svg');
				}
				[data-zniconfam='{$name}']:before , [data-zniconfam='{$name}'] {
					font-family: '{$name}' !important;
				}
				[data-zn_icon]:before {
					content: attr(data-zn_icon)
				}";
		}
		return $output;
	}

	public function generate_icon_data( $icon ) {
		$result = '';
		if ( !is_array( $icon ) ) {
			return $result;
		}
		if ( empty( $icon[ 'family' ] ) || empty( $icon[ 'unicode' ] ) ) {
			return $result;
		}
		// print_z($icon);
		return 'data-zniconfam="' . $icon[ 'family' ] . '" data-zn_icon="' . $this->get_icon( $icon[ 'unicode' ] ) . '"';
	}
}

/*
 * This should be moved elsewhere
 */
if( ! function_exists('zn_generate_icon') ) {
	function zn_generate_icon( $icon ) {
		return ZNHGFW()->getComponent('icon_manager')->generate_icon_data( $icon );
	}
}

return new ZnHgFw_IconManager();
