<?php
/**
 * Theme options > General Options  > Navigation options
 */


$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( 'NAVIGATION OPTIONS', 'zn_framework' ),
	"description" => __( 'These options below are related to site\'s navigations. For example the header contains 2 registered menus: "Main Navigation" and "Header Navigation".', 'zn_framework' ),
	"id"          => "info_title7",
	"type"        => "zn_title",
	"class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Header Dropdowns color scheme", 'zn_framework' ),
	"description" => __( "Select the color scheme for the dropdown menus in the site header (topnav, cart container, language dropdown etc.)", 'zn_framework' ),
	"id"          => "nav_color_theme",
	"std"         => '',
	'options'        => array(
		'' => 'Inherit from Kallyas options > Color Options [Requires refresh]',
		'light' => 'Light (default)',
		'dark' => 'Dark'
	),
	"type"        => "select"
);


$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
				"name"        => __( 'Main Menu', 'zn_framework' ),
				"description" => __( 'These options are dedicated to the Main Menu navigation in Header.', 'zn_framework' ),
				"id"          => "hd_title1",
				"type"        => "zn_title",
				"class"       => "zn_full zn-custom-title-large zn-top-separator"
);

// Menu TYPOGRAPHY
$nav_default = zget_option( 'menu_font', 'font_options', false, array (
		'font-size'   => '14px',
		'font-family'   => 'Lato',
		'line-height' => '14px',
		'font-style'  => 'normal',
		'font-weight'  => '700',
	)
);

if(isset($nav_default['color'])){
	unset($nav_default['color']);
}

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Menu style for 1st level menu items", 'zn_framework' ),
	"description" => __( "Specify the style of the Main Menu's first level links.", 'zn_framework' ),
	"id"          => "menu_style",
	"std"         => '',
	"options"     => array(
		array(
			'value' => '',
			'name'  => __( 'Inherit', 'zn_framework' ),
			'desc'  => __( 'Will inherit from header\'s layout.', 'zn_framework' ),
		),
		array(
			'value' => 'active-bg',
			'name'  => __( 'Background Color on hover/active', 'zn_framework' ),
			'image' => THEME_BASE_URI .'/images/admin/various-theme-options/nav-options-menustyle-bgcolor.gif'
		),
		array(
			'value' => 'active-text',
			'name'  => __( 'Text Color on hover/active', 'zn_framework' ),
			'image' => THEME_BASE_URI .'/images/admin/various-theme-options/nav-options-menustyle-textcolor.gif'
		),
		array(
			'value' => 'active-uline',
			'name'  => __( 'Text Underline on hover/active', 'zn_framework' ),
			'image' => THEME_BASE_URI .'/images/admin/various-theme-options/nav-options-menustyle-textunderline.gif'
		),
	),
	"type"        => "smart_select",
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Menu Font Options for 1st level menu items", 'zn_framework' ),
	"description" => __( "Specify the typography properties for the Main Menu's first level links.", 'zn_framework' ),
	"id"          => "menu_font",
	"std"         => $nav_default,
	'supports'   => array( 'size', 'font', 'line', 'color', 'style', 'weight', 'spacing', 'case' ),
	"type"        => "font"
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Hover / Active color for 1st level menu items", 'zn_framework' ),
	"description" => __( "Specify the hover or active color of the Main Menu's first level links.", 'zn_framework' ),
	"id"          => "menu_font_active",
	"std"         => zget_option( 'zn_main_color', 'color_options', false, '#cd2122' ),
	'alpha'   => true,
	"type"        => "colorpicker"
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
				"name"        => __( 'Main menu - Sub-menus options', 'zn_framework' ),
				"description" => __( "These options are dedicated to the main menu's submenu navigation in Header.", 'zn_framework' ),
				"id"          => "hd_title3",
				"type"        => "zn_title",
				"class"       => "zn_full zn-custom-title-large"
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Sub-Menu Font Options", 'zn_framework' ),
	"description" => __( "Specify the typography properties for the Main sub-menu.", 'zn_framework' ),
	"id"          => "menu_font_sub",
	"std"         => $nav_default,
	'supports'   => array( 'size', 'font', 'line', 'color', 'style', 'weight', 'case' ),
	"type"        => "font"
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Main menu Dropdowns color scheme", 'zn_framework' ),
	"description" => __( "Select the color scheme for the MAIN MENU in the site header", 'zn_framework' ),
	"id"          => "navmain_color_theme",
	"std"         => '',
	'options'        => array(
		'' => 'Inherit from Kallyas options > Color Options [Requires refresh]',
		'light' => 'Light (default)',
		'dark' => 'Dark'
	),
	"type"        => "select"
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
				"name"        => __( 'Mobile Navigation', 'zn_framework' ),
				"description" => __( "These options are dedicated to the main menu's mobile navigation in Header.", 'zn_framework' ),
				"id"          => "hd_title3",
				"type"        => "zn_title",
				"class"       => "zn_full zn-custom-title-large"
);

