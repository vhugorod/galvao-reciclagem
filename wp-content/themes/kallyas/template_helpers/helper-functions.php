<?php if(! defined('ABSPATH')) { return; }
/**
 * Custom Classes
 *
 * @package  Kallyas
 * @category Custom Classes
 * @author Team Hogash
 * @since 3.8.0
 */

if(! class_exists('WpkZn'))
{
	/**
	 * Class WpkZn
	 *
	 * @category Custom Classes
	 * @author Team Hogash
	 */
	class WpkZn
	{

		/**
		 * Retrieve all sidebars from the theme.
		 * @since 4.0.0
		 * @return array
		 */
		public static function getThemeSidebars(){
			$sidebars = array ();
			$sidebars['defaultsidebar'] = __( 'Default Sidebar', 'zn_framework' );
			if ( $unlimited_sidebars = zget_option( 'unlimited_sidebars', 'unlimited_sidebars' ) ) {
				foreach ( $unlimited_sidebars as $sidebar ) {
					if (isset($sidebar['sidebar_name']) && !empty($sidebar['sidebar_name'])) {
						$sidebars[ $sidebar['sidebar_name'] ] = $sidebar['sidebar_name'];
					}
				}
			}
			return $sidebars;
		}

		/**
		 * Retrieve all headers from the theme.
		 * @since 4.0.0
		 * @return array
		 */
		public static function getThemeHeaders( $addnone = false ){

			$headers = array ();
			if($addnone == true){
				$headers[0] = 'None';
			}
			$headers['zn_def_header_style'] = __( 'Default style', 'zn_framework' );
			$saved_headers = zget_option( 'header_generator', 'unlimited_header_options', false, array() );
			foreach ( $saved_headers as $header ) {
				if ( isset ( $header['uh_style_name'] ) && ! empty ( $header['uh_style_name'] ) ) {
					$header_name             = strtolower( str_replace( ' ', '_', $header['uh_style_name'] ) );
					$header_name             = sanitize_html_class( $header_name );
					$headers[ $header_name ] = $header['uh_style_name'];
				}
			}

			return $headers;
		}

		/**
		 * Retrieve all blog categories as an associative array: id => name
		 * @since 4.0.0
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

		/**
		 * @wpk
		 * Retrieve all post tags
		 * @since v4.11.2
		 * @return array
		 */
		public static function getBlogTags(){

			$tags = get_tags( array(
				'orderby' => 'name',
				'order' => 'ASC',
				'hide_empty' => false,
			) );

			if(! $tags || is_wp_error($tags)){
				return array();
			}
			$temp = array();
			foreach($tags as $tag){
				$temp[$tag->term_id] = esc_attr($tag->name);
			}
			return $temp;
		}

		/**
		 * Retrieve all shop categories as an associative array: id => name
		 * @requires plugin WooCommerce installed and active
		 * @since 4.0.0
		 * @return array
		 */
		public static function getShopCategories(){
			$args = array (
				'type'         => 'shop',
				'child_of'     => 0,
				'parent'       => '',
				'orderby'      => 'id',
				'order'        => 'ASC',
				'hide_empty'   => 1,
				'hierarchical' => 1,
				'taxonomy'     => 'product_cat',
				'pad_counts'   => false
			);

			$shop_categories = get_categories( $args );

			$categories = array ();
			if ( ! empty( $shop_categories ) ) {
				foreach ( $shop_categories as $category ) {
					if ( isset( $category->cat_ID ) && isset( $category->cat_name ) ) {
						$categories[ $category->cat_ID ] = $category->cat_name;
					}
				}
			}
			return $categories;
		}

		/**
		 * @wpk
		 * Retrieve all product tags
		 * @since v4.1
		 * @return array
		 */
		public static function getShopTags(){
			$terms = get_terms( 'product_tag', array(
				'orderby' => 'name',
				'order' => 'ASC',
				'hide_empty' => false,
			) );
			if(! $terms || is_wp_error($terms)){
				return array();
			}
			$temp = array();
			foreach($terms as $tag){
				$temp[$tag->term_id] = esc_attr($tag->name);
			}
			return $temp;
		}



		/**
		 * Retrieve the list of all Portfolio Categories
		 * @since 4.0.0
		 * @return array
		 */
		public static function getPortfolioCategories(){
			$args = array (
				'type'         => 'portfolio',
				'child_of'     => 0,
				'parent'       => '',
				'orderby'      => 'id',
				'order'        => 'ASC',
				'hide_empty'   => 1,
				'hierarchical' => 1,
				'taxonomy'     => 'project_category',
				'pad_counts'   => false
			);
			$port_categories = get_categories( $args );
			$categories = array ();
			if ( ! empty( $port_categories ) ) {
				foreach ( $port_categories as $category ) {
					if ( isset( $category->cat_ID ) && isset( $category->cat_name ) ) {
						$categories[ $category->cat_ID ] = $category->cat_name;
					}
				}
			}
			return $categories;
		}

		/**
		 * Retrieve all tags for the custom post type Portfolio Item
		 * @since v4.15.10
		 * @return array
		 */
		public static function getPortfolioTags() {
			$tags_list = get_terms( array( 'taxonomy' => 'portfolio_tags', 'hide_empty' => true ) );
			$tags = array();
			if( ! empty($tags_list)){
				foreach($tags_list as $term ){
					$tags[$term->term_id] = $term->name;
				}
			}
			return $tags;
		}


		/**
		 * Retrieve the list of tags (as links) for the specified post
		 * @param int $postID
		 * @param string $sep The separator
		 * @return string
		 */
		public static function getPostTags($postID, $sep = '')
		{
			$out = '';
			$tagsArray = array();
			$tags = wp_get_post_tags($postID, array('orderby' => 'name', 'order' => 'ASC'));
			if(empty($tags)){
				return $out;
			}
			foreach($tags as $tag){
				$tagsArray[$tag->name] = get_tag_link($tag->term_id);
			}
			foreach($tagsArray as $name => $link){
				$out .= '<a class="kl-blog-tag" href="'.$link.'" rel="tag">'.$name.'</a>';
				if(! empty($sep)){
					$out .= $sep;
				}
			}
			$out = rtrim($out, $sep);
			return $out;
		}
	}

}

