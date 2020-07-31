<?php if(! defined('ABSPATH')){ return; }
if( ZN_HogashDashboard::isManagedApiKey() ){
	if( ZN_HogashDashboard::isConnected() ){
		?>
		<div class="inline notice notice-success">
			<p>
				<?php esc_html_e('The theme has been registered and connected with the Hogash Dashboard. To change the API Key, please contact your site administrator.', 'zn_framework'); ?>
			</p>
		</div>
		<?php
	}
	else {
		$result = ZN_HogashDashboard::connectTheme( ZN_HogashDashboard::getManagedApiKey() );
		if( isset($result['code']) && $result['code'] == ZN_HogashDashboard::E_SUCCESS ) {
			?>
			<div class="inline notice notice-success">
				<p>
					<?php esc_html_e('The theme has been registered and connected with the Hogash Dashboard. To change the API Key, please contact your site administrator.', 'zn_framework'); ?>
				</p>
			</div>
			<?php
		}
		else {
			?>
			<div class="inline notice notice-success">
				<p>
					<?php
						$e = ( isset($result['message']) ? $result['message'] : esc_html__( 'No response from server.', 'zn_framework' ) );
						echo esc_html(sprintf(esc_html__('An error occurred: %s', 'zn_framework'), $e ) );
					?>
				</p>
			</div>
			<?php
		}
	}
}
else {
	include( ZNHGTFW()->getFwPath( 'inc/admin/tmpl/form-register-theme-tmpl.php' ));
}