$admin_options[] = array(
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	'id'          => 'header_res_width',
	'name'        => __( 'Header responsive breakpoint-width', 'zn_framework'),
	'description' => __( 'Choose the desired browser width (viewport) when the Mobile-menu and Hamburger-Icon should be displayed.', 'zn_framework' ),
	'type'        => 'slider',
	'class'       => 'zn_full',
	'std'        => '992',
	'helpers'     => array(
		'min' => '50',
		'max' => '2561'
	)
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"id"          => "mobile_menu_theme",
	"name"        => __( "Mobile-Menu Colors Theme", 'zn_framework' ),
	"description" => __( 'Choose the theming colors style for the side menu theme eg: <a href="http://hogash.d.pr/xCqo" target="_blank">Light version</a> and <a href="http://hogash.d.pr/15l83" target="_blank">Dark version</a>. ', 'zn_framework' ),
	"std"         => "light",
	'type'        => 'select',
	'options'        => array(
		'light' => __( "Light theme", 'zn_framework' ),
		'dark' => __( "Dark theme", 'zn_framework' ),
	),
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"id"          => "burger_style",
	"name"        => __( "Hamburger-Icon Style", 'zn_framework' ),
	"description" => __( "Choose the hamburger icon style.", 'zn_framework' ),
	"std"         => "3--s",
	'type'        => 'select',
	'options'        => array(
		'1--s' => __( "Small 1px lines", 'zn_framework' ),
		'2--s' => __( "Small 2px lines", 'zn_framework' ),
		'3--s' => __( "Small 3px lines", 'zn_framework' ),
		'4--s' => __( "Small 4px lines", 'zn_framework' ),
		'1--m' => __( "Medium 1px lines", 'zn_framework' ),
		'2--m' => __( "Medium 2px lines", 'zn_framework' ),
		'3--m' => __( "Medium 3px lines", 'zn_framework' ),
		'4--m' => __( "Medium 4px lines", 'zn_framework' ),
	),
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"id"          => "burger_anim",
	"name"        => __( "Hamburger-Icon Animation", 'zn_framework' ),
	"description" => __( "Choose the hamburger animation style.", 'zn_framework' ),
	"std"         => "anim1",
	'type'        => 'select',
	'options'        => array(
		'anim1' => __( "Animation #1.", 'zn_framework' ),
		'anim2' => __( "Animation #2.", 'zn_framework' ),
		'anim3' => __( "Animation #3.", 'zn_framework' ),
	),
);


$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"id"          => "burger_color",
	"name"        => __( "Hamburger-Icon Color", 'zn_framework' ),
	"description" => __( "Choose the hamburger colors.", 'zn_framework' ),
	"std"         => "",
	'type'        => 'select',
	'options'        => array(
		'' => __( "Inherit from Header's built-in styles.", 'zn_framework' ),
		'custom' => __( "Force Custom Color", 'zn_framework' ),
	),
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Hamburger-Icon - Custom Color", 'zn_framework' ),
	"description" => __( "Select a color.", 'zn_framework' ),
	"id"          => "burger_color_custom",
	"std"         => "",
	"type"        => "colorpicker",
	"alpha"       => "true",
	"dependency"  => array( 'element' => 'burger_color' , 'value'=> array('custom') ),
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Hamburger-Icon - Custom Color for Sticky Header", 'zn_framework' ),
	"description" => __( "Select a color for the hamburger icon when in sticky header mode.", 'zn_framework' ),
	"id"          => "burger_color_sticky",
	"std"         => "",
	"type"        => "colorpicker",
	"alpha"       => "true",
	"dependency"  => array( 'element' => 'burger_color' , 'value'=> array('custom') ),
);

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Hamburger-Icon - Custom Color for Mobiles (under 767px)", 'zn_framework' ),
	"description" => __( "Force a custom color for the hamburger icon, when on mobiles.", 'zn_framework' ),
	"id"          => "burger_color_mobile",
	"std"         => "",
	"type"        => "colorpicker",
	"alpha"       => "true",
	"dependency"  => array( 'element' => 'burger_color' , 'value'=> array('custom') ),
);


$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
				"name"        => __( 'Top-Header Menu', 'zn_framework' ),
				"description" => __( 'These options are dedicated to the Header-Navigation in TOP-Header.', 'zn_framework' ),
				"id"          => "hd_title1",
				"type"        => "zn_title",
				"class"       => "zn_full zn-custom-title-large zn-top-separator"
);
$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( "Enable dropdown Top Header Navigation? .", 'zn_framework' ),
	"description" => __( "Only available for smartphones and tablets. This option will enable a dropdown menu for the header-navigation (not main-menu!). If you have for example lots of menu items in header, this option will fallback nicely in the header.", 'zn_framework' ),
	"id"          => "header_topnav_dd",
	"std"         => "yes",
	"value"         => "yes",
	"type"        => "toggle2",
);


// HELP STARTS HERE

$admin_options[] = array (
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
	"name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
	"description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
	"id"          => "nvo_title",
	"type"        => "zn_title",
	"class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
);

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
	'slug'        => 'nav_options',
	'parent'      => 'general_options',
));