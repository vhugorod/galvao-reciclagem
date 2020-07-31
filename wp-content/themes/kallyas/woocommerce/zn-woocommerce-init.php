<?php if(! defined('ABSPATH')){ return; }

/** Add specific WOOCOMMERCE OPTIONS **/
require( THEME_BASE.'/woocommerce/woo_options.php' );


// Legacy functions for WC 2.x
if( version_compare(WC_VERSION, '3.0.0', '<') ){
	include( THEME_BASE . '/woocommerce/zn-woocommerce-v2.php' );
}
else {
	include( THEME_BASE . '/woocommerce/zn-woocommerce-v3.php' );
}


/*--------------------------------------------------------------------------------------------------
	REMOVE UNWANTED ACTIONS
--------------------------------------------------------------------------------------------------*/
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_pagination', 'woocommerce_catalog_ordering', 20 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

//REMOVE WOOCOMMERCE ACTIONS
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10); // REMOVE SHPO SIDEBAR
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Shop Catalog mode
if ( zget_option( 'woo_catalog_mode', 'zn_woocommerce_options', false, 'no' ) == 'yes' ) {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

	// Add the read more button
	// Fixes #917
	add_action( 'woocommerce_after_shop_loop_item', 'zn_woocommerce_more_info' );
	function zn_woocommerce_more_info(){
		echo '<span class="kw-actions">';
			echo '<a class="actions-moreinfo" href="'.get_permalink().'" title="'. __( "MORE INFO", 'zn_framework' ).'">';
			if( zget_option( 'woo_prod_layout', 'zn_woocommerce_options', false, 'classic' ) == 'style2' ) {
				echo '<svg width="50px" height="24px" class="svg-moreIcon"><circle cx="12" cy="12" r="2"/><circle cx="20" cy="12" r="2"/><circle cx="28" cy="12" r="2"/></svg>';
			}
			else {
				echo __( "MORE INFO", 'zn_framework' );
			}
			echo '</a>';
		echo '</span>';
	}

}

/* Check to see if we are allowed to show the add to cart button for visitors */
$show_cart_to_visitors = zget_option( 'show_cart_to_visitors', 'zn_woocommerce_options', false, 'yes' );
if( $show_cart_to_visitors == 'no' && !is_user_logged_in() ){
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}

/* PRODUCT THUMBNAIL IN LOOP */
add_filter( 'woocommerce_product_get_image', 'zn_woocommerce_post_thumbnail_html', 98, 6 );
add_filter( 'woocommerce_placeholder_img', 'zn_woocommerce_placeholder_img', 98, 3 );

/*--------------------------------------------------------------------------------------------------
	PRODUCTS PAGE - FILTER IMAGE
--------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'zn_woocommerce_post_thumbnail_html' ) ) {

	function zn_woocommerce_post_thumbnail_html( $html, $productInstance, $size, $attr, $placeholder, $image )
	{
		global $product, $woocommerce;

		// Fix WooCommerce 3.3.0 compatibility
		if( ! in_array( $size, array( 'woocommerce_thumbnail', 'shop_catalog' ) ) ||  ! $productInstance ){
			return $html;
		}

		$img = $img2 ='';

		// Image sizes added into Kallyas options > WooCommerce
		$woo_prodLoop_image_size = zget_option( 'woo_cat_image_size', 'zn_woocommerce_options', false, array() );
		$woo_prodLoop_image_size = array_filter($woo_prodLoop_image_size);

		// Crop images on resize
		$woo_cat_image_crop = apply_filters('zn_woocommerce_imgloop_crop', true);

		/**
		 * Getting image sizes
		 */
		if ( function_exists( 'wc_get_image_size' ) ) {
			$shop_catalog_sizes = wc_get_image_size( 'shop_catalog' );
		} else {
			$shop_catalog_sizes = $woocommerce->get_image_size( 'shop_catalog' );
		}

		$width  = isset($shop_catalog_sizes['width']) ? $shop_catalog_sizes['width'] : '';
		$height = isset($shop_catalog_sizes['height']) ? $shop_catalog_sizes['height'] : '';

		/**
		 * Begin output
		 * @var string
		 */
		$output = '<span class="kw-prodimage">';

		if( ! empty( $woo_prodLoop_image_size ) ){

			$attachment_url = wp_get_attachment_url( $productInstance->get_image_id() );
			$attachment_title = get_the_title($productInstance->get_image_id());

			$width  = ( !empty( $woo_prodLoop_image_size['width'] ) ) ? (int)$woo_prodLoop_image_size['width'] : '';
			$height = ( !empty( $woo_prodLoop_image_size['height'] ) ) ? (int)$woo_prodLoop_image_size['height'] : '';

			$_img_resized = vt_resize('', $attachment_url, $width, $height, $woo_cat_image_crop);

			$img = sprintf(
				'<img src="%s" title="%s" alt="%s" class="kw-prodimage-img" %s />',
				esc_attr( $_img_resized['url'] ),
				$attachment_title,
				get_post_meta($productInstance->get_image_id(), '_wp_attachment_image_alt', true),
				image_hwstring( $width, $height )
			);
		}
		else {
			$img = wp_get_attachment_image( $productInstance->get_image_id(), 'shop_catalog', false, array('class'=>'kw-prodimage-img') );
		}

		$attachment_ids = null;

		// Add Second image, if any
		if( is_callable( array( $productInstance,'get_gallery_image_ids') ) ){
			$attachment_ids = $productInstance->get_gallery_image_ids();
		}
		// Backwards Compatibility
		elseif( is_callable( array( $productInstance,'get_gallery_attachment_ids') ) ) {
			$attachment_ids = $productInstance->get_gallery_attachment_ids();
		}

		if ( $attachment_ids && zget_option( 'zn_use_second_image', 'zn_woocommerce_options', false, 'yes' ) == 'yes' ) {

			$secondary_image_id = $attachment_ids['0'];

			if(!empty($secondary_image_id)){

				if( ! empty( $woo_prodLoop_image_size ) ){

					$secondary_image_url = wp_get_attachment_url( $secondary_image_id );
					$secondary_image_title = get_the_title($secondary_image_id);

					// Fix KAL-253
					if( ! empty( $secondary_image_url ) ){
						$_img2_resized = vt_resize('', $secondary_image_url, $width, $height, $woo_cat_image_crop);

						$img2 = sprintf(
							'<img src="%s" title="%s" alt="%s" class="kw-prodimage-img-secondary" %s />',
							esc_attr( $_img2_resized['url'] ),
							$secondary_image_title,
							get_post_meta($secondary_image_id, '_wp_attachment_image_alt', true),
							image_hwstring( $width, $height )
						);
					}

				}
				else {
					$img2 = wp_get_attachment_image( $secondary_image_id, 'shop_catalog', false, array('class'=>'kw-prodimage-img-secondary') );
				}
			}
		}


		$lazyload = zget_option( 'woo_img_lazyload', 'zn_woocommerce_options', false, 'no' ) == 'yes';

		// if lazyload enabled, do some fadin'
		if( $lazyload && $img ){
			$img = str_replace('src="', 'data-echo="', $img);
			$img = str_replace('srcset="', 'data-srcset="', $img);
			$img = str_replace('sizes="', 'data-sizes="', $img);
		}

		$output .= $img.$img2;
		$output .= '</span>';

		return $output;
	}
}


