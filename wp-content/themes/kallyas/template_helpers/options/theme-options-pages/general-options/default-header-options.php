<?php
/**
 * Theme options > General Options  > Default Header Options
 */
$desc = sprintf(
    '%s ( <a href="%s" target="_blank" title="%s">%s</a>).',
    __( 'These options below are related to site\'s default sub-header block.', 'zn_framework' ),
    esc_url( 'http://hogash.d.pr/14aJa' ),
    __( 'Click to open screenshot', 'zn_framework' ),
    __( 'Open screenshot', 'zn_framework' )
);
$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( 'DEFAULT SUB-HEADER OPTIONS', 'zn_framework' ),
    "description" => $desc,
    "id"          => "info_title9",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);


$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Hide page subheader?", 'zn_framework' ),
    "description" => __( "Choose yes if you want to hide the page subheader ( including sliders ). Please note that this option can be overridden from each page/post", 'zn_framework' ),
    "id"          => "zn_disable_subheader",
    "std"         => 'no',
    "options"     => array ( 'yes' => __( "Yes for all", 'zn_framework' ),'yespb' => __( "Yes. only for dynamic sub-headers", 'zn_framework' ), 'no' => __( "No", 'zn_framework' ) ),
    "type"        => "zn_radio",
//    "class"        => "zn_radio--yesno",
);

$admin_options[] = array (
                'slug'        => 'default_header_options',
                'parent'      => 'general_options',
                "name"        => __( 'Background options', 'zn_framework' ),
                "description" => __( 'These options are applied to the background of the subheader.', 'zn_framework' ),
                "id"          => "hd_title1",
                "type"        => "zn_title",
                "class"       => "zn_full zn-custom-title-large zn-top-separator"
);

// Header background image
$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Sub-Header Background image", 'zn_framework' ),
    "description" => __( "Upload your desired background image for the header.", 'zn_framework' ),
    "id"          => "def_header_bg_new",
    "std"         => array(
            // Backwards compatible bg options
            'image' => zget_option( 'def_header_background', 'general_options', false, false )
        ),
    "type"        => "background",
    'options' => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
);

// Header background color
$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Sub-Header Background Color", 'zn_framework' ),
    "description" => __( "Here you can choose a default color for your header.If you do not select a background image, this color will be used as background.", 'zn_framework' ),
    "id"          => "def_header_color",
    "std"         => '#AAAAAA',
    'alpha'       => true,
    "type"        => "colorpicker"
);

$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Add gradient over color?", 'zn_framework' ),
    "description" => __( "Select yes if you want add a gradient over the selected color", 'zn_framework' ),
    "id"          => "def_grad_bg",
    "std"         => "1",
    "type"        => "zn_radio",
    "options"     => array (
        "1" => __( "Yes", 'zn_framework' ),
        "0" => __( "No", 'zn_framework' ),
    ),
    "class"        => "zn_radio--yesno",
);

// HEADER - Animate

$admin_options[]    = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Use animated header", 'zn_framework' ),
    "description" => __( "Select if you want to add an animation on top of your image/color.", 'zn_framework' ),
    "id"          => "def_header_animate",
    "std"         => "0",
    "type"        => "zn_radio",
    "options"     => array (
        '1' => __('Yes', 'zn_framework'),
        '0' => __('No', 'zn_framework'),
    ),
    "class"        => "zn_radio--yesno",
);

$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Add Glare effect?", 'zn_framework' ),
    "description" => __( "Select yes if you want to add a glare effect over the background", 'zn_framework' ),
    "id"          => "def_glare",
    "std"         => "0",
    "type"        => "zn_radio",
    "options"     => array (
        "1" => __( "Yes", 'zn_framework' ),
        "0" => __( "No", 'zn_framework' )
    ),
    "class"        => "zn_radio--yesno",
);

