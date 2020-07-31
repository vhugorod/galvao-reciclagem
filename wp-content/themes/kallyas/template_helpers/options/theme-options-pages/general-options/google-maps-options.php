<?php
/**
 * Theme options > General Options  > Google Analytics
 */

$admin_options[] = array (
    'slug'        => 'google_maps',
    'parent'      => 'general_options',
    "name"        => __( 'GOOGLE MAPS OPTIONS', 'zn_framework' ),
    "description" => __( 'The options below are related to Google Maps API key integration in Kallyas. ', 'zn_framework' ),
    "id"          => "info_title11",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);


$google_dev_link = "https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key";

$admin_options[] = array (
    'slug'        => 'google_maps',
    'parent'      => 'general_options',
    "name"        => __( "Google Maps API Key", 'zn_framework' ),
    "description" => sprintf( __('Add a Google Map Api Key. This key will be used by Page Builder elements that require a Google Map API Key, for example the Google Map element. More on <a target="_blank" href="%s">Google Maps Key</a>', 'zn_framework' ), $google_dev_link),
    "id"          => "google_maps_key",
    "std"         => '',
    "type"        => "textarea",
    "class"       => "zn_full"
);

// $admin_options[] = array (
//     'slug'        => 'google_analytics',
//     'parent'      => 'general_options',
//     "name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
//     "description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
//     "id"          => "gao_title",
//     "type"        => "zn_title",
//     "class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
// );

// $admin_options[] = zn_options_video_link_option( 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#zxQaeY_bFxY', __( "Click here to access the video tutorial for this section's options.", 'zn_framework' ), array(
//     'slug'        => 'google_analytics',
//     'parent'      => 'general_options'
// ));
// $admin_options[] = zn_options_doc_link_option( 'https://my.hogash.com/documentation/google-analytics/', array(
//     'slug'        => 'google_analytics',
//     'parent'      => 'general_options'
// ));

// $admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
//     'slug'        => 'google_analytics',
//     'parent'      => 'general_options',
// ));
