<?php

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin View: Notice - Update
 * @see class-zn-about.php
 */

?>

<div id="message" class="notice notice-error">
	<h3><?php esc_html_e( 'Theme Data Update Required', 'zn_framework' ); ?></h3>
	<p>&#8211; <?php esc_html_e( 'We just need to update your install to the latest version.', 'zn_framework' ); ?></p>
	<p>&#8211; <?php esc_html_e( "Don't forget about backups, always backup!", 'zn_framework' ); ?></p>
	<p class="submit"><a href="<?php echo esc_url( add_query_arg( 'do_theme_update', 'true', ZNHGTFW()->getComponent( 'utility' )->get_options_page_url() ) ); ?>" class="button-primary zn_run_theme_updater"><?php esc_html_e( 'Run the updater', 'zn_framework' ); ?></a></p>
</div>

<div id="message" class="notice notice-info zn_updater_msg_container">

</div>
