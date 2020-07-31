<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Latest Posts Carousel
 Description: Display latest post from specific categories in a carousel layout.
 Class: TH_LatestPostsCarousel
 Category: content
 Level: 3
 Scripts: true
 Keywords: blog, news, article
*/

/**
 * Class TH_LatestPostsCarousel
 *
 * Create and display a Latest Posts Carousel element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_LatestPostsCarousel extends ZnElements
{
	public static function getName(){
		return __( "Latest Posts Carousel", 'zn_framework' );
	}

	/**
	 * Load dependant resources
	 */
	function scripts(){
		wp_enqueue_script( 'slick', THEME_BASE_URI . '/addons/slick/slick.min.js', array ( 'jquery' ), ZN_FW_VERSION, true );
	}

	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{
		global $post;

		$options = $this->data['options'];

		$elm_classes[] = $uid = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($options);
		$elm_classes[] = 'latest-posts-carousel latest-posts-crs';

		$numPosts   = isset( $options['lpc_num_posts'] ) ? $options['lpc_num_posts'] : 10; // how many posts
		$categories = isset( $options['lpc_categories'] ) ? $options['lpc_categories'] : get_option('default_category');
		$title = isset( $options['lpc_title'] ) ? $options['lpc_title'] : __('Latest Posts', 'zn_framework');

		// Slick Attributes
		$slick_attributes = array(
			"infinite" => true,
			"slidesToShow" => 3,
			"slidesToScroll" => 1,
			"autoplay" => false,
			"appendArrows" => '.'. $uid . ' .latest-posts-crs-controls',
			"arrows" => true,
			"responsive" => array(
				array(
					"breakpoint" => 1199,
					"settings" => array(
						"slidesToShow" => 3
					)
				),
				array(
					"breakpoint" => 767,
					"settings" => array(
						"slidesToShow" => 2
					)
				),
				array(
					"breakpoint" => 520,
					"settings" => array(
						"slidesToShow" => 1
					)
				)
			)
		);

		// Configure the query
		$queryArgs = array(
			'post_type'      => 'post',
			'posts_per_page' => $numPosts,
			'category__in' => $categories
		);

		// @since v4.1.6
		// Exclude the current viewed post from the query
		if(is_single() && ('post' == get_post_type())){
			$queryArgs['post__not_in'] = array( get_the_ID() );
		}

		$theQuery = new WP_Query($queryArgs);

		if ( $theQuery->have_posts() )
		{
			?>
				<div class=" <?php echo implode(' ', $elm_classes); ?>" <?php zn_the_element_attributes($options); ?>>

					<div class="row">
						<div class="col-sm-12">
							<div class="controls latest-posts-crs-controls">
								<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) );?>" class="latest-posts-grid kw-gridSymbol"></a>
							</div>
							<h3 class="m_title m_title_ext text-custom latest-posts-crs-elmtitle" <?php echo WpkPageHelper::zn_schema_markup('title'); ?>><?php echo $title;?></h3>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<div class="posts-carousel latest-posts-crs-list-wrapper">
								<ul class="latest-posts-crs-list js-slick" data-slick='<?php echo json_encode($slick_attributes) ?>'>
									<?php
										// Start the loop
										while ( $theQuery->have_posts() ) {
											$theQuery->the_post();
											// post categories
											$categories = get_the_category();
											$separator = ', ';
											$catList = '';
											if($categories){
												foreach($categories as $category) {
													$catList .= '<a href="'.get_category_link( $category->term_id ).'" title="' .
														esc_attr( sprintf( __( "View all posts in %s", 'zn_framework'),
															$category->name ) ) . '">'.
														$category->cat_name.'</a>'.$separator;
												}
												$catList = trim($catList, $separator);
											}
											$permalink = get_the_permalink();
											$usePostFirstImage = ( zget_option( 'zn_use_first_image', 'blog_options', false, 'yes' ) == 'yes' );
											// Create the featured image html
											if ( has_post_thumbnail( $post->ID ) ) {
												$thumb   = get_post_thumbnail_id( $post->ID );
												$f_image = wp_get_attachment_url( $thumb );
												$alt = get_post_meta($thumb, '_wp_attachment_image_alt', true);
												$title = get_the_title($thumb);

											}
											elseif( $usePostFirstImage ){
												$f_image = echo_first_image();
												$alt   = ZngetImageAltFromUrl( $f_image );
												$title = ZngetImageTitleFromUrl( $f_image );
											}
											?>
											<li class="post latest-posts-crs-post">
												<a href="<?php echo esc_url( $permalink );?>" class="hoverBorder plus latest-posts-crs-link text-custom-parent-hov">
													<?php 
													// Feature image
													if ( ! empty ( $f_image ) ) {
														// Make the "alt" attribute available in the front-end
														$latest_posts_sizes = apply_filters('zn_latest_posts_element_img_sizes', array('width'=> 420, 'height'=> 240, 'crop' => true));
														$image = vt_resize( '', $f_image, $latest_posts_sizes['width'], $latest_posts_sizes['height'], $latest_posts_sizes['crop'] );
														echo '<img class="latest-posts-crs-img" src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $alt . '" title="' . $title . '"/>';
													}
													?>
													<span class="latest-posts-crs-readon u-trans-all-2s text-custom-child kl-main-bgcolor"><?php _e('Read more +', 'zn_framework');?></span>
												</a>
												<em class="latest-posts-crs-details">
													<?php the_date();?>
													<?php _e('By', 'zn_framework');?>
													<?php the_author();?>
													<?php _e('in', 'zn_framework');?>
													<?php echo $catList;?>
												</em>
												<h3 class="m_title m_title_ext text-custom latest-posts-crs-title" <?php echo WpkPageHelper::zn_schema_markup('title'); ?>><a class="latest-posts-crs-title-link" href="<?php echo esc_url( $permalink );?>"><?php the_title();?></a></h3>
											</li>
										<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>

			<?php
			wp_reset_postdata();
		}
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
						"name"        => __( "Title", 'zn_framework' ),
						"description" => __( "Enter a title for the latest posts carousel", 'zn_framework' ),
						"id"          => "lpc_title",
						"std"         => "",
						"type"        => "text",
					),
					array (
						"name"        => __( "Posts Category", 'zn_framework' ),
						"description" => __( "Select the category to show items", 'zn_framework' ),
						"id"          => "lpc_categories",
						"multiple"    => true,
						"std"         => 0,
						"type"        => "select",
						"options"     => WpkZn::getBlogCategories(),
					),
					array (
						"name"        => __( "Number of items", 'zn_framework' ),
						"description" => __( "Please enter how many items you want to load.", 'zn_framework' ),
						"id"          => "lpc_num_posts",
						"std"         => 10,
						"type"        => "text"
					),
				),
			),



			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#gFcL4BXQpAs') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/latest-posts-carousel/') ),
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;

	}
}
