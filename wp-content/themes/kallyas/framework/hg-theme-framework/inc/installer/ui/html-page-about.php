<?php if(! defined('ABSPATH')){ exit; }
/**
 * Admin View: Notice - Update
 * @see class-zn-about.php
 */
?>
<?php
$allowed_html = array(
    'strong' => array(),
);
?>
<div id="message" class="notice notice-success">
    <h3><?php esc_html_e( 'Successfully updated!', 'zn_framework' ); ?></h3>
    <p><?php echo wp_kses(sprintf( __( "Thanks! You've just updated <strong> %s options</strong> to the latest version!", 'zn_framework' ), ZNHGTFW()->getThemeName()),  $allowed_html ); ?></p>
</div>