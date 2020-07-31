<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Blog Archive
 Description: Create and display the current post content
 Class: TH_BlogArchive
 Category: content
 Level: 3
 Keywords: category, news
*/

/**
 * Class TH_BlogArchive
 *
 * Create and display the current page content
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_BlogArchive extends ZnElements
{
	public static function getName(){
		return __( "Blog archive", 'zn_framework' );
	}

	/**
	 * Load dependant resources
	 */
	function scripts(){
		/*
		 * Load resources in footer
		 */
		wp_enqueue_script('isotope');

	}


	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{

		global $zn_config, $query_string, $wp_query, $paged;

		$options = $this->data['options'];

		// Get the proper page - this resolves the pagination on static frontpage
		if( get_query_var('paged') ){ $paged = get_query_var('paged'); }
		elseif( get_query_var('page') ){ $paged = get_query_var('page'); }
		else{ $paged = 1; }

		$zn_config['blog_style'] = $this->opt( 'blog_style', '' );

		$zn_config['blog_layout'] = $this->opt( 'blog_layout', 'def_classic' );
		$zn_config['blog_columns'] = $this->opt( 'blog_columns', '1' );

		$category = $this->opt('category') ? $this->opt('category') : '';
		$post_type = $this->opt('post_type') ? $this->opt('post_type') : '';
		$count = $this->opt('count')  ? $this->opt('count') : '4';

		$args = array(
			'posts_per_page' => ( int )$count,
			'post_status' => 'publish',
			'paged' => $paged
		);

		if( !empty( $category ) ){
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => $category
				),
			);
		}

		if ( ! empty( $post_type ) ) {
			$args['post_type'] = $post_type;
		}

		$classes=array();
		$classes[] = $this->data['uid'];
		$classes[] = zn_get_element_classes($options);

		$attributes = zn_get_element_attributes($options);

		// PERFORM THE QUERY
		query_posts( $args );

		echo '<div class="zn_blog_archive_element '.implode(' ', $classes).'" '.$attributes.'>';

			if ( $zn_config['blog_layout'] == 'cols' && in_array( $zn_config['blog_columns'], array(1, 2, 3, 4, 5, 6) ) ) {
				get_template_part( 'blog', 'columns' );
			}
			elseif ( $zn_config['blog_layout'] == 'def_classic' || $zn_config['blog_layout'] == 'def_modern' ) {
				get_template_part( 'blog', 'default' );
			}

		echo '</div>';
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_query();
	}

	// TODO : Uncomment this if JS errors appears because of clients shortcodes/plugins
	// /**
	//  * This method is used to display the output of the element.
	//  * @return void
	//  */
	// function element_edit()
	// {
	//     echo '<div class="zn-pb-notification">This element will be rendered only in View Page Mode and not in PageBuilder Edit Mode.</div>';
	// }

	function options() {

		$args = array(
			'type' => 'post'
		);

		$post_categories = get_categories($args);

		$option_post_cat = array();
		$option_post_types = array();
		$post_types = get_post_types( array(
			'public' => true,
			'publicly_queryable' => true
		), 'objects' );


		// Create a list of post types
		foreach ( $post_types as $key => $post_type_object ) {

			// Exclude post types list
			$exclude_post_type = array(
				'zn_layout',
				'znpb_template_mngr',
				'attachment'
			);

			// Only add options if the post type is not in the exclude list
			if( ! in_array( $key, $exclude_post_type ) ) {
				$option_post_types[$key] = $post_type_object->label;
			}

		}

		foreach ($post_categories as $category) {
			$option_post_cat[$category->cat_ID] = $category->cat_name;
		}

		$uid = $this->data['uid'];

		// Maintain backwards compatibility
		if(isset($this->data['options']['blog_columns'])){
			$std_blog_layout = $this->data['options']['blog_columns'] == 1 ? 'def_classic' : 'cols';
		} else {
			$std_blog_layout = 'def_classic';
		}

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						"name"        => __( "Blog Layout", 'zn_framework' ),
						"description" => __( "Select the blog layout.", 'zn_framework' ),
						"id"          => "blog_layout",
						"std"         => $std_blog_layout,
						"options"     => array(
					        array(
					            'value' => 'def_classic',
					            'name'  => __( 'Generic Blog - Classic Style', 'zn_framework' ),
					            'image' => THEME_BASE_URI .'/images/admin/blog_layouts/def.gif'
					        ),
					        array(
					            'value' => 'def_modern',
					            'name'  => __( 'Generic Blog - Modern Style', 'zn_framework' ),
					            'image' => THEME_BASE_URI .'/images/admin/blog_layouts/def-modern.gif'
					        ),
					        array(
					            'value' => 'cols',
					            'name'  => __( 'Multi-columns blog', 'zn_framework' ),
					            'image' => THEME_BASE_URI .'/images/admin/blog_layouts/multicols.gif'
					        ),
					    ),
					    "type"        => "radio_image",
					    "class"        => "ri-hover-line ri-3",
					),

					array(
						'id'          => 'blog_columns',
						'name'        => 'Blog columns',
						'description' => 'Select the number of columns to use',
						'type'        => 'select',
						'std'		  => '1',
						'options'        => array(
							'1' => '1 column',
							'2' => '2 column',
							'3' => '3 column',
							'4' => '4 column',
							'5' => '5 column',
							'6' => '6 column',
						),
						'dependency'   => array( "element" => 'blog_layout', 'value' => array( 'cols' ) ),
					),
					array(
						'id'          => 'post_type',
						'name'        => 'Post type',
						'description' => 'Select your desired post types to be displayed.',
						'type'        => 'select',
						'options'	  => $option_post_types
					),
					array(
						'id'          => 'category',
						'name'        => 'Categories',
						'description' => 'Select your desired categories for post items to be displayed.',
						'type'        => 'select',
						'options'	  => $option_post_cat,
						'multiple'	  => true,
						'dependency'   => array( "element" => 'post_type', 'value' => array( 'post' ) ),
					),
					array(
						'id'          => 'count',
						'name'        => 'Number of items per page',
						'description' => 'Please choose the desired number of items that will be shown on a page. <br><br><strong>Note:</strong> It\'s likely to get 404 errors when using the pagination in the case of using this element on the page you also use as your "posts page" in WP Dashboard > Settings > Reading. To avoid this, make sure to set the option "Blog pages show at most" not less than this option\'s value, or this value not higher than the one in the WP Settings.',
						'type'        => 'slider',
						'std'		  => '4',
						'helpers'	  => array(
							'min' => '1',
							'max' => '50',
							'step' => '1'
						),
					),
					array(
						'id'          => 'blog_style',
						'name'        => 'Blog color scheme',
						'description' => 'Select the style of this blog page',
						'type'        => 'select',
						'std'		  => '',
						'options'        => array(
							'' => 'Inherit from Blog Options (Kallyas options)',
							'light' => 'Light',
							'dark' => 'Dark'
						),
					),
				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#2dkIHxjdCG4') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/blog-archive/') ),
				'copy'    => $uid,
				'general' => true,
			)),
		);
		return $options;
	}

}
