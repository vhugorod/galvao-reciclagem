<?php
/**
 * This file displays the server check button
 */

$message           = '';
$icon              = 'dashicons-warning';
$connection_status = get_transient( 'zn_server_connection_check' );
$amazon_domain     = 'myhogash.s3.amazonaws.com ';
$btn_class         = '';
if ( 'ok' == $connection_status ) {
	$btn_class = 'zn-action--gray';
	$icon      = 'dashicons-yes';
} elseif ( 'notok' == $connection_status ) {
	$icon    = 'dashicons-no';
	$message = '<br />' . esc_html__( 'It seems that your server cannot connect to Amazon Servers. Demo import will not work.', 'zn_framework' );
}
?>
<div class="zn-server-status-column zn-server-status-column-name"><?php echo esc_html__( 'Connection to Amazon S3', 'zn_framework' ); ?></div>
<div class="zn-server-status-column">
	<span
		class="zn-server-status-column-icon dashicons-before dashicons-update js-zn-server-status-icon <?php echo esc_attr($icon); ?>"
		title="<?php echo sprintf( esc_html__( 'If a connection can be established between your current server and %s server', 'zn_framework' ), $amazon_domain ); ?>"></span>
</div>
<div class="zn-server-status-column zn-server-status-column-value">
	<a class="zn-server-status-button-custom zn-action-input-custom zn-about-action <?php echo esc_attr($btn_class); ?>" href="#"
	   title="<?php echo sprintf( esc_html__( 'Verify the connection to our domain %s to see if you will be able to install our demos or plugins', 'zn_framework' ), $amazon_domain ); ?>"><?php echo esc_html__( 'Check now', 'zn_framework' ); ?></a>
	<?php echo '' . $message; ?>
</div>
