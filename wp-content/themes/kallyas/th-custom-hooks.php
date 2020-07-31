<?php if(! defined('ABSPATH')) { return; }

/**
 * This file holds all methods hooked to custom actions
 *
 * @package    Kallyas
 * @category   Custom Hooks
 * @author     Team Hogash
 * @since      3.8.0
 */

//<editor-fold desc=">>> WP HOOKS - CUSTOM">

    /**
     * Add page loading
     */
    add_action( 'zn_after_body', 'zn_add_page_loading', 10 );
    /**
     * Add the Support Panel
     */
    add_action( 'zn_after_body', 'zn_add_hidden_panel', 10 );
    /**
     * Display the login form
     */
    add_action( 'zn_after_body', 'zn_add_login_form', 10 );
    /**
     * Open Graph
     */
    add_action( 'zn_after_body', 'zn_add_open_graph', 10 );
    /**
     * Display the Info Card when you hover over the logo.
     */
    add_action( 'zn_show_infocard', 'kfn_showInfoCard' );


/**
 * Remove the scripts added to the page footer by the nextgen-gallery plugin
 */
if(class_exists('C_Photocrati_Resource_Manager')) {
    remove_action('wp_print_footer_scripts', array('C_Photocrati_Resource_Manager', 'get_resources'), 1);
}

/*
 * @since 4.0
 * Fixes issue with JetPack Comments
 */
add_filter( 'comment_form_default_fields' , 'zn_wp_comment_filter', 98 );
add_filter( 'comment_form_field_comment' , 'zn_wp_comment_form_field_comment', 98 );

if ( !function_exists('zn_wp_comment_filter') ){
    function zn_wp_comment_filter( $fields )
    {
        $fields['author'] = str_replace( '<input ', '<input class="form-control" placeholder="'.__('Name','zn_framework').'" ', $fields['author'] );
        $fields['email'] = str_replace( '<input ', '<input class="form-control" placeholder="'.__('Email','zn_framework').'" ', $fields['email'] );
        $fields['url'] = str_replace( '<input ', '<input class="form-control" placeholder="'.__('Website','zn_framework').'" ', $fields['url'] );

        $fields['author'] = '<div class="row"><div class="form-group col-sm-4">' .$fields['author'].'</div>';
        $fields['email'] = '<div class="form-group col-sm-4">' .$fields['email'].'</div>';
        $fields['url'] = '<div class="form-group col-sm-4">' .$fields['url'].'</div></div>';

        return $fields;
    }
}

if ( !function_exists('zn_wp_comment_form_field_comment') ){
    function zn_wp_comment_form_field_comment( $textarea ){
        $textarea = str_replace( '<textarea ', '<textarea class="form-control" placeholder="'.__('Message:','zn_framework').'" ',$textarea );
        $textarea = '<div class="row"><div class="form-group col-sm-12">'. $textarea .'</div></div>';
        return $textarea;
    }
}

/*
 * Update custom fonts paths in case domain changed
 *
 * @since v4.15.6
 */
add_action( 'admin_init', 'znhg_update_font_paths', 2, 80000 );
function znhg_update_font_paths(){
	if( ! ZNHGFW()->isDomainChanged() ){
		return;
	}

	//#! Get theme options
	$optName = ZNHGTFW()->getThemeDbId();
	//#! Get custom fonts
	$customFontsOptions = zget_option( 'zn_custom_fonts', 'google_font_options' );
	if( empty($customFontsOptions)){
		return;
	}

	//#! Check if domain changed and update font paths
	$currentDomain = home_url();
	// Get the saved domain from DB. Note that the domain URI is reversed at this point
	$savedDomain = get_option( 'znhgfw_current_domain' );
	//#! Get theme options
	$themeOptions = get_option( $optName );
	//#! Flag to see whether or not we had changes
	$hasChanges = false;

	//#! Update paths
	foreach( $customFontsOptions as $i => &$entry ){
		if( isset($entry['cf_woff'])){
			$hasChanges = true;
			$entry['cf_woff'] = str_ireplace( $savedDomain, $currentDomain, $entry['cf_woff'] );
		}
		if( isset($entry['cf_ttf'])){
			$hasChanges = true;
			$entry['cf_ttf'] = str_ireplace( $savedDomain, $currentDomain, $entry['cf_ttf'] );
		}
		if( isset($entry['cf_svg'])){
			$hasChanges = true;
			$entry['cf_svg'] = str_ireplace( $savedDomain, $currentDomain, $entry['cf_svg'] );
		}
		if( isset($entry['cf_eot'])){
			$hasChanges = true;
			$entry['cf_eot'] = str_ireplace( $savedDomain, $currentDomain, $entry['cf_eot'] );
		}
	}

	//#! Update options if we have changes
	if( $hasChanges ){
		$themeOptions['google_font_options']['zn_custom_fonts'] = $customFontsOptions;
		update_option( $optName, $themeOptions );
	}
}
//</editor-fold desc=">>> WP HOOKS - CUSTOM">