if ( ! function_exists( 'zn_woocommerce_placeholder_img' ) ) {
	function zn_woocommerce_placeholder_img($html, $size, $dimensions)
	{

		// Stop if this isn't product loop
		if( $size != 'shop_catalog' ) return $html;

		return '<span class="kw-prodimage"><img class="kw-prodimage-img kw-prodimage-placeholder" src="' . wc_placeholder_img_src() . '" alt="'. esc_attr__( 'Placeholder', 'zn_framework' ) .'"  width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '"></span>';
	}
}

// Check to see if the page has a sidebar or not
if (!function_exists('zn_check_sidebar')) {
	function zn_check_sidebar() {

		global $zn_config;
		if ( is_product() ) {
			$layout = 'woo_single_sidebar';
		}
		elseif( is_archive() ){
			$layout = 'woo_archive_sidebar';
		}
		else{
		    return false;
        }

		$zn_config['force_sidebar'] = $layout;
		$main_class = zn_get_sidebar_class($layout);

		if( strpos( $main_class , 'right_sidebar' ) !== false || strpos( $main_class , 'left_sidebar' ) !== false ) {
			$zn_config['sidebar'] = true;
		}
		else {
			$zn_config['sidebar'] = false;
		}

		// Force disabled sidebar for Style #3 pages
		if(is_single() && zget_option( 'woo_prod_page_layout', 'zn_woocommerce_options', false, 'classic' ) == 'style3' ){
			$zn_config['sidebar'] = false;
		}

		return $zn_config['sidebar'];
	}
}

// Change number or products per row to the specified number of columns in theme options
add_filter('loop_shop_columns', 'zn_woo_loop_columns' , 998);
if (!function_exists('zn_woo_loop_columns')) {
	function zn_woo_loop_columns() {

		$check_sidebar = zn_check_sidebar();
		$columns = $check_sidebar ? 3 : 4;
		$saved_columns = zget_option( 'woo_num_columns', 'zn_woocommerce_options', false, false );
		$saved_columns = $saved_columns ? $saved_columns : $columns;
		// set the number of specified columns
		return $saved_columns;
	}
}


add_action('woocommerce_before_shop_loop', 'zn_wrap_productlist_start', 90);
add_action('woocommerce_after_shop_loop', 'zn_wrap_productlist_end', 90);

if(!function_exists('zn_wrap_productlist_start')){
	function zn_wrap_productlist_start(){
		$nc = apply_filters('loop_shop_columns', zn_woo_loop_columns());
		echo '<div class="kallyas-productlist-wrapper kallyas-wc-cols--'.$nc.'">';
	}
}

if(!function_exists('zn_wrap_productlist_end')){
	function zn_wrap_productlist_end(){
		echo '</div>';
	}
}

// Change number or related products per row to 3 (in case it has a sidebar)
add_filter( 'woocommerce_output_related_products_args', 'zn_related_products_args' );
if (!function_exists('zn_related_products_args')) {
	function zn_related_products_args( $args ) {

		$check_sidebar = zn_check_sidebar();
		$p_nr = $check_sidebar ? 3 : 4;

		$args['posts_per_page'] = $p_nr;
		$args['columns'] = $p_nr;
		return $args;
	}
}



