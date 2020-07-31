<?php

if ( ! defined( 'ABSPATH') ) {
	return;
}
//<editor-fold desc=">>> IMPORTANT. READ ME.">
	// This is the main file for this theme. This file is automatically loaded by WordPress when the
	// theme is active. Normally, you should never edit this file as it will be overridden by future updates.
	// All changes should be implemented in the child theme's functions.php file.
//</editor-fold desc=">>> IMPORTANT. READ ME.">


//<editor-fold desc=">>> Include and configure the framework">

global $zn_config;

// Setup config for theme framework
add_filter( 'znhgtfw_config', 'znhg_kallyas_theme_config' );
function znhg_kallyas_theme_config( $config ) {
	$kallyas_config = array(
		'theme_db_id'  => 'zn_kallyas_optionsv4',
		'theme_id'     => 'kallyas',
		'server_url'   => 'http://my.hogash.com/hg_api/',
		'api_version'  => '3',
		'themeLogoUrl' => get_template_directory_uri() . '/images/admin_logo.png',
		//@since v4.15.6 - Utility endpoint to provide easy access to our plugins
		'api_assets_url' => 'https://api.my.hogash.com/',
		//@since v4.15.6 - Config data to Theme Options > Sample data
		'dash_config' => array(
			'sample_data' => array(
				'title' => __( 'Access 50+ Pre-made Demos when you activate %s.', 'zn_framework' ),

				'btn_view_text'   => __( 'View demos', 'zn_framework' ),
				'btn_view_url'    => 'https://kallyas.net',
				'btn_view_title'  => __( 'Will open in a new window/tab', 'zn_framework' ),
				'btn_view_target' => '_blank',

				'btn_register_text'   => __( 'Register', 'zn_framework' ),
				'btn_register_url'    => admin_url( 'admin.php?page=zn-about#zn-about-tab-registration-dashboard' ),
				'btn_register_title'  => __( 'Will open in a new window/tab', 'zn_framework' ),
				'btn_register_target' => '_self',

				'bg_image' => get_template_directory_uri() . '/images/admin/kallyas-dash-demos.png',
			),
		),
	);

	return array_merge( $config, $kallyas_config );
}
$fwDirPath = trailingslashit( get_template_directory() ) . 'framework';
require $fwDirPath . '/zn-framework.php'; // FRAMEWORK FILE
require $fwDirPath . '/hg-theme-framework/hg-theme-framework.php'; // New THEME FRAMEWORK FILE
require $fwDirPath . '/compatibility/config.php'; // compatibility config FILE
require $fwDirPath . '/hogash-mailchimp/hogash-mailchimp.php'; // new MailChimp plugin
unset( $fwDirPath );
//</editor-fold desc=">>> Include and configure the framework">

//<editor-fold desc=">>> LOAD CUSTOM CLASSES & WIDGETS & HOOKS">

	include THEME_BASE . '/deprecated.php';
	include THEME_BASE . '/template_helpers/theme_layout_manager.php';
	include THEME_BASE . '/template_helpers/helper-functions.php';
	include THEME_BASE . '/theme-functions-override.php';
	include THEME_BASE . '/template_helpers/pagebuilder/pagebuilder-animations.php';
	include THEME_BASE . '/components/theme-header/header-components.php';

	// Masks functions
	include THEME_BASE . '/components/masks/masks-functions.php';

	// Load Widgets
	include THEME_BASE . '/template_helpers/tweeter-helper.php';
	include locate_template( '/template_helpers/widgets/widget-blog-categories.php' );
	include locate_template( '/template_helpers/widgets/widget-archive.php' );
	include locate_template( '/template_helpers/widgets/widget-menu.php' );
	include locate_template( '/template_helpers/widgets/widget-twitter.php' );
	include locate_template( '/template_helpers/widgets/widget-contact-details.php' );
	include locate_template( '/template_helpers/widgets/widget-mailchimp.php' );
	include locate_template( '/template_helpers/widgets/widget-tag-cloud.php' );
	include locate_template( '/template_helpers/widgets/widget-latest_posts.php' );
	include locate_template( '/template_helpers/widgets/widget-social_buttons.php' );
	include locate_template( '/template_helpers/widgets/widget-flickr.php' );


	// LOAD WOOCOMMERCE CONFIG FILE
	if ( znfw_is_woocommerce_active() ) {
		locate_template( array( 'woocommerce/zn-woocommerce-init.php' ), true, false );
	}

	// Actions
	locate_template( 'th-action-hooks.php', true, true);

	// Filters
	locate_template( 'th-filter-hooks.php', true, true);

	// Custom Hooks
	locate_template( 'th-custom-hooks.php', true, true);

	// Pagebuilder functions
	require THEME_BASE . '/template_helpers/pagebuilder/pagebuilder-functions.php'; // EXTRA PAGEBUILDER FUNCTIONS

//</editor-fold desc=">>> LOAD CUSTOM CLASSES & WIDGETS & HOOKS">


/**
 * Adjust content width
 *
 * @uses global $content_width
 */
if ( ! isset( $content_width ) ) {
	$content_width = zget_option( 'zn_width', 'layout_options', false, '1170' );
}


