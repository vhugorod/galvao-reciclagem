<?php if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionPageBuilderAdmin
 *
 */
class ZionPageBuilderAdmin
{
	function __construct()
	{
		// Add the edit with page builder button on post/pages
		add_action( 'edit_form_after_title', array( $this, 'get_buttons_html' ));

		// Add edit with page builder links to ROW actions
		add_filter( 'page_row_actions', array( $this, 'renderRowAction' ) );
		add_filter( 'post_row_actions', array( $this, 'renderRowAction' ) );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'loadScripts') );

		// Add ajax functionality
		add_action( 'wp_ajax_zn_editor_enabler', array( $this, 'enableEditorAjax' ));
	}

	function enableEditorAjax()
	{
		check_ajax_referer( 'zn_framework', 'security' );

		if( ! ZNB()->utility->allowedEdit() ){
			wp_send_json_error( array( 'message' => __( 'You don\'t have permission to edit this page!', 'zn_framework' ) ) );
		}

		if ( ZNB()->utility->isPageBuilderEnabled() ) {
			ZNB()->utility->disableEditor();
			wp_send_json_success( array('status' => 'disabled') );
		}
		else{
			ZNB()->utility->enableEditor();
			wp_send_json_success( array('status' => 'enabled') );
		}
	}

	/**
	 * Adds the render with pagebuilder action to the inline actions
	 *
	 * @access public
	 * @param $actions array
	 * @return array
	 * @since 1.0.0
	 */
	public function renderRowAction( $actions ){
		$post = get_post();
		$actions['zn_edit'] = '<a href="' . ZNB()->utility->getEditUrl( $post->ID ) . '">' . __( 'Edit with Zion builder', 'zn_framework' ) . '</a>';
		return $actions;
	}

	/**
	 * Load the js/css assets for the edit screen pages
	 * @param  sctring $hook The current page hook
	 * @since 1.0.0
	 */
	function loadScripts( $hook ){
		if ( $hook == 'post.php' || $hook == 'post-new.php' ) {
			wp_enqueue_script( 'znb-edit-screen-js', ZNB()->assetsUrl('js/admin') .'edit-screen.js', 'jquery', ZNB()->version, true );
		}
	}


	public static function get_buttons_html(){
		global $post;

		$enable_text 	= __( 'Enable Zion builder', 'zn_framework' );
		$disable_text  	= __( 'Disable Zion builder', 'zn_framework' );
		$editor_enabled = ZNB()->utility->isPageBuilderEnabled( $post->ID );
		$active_text    = $editor_enabled ? $disable_text : $enable_text;
		$button_css     = $editor_enabled ? '' : 'style="display:none;"';
		$preview_link   = ZNB()->utility->getEditUrl( $post->ID );

		echo '<div class="zn_pb_buttons">';
			wp_nonce_field( 'ZnNonce', 'ZnNonce', false, true );
			echo '<div type="button" name="publish" id="zn_enable_pb" data-postid="'.$post->ID.'" data-active-text="'.$disable_text.'" data-inactive-text="'.$enable_text.'" class="button button-zn_save button-large"><span class="spinner"></span> <span class="zn_bt_text">'.$active_text.'</span></div>';
			echo '<a href="'.$preview_link.'" id="zn_edit_page" class="button button-primary button-large zn_pb_button" '.$button_css.'>Edit this page with pagebuilder</a>';
		echo '</div>';
	}

}