/*--------------------------------------------------------------------------------------------------
	FILTER PRODUCT DESCRIPTION
--------------------------------------------------------------------------------------------------*/
if( !function_exists('woo_short_desc_filter') ){
	function woo_short_desc_filter( $content )
	{
		if(!empty($content)){
			$content = '<div class="kw-details-desc">'. $content .'</div>';
		}
		return $content;
	}
}
add_filter( 'woocommerce_short_description', 'woo_short_desc_filter' );

/* UPDATE 3.5 */

/*--------------------------------------------------------------------------------------------------
REPLACE THE WOOCOMMERCE PAGINATION
--------------------------------------------------------------------------------------------------*/
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'zn_woocommerce_pagination', 10 );
function zn_woocommerce_pagination()
{
	echo '<div class="pagination--'.zget_option( 'zn_main_style', 'color_options', false, 'light' ).'">';
	zn_pagination();
	echo '</div>';
}


/**
 * Set the number of products to be displayed per page in the shop
 *
 * @hooked to add_filter( 'loop_shop_per_page', 'wpkzn_woo_cat_posts_per_page', 100 );
 * @wpk
 * @since v3.6.5
 *
 * @return int|mixed|void
 */
add_filter( 'loop_shop_per_page', 'wpkzn_woo_show_posts_per_page' );
function wpkzn_woo_show_posts_per_page(){
	return zget_option( 'woo_show_products_per_page', 'zn_woocommerce_options', false, get_option('posts_per_page') );
}



// Loop page
add_action( 'woocommerce_before_shop_loop_item', 'zn_woocommerce_before_shop_loop_item', 1 );
add_action( 'woocommerce_after_shop_loop_item', 'zn_woocommerce_after_shop_loop_item', 100 );

// Subcategory display
add_action( 'woocommerce_before_subcategory', 'zn_woocommerce_before_shop_loop_item' );
add_action( 'woocommerce_after_subcategory', 'zn_woocommerce_after_shop_loop_item' );

function zn_woocommerce_before_shop_loop_item(){

	$product_layout = 'prod-layout-' . zget_option( 'woo_prod_layout', 'zn_woocommerce_options', false, 'classic' );
?>
	<div class="product-list-item text-custom-parent-hov <?php echo esc_attr( $product_layout ); ?>">
<?php
}

function zn_woocommerce_after_shop_loop_item(){
?>
	</div> <!-- Close product-list-item -->
<?php
}


add_action( 'woocommerce_before_shop_loop_item_title', 'zn_woocommerce_before_shop_loop_item_title' );
add_action( 'woocommerce_after_shop_loop_item_title', 'zn_woocommerce_after_shop_loop_item_title' );

function zn_woocommerce_before_shop_loop_item_title(){
?>
	<div class="kw-details clearfix">
		<h3 class="kw-details-title text-custom-child" <?php echo WpkPageHelper::zn_schema_markup('title'); ?>><?php the_title(); ?></h3>
<?php
}

function zn_woocommerce_after_shop_loop_item_title(){
	do_action('zn_woocommerce_after_shop_loop_item_details');
?>
	</div> <!-- Close details clearfix -->
	<?php
}

// Reposition Rating
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'zn_woocommerce_after_shop_loop_item_details', 'woocommerce_template_loop_rating' );

// Add the description in loop single item after rating
add_action( 'woocommerce_after_shop_loop_item_title', 'zn_woocommerce_template_loop_description', 9 );
function zn_woocommerce_template_loop_description(){
	if ( zget_option( 'woo_hide_small_desc', 'zn_woocommerce_options', false, 'no' ) == 'no' )  {
		global $post;
		echo apply_filters( 'woocommerce_short_description', $post->post_excerpt );
	}
}

