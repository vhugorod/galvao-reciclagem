<?php
/**
 * This file displays the server check button
 */

$message                      = '';
$icon                         = 'dashicons-warning';
$connection_status            = get_transient( 'zn_server_connection_check' );
$hgDomain                     = str_replace( 'http://', '', ZNHGTFW()->getThemeServerUrl() );
$btn_class                    = '';
$server_fix_documentation_url = 'https://my.hogash.com/documentation/how-to-resolve-server-connection/';
if ( 'ok' == $connection_status ) {
	$btn_class = 'zn-action--gray';
	$icon      = 'dashicons-yes';
} elseif ( 'notok' == $connection_status ) {
	$icon    = 'dashicons-no';
	$message = '<br />' . esc_html__( 'It seems that your server cannot connect to Hogash Servers. Some features like demo data import will not work. In order to resolve this, please view the following documentation article.', 'zn_framework' );
	$message .= '<a href="'.$server_fix_documentation_url.'" target="_blank">' . esc_html__( 'How to resolve', 'zn_framework' ) . '</a>';
}
?>
<div class="zn-server-status-column zn-server-status-column-name"><?php echo esc_html__( 'Connection to server', 'zn_framework' ); ?></div>
<div class="zn-server-status-column">
	<span
		class="zn-server-status-column-icon dashicons-before dashicons-update js-zn-server-status-icon <?php echo esc_attr($icon); ?>"
		title="<?php echo sprintf( esc_html__( 'If a connection can be established between your current server and theme servers', 'zn_framework' ), $hgDomain ); ?>"></span>
</div>
<div class="zn-server-status-column zn-server-status-column-value">
	<a class="zn-server-status-button-custom zn-action-input-custom zn-about-action <?php echo esc_attr($btn_class); ?>" href="#"
	   title="<?php echo sprintf( esc_html__( 'Verify the connection to our domain %s to see if you will be able to install our demos or plugins', 'zn_framework' ), $hgDomain ); ?>"><?php echo esc_html__( 'Check now', 'zn_framework' ); ?></a>
	<?php echo '' . $message; ?>
</div>
