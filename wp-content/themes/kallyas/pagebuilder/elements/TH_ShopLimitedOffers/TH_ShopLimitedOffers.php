<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Shop Limited Offers
 Description: Create and display a Shop Limited Offers element
 Class: TH_ShopLimitedOffers
 Category: content
 Level: 3
 Scripts: true
 Dependency_class: WooCommerce
 Keywords: carousel, discount, offers, shop, store
*/
/**
 * Class TH_ShopLimitedOffers
 *
 * Create and display a Shop Limited Offers element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_ShopLimitedOffers extends ZnElements
{

	public static function getName(){
		return __( "Shop Limited Offers", 'zn_framework' );
	}

	/**
	 * Load dependant resources
	 */
	function scripts(){
		wp_enqueue_script( 'slick', THEME_BASE_URI . '/addons/slick/slick.min.js', array ( 'jquery' ), ZN_FW_VERSION, true );
	}

	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];

		if( empty( $options['woo_categories'] ) ) { return; }

		global $woocommerce;

		if (!isset($woocommerce) || empty( $woocommerce ) ) {
			return;
		}

		$elm_classes=array();
		$elm_classes[] = $uid = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes($options);

		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$elm_classes[] = 'slo--'.$color_scheme;

		?>
		<div class="elm-shoplimited <?php echo implode(' ', $elm_classes); ?>" <?php echo zn_get_element_attributes($options); ?>>
			<?php

			if ( ! empty ( $options['woo_lo_title'] ) ) {
				echo '<h3 class="m_title m_title_ext text-custom elm-shoplimited-title" '.WpkPageHelper::zn_schema_markup('title').'>' . $options['woo_lo_title'] . '</h3>';
			}

			// Get products on sale
			$product_ids_on_sale = wc_get_product_ids_on_sale();
			if(empty($product_ids_on_sale)){
				delete_transient( 'wc_products_onsale' );
				$product_ids_on_sale = wc_get_product_ids_on_sale();
			}


			$meta_query   = array ();
			$meta_query[] = $woocommerce->query->visibility_meta_query();
			$meta_query[] = $woocommerce->query->stock_status_meta_query();

			if ( empty ( $options['woo_categories'] ) ) {
				$options['woo_categories'] = '';
			}

			$query_args = array (
				'posts_per_page' => $options['prods_per_page'],
				'tax_query'      => array (
					array (
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $options['woo_categories']
					)
				),
				'no_found_rows'  => 1,
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'orderby'        => 'date',
				'order'          => 'ASC',
				'meta_query'     => $meta_query,
				'post__in'       => $product_ids_on_sale
			);

			$r = new WP_Query( $query_args );

			$slick_attibutes = ' data-slick=\''.json_encode(
				array(
					"infinite" => true,
					"slidesToShow" => 4,
					"slidesToScroll" => 1,
					"autoplay" => $this->opt('sl_autoplay',1) == 1 ? true : false,
					"autoplaySpeed" => $this->opt('sl_timeout', 6000),
					"appendArrows" => '.'.$uid.' .znSlickNav',
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
							"breakpoint" => 480,
							"settings" => array(
								"slidesToShow" => 1
							)
						)
					)
				)
			).'\'';
			$post_count_class = ( $r->post_count == 1 ) ? 'lofc--single' : '';
			?>
			<div class="woocommerce limited-offers-carousel lt-offers <?php echo esc_attr( $post_count_class ); ?> fixclear ">
				<ul class="products zn_limited_offers lt-offers-carousel js-slick" <?php echo $slick_attibutes; ?>>
					<?php

					if ( $r->have_posts() ) {
						while ( $r->have_posts() ) {
							$r->the_post();
							global $product;

							// bail
							if ( ! isset( $product ) || empty( $product ) || ! is_object( $product ) ) {
								continue;
							}

							// $product->product_type;
							if ( $product->get_type() == 'variable' ) {

								$old_price = $product->min_variation_regular_price;
								$new_price = $product->min_variation_price;
							}
							else {

								$old_price = $product->get_regular_price();
								$new_price = $product->get_sale_price();
							}

							$reduced = 0;
							if ( $old_price != 0 ) {
								$reduced = round( 100 - ( $new_price * 100 ) / $old_price, 0 );
							}

							echo '<li class="product">';
								echo '<div class="product-list-item lt-offers-item" data-discount="' . $reduced . '%">';
								echo '<a href="'.get_permalink().'" class="lt-offers-item-link">';
									do_action( 'woocommerce_before_shop_loop_item_title' );
										echo '<h6 class="price lt-offers-price">' . $product->get_price_html() . '</h6>';
									echo '</div>'; // This is necessary to close the div coming from before shop loop item title
								echo '</a>';
								echo '</div>';
							echo '</li>';
						}
					}
					wp_reset_query();
					?>

				</ul>

				<div class="znSlickNav"></div>
			</div>
			<!-- end limited offers carousel -->
		</div>
		<?php
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		/*
		 * Get Shop categories
		 */
		$categories = WpkZn::getShopCategories();

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						"name"        => __( "Element Title", 'zn_framework' ),
						"description" => __( "Enter a title for this element", 'zn_framework' ),
						"id"          => "woo_lo_title",
						"std"         => "",
						"type"        => "text",
					),
					array (
						"name"        => __( "Shop Category", 'zn_framework' ),
						"description" => __( "Select the shop category to show items", 'zn_framework' ),
						"id"          => "woo_categories",
						"multiple"    => true,
						"std"         => "0",
						"type"        => "select",
						"options"     => $categories
					),
					array (
						"name"        => __( "Number of products", 'zn_framework' ),
						"description" => __( "Please enter how many products you want to load.", 'zn_framework' ),
						"id"          => "prods_per_page",
						"std"         => "6",
						"type"        => "text"
					),

					array (
						"name"        => __( "Autoplay carousel?", 'zn_framework' ),
						"description" => __( "Does the carousel autoplay itself?", 'zn_framework' ),
						"id"          => "sl_autoplay",
						"std"         => "1",
						"value"         => "1",
						"type"        => "toggle2"
					),
					array (
						"name"        => __( "Timeout duration", 'zn_framework' ),
						"description" => __( "The amount of milliseconds the carousel will pause", 'zn_framework' ),
						"id"          => "sl_timeout",
						"std"         => "6000",
						"type"        => "text"
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
									'val_prepend'  => 'slo--',
								),
							)
						)
					),
				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#H06NN5lC_Ic') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/shop-limited-offers/') ),
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