// Wrap the add to cart button for loops with .actions class and add the more info button
add_filter( 'woocommerce_loop_add_to_cart_link', 'zn_woocommerce_loop_add_to_cart_link', 10, 3 );
add_action( 'woocommerce_before_add_to_cart_form', 'zn_woocommerce_disable_loop_add_to_cart_link' );
add_action( 'woocommerce_after_add_to_cart_form', 'zn_woocommerce_enable_loop_add_to_cart_link' );
function zn_woocommerce_disable_loop_add_to_cart_link(){
	global $zn_woo_config;
	$zn_woo_config['remove_loop_add_to_cart'] = true;
}
function zn_woocommerce_enable_loop_add_to_cart_link(){
	global $zn_woo_config;
	$zn_woo_config['remove_loop_add_to_cart'] = false;
}
function zn_woocommerce_loop_add_to_cart_link( $link, $product, $args ){
	global $zn_woo_config;
	if( isset( $zn_woo_config['remove_loop_add_to_cart'] ) && $zn_woo_config['remove_loop_add_to_cart'] ){
		return $link;
	}

	// Remove the button class that adds extra styles from woocommerce
	$link = str_replace( 'class="button', 'class="actions-addtocart ', $link );

	$product_layout = zget_option( 'woo_prod_layout', 'zn_woocommerce_options', false, 'classic' );
	$more_icon = '<svg width="50px" height="24px" class="svg-moreIcon"><circle cx="12" cy="12" r="2"/><circle cx="20" cy="12" r="2"/><circle cx="28" cy="12" r="2"/></svg>';

	// Replace with SVG Icon if style 3
	if( $product_layout == 'style2' ) {
		$addtocart_icon = '<svg width="24px" height="27px" viewBox="0 0 24 27" class="svg-addCartIcon"> <path d="M3.0518948,6.073 L0.623,6.073 C0.4443913,6.073064 0.2744004,6.1497833 0.1561911,6.2836773 C0.0379818,6.4175713 -0.0170752,6.5957608 0.005,6.773 L1.264,16.567 L0.006,26.079 C-0.0180763,26.2562394 0.0363321,26.4351665 0.155,26.569 C0.2731623,26.703804 0.4437392,26.7810739 0.623,26.781 L17.984,26.781 C18.1637357,26.7812017 18.3347719,26.7036446 18.4530474,26.5683084 C18.5713228,26.4329722 18.6252731,26.2530893 18.601,26.075 L18.489,25.233 C18.4652742,25.0082534 18.3215123,24.814059 18.1134843,24.7257511 C17.9054562,24.6374431 17.6658978,24.6689179 17.4877412,24.8079655 C17.3095847,24.947013 17.2208653,25.1717524 17.256,25.395 L17.274,25.534 L1.332,25.534 L2.509,16.646 C2.5159976,16.5925614 2.5159976,16.5384386 2.509,16.485 L1.33,7.312 L2.853102,7.312 C2.818066,7.6633881 2.8,8.0215244 2.8,8.385 C2.8,8.7285211 3.0784789,9.007 3.422,9.007 C3.7655211,9.007 4.044,8.7285211 4.044,8.385 C4.044,8.0203636 4.0642631,7.6620439 4.103343,7.312 L14.5126059,7.312 C14.5517192,7.6620679 14.572,8.02039 14.572,8.385 C14.571734,8.5500461 14.6371805,8.7084088 14.7538859,8.8251141 C14.8705912,8.9418195 15.0289539,9.007266 15.194,9.007 C15.3590461,9.007266 15.5174088,8.9418195 15.6341141,8.8251141 C15.7508195,8.7084088 15.816266,8.5500461 15.816,8.385 C15.816,8.0215244 15.797934,7.6633881 15.762898,7.312 L17.273,7.312 L16.264,15.148 C16.2418906,15.3122742 16.2862643,15.4785783 16.3872727,15.6100018 C16.4882811,15.7414254 16.6375681,15.8270962 16.802,15.848 C16.9668262,15.8735529 17.1349267,15.8304976 17.2671747,15.7288556 C17.3994227,15.6272135 17.4842817,15.4758514 17.502,15.31 L18.602,6.773 C18.6234087,6.5958949 18.5681158,6.4180821 18.4500484,6.2843487 C18.3319809,6.1506154 18.1623929,6.0737087 17.984,6.073 L15.5641052,6.073 C14.7827358,2.5731843 12.2735317,0.006 9.308,0.006 C6.3424683,0.006 3.8332642,2.5731843 3.0518948,6.073 Z M4.3273522,6.073 L14.2884507,6.073 C13.5783375,3.269785 11.6141971,1.249 9.308,1.249 C7.0015895,1.249 5.0372989,3.2688966 4.3273522,6.073 Z" class="addtocart_bag" fill="#141414" fill-rule="evenodd"></path> <path d="M17.6892,25.874 C14.6135355,25.8713496 12.1220552,23.3764679 12.1236008,20.3008027 C12.1251465,17.2251374 14.6191332,14.7327611 17.6947988,14.7332021 C20.7704644,14.7336431 23.2637363,17.2267344 23.2644,20.3024 C23.2604263,23.3816113 20.7624135,25.8753272 17.6832,25.874 L17.6892,25.874 Z M17.6892,16.2248 C15.4358782,16.2248 13.6092,18.0514782 13.6092,20.3048 C13.6092,22.5581218 15.4358782,24.3848 17.6892,24.3848 C19.9425218,24.3848 21.7692,22.5581218 21.7692,20.3048 C21.7692012,19.2216763 21.3385217,18.1830021 20.5720751,17.4176809 C19.8056285,16.6523598 18.7663225,16.2232072 17.6832,16.2248 L17.6892,16.2248 Z" class="addtocart_circle" fill="#141414"></path> <path d="M18.4356,21.0488 L19.6356,21.0488 L19.632,21.0488 C20.0442253,21.0497941 20.3792059,20.7164253 20.3802,20.3042 C20.3811941,19.8919747 20.0478253,19.5569941 19.6356,19.556 L18.4356,19.556 L18.4356,18.356 C18.419528,17.9550837 18.0898383,17.6383459 17.6886,17.6383459 C17.2873617,17.6383459 16.957672,17.9550837 16.9416,18.356 L16.9416,19.556 L15.7392,19.556 C15.3269747,19.556 14.9928,19.8901747 14.9928,20.3024 C14.9928,20.7146253 15.3269747,21.0488 15.7392,21.0488 L16.9416,21.0488 L16.9416,22.2488 C16.9415997,22.4469657 17.0204028,22.6369975 17.1606396,22.7770092 C17.3008764,22.9170209 17.4910346,22.9955186 17.6892,22.9952 L17.6856,22.9952 C17.8842778,22.99648 18.0752408,22.9183686 18.2160678,22.7782176 C18.3568947,22.6380666 18.4359241,22.4474817 18.4356,22.2488 L18.4356,21.0488 Z" class="addtocart_plus" fill="#141414"></path> </svg>';

		$add_to_cart_text = $product->add_to_cart_text();
		$add_to_cart_text_enc = htmlentities($add_to_cart_text, ENT_COMPAT, "UTF-8");

		if( in_array( $product->get_type(), array( 'variable', 'grouped' ) ) ){
			$link = str_replace( '%%%%%REPLACEWITHMOREICON%%%%%', $more_icon, $link );
		}
		else {
			$link = str_replace( '%%%%%REPLACEWITHBUICON%%%%%', $addtocart_icon, $link );
		}

	}

	$return  = '<span class="kw-actions">';
		$return .= $link;

		// Don't show the More info button if a a link to the product is already present
		if( ! in_array( $product->get_type(), array( 'variable', 'grouped' ) ) ){
			$return .= '<a class="actions-moreinfo" href="'.get_permalink().'" title="'. __( "MORE INFO", 'zn_framework' ).'">';
			$return .= $product_layout == 'style2' ? $more_icon : __( "MORE INFO", 'zn_framework' );
			$return .= '</a>';
		}

	$return .= '</span>';

	return $return;
}


