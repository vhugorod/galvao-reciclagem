<?php if(! defined('ABSPATH')){ return; }
/**
 * Displays the main header
*/
global $post;

if ( !isset( $post ) || empty( $post ) ) {
	$pid = get_option( 'page_for_posts' );
	$post = get_post( $pid );
}

$style = "";
$show_header = true;
if( is_singular() && get_post_meta( get_the_ID() , 'show_header', true ) === 'zn_dummy_value') {
	$show_header = false;
	if ( ZNB()->utility->isActiveEditor() ){
		$show_header = true;
		$style = ' style="display:none" ';
	}
}
// Possibility to override
$show_header = apply_filters('zn_display_header', $show_header);

/* Should we display a template ? */
$config = zn_get_pb_template_config( 'header' );
if( $config['template'] !== 'no_template' ){
	// We have a subheader template... let's get it's possition
	$pb_data = get_post_meta( $config['template'], 'zn_page_builder_els', true );

	if( $config['location'] === 'before' ){
		echo '<div class="znpb-header-smart-area" '. $style .'>';
			ZNB()->frontend->renderUneditableContent( $pb_data, $config['template'] );
		echo '</div>';
	}
	elseif( $config['location'] === 'replace' && $show_header ){
		echo '<div class="znpb-header-smart-area" '. $style .'>';
			ZNB()->frontend->renderUneditableContent( $pb_data, $config['template'] );
		echo '</div>';
		$show_header = false;
	}

}



// Bail early if we don't have to show the header
if( ! $show_header ){ return; }

$header_class = array();

$header_class[] = apply_filters('zn_header_class', '');

/*
 * Header Layout
 */
$header_class[] = $headerLayoutStyle = zn_get_header_layout();

/*
 * Call to Action button
 */
if ( zget_option( 'head_show_cta', 'general_options', false, 'no' ) == 'yes' ) {
	$header_class[] = 'cta_button';
}

// Sticky menu
$menu_follow = zn_get_layout_option( 'menu_follow', 'general_options', false, 'no' );

$header_class[] = $stickyMenu = ( 'sticky' == $menu_follow && $headerLayoutStyle != 'style16' ) ? 'header--sticky header--not-sticked' : '';
$header_class[] = $follow_menu = ( 'yes' == $menu_follow ) ? 'header--follow' : '';
$header_class[] = ( 'fixed' == $menu_follow ) ? 'header--fixed' : '';
$header_class[] = ( 'no' == $menu_follow ) ? 'header--no-stick' : '';
$stickyMenuAttrs = '';

if( 'yes' == zget_option('zn_header_xs_absolute', 'general_options', false, 'no') )
{
	//#! Whether or not the header is absolutely positioned on mobile devices
	$header_class[] = 'site-header--absolute-xs';
	//#! Color scheme
	$header_class[] = 'site-header-xs-color-scheme--' . zget_option( 'header_xs_absolute_dropdown_color_scheme', 'general_options', false, 'light' );
}
//#! If bg color selected, add class
if( '' != zget_option( 'zn_header_resp_color', 'general_options', false, '' ) ) {
	$header_class[] = 'headerstyle-xs--image_color';
}



$sticky_header_text_scheme = zget_option( 'sticky_header_text_scheme', 'general_options', false, 'default' );
if(!empty($stickyMenu) && $sticky_header_text_scheme != 'default'){
	$stickyMenuAttrs .= ' data-custom-sticky-textscheme="sh--'.$sticky_header_text_scheme.'"';
}

// Resize header on sticked mode. Append class;
$header_class[] = zn_resize_sticky_header() ? ' sticky-resize':'';

/*
 * Header Custom Background
 */
$header_style = zn_get_layout_option( 'header_style', 'general_options', false, 'default' );
$headerStyleScheme = $header_style == 'image_color' ? 'headerstyle--image_color' : 'headerstyle--default';
$header_class[] = $headerStyleScheme;
/*
 * Header text colors
 */
$headerTextScheme = 'sh--default'; // the default value
$header_text_scheme = zn_get_layout_option( 'header_text_scheme', 'general_options', false, 'default' );

// Absolute / Relative header (from 4.0.3)
$header_pos = zn_get_layout_option( 'head_position', 'general_options', false, '1' );
// Check for page layout override
if($header_pos == 1 || $header_pos == 'abs' ) {
	$header_position =  'site-header--absolute';
} else if($header_pos == 0 || $header_pos == 'rel' ) {
	$header_position =  'site-header--relative';
}
$header_class[] = $header_position;

// General dropdown color scheme
$nav_color_theme = 'nav-th--' . ( zget_option( 'nav_color_theme', 'general_options', false, '' ) == '' ?
		zget_option( 'zn_main_style', 'color_options', false, 'light' ) :
		zget_option( 'nav_color_theme', 'general_options', false, '' ) );
$header_class[] = $nav_color_theme;

if ( ZNB()->utility->isActiveEditor() ) {
	echo '<a href="#" title="Hide header to access the first element." class="toggle-header js-toggle-class" data-target-class="site-header--hide"><span class="glyphicon glyphicon-chevron-up"></span></a>';
}

do_action('th_display_site_header'); // Used for backwards compatility. It will be removed in a future version

// Add a hook before the header display
do_action('zn_before_siteheader');

// Load the header style
include(locate_template('components/theme-header/header-'. $headerLayoutStyle .'.php'));

if( $config['template'] !== 'no_template' && $config['location'] === 'after' ){
	echo '<div class="znpb-footer-smart-area" '. $style .'>';
		ZNB()->frontend->renderUneditableContent( $pb_data, $config['template'], 'znpb-footer-smart-area' );
	echo '</div>';
}

// Add a hook after the header display
do_action('zn_after_siteheader');
