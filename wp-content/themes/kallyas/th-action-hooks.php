<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * This file holds all methods hooked to WordPress' actions
 *
 * @package    Kallyas
 * @category   Action Hooks
 * @author     Team Hogash
 * @since      3.8.0
 */

//<editor-fold desc=">>> WP HOOKS - ACTIONS">

/*
 * @hook after_setup_theme
 * Loads after the theme functions.php file is loaded...may break other things ?
 */
add_action( 'after_setup_theme', 'wpk_zn_on_init' );
/*
 * @hook after_setup_theme
 */
add_action( 'after_setup_theme', 'wpk_zn_on_after_setup_theme' );
/**
 * Flush WP Rewrite rules
 */
add_action( 'after_switch_theme', 'zn_rewrite_flush' );
/**
 * Check if we are on the taxonomy archive page. We will display all items if it is selected
 */
if ( ! is_admin() ) {
	add_action( 'pre_get_posts', 'zn_portfolio_taxonomy_pagination' );
}
/**
 * Add extra data to head
 */
add_action( 'wp_head', 'zn_head' );
/**
 * Register menus
 */
add_action( 'init', 'zn_register_menu' );
/**
 * Register the Custom Post Type: Portfolio
 */
add_action( 'init', 'zn_portfolio_post_type' );
/**
 * Register the Documentation Custom Post Type
 */
add_action( 'init', 'zn_documentation_post_type' );
/**
 * Register the Documentation Post Taxonomy
 */
add_action( 'init', 'zn_documentation_category', 0 );
/**
 *
 */
add_action( 'init', 'zn_portfolio_category', 0 );
/**
 * Display Google analytics to page
 */
add_action( 'wp_head', 'add_googleanalytics', 999 );

/**
 * Add support for ajax login
 */
if ( ! is_user_logged_in() ) {
	add_action( "wp_ajax_nopriv_zn_do_login", "zn_do_login" );
}

$allowed_post_types = array(
	'category',
	'product_cat',
	'project_category',
	'documentation_category',
);

/**
 * Add option to select custom header in Edit Category page
 */
if ( is_admin() && ( isset( $_REQUEST[ 'taxonomy' ] ) && $_REQUEST[ 'taxonomy' ] !== '' ) && ( in_array( $_REQUEST[ 'taxonomy' ], $allowed_post_types ) ) ) {
	add_action( sanitize_text_field( $_REQUEST[ 'taxonomy' ] ) . '_edit_form', 'wpk_zn_edit_category_form', 40, 1 );
	/**
	 * Save the custom header set in the edit category screen
	 */
	add_action( 'admin_init', 'wpk_zn_filterProductCatPost' );
}

/*
 * Register sidebars
 */
add_action( 'widgets_init', 'wpkRegisterSidebars' );

//@since 4.0.0
add_action( 'wp_enqueue_scripts', 'wpkLoadGlobalStylesheetsFrontend' );
add_action( 'wp_enqueue_scripts', 'wpkLoadPrintRtl', 99 );
add_action( 'wp_enqueue_scripts', 'wpkLoadPluginsCss' );

// Scripts
add_action( 'wp_enqueue_scripts', 'wpkSmartScriptLoaderFrontend' );

/*
 * Check if any typekit lists are selected and mix with the existent google fonts
 * @since v4.15.6
 */
//#! Check to see if we have any typekit fonts saved
add_filter( 'znb_field_font_families', 'znhg_add_typekit_fonts', 200 );
function znhg_add_typekit_fonts( $existentFonts = array() ) {
	$typekitFonts = get_option( ZNHGTFW()->getThemeDbId() . '_' . 'typekit_fonts' );
	if ( empty( $typekitFonts ) ) {
		return $existentFonts;
	}
	foreach ( $typekitFonts as $fontSlug => $fontName ) {
		$existentFonts[ $fontSlug ] = $fontName;
	}

	return $existentFonts;
}

//#! Print the Typekit script in the header
add_action( 'wp_head', 'znhg_print_typekit_script' );
function znhg_print_typekit_script() {
	$typekitLists = get_option( ZNHGTFW()->getThemeDbId() . '_' . 'typekit_lists' );
	if ( ! empty( $typekitLists ) ) {
		foreach ( $typekitLists as $listID => $listName ) {
			echo '<script id="typekit-' . esc_attr( $listID ) . '" src="//use.typekit.net/' . esc_attr( $listID ) . '.js"></script>';
		}
		echo '<script>try{Typekit.load({ async: true });}catch(e){}</script>';
	}
}


//</editor-fold desc=">>> WP HOOKS - ACTIONS">