add_filter( 'woocommerce_product_add_to_cart_text', 'zn_woocommerce_change_add_to_cart_text', 99, 2 );
function zn_woocommerce_change_add_to_cart_text( $text, $product ){
	$product_layout = zget_option( 'woo_prod_layout', 'zn_woocommerce_options', false, 'classic' );

	// Replace with SVG Icon if style 3
	if( $product_layout == 'style2' ) {
		if( in_array( $product->get_type(), array( 'variable', 'grouped' ) ) ){
			$text = '%%%%%REPLACEWITHMOREICON%%%%%';
		}
		else{
			$text = '%%%%%REPLACEWITHBUICON%%%%%';
		}
	}

	return $text;

}


/** Product sale and new flash **/
add_action( 'woocommerce_before_shop_loop_item', 'zn_woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_before_single_product_summary', 'zn_woocommerce_show_product_sale_flash', 10 );

function zn_woocommerce_show_product_sale_flash(){
	global $product, $post;

	$new_badge = '';
	if ( zget_option( 'woo_new_badge', 'zn_woocommerce_options', false, 1 ) && $product->is_in_stock() ) {

		$now  = time();
		$diff = ( get_the_time( 'U' ) > $now ) ? get_the_time( 'U' ) - $now : $now - get_the_time( 'U' );
		$val  = floor( $diff / 86400 );
		$days = floor( get_the_time( 'U' ) / ( 86400 ) );

		if ( zget_option( 'woo_new_badge_days', 'zn_woocommerce_options', false, 3 ) >= $val ) {
			$new_badge = '<span class="znew zn_badge_new kl-font-alt">' . __( 'NEW!', 'zn_framework' ) . '</span>';
		}
	}

	$on_sale = '';
	if ( $product->is_on_sale() && $product->is_in_stock() && zget_option( 'woo_sale_badge', 'zn_woocommerce_options', false, 1 ) == 1 ) {
		// call apply filters, so others can modify this
		$on_sale = apply_filters( 'woocommerce_sale_flash', '<span class="zonsale zn_badge_sale kl-font-alt">' . __( 'SALE!', 'zn_framework' ) . '</span>', $post, $product );
	}

	$sold_out = '';
	if ( ! $product->is_in_stock() && zget_option( 'woo_soldout_badge', 'zn_woocommerce_options', false, 'no' ) == 'yes' ) {
		$sold_out = '<span class="zn_badge_soldout">' . __( 'SOLD OUT', 'zn_framework' ) . '</span>';
	}
?>
	<div class="zn_badge_container">
		<?php echo '' . $on_sale . $new_badge . $sold_out; ?>
	</div>
<?php
}


/** Single product page **/

function zn_close_div() {
	echo "</div>";
}

function zn_add_post_styleclass($classes)
{
	$classes[] = 'prodpage-'.zget_option( 'woo_prod_page_layout', 'zn_woocommerce_options', false, 'classic' );

	return $classes;
}
add_filter( 'post_class', 'zn_add_post_styleclass', 20 );

switch( zget_option( 'woo_prod_page_layout', 'zn_woocommerce_options', false, 'classic' ) ):

	case "classic":
	case "style2":
		include(locate_template('woocommerce/single-product-default.php'));
	break;

	case "style3":
		include(locate_template('woocommerce/single-product-style3.php'));
	break;

endswitch;


/** REPLACE TEMAPLTE FILES WITH ACTIONS **/
add_action( 'woocommerce_before_main_content', 'zn_woocommerce_before_main_content' );
add_action( 'woocommerce_after_main_content', 'zn_woocommerce_after_main_content' );

