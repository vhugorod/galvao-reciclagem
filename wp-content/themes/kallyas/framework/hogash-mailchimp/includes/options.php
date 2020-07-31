<?php if( ! defined( 'ABSPATH' ) ) {
	return;
}
/**
 * Theme options > General Options  > Mailchimp options
 */

$mailchimpURL = 'https://mailchimp.com/';
$admin_options[] = array (
	'slug'        => 'mailchimp_options',
	'parent'      => 'general_options',
	"name"        => __( 'MAILCHIMP OPTIONS', 'hogash-mailchimp' ),
	"description" => sprintf( __( 'The options below are related to <a href="%s" target="_blank">MailChimp</a> (Online email marketing) platform integration. ', 'hogash-mailchimp' ), $mailchimpURL),
	"id"          => "info_title12",
	"type"        => "zn_title",
	"class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);

$mailchimpApiUrl = 'http://kb.mailchimp.com/integrations/api-integrations/about-api-keys';
$admin_options[] = array (
	'slug'        => 'mailchimp_options',
	'parent'      => 'general_options',
	"name"        => __( "MailChimp API KEY", 'hogash-mailchimp' ),
	"description" => sprintf( __( 'Paste your MailChimp API Key that will be used. Here\'s <a href="%s" target="_blank">how to generate one</a>.', 'hogash-mailchimp' ), $mailchimpApiUrl ),
	"id"          => "mailchimp_api",
	"std"         => '',
	"type"        => "textarea"
);

$admin_options[] = array (
	'slug'        => 'mailchimp_options',
	'parent'      => 'general_options',
	'id'            => 'mailchimp_double_opt_in',
	'name'          => 'Enable Double Opt in?',
	'description'   => 'If enabled, the users will receive an opt-in confirmation email.',
	'type'          => 'toggle2',
	'std'           => 'no',
	'value'         => 'yes'
);

$admin_options[] = array (
	'slug'        => 'mailchimp_options',
	'parent'      => 'general_options',
	"name"        => __( 'GDPR Options', 'hogash-mailchimp' ),
	"description" => __( 'These options helps you be in compliance with General Data Protection Regulation.', 'hogash-mailchimp' ),
	"id"          => "gdpr_info",
	"type"        => "zn_title",
	"class"       => "zn_full zn-custom-title-large zn-top-separator"
);

$admin_options[] = array(
	'slug'        => 'mailchimp_options',
	'parent'      => 'general_options',
	"name"        => __( 'After form consent', 'hogash-mailchimp' ),
	"description" => __( 'Using this option you can add extra information after the newsletter forms.', 'hogash-mailchimp' ),
	"id"          => "after_newsletter_boxes",
	"type"        => "group",
	"subelements"       => array(
		array(
			"name"        => __( 'Checkbox text', 'hogash-mailchimp' ),
			"description" => __( 'Add the text that will appear next to the checkbox.', 'hogash-mailchimp' ),
			"id"          => "text",
			"type"        => "visual_editor",
			"class"       => "zn_full"
		),
		array(
			"name"        => __( 'Validation error text', 'hogash-mailchimp' ),
			"description" => __( "Add the text that will appear if the user doesn't check the checkbox.", 'hogash-mailchimp' ),
			"id"          => "validation_text",
			"type"        => "visual_editor",
			"class"       => "zn_full"
		),
	)
);


/*
 * Render status output
 */

$tableStart = '<div class="table-status-wrapper"><table class="wp-list-table widefat fixed striped"><thead>';
$tableStart .= '<tr>';
$tableStart .= '<th>'.__( 'List ID', 'hogash-mailchimp').'</th>';
$tableStart .= '<th>'.__( 'List Name', 'hogash-mailchimp').'</th>';
$tableStart .= '<th>'.__( 'Web ID', 'hogash-mailchimp').'</th>';
$tableStart .= '<th>'.__( 'Subscribed', 'hogash-mailchimp').'</th>';
$tableStart .= '<th>'.__( 'Unsubscribed', 'hogash-mailchimp').'</th>';
$tableStart .= '<th>'.__( 'Cleaned', 'hogash-mailchimp').'</th>';
$tableStart .= '</tr>';
$tableContent = '</thead><tbody>';
$tableEnd = '</tbody></table></div>';


$mcApiKey = zget_option( 'mailchimp_api', 'general_options' );
/*
 * TODO: IMPROVE STATUS DISPLAY
 */
if ( !empty( $mcApiKey ) ) {
	Hg_Mailchimp::loadHgMcApiClass();

	$mcapi = new HG_MCAPI( $mcApiKey );

	$mcStatus = '<strong>'.__( 'Refresh to update.', 'hogash-mailchimp').'</strong><br><br>';
	$lists    = $mcapi->getLists();

	$status = '<span style="color:#ff0000;">'.__( 'Not Connected!', 'hogash-mailchimp').'</span>';

	if ( 200 <> $mcapi->responseCode ) {
		$mcStatus .= __( 'Unable to load lists!', 'hogash-mailchimp').'<br>';
		$mcStatus .= __("Code: ", 'hogash-mailchimp') . $mcapi->responseCode . "<br>";
		$mcStatus .= __("Msg: ", 'hogash-mailchimp') . $mcapi->getErrorMessage() . ' <br>';
		$status   = '<span style="color:#ff0000;">'.__( 'Not Connected!', 'hogash-mailchimp').'</span>';
	}
	else {
		$status = '<span style="color:#009900;">'.__( 'Connected!', 'hogash-mailchimp').'</span>';
		$mcStatus .= __("Lists that matched: ", 'hogash-mailchimp') . $lists[ 'total_items' ] . "<br>";
		$mcStatus .= __("Lists returned: ", 'hogash-mailchimp') . count( $lists[ 'lists' ] ) . "<br><br>";

		foreach ( $lists[ 'lists' ] as $list ) {
			$tableContent .= '<tr>';
			$tableContent .= '<td>'.esc_html($list[ 'id' ]).'</td>';
			$tableContent .= '<td>'.esc_html($list[ 'name' ]).'</td>';
			$tableContent .= '<td>'.esc_html($list[ 'web_id' ]).'</td>';
			$tableContent .= '<td>'.esc_html($list[ 'stats' ][ 'member_count' ]).'</td>';
			$tableContent .= '<td>'.esc_html($list[ 'stats' ][ 'unsubscribe_count' ]).'</td>';
			$tableContent .= '<td>'.esc_html($list[ 'stats' ][ 'cleaned_count' ]).'</td>';
			$tableContent .= '</tr>';
		}
		$mcStatus .= $tableStart . $tableContent . $tableEnd;
	}

	$admin_options[] = array(
		'slug' => 'mailchimp_options',
		'parent' => 'general_options',
		'name' => 'Status - ' . $status,
		'description' => $mcStatus,
		'type' => 'zn_title',
		'id' => 'zn_error_notice',
		'class' => 'zn_full'
	);
}
