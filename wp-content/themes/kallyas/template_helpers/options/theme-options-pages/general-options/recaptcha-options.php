<?php
/**
 * Theme options > General Options  > ReCaptcha options
 */

$recaptcha_url = esc_url( 'http://www.google.com/recaptcha' );

$admin_options[] = array (
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options',
	"name"        => __( 'RECAPTCHA OPTIONS', 'zn_framework' ),
	"description" => sprintf( __( 'The options below are related to <a href="%s" target="_blank">Google ReCaptcha</a> security integration in Kallyas forms. <br> Please notice that at the moment, the theme is only compatible with reCaptcha v2. ', 'zn_framework' ), $recaptcha_url ),
	"id"          => "info_title13",
	"type"        => "zn_title",
	"class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);


$admin_options[] = array (
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options',
	"name"        => __( "Recaptcha style", 'zn_framework' ),
	"description" => __( "Choose the desired recapthca style.", 'zn_framework' ),
	"id"          => "rec_theme",
	"std"         => "red",
	"type"        => "select",
	"options"     => array (
		"light"      => __( "Light theme", 'zn_framework' ),
		"dark" => __( "Dark theme", 'zn_framework' ),
	)
);

$admin_options[] = array (
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options',
	"name"        => __( "reCaptcha Site Key", 'zn_framework' ),
	"description" => __( "Please enter the Site Key you've got from ", 'zn_framework' ) . $recaptcha_url,
	"id"          => "rec_pub_key",
	"std"         => "",
	"type"        => "textarea"
);

$admin_options[] = array (
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options',
	"name"        => __( "reCaptcha Secret Key", 'zn_framework' ),
	"description" => __( "Please enter the Secret Key you've got from ", 'zn_framework' ) . $recaptcha_url,
	"id"          => "rec_priv_key",
	"std"         => "",
	"type"        => "textarea"
);

//@since v4.11
$admin_options[] = array (
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options',
	"name"        => __( "Use ReCaptcha for registration?", 'zn_framework' ),
	"description" => __( "Choose yes if you want to display ReCaptcha on the registration form.", 'zn_framework' ),
	"id"          => "recaptcha_register",
	"std"         => 'no',
	"options"     => array ( 'yes' => __( "Yes", 'zn_framework' ), 'no' => __( "No", 'zn_framework' ) ),
	"type"        => "zn_radio",
	"class"        => "zn_radio--yesno",
);

//@since v4.11
$admin_options[] = array (
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options',
	"name"        => __( "ReCaptcha language", 'zn_framework' ),
	"description" => sprintf( __( 'Please specify the language to display the ReCaptcha in. You can get it from  <a href="%s" target="_blank">here</a>', 'zn_framework' ), esc_url( 'https://developers.google.com/recaptcha/docs/language' ) ),
	"id"          => "recaptcha_lang",
	"std"         => '',
	"type"        => "text",
);


$admin_options[] = array (
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options',
	"name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
	"description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
	"id"          => "rco_title",
	"type"        => "zn_title",
	"class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
);

$admin_options[] = zn_options_video_link_option( 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#MXRAmRVaOaY', __( "Click here to access the video tutorial for this section's options.", 'zn_framework' ), array(
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options'
));
$admin_options[] = zn_options_doc_link_option( 'https://my.hogash.com/documentation/configure-recaptcha/', array(
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options'
));

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
	'slug'        => 'recaptcha_options',
	'parent'      => 'general_options',
));