function zn_woocommerce_before_main_content(){

	$args = array();
	if( is_single() ){
		$override_page_title = zget_option( 'zn_override_single_shop_title', 'zn_woocommerce_options' );
		if( 'yes' === $override_page_title ){
			$args['title'] = zget_option( 'single_shop_page_title', 'zn_woocommerce_options' );
		}
	}
	else{

		// SHOW THE HEADER
		$args['title'] = zget_option( 'woo_arch_page_title', 'zn_woocommerce_options' );
		$args['subtitle'] = zget_option( 'woo_arch_page_subtitle', 'zn_woocommerce_options' );
		if( empty( $args['title'] ) ){
			//** Put the header with title and breadcrumb
			$args['title'] = __( 'Shop', 'zn_framework' );
		}

		if( is_shop() ){
			$headerClass = zget_option( 'woo_sub_header', 'zn_woocommerce_options', false, 'zn_def_header_style' );
			if( $headerClass != 'zn_def_header_style' ) {
				$headerClass = 'uh_'.$headerClass;
			}
			$args['headerClass'] = $headerClass;
		}

		if(is_product_category() || is_product_tag())
		{
			global $wp_query;
			$tax = $wp_query->get_queried_object();
			$args['title'] = $tax->name;
			$args['subtitle'] = ''; // Reset the subtitle for categories and tags
		}
	}

	WpkPageHelper::zn_get_subheader( $args );

	// Check to see if the page has a sidebar or not
	global $zn_config;
	$sidebar_pos = false;
	if ( is_single() ) {
		$layout = 'woo_single_sidebar';
	}
	elseif( is_shop() ){
		$sidebar_pos = get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'zn_page_layout', true );
		$zn_config['forced_sidebar_id'] = get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'zn_sidebar_select', true );
		$layout = 'woo_archive_sidebar';
	}
	elseif( is_archive() ){
		$layout = 'woo_archive_sidebar';
	}

	$zn_config['force_sidebar'] = $layout;
	$main_class = zn_get_sidebar_class($layout, $sidebar_pos);
	if( strpos( $main_class , 'right_sidebar' ) !== false || strpos( $main_class , 'left_sidebar' ) !== false ) {
		$zn_config['sidebar'] = true;
		// Fixes the layout if num columns is set to 4
		$nc = apply_filters('loop_shop_columns', zn_woo_loop_columns());
		if($nc == 4) {
			$main_class = str_replace( array( 'left_sidebar', 'right_sidebar' ), '', $main_class );
		}
	}
	else {
		$zn_config['sidebar'] = false;
	}
	$sidebar_size = zget_option( 'sidebar_size', 'unlimited_sidebars', false, 3 );
	$content_size = 12 - (int)$sidebar_size;
	$zn_config['size'] = $zn_config['sidebar'] ? 'col-sm-8 col-md-'.$content_size : 'col-sm-12';

	global $post;

	$woo_prod_page_layout = zget_option( 'woo_prod_page_layout', 'zn_woocommerce_options', false, 'classic' );

	if( is_single() && $woo_prod_page_layout == 'style3' ){
		// nothing. For style3 have the product content fully stretched
	}
	else {
		$columns_class = ((zn_woo_loop_columns() == 4) ? 'zn_shop_four_columns' : '');
		zn_woocommerce_before_main_content_html($main_class, $columns_class);
	}
}

if(!function_exists('zn_woocommerce_after_main_content')){
	function zn_woocommerce_after_main_content(){

		$woo_prod_page_layout = zget_option( 'woo_prod_page_layout', 'zn_woocommerce_options', false, 'classic' );

		if( is_single() && $woo_prod_page_layout == 'style3' ){
			// nuthin. For style3 have the product content fully stretched
		} else {
			zn_woocommerce_after_main_content_html();
		}
	}
}

if(!function_exists('zn_woocommerce_before_main_content_html')){
	function zn_woocommerce_before_main_content_html($main_class, $columns_class){
		?>
			<section id="content" class="site-content shop_page">
				<div class="container">
					<div class="row">
						<div class="<?php echo esc_attr( $main_class ); ?> <?php echo esc_attr( $columns_class ); ?>">
			<?php
	}
}

if(!function_exists('zn_woocommerce_after_main_content_html')){
	function zn_woocommerce_after_main_content_html(){
		?>
			</div>
						<!-- sidebar -->
						<?php get_sidebar(); ?>
					</div>
				</div>
			</section>
			<?php
	}
}






function zn_cart_icon_markup(){
	global $woocommerce;

	// Add "choose style" option?
	$cart_style = zget_option( 'woo_cart_style', 'zn_woocommerce_options', false, '' );

	/**
	 * Markup for Style 2
	 */
	if($cart_style == 'style2'){
	?>
		<span class="kl-cart-icon xs-icon svg-cart" data-count="<?php echo esc_attr( $woocommerce->cart->cart_contents_count ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="28" height="32" viewBox="0 0 28 32" >
				<path class="svg-cart-icon" d="M26,8.91A1,1,0,0,0,25,8H20V6A6,6,0,1,0,8,6V8H3A1,1,0,0,0,2,8.91l-2,22A1,1,0,0,0,1,32H27a1,1,0,0,0,1-1.089ZM10,6a4,4,0,0,1,8,0V8H10V6ZM2.1,30L3.913,10H8v2.277a2,2,0,1,0,2,0V10h8v2.277a2,2,0,1,0,2,0V10h4.087L25.9,30H2.1Z"/>
			</svg>
		</span>
	<?php
	}
	/**
	 * Default markup
	 */
	else{
	?>
		<i class="glyphicon glyphicon-shopping-cart kl-cart-icon flipX-icon xs-icon" data-count="<?php echo esc_attr( $woocommerce->cart->cart_contents_count ); ?>"></i>
	<?php
	}
}