/* TO BE MOVED ELSEWHERE */
function zn_get_sidebar_class( $type, $sidebar_pos = false ) {
	if ( ! $sidebar_pos && is_singular() || ( function_exists( 'is_shop') && is_shop() ) ) {
		$sidebar_pos = get_post_meta( zn_get_the_id(), 'zn_page_layout', true );
	}

	// Check if shop is active
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		if ( $shop_page_id ) {
			$sidebar_pos = get_post_meta( $shop_page_id, 'zn_page_layout', true );
		}
	}

	if ( 'default' === $sidebar_pos || ! $sidebar_pos ) {
		$sidebar_data = zget_option( $type, 'unlimited_sidebars', false, array( 'layout' => 'right_sidebar', 'sidebar' => 'defaultsidebar' ) );

		// Check to see if we have a sidebar set or we need to use the default one
		if ( empty( $sidebar_data['layout'] ) ) {
			if ( is_archive() ) {
				$sidebar_data = zget_option( 'archive_sidebar', 'unlimited_sidebars' );
			} elseif ( is_singular() ) {
				$sidebar_data = zget_option( 'single_sidebar', 'unlimited_sidebars' );
			}
		}

		$sidebar_pos  = $sidebar_data['layout'];
	}

	if ( 'no_sidebar' !== $sidebar_pos ) {
		$sidebar_size = zget_option( 'sidebar_size', 'unlimited_sidebars', false, 3 );
		$content_size = 12 - (int) $sidebar_size;
		$sidebar_pos .= ' col-sm-8 col-md-' . $content_size . ' ';
		// For left sidebar, push content 3cols to
		$sidebar_pos .= false !== strpos( $sidebar_pos, 'left_sidebar' ) ? ' col-md-push-' . $sidebar_size . ' ' : '';
	} else {
		$sidebar_pos = 'col-md-12';
	}

	return apply_filters( 'kl_sidebar_content_css_class', $sidebar_pos );
}

/* ADD PB ELEMENTS TO EMPTY PAGES  */
add_filter( 'znpb_empty_page_layout', 'znpb_add_kallyas_template', 10, 3 );
function znpb_add_kallyas_template( $current_layout, $post, $post_id ) {
	if ( ! is_page( $post_id ) ) {
		return $current_layout;
	}

	$sidebar_pos        = get_post_meta( $post_id, 'zn_page_layout', true );
	$sidebar_to_use     = get_post_meta( $post_id, 'zn_sidebar_select', true );
	$subheader_style    = '0' !== get_post_meta( $post_id, 'zn_subheader_style', true ) ? get_post_meta( $post_id, 'zn_subheader_style', true ) : 'zn_def_header_style';
	$sidebar_saved_data = zget_option( 'page_sidebar', 'unlimited_sidebars', false, array('layout' => 'right_sidebar', 'sidebar' => 'defaultsidebar' ) );

	if ( 'default' == $sidebar_pos || empty( $sidebar_pos ) ) {
		$sidebar_pos = $sidebar_saved_data['layout'];
	}
	if ( 'default' == $sidebar_to_use || empty( $sidebar_to_use ) ) {
		$sidebar_to_use = $sidebar_saved_data['sidebar'];
	}

	// We will add the new elements here
	$sidebar        = ZNB()->frontend->addModuleToLayout( 'TH_Sidebar', array( 'sidebar_select' => $sidebar_to_use ) );
	$sidebar_column = ZNB()->frontend->addModuleToLayout( 'ZnColumn', array(), array( $sidebar ), 'col-sm-4 col-md-3' );
	$sections[]     = ZNB()->frontend->addModuleToLayout( 'TH_CustomSubHeaderLayout', array( 'hm_header_style' => $subheader_style ) );

	// If the sidebar was saved as left sidebar
	if ( 'left_sidebar' == $sidebar_pos  ) {
		$columns[] = $sidebar_column;
	}

	// Add the main shop content
	$archive_columns = 'no_sidebar' == $sidebar_pos ? 4 : 3;
	$textbox         = ZNB()->frontend->addModuleToLayout( 'TH_TextBox', array( 'stb_title' => $post->post_title, 'stb_content' => $post->post_content ) );
	$columns[]       = ZNB()->frontend->addModuleToLayout( 'ZnColumn', array(), array( $textbox ), 'col-sm-8 col-md-9' );

	// If the sidebar was saved as right sidebar
	if ( 'right_sidebar' == $sidebar_pos  ) {
		$columns[] = $sidebar_column;
	}

	$sections[]   = ZNB()->frontend->addModuleToLayout( 'ZnSection', array(), $columns, 'col-sm-12' );

	return $sections;
}

/*
 * Kallyas integration with Zion Builder
 */
add_action( 'znb_integrations_init', 'znb_kallyas_integration' );
function znb_kallyas_integration( $integrationManager ) {
	$fp = THEME_BASE . '/template_helpers/zion-integration/class_kallyas_integration.php';
	if ( is_file( $fp) ) {
		require $fp;
	}
	call_user_func( array( $integrationManager, 'register_integration' ), 'kallyas', 'Znb_Kallyas_Integration' );
}


/*
 * @KAL-2: Add general option to set Product Template
 * @kos
 * @desc: Setup the Single Product Template that will be used if a Smart Area is set to replace the default content
 * @since v4.16
 * @see Theme options > WooCommerce options > General options > Smart Area for Single Product pages
 */
add_filter( 'template_include', 'hg_single_product_template', 99 );
if ( ! function_exists( 'hg_single_product_template' ) ) {
	function hg_single_product_template( $template ) {
		if ( is_singular( 'product' ) ) {
			$hasSmartArea = ( 'no_template' != zget_option( 'woo_single_product_smart_area', 'zn_woocommerce_options', false, 'no_template' ) );
			if ( $hasSmartArea ) {
				$new_template = locate_template( array( 'inc/single-product-template.php' ) );
				if ( '' != $new_template ) {
					return $new_template;
				}
			}
		}
		return $template;
	}
}