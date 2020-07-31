<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

class ZnHgTFwUtility {

	/**
	 * Holds a refference to the theme options pages
	 * @return array the theme options pages
	 */
	var $theme_pages = array();

	/**
	 * Holds a refference to the theme options
	 * @return array the theme options
	 */
	var $theme_options = array();

	/**
	 * Holds a refference to an instance of WP_Filesystem_Direct class
	 * @var null|WP_Filesystem_Direct
	 */
	private $_fileSystemDefault = null;

	var $_theme_options_page_root;

	function __construct()
	{
		$this->_theme_options_page_root = apply_filters( 'znhgtfw_options_page_base_url', 'themes.php');
	}

	/**
	 * Returns the theme options pages
	 * @return array the theme options pages
	 */
	function get_theme_options_pages()
	{

		// Check if the pages are cached
		if( ! empty( $this->theme_pages ) ){
			return $this->theme_pages;
		}


		// TODO: remove the following code and add it from Kallyas
		$admin_pages = array();
		if ( file_exists( ZNHGTFW()->getThemePath( '/template_helpers/options/theme-pages.php' ) ) )
		{
			include( ZNHGTFW()->getThemePath( '/template_helpers/options/theme-pages.php' ) );
		}

		// Cache the values
		$this->theme_pages = apply_filters( 'zn_theme_pages', $admin_pages );

		return $this->theme_pages;
	}


	/**
	 * Returns the URL to a specific theme options page
	 * @param array $slug The specific page for which we request the URL
	 * @return string The options page url
	 */
	function get_options_page_url( $slug = array() ){
		return admin_url( $this->get_options_page_base_url() . '?page=zn-about' . implode( '&', $slug ) );
	}

	/**
	 * Retrieve the base url for theme admin pages
	 * @return string
	 */
	function get_options_page_base_url(){
		return $this->_theme_options_page_root;
	}

	/**
	 * Returns the theme options
	 * @return array the theme options
	 */
	function get_theme_options()
	{

		// Check if the options are cached
		if( ! empty( $this->theme_options ) ){
			return $this->theme_options;
		}

		// TODO: remove the following code and add it from Kallyas
		$admin_options = array();
		if ( file_exists( ZNHGTFW()->getThemePath( '/template_helpers/options/theme-options.php' ) ) )
		{
			include( ZNHGTFW()->getThemePath( '/template_helpers/options/theme-options.php' ) );
		}
		$this->theme_options =  apply_filters( 'zn_theme_options', $admin_options );

		return $this->theme_options;
	}

	/**
	 * This is a wrapper for WP_Filesystem_Direct
	 * @return WP_Filesystem_Direct an instance of WP_Filesystem_Direct
	 */
	public function getFileSystem(){
		if( is_null( $this->_fileSystemDefault ) ) {
			$this->loadWpFileSystemDirect();
			$this->_fileSystemDefault = new WP_Filesystem_Direct( array() );
		}
		return $this->_fileSystemDefault;
	}

	/**
	 * Load the WP_Filesystem_Direct class into the current execution scope
	 */
	public function loadWpFileSystemDirect()
	{
		//#! Try with WP File System first
		if ( !class_exists( 'WP_Filesystem_Base' ) ) {
			require_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/class-wp-filesystem-base.php' );
		}
		if ( !class_exists( 'WP_Filesystem_Direct' ) ) {
			require_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/class-wp-filesystem-direct.php' );
		}
		if( ! defined('FS_CHMOD_DIR') ) {
			define( 'FS_CHMOD_DIR', ( 0755 & ~umask() ) );
		}
		if( ! defined('FS_CHMOD_FILE') ) {
			define( 'FS_CHMOD_FILE', ( 0644 & ~umask() ) );
		}
	}