/**
 * Ensure the cart contents update when products are added to the cart via AJAX
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment_number' );
if ( ! function_exists( 'woocommerce_header_add_to_cart_fragment_number' ) ) {
	/**
	 * Ensure the cart contents update when products are added to the cart via AJAX
	 * @param $fragments
	 * @hooked to woocommerce_add_to_cart_fragments
	 * @see functions.php
	 * @return mixed
	 */
	function woocommerce_header_add_to_cart_fragment_number( $fragments ){
		global $woocommerce;
		ob_start();
		zn_cart_icon_markup();
		$fragments['.kl-cart-icon'] = ob_get_clean();


		// Return the new added to cart popup
		// @since v4.0.13
		if(isset($_POST['product_id'])){
			$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$product_object = WC()->product_factory->get_product( $product_id );

			// Add the new modal
			ob_start();
			?>
				<div class="kl-addedtocart">
					<div class="kl-addedtocart-container">
						<?php echo '<div class="kl-addedtocart-image">'.$product_object->get_image() .'</div>'; ?>
						<?php echo '<h3 class="kl-addedtocart-title">' . $product_object->get_title() . '</h3>'; ?>
						<div class="kl-addedtocart-desc"><?php _e( 'has been added to your cart.', 'zn_framework' );?></div>
						<?php echo '<div class="kl-addedtocart-price">' . $product_object->get_price_html() .'</div>'; ?>
						<a href="<?php echo esc_attr( esc_url( wc_get_checkout_url() ) ); ?>" class="kl-addedtocart-checkout btn btn-lined lined-dark btn-md"><?php _e( 'CHECKOUT &rarr;', 'zn_framework' );?></a>
						<a class="kl-addedtocart-close" title="<?php esc_attr_e( 'Continue Shopping', 'zn_framework' );?>"></a>
					</div>
				</div>
			<?php
			$fragments['zn_added_to_cart'] = ob_get_clean();
		}
		return $fragments;
	}
}
/**
 * Add WooCommerce cart link
 */
if ( ! function_exists( 'zn_woocomerce_cart' ) ) {
	/**
	 * Add WooCommerce cart link
	 * @hooked to zn_head_right_area
	 * @see functions.php
	 */
	function zn_woocomerce_cart(){
		$show_cart_to_visitors = zget_option( 'show_cart_to_visitors', 'zn_woocommerce_options', false, 'yes' );
		if( $show_cart_to_visitors == 'no' && ! is_user_logged_in() ){
			return;
		}
		if ( zget_option( 'woo_show_cart', 'zn_woocommerce_options' ) ) {
			global $woocommerce;
			?>
			<ul class="sh-component topnav navLeft topnav--cart topnav-no-sc topnav-no-hdnav">
				<li class="drop topnav-drop topnav-li">
					<?php
					global $woocommerce;

					// Add "choose style" option?
					$cart_style = zget_option( 'woo_cart_style', 'zn_woocommerce_options', false, '' );

					?>

					<a id="mycartbtn" class="kl-cart-button topnav-item kl-cart--<?php echo esc_attr( $cart_style ); ?>" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart', 'zn_framework' ); ?>">
						<?php
							zn_cart_icon_markup();
							if($cart_style != 'style2' && $cart_style != 'icononly') {
								echo '<span class="hidden-xs hidden-sm hidden-md">';
									_e( "MY CART", 'zn_framework' );
								echo '</span>';
							}
						?>
					</a>

					<div class="pPanel topnav-drop-panel topnav--cart-panel u-trans-all-2s">
						<div class="inner topnav-drop-panel-inner topnav--cart-panel-inner cart-container">
							<div class="widget_shopping_cart_content"><?php _e('No products in cart.','zn_framework'); ?></div>
						</div>
					</div>
				</li>
			</ul>
			<?php
		}
	}
}


/*--------------------------------------------------------------------------------------------------
	FILTER PRODUCT PRICE
--------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'zn_woocommerce_price_html' ) ) {
	function zn_woocommerce_price_html( $content )
	{
		$content = str_replace( '<del><span class="amount">', '<del data-was="'. esc_attr( __( 'WAS', 'zn_framework' ) ) .'"><span class="amount">', $content );
		$content = str_replace( '<ins><span class="amount">', '<ins data-now="'. esc_attr( __( 'NOW', 'zn_framework' ) ) .'"><span class="amount">', $content );

		return $content;
	}
}
add_filter( 'woocommerce_get_price_html', 'zn_woocommerce_price_html' );


/**
 * Add Default WooCommerce tempplate for pagebuilder
 */