$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Bottom mask", 'zn_framework' ),
    "description" => __( "The new masks are svg based, vectorial and color adapted.", 'zn_framework' ),
    "id"          => "def_bottom_style",
    "std"         => "none",
    "type"        => "select",
    "options"     => zn_get_masks(),
);
$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    'name'        => 'Bottom Mask Background Image',
    'description' => 'Select the image for the mask.',
    "id"          => "bottom_mask_bg_image",
    'type'        => 'background',
    'options' => array( "repeat" => true , "position" => true , "attachment" => true, "size" => true ),
    "dependency"  => array( 'element' => 'def_bottom_style' , 'value'=> array('custom') ),
);
$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    'name'        => __( 'Custom Mask height (in pixels)', 'zn_framework'),
    'description' => __( 'Specify the height for the custom mask.', 'zn_framework' ),
    'id'          => 'bottom_mask_bg_height',
    'type'        => 'slider',
    'std'        => '100',
    'helpers'     => array(
        'min' => '1',
        'max' => '200'
    ),
    "dependency"  => array( 'element' => 'def_bottom_style' , 'value'=> array('custom') ),
);


$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    'id'          => 'def_bottom_style_bg',
    'name'        => 'Bottom Mask Background Color',
    'description' => 'If you need the mask to have a different color than the main site background, please choose the color. Usually this color is needed when the next section, under this one has a different background color.',
    'type'        => 'colorpicker',
    'std'         => '',
    "dependency"  => array( 'element' => 'def_bottom_style' , 'value'=> zn_get_masks_deps() ),
);


$admin_options[] = array (
                'slug'        => 'default_header_options',
                'parent'      => 'general_options',
                "name"        => __( 'Components options', 'zn_framework' ),
                "description" => __( 'These options are applied to the contents of the subheader.', 'zn_framework' ),
                "id"          => "hd_title1",
                "type"        => "zn_title",
                "class"       => "zn_full zn-custom-title-large zn-top-separator"
);

// HEADER show breadcrumbs
$admin_options[]     = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Show Breadcrumbs", 'zn_framework' ),
    "description" => __( "Select if you want to show the breadcrumbs or not.", 'zn_framework' ),
    "id"          => "def_header_bread",
    "std"         => "",
    "type"        => "zn_radio",
    "options"     => array (
        '1' => __( 'Show', 'zn_framework' ),
        '0' => __( 'Hide', 'zn_framework' ),
    ),
    "class"        => "zn_radio--yesno",
);
$admin_options[]     = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Breadcrumbs style", 'zn_framework' ),
    "description" => __( "Select Breadcrumbs style.", 'zn_framework' ),
    "id"          => "def_subh_bread_stl",
    "std"         => "black",
    "type"        => "select",
    "options"     => array (
        'black' => __( 'Black Bar', 'zn_framework' ),
        'minimal' => __( 'Minimal', 'zn_framework' ),
    ),
    "dependency"  => array(
        'element' => 'def_header_bread' ,
        'value'=> array('1')
    ),
);

// HEADER show date

$admin_options[]    = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Show Date", 'zn_framework' ),
    "description" => __( "Select if you want to show the current date under breadcrumbs or not.", 'zn_framework' ),
    "id"          => "def_header_date",
    "std"         => "",
    "type"        => "zn_radio",
    "options"     => array (
        '1' => 'Show',
        '0' => 'Hide'
    ),
    "class"        => "zn_radio--yesno",
);

// HEADER show title

$admin_options[]     = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Show Page Title", 'zn_framework' ),
    "description" => __( "Select if you want to show the page title or not.", 'zn_framework' ),
    "id"          => "def_header_title",
    "std"         => "",
    "type"        => "zn_radio",
    "options"     => array (
        '1' => __( 'Show', 'zn_framework' ),
        '0' => __( 'Hide', 'zn_framework' ),
    ),
    "class"        => "zn_radio--yesno",
);

// HEADER show subtitle

$admin_options[]        = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Show Page Subtitle", 'zn_framework' ),
    "description" => __( "Select if you want to show the page subtitle or not.", 'zn_framework' ),
    "id"          => "def_header_subtitle",
    "std"         => "",
    "type"        => "zn_radio",
    "options"     => array (
        '1' => __( 'Show', 'zn_framework' ),
        '0' => __( 'Hide', 'zn_framework' ),
    ),
    "class"        => "zn_radio--yesno",
);