	/**
	 * Schema.org additions
	 * @param string $type The type of the element
	 * @param bool|false $echo Whether or not to display the output
	 * @return bool
	 */
	public static function schemaMarkup($type = '', $echo = false) {

		if (empty($type)) {
			return false;
		}

		$disable = apply_filters('hg_schema_markup_disable', false);

		if($disable == true) {
			return false;
		}

		$attributes = '';
		$attr = array();

		switch ($type) {
			case 'body':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WebPage';
				break;

			case 'header':
				$attr['role'] = 'banner';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPHeader';
				break;

			case 'nav':
				$attr['role'] = 'navigation';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/SiteNavigationElement';
				break;

			case 'title':
				$attr['itemprop'] = 'headline';
				break;

			case 'subtitle':
				$attr['itemprop'] = 'alternativeHeadline';
				break;

			case 'sidebar':
				$attr['role'] = 'complementary';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPSideBar';
				break;

			case 'footer':
				$attr['role'] = 'contentinfo';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPFooter';
				break;

			case 'main':
				$attr['role'] = 'main';
				$attr['itemprop'] = 'mainContentOfPage';
				if (is_search()) {
					$attr['itemtype'] = 'https://schema.org/SearchResultsPage';
				}

				break;

			case 'author':
				$attr['itemprop'] = 'author';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'person':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'comment':
				$attr['itemprop'] = 'comment';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/UserComments';
				break;

			case 'comment_author':
				$attr['itemprop'] = 'creator';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'comment_author_link':
				$attr['itemprop'] = 'creator';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				$attr['rel'] = 'external nofollow';
				break;

			case 'comment_time':
				$attr['itemprop'] = 'commentTime';
				$attr['itemscope'] = 'itemscope';
				$attr['datetime'] = get_the_time('c');
				break;

			case 'comment_text':
				$attr['itemprop'] = 'commentText';
				break;

			case 'author_box':
				$attr['itemprop'] = 'author';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'video':
				$attr['itemprop'] = 'video';
				$attr['itemtype'] = 'https://schema.org/VideoObject';
				break;

			case 'audio':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/AudioObject';
				break;

			case 'blog':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Blog';
				break;

			case 'blogpost':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Blog';
				break;

			case 'name':
				$attr['itemprop'] = 'name';
				break;

			case 'url':
				$attr['itemprop'] = 'url';
				break;

			case 'email':
				$attr['itemprop'] = 'email';
				break;

			case 'post_time':
				$attr['itemprop'] = 'datePublished';
				break;

			case 'post_content':
				$attr['itemprop'] = 'text';
				break;

			case 'creative_work':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/CreativeWork';
				break;
		}

		/**
		 * Filter to override or append attributes
		 * @var array
		 */
		$attr = apply_filters('hg_schema_markup_attributes', $attr);

		foreach ($attr as $key => $value) {
			$attributes.= $key . '="' . $value . '" ';
		}

		if ($echo) {
			echo ''.$attributes;
		}
		return $attributes;
	}

	/**
	 * Display the custom bottom mask markup
	 *
	 * @param  [type] $bm The mask ID
	 *
	 * @return [type]     HTML Markup to be used as mask
	 */
	public static function zn_background_source( $args = array() )
	{
		$defaults = array(
			'uid' => '',
			'source_type' => '',
			'source_background_image' => array(
				'image' => '',
				'repeat' => 'repeat',
				'attachment' => 'scroll',
				'position' => array(
					'x' => 'left',
					'y' => 'top'
				),
				'size' => 'auto',
			),
			'source_vd_yt' => '',
			'source_vd_vm' => '',
			'source_vd_self_mp4' => '',
			'source_vd_self_ogg' => '',
			'source_vd_self_webm' => '',
			'source_vd_embed_iframe' => '',
			'source_vd_vp' => '',
			'source_vd_autoplay' => 'yes',
			'source_vd_loop' => 'yes',
			'source_vd_muted' => 'yes',
			'source_vd_controls' => 'yes',
			'source_vd_controls_pos' => 'bottom-right',
			'source_overlay' => 0,
			'source_overlay_color' => '',
			'source_overlay_opacity' => '100',
			'source_overlay_color_gradient' => '',
			'source_overlay_color_gradient_opac' => '100',
			'source_overlay_gloss' => '',
			'source_overlay_custom_css' => '',
			'enable_parallax' => '',
			'mobile_play' => 'no',
		);

		$args = wp_parse_args( $args, $defaults );

		$bg_source = '';
		$sourceType = $args['source_type'];

		/**
		 * Stop and use ZB's bg video functionality
		 * @since 4.14.0
		 */
		if( function_exists('znb_background_source') ){
			// Append Youtube Path on Kallyas only
			// ZB's bg video func. is using full URL while old Kallyas's options are using video's ID
			if( $sourceType == 'video_youtube' && $args['source_vd_yt'] != '' ){
				$args['source_vd_yt'] = 'https://www.youtube.com/watch?v=' . $args['source_vd_yt'];
			}
			// Append Vimeo Path on Kallyas only
			// ZB's bg video func. is using full URL while old Kallyas's options are using video's ID
			if( $sourceType == 'video_vimeo' && $args['source_vd_vm'] != '' ){
				$args['source_vd_vm'] = 'https://vimeo.com/' . $args['source_vd_vm'];
			}
			znb_background_source($args);
		}
	}

	/**
	 * Retrieve all blog categories as an associative array: id => name
	 * @return array
	 */
	public static function getBlogCategories(){
		$args = array (
			'type'         => 'post',
			'child_of'     => 0,
			'parent'       => '',
			'orderby'      => 'id',
			'order'        => 'ASC',
			'hide_empty'   => 1,
			'hierarchical' => 1,
			'taxonomy'     => 'category',
			'pad_counts'   => false
		);
		$blog_categories = get_categories( $args );

		$categories = array ();
		foreach ( $blog_categories as $category ) {
			$categories[ $category->cat_ID ] = $category->cat_name;
		}
		return $categories;
	}
}
return new ZnHgTFwUtility();