add_filter( 'znpb_empty_page_layout', 'znpb_woo_add_kallyas_template', 10, 3 );
function znpb_woo_add_kallyas_template( $current_layout, $post, $post_id ){

	if( is_product() ){

		global $zn_config;
		$current_layout = $columns = array();

		// Get sidebars set in page options
		$sidebar_pos = get_post_meta( $post_id, 'zn_page_layout', true );
		$sidebar_to_use = get_post_meta( $post_id, 'zn_sidebar_select', true );
		$subheader_style = get_post_meta( $post_id, 'zn_subheader_style', true );

		// Get sidebar set in theme options
		$sidebar_saved_data = zget_option( 'woo_archive_sidebar', 'unlimited_sidebars' , false , array('layout' => 'right_sidebar' , 'sidebar' => 'defaultsidebar' ) );

		if( $sidebar_pos == 'default' || empty( $sidebar_pos ) ){
			$sidebar_pos = $sidebar_saved_data['layout'];
		}
		if( $sidebar_to_use == 'default' || empty( $sidebar_to_use ) ){
			$sidebar_to_use = $sidebar_saved_data['sidebar'];
		}

		// We will add the new elements here
		$sidebar        = ZNB()->frontend->addModuleToLayout( 'TH_Sidebar', array( 'sidebar_select' => $sidebar_to_use ) );
		$sidebar_column = ZNB()->frontend->addModuleToLayout( 'ZnColumn', array(), array( $sidebar ), 'col-sm-4 col-md-3' );

		$current_layout[]     = ZNB()->frontend->addModuleToLayout( 'TH_CustomSubHeaderLayout', array( 'hm_header_style' => $subheader_style ) );

		// If the sidebar was saved as left sidebar
		if( $sidebar_pos == 'left_sidebar'  ){
			$columns[] = $sidebar_column;
		}

		// Add the main shop content
		$archive_columns = $sidebar_pos == 'no_sidebar' ? 4 : 3;
		$shop_archive = ZNB()->frontend->addModuleToLayout( 'TH_ProductContent', array( 'num_columns' => $archive_columns ) );
		$columns[]    = ZNB()->frontend->addModuleToLayout( 'ZnColumn', array(), array( $shop_archive ), 'col-sm-8 col-md-9' );

		// If the sidebar was saved as right sidebar
		if( $sidebar_pos == 'right_sidebar'  ){
			$columns[] = $sidebar_column;
		}

		if( zget_option( 'woo_prod_page_layout', 'zn_woocommerce_options', false, 'classic' ) == 'style3' ){
			$current_layout[]   = ZNB()->frontend->addModuleToLayout( 'TH_ProductContent' );
		}
		else{
			$current_layout[]   = ZNB()->frontend->addModuleToLayout( 'ZnSection', array(), $columns, 'col-sm-12' );
		}

		return $current_layout;

	}

	return $current_layout;

}



/*
 * @since v4.0.8
 * @wpk
 */
add_filter( 'woocommerce_sale_flash', 'wcSaleFlashGetDiscount', 90, 3 );
if(! function_exists('wcSaleFlashGetDiscount')) {
	/**
	 * Display the amount of the discount as percentage in the sale flash
	 *
	 * @param string $text
	 * @param object $post
	 * @param object $product
	 *
	 * @return string
	 */
	function wcSaleFlashGetDiscount( $text, $post, $product )
	{
		// print_z();
		if ( zget_option( 'woo_show_sale_flash_discount', 'zn_woocommerce_options', false, 'yes' ) == 'yes' ) {
			$discount = 0;
			if ( $product->is_on_sale() ) {
				if ( $product->get_type() == 'variable' ) {
					$available_variations = $product->get_available_variations();

					for ( $i = 0; $i < count( $available_variations ); ++$i ) {
						$variation_id = $available_variations[$i]['variation_id'];
						$variable_product1 = new WC_Product_Variation( $variation_id );
						$regular_price = $variable_product1->get_regular_price();
						// Prevent "division by 0" notice when there is no regular price set
						if ( $regular_price > 0 ) {
							$sales_price = (int) $variable_product1->get_sale_price();

							// Don't proceed if the sale price is not set
							if( empty( $sales_price ) ){
								continue;
							}

							$percentage = round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ), 1 );

							// Get only the smallest discount
							if ( $percentage > $discount ) {
								$discount = $percentage;
							}
						}
					}
				}
				elseif ( $product->get_type() == 'simple' ) {
					$discount =
						round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
				}
				if ( $discount ) {
					$discount = "-{$discount}%";
				}
				else {
					$discount = '';
				}
				$text =
					'<span class="zonsale zn_badge_sale">' . sprintf( __( 'Sale! %s', 'zn_framework' ), $discount ) .
					'</span>';
			}
		}
		return $text;
	}
}

// Override WC's (max-width:768px) to 767px
add_filter('woocommerce_style_smallscreen_breakpoint','zn_woo_custom_breakpoint');
function zn_woo_custom_breakpoint($px) {
	return '767px';
}

/**
 * Wrap Cart table in custom class
 */
add_action('woocommerce_before_cart', 'zn_woocommerce_before_cart');
add_action('woocommerce_after_cart', 'zn_close_div');

function zn_woocommerce_before_cart(){
	echo '<div class="zn-cartpage-'.zget_option( 'woo_pages_layout', 'zn_woocommerce_options', false, 'classic' ).'">';
}


add_filter( 'body_class', 'zn_wcbodyclass_pagestyle' );

function zn_wcbodyclass_pagestyle( $classes ){
	if ( is_woocommerce()|| is_checkout() || is_cart() || is_account_page() || is_shop() ) {
		$classes[] = 'zn-wc-pages-' . zget_option( 'woo_pages_layout', 'zn_woocommerce_options', false, 'classic' );
	}

	return $classes;
}
