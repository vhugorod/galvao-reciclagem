<?php if(! defined('ABSPATH')){ return; }
/*
Name: Latest Posts 4
Description: Create and display a Latest Posts 4 element
Class: TH_LatestPosts4
Category: content
Level: 3
Keywords: blog, news, article
*/

/**
 * Class TH_LatestPosts4
 *
 * Create and display a Latest Posts 4 element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    3.8.0
 */
class TH_LatestPosts4 extends ZnElements
{
	public static function getName(){
		return __( "Latest Posts 4", 'zn_framework' );
	}

	function css(){
		$uid = $this->data['uid'];
		$lp_title_font = $this->opt('lp_title_font', 54);
		$css = '';

		if( $lp_title_font != 54 ){
			$css =  ".{$uid}.latest_posts--4.kl-style-2 .latest_posts-elm-title { font-size: {$lp_title_font}px; }";
		}

		return $css;
	}

	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];

		$placement = (isset($options['lp_placement']) ? $options['lp_placement'] : 'normal');
		$category = $this->opt( 'lp_blog_categories' );

		// required inside /inc/...
		global $post;

		$elm_classes=array();
		$elm_classes[] = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($options);

		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$elm_classes[] = 'latestposts4--'.$color_scheme;
		$elm_classes[] = 'element-scheme--'.$color_scheme;

		$elm_classes[] = $style = (isset($options['lp_style_select']) ? $options['lp_style_select'] : 'default-style');

		$postTitle = (isset($options['lp_title']) ? $options['lp_title'] : '');

		// HOW MANY POSTS
		$num_posts = 5;
		if ('default-style' == $style ) {
			$num_posts = (isset($options['lp_num_posts']) ? intval($options['lp_num_posts']) : $num_posts);
		}

		$GLOBALS['lp_info'] = array(
			'options' => $options,
			'post' => $post,
			'blog_category' => $category,
			'num_posts' => $num_posts,
			'postTitle' => $postTitle,
			'placement' => $placement,
		);

		$styleClass = 'default-style';
		if('default-style' != $style){
			$styleClass .= ' '.$style;
		}


		?>
			<div class="latest_posts latest_posts--style4 latest_posts--4 <?php echo implode(' ', $elm_classes); ?> " <?php echo zn_get_element_attributes($options); ?>>
				<?php
					$path = dirname(__FILE__). '/inc/'.$style.'.php';
					if(is_file($path)){
						include($path);
					}
				?>
			</div>
			<!-- end // latest posts style 2 -->
	<?php
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(
					array (
						"name"        => __( "Style", 'zn_framework' ),
						"description" => __( "Please select the style you want to use.", 'zn_framework' ),
						"id"          => "lp_style_select",
						"std"         => "default-style",
						"options"     => array (
							'default-style' => __( 'Default Style', 'zn_framework' ),
							'kl-style-2'    => __( 'Style 2 (since v4.0)', 'zn_framework' ),
						),
						"type"        => "select",
					),

					array(
						'id'          => 'element_scheme',
						'name'        => 'Element Color Scheme',
						'description' => 'Select the color scheme of this element',
						'type'        => 'select',
						'std'         => '',
						'options'        => array(
							'' => 'Inherit from Kallyas options > Color Options [Requires refresh]',
							'light' => 'Light (default)',
							'dark' => 'Dark'
						),
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'latestposts4--',
								),
								array(
									'type'      => 'class',
									'css_class' => '.'.$uid,
									'val_prepend'  => 'element-scheme--',
								),
							)
						)
					),

					array (
						"name"        => __( "Boxes placement", 'zn_framework' ),
						"description" => __( "Please select the boxes placement.", 'zn_framework' ),
						"id"          => "lp_placement",
						"std"         => "normal",
						"options"     => array (
							'normal' => __( 'Normal (title left-top)', 'zn_framework' ),
							'flipped'    => __( 'Flipped (title box, bottom-right)', 'zn_framework' ),
						),
						"type"        => "select",
						"dependency"  => array('element' => 'lp_style_select', 'value' => array('kl-style-2')),
					),
					array (
						"name"        => __( "Title font size", 'zn_framework' ),
						"description" => __( "Please select the desired title font size.", 'zn_framework' ),
						"id"          => "lp_title_font",
						"std"         => "54",
						'class'		  => 'zn_full',
						'helpers'	  => array(
							'min' => '0',
							'max' => '400',
							'step' => '1'
						),
						'live' => array(
							'type'		=> 'css',
							'css_class' => '.'.$this->data['uid'].'.latest_posts--4.kl-style-2 .latest_posts-elm-title',
							'css_rule'	=> 'font-size',
							'unit'		=> 'px'
						),
						"type"        => "slider",
						"dependency"  => array('element' => 'lp_style_select', 'value' => array('kl-style-2')),
					),
					array (
						"name"        => __( "Title", 'zn_framework' ),
						"description" => __( "Enter a title for your Latest Posts element", 'zn_framework' ),
						"id"          => "lp_title",
						"std"         => "",
						"type"        => "text",
					),
					array (
						"name"        => __( "Number of posts", 'zn_framework' ),
						"description" => __( "Enter the number of posts that you want to show", 'zn_framework' ),
						"id"          => "lp_num_posts",
						"std"         => "3",
						"type"        => "text",
						"dependency"  => array('element' => 'lp_style_select', 'value' => array('default-style')),
					),

					array (
						"name"        => __( "Show excerpt?", 'zn_framework' ),
						"description" => __( "Please select if you want to display the article's excerpt (short description).", 'zn_framework' ),
						"id"          => "lp_def_excerpt",
						"std"         => "",
						"value"       => "1",
						"type"        => "toggle2",
						"dependency"  => array('element' => 'lp_style_select', 'value' => array('default-style')),
					),
					array (
						"name"        => __( "Blog Category", 'zn_framework' ),
						"description" => __( "Select the blog category to show items", 'zn_framework' ),
						"id"          => "lp_blog_categories",
						"multiple"    => true,
						"std"         => "0",
						"type"        => "select",
						"options"     => WpkZn::getBlogCategories()
					),
					array (
						"name"        => __( "Order type", 'zn_framework' ),
						"description" => __( "Please select the order to apply to the results.", 'zn_framework' ),
						"id"          => "lp_orderby_select",
						"std"         => "date",
						"options"     => array (
							'date' => __( 'Order by date', 'zn_framework' ),
							'rand'    => __( 'Random order', 'zn_framework' ),
							'comment_count' => __( 'Order by popularity', 'zn_framework' ),
							'title'    => __( 'Order by title', 'zn_framework' ),
						),
						"type"        => "select",
					),
					array (
						"name"        => __( "Sorting type", 'zn_framework' ),
						"description" => __( "Please select how to sort the results.", 'zn_framework' ),
						"id"          => "lp_sort_type_select",
						"std"         => "DESC",
						"options"     => array (
							'ASC' => __( 'Ascending', 'zn_framework' ),
							'DESC'    => __( 'Descending', 'zn_framework' ),
						),
						"type"        => "select",
					),

					array (
						"name"        => __( "Use custom image size", 'zn_framework' ),
						"description" => __( "This option will allow you to specify the desired image sizes.", 'zn_framework' ),
						"id"          => "cusotm_image_sizes",
						"std"         => "no",
						"type"        => "toggle2",
						"value"        => "yes",
					),

					array (
						"name"        => __( "Images Size", 'zn_framework' ),
						"description" => __( "By default, images have a default size based on the element style. Using this option you can set your desired image sizes. You can set the values to 0 in order to disable resizing.", 'zn_framework' ),
						"id"          => "img_sizes",
						"type"        => "image_size",
						"std"        => '',
						"dependency"  => array( 'element' => 'cusotm_image_sizes', 'value' => array('yes') ),
					),

					array (
						"name"        => __( "Main Image Size (style 2)", 'zn_framework' ),
						"description" => __( "By default, images have a default size based on the element style. Using this option you can set your desired image sizes. You can set the values to 0 in order to disable resizing.", 'zn_framework' ),
						"id"          => "main_img_size",
						"type"        => "image_size",
						"std"        => '',
						"dependency"  => array(
							array( 'element' => 'cusotm_image_sizes', 'value' => array('yes') ),
							array( 'element' => 'lp_style_select', 'value' => array('kl-style-2') ),
						),
					),

				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#gFcL4BXQpAs') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/latest-posts/') ),
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;

	}
}
