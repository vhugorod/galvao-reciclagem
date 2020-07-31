<?php

/**
 * This class will add kallyas integration for WP Hotel Booking plugin
 */
class ZnKlWpHotelBooking{
	function __construct(){
		// Fix #2168
		add_action( 'wp_enqueue_scripts', array( $this, 'znkl_dequeue_wp_hotel_gallery' ), 100 );

		// Single page wrappers
		add_action( 'hotel_booking_single_room_infomation', array( $this, 'znkl_wp_hotel_booking_wrapper_start' ), 1 );
		add_action( 'hotel_booking_single_room_infomation', array( $this, 'znkl_wp_hotel_booking_wrapper_end' ), 999999 );

		// Single page related products
		add_action( 'hotel_booking_after_single_product', array( $this, 'znkl_wp_hotel_booking_related_wrapper_start' ), 1 );
		add_action( 'hotel_booking_after_single_product', array( $this, 'znkl_wp_hotel_booking_related_wrapper_end' ), 999999 );

		// Add extra options
		// add_filter( 'zn_theme_pages', array( $this, 'add_options_page' ) );
		add_filter( 'zn_theme_options', array ( $this, 'add_options' ) );
	}
	//
	// function add_options_page( $admin_pages ){
	//
	// 	$admin_pages['zn_wp_booking'] = array(
	// 		'title' =>  'WooCommerce',
	// 		'submenus' => 	array(
	// 			array(
	// 				'slug' => 'zn_wp_booking',
	// 				'title' =>  __( "General options", 'zn_framework' )
	// 			),
	// 		)
	// 	);
	//
	// 	return $admin_pages;
	// }


	function add_options( $admin_options ){

		$sidebar_options = array( 'right_sidebar' => 'Right sidebar' , 'left_sidebar' => 'Left sidebar' , 'no_sidebar' => 'No sidebar' );
		// $admin_options[] = array(
		// 	'slug'        => 'sidebar_settings',
		// 	'parent'      => 'unlimited_sidebars',
		// 	'id'          => 'znkl_wp_booking_archive_sidebar',
		// 	'name'        => 'Sidebar on Wo Booking rooms archive page',
		// 	'description' => 'Please choose the sidebar position for the shop archive pages.',
		// 	'type'        => 'sidebar',
		// 	'class'     => 'zn_full',
		// 	'std'       => array (
		// 		'layout' => 'sidebar_right',
		// 		'sidebar' => 'default_sidebar',
		// 	),
		// 	'supports'  => array(
		// 		'default_sidebar' => 'defaultsidebar',
		// 		'sidebar_options' => $sidebar_options
		// 	),
		// );

		$admin_options[] = array(
			'slug'        => 'sidebar_settings',
			'parent'      => 'unlimited_sidebars',
			'id'          => 'znkl_wp_booking_single_sidebar',
			'name'        => 'Sidebar on single Room page ( wp hotel booking )',
			'description' => 'Please choose the sidebar position for the wp booking single room page.',
			'type'        => 'sidebar',
			'class'     => 'zn_full',
			'std'       => array (
				'layout' => 'sidebar_right',
				'sidebar' => 'default_sidebar',
			),
			'supports'  => array(
				'default_sidebar' => 'defaultsidebar',
				'sidebar_options' => $sidebar_options
			),
		);

		return $admin_options;
	}

	/**
	 * Dequeue the jQuery UI script.
	 *
	 * Hooked to the wp_print_scripts action, with a late priority (100),
	 * so that it is after the script was enqueued.
	 */
	function znkl_dequeue_wp_hotel_gallery() {
		// Fixes #2168
		if( ZNB()->utility->isActiveEditor() ){
			wp_dequeue_script( 'wp-hotel-booking-gallery' );
		}

		// Load WP hotel booking custom style
		wp_enqueue_style( 'wp-hotel-booking-overrides', THEME_BASE_URI . '/css/plugins/kl-wp-hotel-booking.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
	}

	/**
	 * Add the opening divs to the single room details page
	 * @return string the opening tags for single page details
	 */
	function znkl_wp_hotel_booking_wrapper_start(){

		global $zn_config;

		// The name of the wp hotel booking sidebar option id
		$sidebar_layout = 'znkl_wp_booking_single_sidebar';

		// Main content class for current sidebar settings
		$main_class = zn_get_sidebar_class($sidebar_layout);

		$zn_config['force_sidebar'] = $sidebar_layout;
		$zn_config['sidebar'] = ( strpos( $main_class , 'right_sidebar' ) !== false || strpos( $main_class , 'left_sidebar' ) !== false );
		$sidebar_size = zget_option( 'sidebar_size', 'unlimited_sidebars', false, 3 );
		$content_size = 12 - (int)$sidebar_size;
		$zn_config['size'] = $zn_config['sidebar'] ? 'col-sm-8 col-md-'.$content_size : 'col-sm-12';

		?>
		<section id="content" class="site-content wp_hotel_booking_single_room_details_wrapper">
			<div class="container">
				<div class="row">
					<div class="<?php echo esc_Attr( $main_class ); ?>">
		<?php
	}

	/**
	 * Close the single room details page tags
	 * @return string the closing tags for single page details
	 */
	function znkl_wp_hotel_booking_wrapper_end(){
		?>
					</div>
					<!-- sidebar -->
					<?php get_sidebar(); ?>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Add the opening tags for single room related rooms section
	 * @return string the HTML output for opening tags
	 */
	function znkl_wp_hotel_booking_related_wrapper_start(){
		$main_class = 'col-sm-12';
		?>
		<section class="wp_hotel_booking_single_room_related_wrapper">
			<div class="container">
				<div class="row">
					<div class="<?php echo esc_attr( $main_class ); ?>">
		<?php
	}

	/**
	 * Add the closing HTML tags for single room related rooms section
	 * @return string the HTML output for closing tags
	 */
	function znkl_wp_hotel_booking_related_wrapper_end(){
		?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}


}
new ZnKlWpHotelBooking();