$admin_options[]        = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Title & Subtitle text alignment", 'zn_framework' ),
    "description" => __( "If you have disabled both Breadcrumbs & Date, you can choose to custom align the title and subtitle in subheader. You can override this setting in the Custom Subheader Element.", 'zn_framework' ),
    "id"          => "def_subheader_alignment",
    "std"         => "right",
    "type"        => "select",
    "options"     => array (
        'left' => __( 'Left', 'zn_framework' ),
        'center' => __( 'Center', 'zn_framework' ),
        'right' => __( 'Right', 'zn_framework' ),
    ),
    "dependency"  => array(
        array(
            'element' => 'def_header_bread' ,
            'value'=> array('0')
        ),
        array(
            'element' => 'def_header_date' ,
            'value'=> array('0')
        ),
    ),
);

$admin_options[]     = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( "Text Color Scheme", 'zn_framework' ),
    "description" => __( "Select the text color scheme.", 'zn_framework' ),
    "id"          => "def_subh_textcolor",
    "std"         => "light",
    "type"        => "select",
    "options"     => array (
        'light' => __( 'Light', 'zn_framework' ),
        'dark' => __( 'Dark', 'zn_framework' ),
    )
);

$admin_options[] = array (
                'slug'        => 'default_header_options',
                'parent'      => 'general_options',
                "name"        => __( 'Height / Padding options', 'zn_framework' ),
                "description" => __( 'These options are applied to the height and top padding of the subheader.', 'zn_framework' ),
                "id"          => "hd_title1",
                "type"        => "zn_title",
                "class"       => "zn_full zn-custom-title-large zn-top-separator"
);

// HEADER Custom height
//@since 3.6.9
//@k
// @4.0.7 Upgraded to slider field
// @4.16.0 Upgraded to smart_slider field
$headerHeight = zget_option( 'def_header_custom_height' , 'general_options', false, '300' );
$admin_options[] = array (
	'slug'        => 'default_header_options',
	'parent'      => 'general_options',
	"name"        => __( "Default Sub-Header Height", 'zn_framework' ),
	"description" => __( "Please enter your desired height in pixels for this header.", 'zn_framework' ),
	"id"          => "def_header_custom_height_v2",
	'type'        => 'smart_slider',
	'std'        => array(
		'breakpoints' => 0,
		'lg' => $headerHeight,
		'unit_lg' => 'px',
		'md' => $headerHeight,
		'unit_md' => 'px',
		'sm' => $headerHeight,
		'unit_sm' => 'px',
		'xs' => $headerHeight,
		'unit_xs' => 'px'
	),
	'supports' => array('breakpoints'),
	'units' => array('px'),
	'helpers'     => array(
		'min' => '1',
		'max' => '1280',
		'step' => '1'
	),
);

$headerPadding = zget_option( 'def_header_top_padding' , 'general_options', false, '170' );
$admin_options[] = array (
	'slug'        => 'default_header_options',
	'parent'      => 'general_options',
	'id'          => 'def_header_top_padding_v2',
	'name'        => 'Top padding',
	'description' => 'Select the top padding ( in pixels ) for this Subheader.',
	'type'        => 'smart_slider',
	'std'        => array(
		'breakpoints' => 0,
		'lg' => $headerPadding,
		'unit_lg' => 'px',
		'md' => $headerPadding,
		'unit_md' => 'px',
		'sm' => $headerPadding,
		'unit_sm' => 'px',
		'xs' => $headerPadding,
		'unit_xs' => 'px'
	),
	'supports' => array('breakpoints'),
	'units' => array('px'),
	'helpers'     => array(
		'min' => '1',
		'max' => '1280',
		'step' => '1'
	),
);

$admin_options[] = array (
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
    "name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
    "description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
    "id"          => "dfho_title",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
);

$admin_options[] = zn_options_video_link_option( 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#1olr-Oy_RD0', __( "Click here to access the video tutorial for this section's options.", 'zn_framework' ), array(
    'slug'        => 'default_header_options',
    'parent'      => 'general_options'
));

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
    'slug'        => 'default_header_options',
    'parent'      => 'general_options',
));