if(! class_exists('WpkPageHelper')) {
	/**
	 * Class WpkPageHelper
	 *
	 * Helper class to manage various aspects from pages
	 *
	 * @package  Kallyas
	 * @category UI
	 * @author   Team Hogash
	 * @since    4.0.0
	 */
	class WpkPageHelper
	{

		/**
		 * Display the proper sub-header based on the provided arguments
		 *
		 * @param array $args The list of arguments
		 */
		public static function zn_get_subheader( $args = array(), $is_pb_element = false )
		{

			$config = zn_get_pb_template_config();
			if( ( $config['template'] !== 'no_template' && (int)$config['template'] > 0 ) && ! $is_pb_element ){
				// We have a subheader template... let's get it's possition
				$pb_data = get_post_meta( $config['template'], 'zn_page_builder_els', true );

				if( $config['location'] == 'before' ){
					ZNB()->frontend->renderUneditableContent( $pb_data, $config['template'] );
					self::render_sub_header( $args );
				}
				elseif( $config['location'] == 'replace' ){
					ZNB()->frontend->renderUneditableContent( $pb_data, $config['template'] );
				}
				elseif( $config['location'] == 'after' ){
					self::render_sub_header( $args );
					ZNB()->frontend->renderUneditableContent( $pb_data, $config['template'] );
				}
			}
			else{
				self::render_sub_header( $args );
			}

		}

		public static function render_sub_header( $args = array() )
		{
			$id = zn_get_the_id();
			// Breadcrumb / Date
			$default_bread = zget_option( 'def_header_bread', 'general_options', false, 1 );
			$default_date = zget_option( 'def_header_date', 'general_options', false, 1 );

			// Title / Subtitle
			$show_title = zget_option( 'def_header_title', 'general_options', false, 1 );
			$show_subtitle = zget_option( 'def_header_subtitle', 'general_options', false, true );

			$def_subheader_alignment = zget_option( 'def_subheader_alignment', 'general_options', false, 'right' );
			$def_subheader_textcolor = zget_option( 'def_subh_textcolor', 'general_options', false, 'light' );

			$defaults = array(
				'headerClass' => 'zn_def_header_style',
				'title' => get_the_title( $id ),
				'layout' => zget_option( 'zn_disable_subheader', 'general_options' ),
				'def_header_bread' => $default_bread,
				'def_header_date' => $default_date,
				'def_header_title' => $show_title,
				'show_subtitle' => $show_subtitle,
				'extra_css_class' => '',
				'bottommask' => zget_option( 'def_bottom_style', 'general_options', false, 'none' ),
				'bottommask_bg' => zget_option( 'def_bottom_style_bg', 'general_options', false, '' ),
				'bottom_mask_bg_image' => zget_option( 'bottom_mask_bg_image', 'general_options', false, '' ),
				'bottom_mask_bg_height' => zget_option( 'bottom_mask_bg_height', 'general_options', false, '100' ),
				'bg_source' => '',
				'is_element' => false,
				'inherit_head_pad' => true,
				'subheader_alignment' => $def_subheader_alignment,
				'subheader_textcolor' => $def_subheader_textcolor,
				'title_tag' => 'h2',
				'subtitle_tag' => 'h4',
				'is_pb_element' => false
			);

			$saved_headers = zget_option( 'header_generator', 'unlimited_header_options', false, array() );

			// Combine defaults with the options saved in post meta
			if ( is_singular() )
			{
				// if ( is_singular() || is_home() || is_shop() ) {
				$post_defaults = array();
				$title_bar_layout = get_post_meta( $id, 'zn_zn_disable_subheader', true );

				//@wpk: empty means Default - Set from theme options
				if ( empty( $title_bar_layout ) )
				{
					// "no" means show subheader
					if ( 'no' == ( $state = zget_option( 'zn_disable_subheader', 'general_options' ) ) )
					{
						$post_defaults = array(
							'layout' => $state,
							'subtitle' => get_post_meta( $id, 'zn_page_subtitle', true ),
						);
						$saved_title = get_post_meta( $id, 'zn_page_title', true );
						if ( !empty( $saved_title ) )
						{
							$post_defaults[ 'title' ] = $saved_title;
						}
					}
				}
				else
				{
					$post_defaults = array(
						'layout' => $title_bar_layout,
						'subtitle' => get_post_meta( $id, 'zn_page_subtitle', true ),
					);
					$saved_title = get_post_meta( $id, 'zn_page_title', true );
					if ( !empty( $saved_title ) )
					{
						$post_defaults[ 'title' ] = $saved_title;
					}
				}

				// Sub-header style
				$zn_subheader_style = get_post_meta( $id, 'zn_subheader_style', true );
				if ( !empty( $zn_subheader_style ) )
				{
					$post_defaults[ 'headerClass' ] = 'uh_' . $zn_subheader_style;
				}

				// Get Subheader settings from Unlimited Subheader style
				foreach ( $saved_headers as $header )
				{
					if ( isset ( $header[ 'uh_style_name' ] ) && !empty ( $header[ 'uh_style_name' ] ) )
					{
						$header_name = strtolower( str_replace( ' ', '_', $header[ 'uh_style_name' ] ) );
						if ( $zn_subheader_style == $header_name )
						{
							// Bottom Mask
							$defaults[ 'bottommask' ] = $header[ 'uh_bottom_style' ];
							// Text Color
							if ( isset( $header[ 'uh_textcolor' ] ) )
							{
								$defaults[ 'subheader_textcolor' ] = $header[ 'uh_textcolor' ];
							}
						}
					}
				}

				$defaults = wp_parse_args( $post_defaults, $defaults );
			}
			elseif ( is_tax() || is_category() )
			{
				global $wp_query;
				$cat = $wp_query->get_queried_object();
				if ( $cat && isset( $cat->term_id ) )
				{
					$id = $cat->term_id;
					$ch = get_option( 'wpk_zn_select_custom_header_' . $id, false );
					if ( !empty( $ch ) )
					{

						if ( 'zn_def_header_style' != $ch )
						{
							$defaults[ 'headerClass' ] = 'uh_' . $ch;
						}

						// Get Subheader settings from Unlimited Subheader style
						foreach ( $saved_headers as $header )
						{
							if ( isset ( $header[ 'uh_style_name' ] ) && !empty ( $header[ 'uh_style_name' ] ) )
							{
								$header_name = strtolower( str_replace( ' ', '_', $header[ 'uh_style_name' ] ) );
								if ( $ch == $header_name )
								{
									// Bottom Mask
									$defaults[ 'bottommask' ] = $header[ 'uh_bottom_style' ];
									// Text Color
									if ( isset( $header[ 'uh_textcolor' ] ) )
									{
										$defaults[ 'subheader_textcolor' ] = $header[ 'uh_textcolor' ];
									}
								}
							}
						}

					}
				}
			}
			else
			{
				// Check if we have a custom header for the blog
				if ( is_home() )
				{
					$blog_header = zget_option( 'blog_sub_header', 'blog_options', false, 'zn_def_header_style' );
					if ( $blog_header !== 'zn_def_header_style' )
					{
						$defaults[ 'headerClass' ] = 'uh_' . $blog_header;
					}
				}
				elseif ( function_exists( 'is_shop' ) && is_shop() )
				{
					$id = (int)get_option('woocommerce_shop_page_id');
					// $post_defaults = array();
					$zn_subheader_style = get_post_meta( $id, 'zn_subheader_style', true );
					if ( !empty( $zn_subheader_style ) )
					{
						$defaults[ 'headerClass' ] = 'uh_' . $zn_subheader_style;
					}

					// Get Subheader settings from Unlimited Subheader style
					foreach ( $saved_headers as $header )
					{
						if ( isset ( $header[ 'uh_style_name' ] ) && !empty ( $header[ 'uh_style_name' ] ) )
						{
							$header_name = strtolower( str_replace( ' ', '_', $header[ 'uh_style_name' ] ) );
							if ( $zn_subheader_style == $header_name )
							{
								// Bottom Mask
								$defaults[ 'bottommask' ] = $header[ 'uh_bottom_style' ];
								// Text Color
								if ( isset( $header[ 'uh_textcolor' ] ) )
								{
									$defaults[ 'subheader_textcolor' ] = $header[ 'uh_textcolor' ];
								}
							}
						}
					}
				}
			}
			// $args = array_merge( $args, $defaults );
			$args = wp_parse_args( $args, $defaults );
			$args = apply_filters( 'zn_sub_header', $args );

			//#! Remove title and subtitle on 404 pages
			//#! see: hogash/kallyas/issues/2041
			if( is_404() ){
				$args['show_subtitle'] = false;
				$args['title'] = '';
			}

			// If the subheader shouldn't be shown
			if ( $args[ 'layout' ] == 'yes' || ( $args[ 'layout' ] == 'yespb' && ! $args[ 'is_pb_element' ] ) )
			{
				return;
			}

			// Breadcrumb / Date defaults
			$args_def_header_bread = $args[ 'def_header_bread' ] != '' ? $args[ 'def_header_bread' ] : $default_bread;
			$args_def_header_date = $args[ 'def_header_date' ] != '' ? $args[ 'def_header_date' ] : $default_date;
			// Check for Breadcrumbs or Date
			$br_date = $args_def_header_bread || $args_def_header_date;

			// Compose Classes array
			$extra_classes = array();
			$bottom_mask = $args[ 'bottommask' ];
			if( 'none' != $bottom_mask) {
				$extra_classes[] = 'maskcontainer--' . $bottom_mask;
			}


			$is_element = $args[ 'is_element' ];
			if ( $is_element )
			{
				$extra_classes[] = 'page-subheader--custom';
			}
			else
			{
				$extra_classes[] = 'page-subheader--auto';
			}

			// Inherit heading & padding from Unlimited Subheader styles
			// Enabled by default for autogenerated pages and via option in Custom Subheader Element
			$inherit_head_pad = $args[ 'inherit_head_pad' ];
			if ( $inherit_head_pad )
			{
				$extra_classes[] = 'page-subheader--inherit-hp';
			}

			$extra_classes[] = $args[ 'headerClass' ];
			$extra_classes[] = $args[ 'extra_css_class' ];

			// Get Site Header's Position (relative | absolute)
			$header_pos = 'psubhead-stheader--absolute';
			$headerLayoutStyle = zn_get_header_layout();
			if ( zget_option( 'head_position', 'general_options', false, '1' ) != 1 )
			{
				if ( $headerLayoutStyle != 'style7' )
				{
					$header_pos = 'psubhead-stheader--relative';
				}
			}
			$extra_classes[] = $header_pos;

			// Subheader Alignment
			if ( !$br_date )
			{
				$extra_classes[] = 'sh-titles--' . ( $args[ 'subheader_alignment' ] != '' ? $args[ 'subheader_alignment' ] : $def_subheader_alignment );
			}

			// Subheader Text color scheme
			$extra_classes[] = 'sh-tcolor--' . ( $args[ 'subheader_textcolor' ] != '' ? $args[ 'subheader_textcolor' ] : $def_subheader_textcolor );

			// Get title/subtitle's tag: used in template below
			$title_heading = apply_filters( 'zn_subheader_title_tag', $args[ 'title_tag' ] );
			$subtitle_tag = apply_filters( 'zn_subheader_subtitle_tag', $args[ 'subtitle_tag' ] );

			// Get markup
			include( locate_template( 'components/theme-subheader/subheader-default.php' ) );
		}

		/**
		 * Display the custom bottom mask markup
		 *
		 * @param  [type] $bm The mask ID
		 *
		 * @return [type]     HTML Markup to be used as mask
		 */
		public static function zn_bottommask_markup( $bm, $bgcolor = false ) {}

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
		 * Schema.org additions
		 * @param 	string 	Type of the element
		 * @return  string  HTML Attribute
		 */

		public static function zn_schema_markup($type, $echo = false) {

			if (empty($type)) return false;

			$disable = apply_filters('zn_schema_markup_disable', false);

			if($disable == true) return false;

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
			$attr = apply_filters('zn_schema_markup_attributes', $attr);

			foreach ($attr as $key => $value) {
				$attributes.= $key . '="' . $value . '" ';
			}

			if ($echo) {
				echo '' . $attributes;
			}
			return $attributes;
		}


		/**
		 * Display the page header for Documentation pages
		 * Will be removed in 4.1
		 *
		 * @internal
		 * @deprecated 4.0.11
		 */
		public static function zn_get_documentation_header(){}

		/**
		 * Display the site header
		 * Will be removed in 4.1
		 *
		 * @since 4.0
		 * @deprecated 4.0.10
		 */
		public static function displaySiteHeader(){}
	}
}
