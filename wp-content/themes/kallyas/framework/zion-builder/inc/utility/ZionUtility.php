<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionUtility
 *
 * This class provides utility methods to use throughout the plugin
 */
class ZionUtility
{
	/**
	 * Holds a refference of the current post id
	 * @var string
	 */
	public static $post_id = false;

	/**
	 * Caching the value of isPageBuilderEnabled method
	 * @var array
	 */
	private $_isPageBuilderEnabled = array();

	/**
	 * Caching the value of isActiveEditor method
	 * @var null
	 */
	private $_isActiveEditor = null;

	/**
	 * Holds a reference of the active pagebuilder slug used in URL
	 * @var string
	 */
	var $edit_url_arg = 'zn_pb_edit';


	public function getEditUrl( $post_id = false )
	{
		$post_id = $post_id ? $post_id : $this->getPostID();

		$preview_link = apply_filters( 'znb_edit_url', get_preview_post_link( $post_id ) );
		return esc_url( add_query_arg( $this->edit_url_arg, '1', $preview_link ) );
	}

	/**
	 * What type of request is this?
	 * @var string $type ajax, frontend or admin
	 * @return bool
	 */
	public function isRequest( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ! is_admin();
		}

		return false;
	}

	/**
	 * Will check if we can edit with pagebuilder based on current user and post type
	 * @return bool
	 */
	public function allowedEdit()
	{
		return ( $this->allowedPostType() && $this->allowedCurrentUser() );
	}

	public function allowedPostType()
	{
		// TODO: Entire function
		return true;
	}

	public function allowedCurrentUser()
	{
		return current_user_can( 'edit_post', $this->getPostID() );
	}


	/**
	 * Will check if we are on the edit page and we can edit it
	 * Will only work after wp action ( the first action were we have access to post data )
	 */
	public function isActiveEditor()
	{
		if( is_null( $this->_isActiveEditor ) ) {
			$this->_isActiveEditor = ( ( isset( $_GET[ $this->edit_url_arg ] ) && $_GET[ $this->edit_url_arg ] == 1 ) || defined( 'ZN_PB_AJAX' ) ) && $this->allowedEdit();
		}
		return apply_filters( 'znb:isActiveEditor', $this->_isActiveEditor );
	}



	/**
	 * Will check if we are on the edit page and we can edit it
	 * Will only work after wp action ( the first action were we have access to post data )
	 */
	public function isPageBuilderEnabled( $post_id = false )
	{

		$post_id = $post_id ? $post_id : $this->getPostID();

		if(!isset($this->_isPageBuilderEnabled[$post_id])){

			// Automatically enable the PB editor if the user visits zn_pb_edit and is allowed to edit
			if( $this->isActiveEditor() ){
				$this->enableEditor();
			}

			$builder_status = get_post_meta( $post_id, '_zn_zion_builder_status', true );
			$status = ('enabled' == $builder_status);
			$this->_isPageBuilderEnabled[$post_id] = apply_filters( 'znb:builder_status_id', $status, $post_id );

		}

		return $this->_isPageBuilderEnabled[$post_id];
	}


	public function enableEditor( $post_id = false )
	{
		$post_id = $post_id ? $post_id : $this->getPostID();
		$post = get_post( $post_id );

		// Save the post as draft if this is an auto-draft
		if ( $post->post_status === 'auto-draft' ) {
			$post_data = array(
				'ID'          => $post_id,
				'post_status' => 'draft',
			);
			wp_update_post( $post_data );
		}

		do_action( 'znb:builder_status_enabled', $post_id );
		return update_post_meta( $post_id, '_zn_zion_builder_status', 'enabled' );
	}


	public function disableEditor( $post_id = false )
	{
		$post_id = $post_id ? $post_id : $this->getPostID();
		do_action( 'znb:builder_status_disabled', $post_id );
		return update_post_meta( $post_id, '_zn_zion_builder_status', 'disabled' );
	}


	public function getPostID()
	{
		//TODO: Apply filter on the self::$post_id so can import WooCommerce functionality from Kallyas
		if ( !empty( self::$post_id ) ) {
			return self::$post_id;
		}

		global $post;

		if ( isset( $_POST[ 'post_id' ] ) ) {
			self::$post_id = $_POST[ 'post_id' ];
		}
		elseif ( is_singular() && !empty( $post ) ) {
			self::$post_id = $post->ID;
		}
		elseif ( is_archive() ) {
			// Check woocommerce archive
			if ( $this->isWooCommerceActive() ) {

				// Woocommerce archive pages
				if ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
					self::$post_id = wc_get_page_id( 'shop' );
				}

			}

		}
		elseif ( is_home() ) {
			// This is the Blog archive page. We need to check if a custom page is used or not
			self::$post_id = get_option( 'page_for_posts' );
		}

		// Check if we have a post id to return
		if ( !empty( self::$post_id ) ) {
			return self::$post_id;
		}

		return false;
	}

	/**
	 * Check to see whether or not the WooCommerce plugin is installed and activated
	 * @return bool
	 */
	public function isWooCommerceActive()
	{
		return class_exists( 'WooCommerce' );
	}

	public function makeTextEditable( $content = '', $option_id = '' )
	{

		if ( !$this->isActiveEditor() ) {
			return $content;
		}

		$unique_id = zn_uid( 'zneda_' );
		$output = '<div id="' . $unique_id . '" class="znhg-editable-area" data-optionid="' . $option_id . '">';

		// Output the content
		$output .= $content;

		$output .= '</div>';

		return $output;
	}


	/**
	 * Create the specified directory and add an index.html file inside to prevent directory listing if $add_index = true
	 * @param string $dir_path The system path to the directory to create
	 * @param bool|true $add_index
	 */
	public function createFolder( $dir_path, $add_index = true )
	{
		if ( !is_dir( $dir_path ) ) {
			$fs = ZNHGFW()->getComponent( 'utility' )->getFileSystem();
			$dirCreated = wp_mkdir_p( $dir_path );
			if ( $dirCreated && $add_index ) {
				$fs->put_contents( trailingslashit( $dir_path ) . 'index.html', '' );
			}
		}
	}


	/**
	 * Delete the specified file
	 * @param string $filePath
	 * @return bool
	 */
	public function deleteFile( $filePath )
	{
		$fs = ZNHGFW()->getComponent( 'utility' )->getFileSystem();
		if ( $fs->is_file( $filePath ) && $fs->is_writable( $filePath ) ) {
			return $fs->delete( $filePath );
		}
		return false;
	}


	public function getColumnsContainer( $args )
	{
		$classes = !empty( $args[ 'cssClasses' ] ) ? $args[ 'cssClasses' ] : '';

		$content = '<div';

		if ( $this->isActiveEditor() ) {
			$classes .= ' zn_sortable_content zn_content';
			$content .= ' data-droplevel="2"';
		}

		$content .= !empty( $classes ) ? ' class="' . $classes . '"' : '';
		$content .= '>';

		return $content;
	}


	public function getElementContainer( $args )
	{
		$classes = !empty( $args[ 'cssClasses' ] ) ? $args[ 'cssClasses' ] : '';

		$content = '<div';

		if ( $this->isActiveEditor() ) {
			$classes .= ' zn_columns_container zn_content';
			$content .= ' data-droplevel="1"';
		}

		$content .= !empty( $classes ) ? ' class="' . $classes . '"' : '';

		if ( !empty( $args[ 'attributes' ] ) ) {
			foreach ( $args[ 'attributes' ] as $name => $value ) {
				$content .= " $name=" . '"' . $value . '"';
			}
		}

		$content .= '>';

		return $content;
	}

	/**
	 * Retrieve the HTTP path for the System path provided
	 * @param string $fwPath
	 * @return string
	 */
	public function getFileUrl( $fwPath )
	{
		// Set base URI
		$theme_base = get_template_directory();

		//#! Normalize paths
		$theme_base = wp_normalize_path( $theme_base );
		$fwPath = wp_normalize_path( $fwPath );

		$is_theme = preg_match( '#' . $theme_base . '#', $fwPath );
		$directory_uri = ( $is_theme ) ? get_template_directory_uri() : WP_PLUGIN_URL;
		$directory_path = ( $is_theme ) ? $theme_base : WP_PLUGIN_DIR;
		$fw_basename = str_replace( wp_normalize_path( $directory_path ), '', $fwPath );

		return $directory_uri . $fw_basename;
	}


	public function minifyCss( $css ){
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css); // Remove comments
		$css = str_replace(': ', ':', $css); // Remove space after colons
		$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css); // Remove whitespace

		return $css;
	}

	/**
	 * Replace the http or https scheme from the specified $url
	 * @param string $url
	 * @return mixed
	 */
	public function fixInsecureContent( $url )
	{
		return preg_replace( '#^https?://#', '//', $url );
	}

	/**
	 * Retrieve the list (ID => Title) of all smart areas
	 * @kos
	 * @return array
	 */
	public static function getAllSmartAreas() {
		$areas = array();

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			global $sitepress;
			// save current language
			$current_lang = $sitepress->get_current_language();
			//get the default language
			$default_lang = $sitepress->get_default_language();
			//fetch posts in default language
			$sitepress->switch_lang($default_lang);
		}

		$entries = get_posts( array (
			'post_type'      => 'znpb_template_mngr',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'suppress_filters' => false
		) );

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$sitepress->switch_lang($current_lang);
		}
		//#! Add all smart areas found
		if( ! empty($entries)) {
			foreach ( $entries as $key => $value ) {
				$areas[ $value->ID ] = $value->post_title;
			}
		}
		return $areas;
	}
}
