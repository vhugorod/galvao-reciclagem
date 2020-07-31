<?php

class Znb_Yoast_Integration extends Znb_Integration {

	/**
	 * Check if we can load this integration or not
	 *
	 * @return [type] [description]
	 */
	static public function can_load() {
		return defined( 'WPSEO_VERSION' ) && is_admin();
	}

	function initialize() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts') );
		add_action( 'admin_footer', array( $this, 'wpseo_znpb_data_footer'), 100 );

		// Add ajax actions
		add_action( 'wp_ajax_znpb_get_page_content', array( $this, 'get_page_builder_content' ) );
	}


	/**
	 * Will return the content html for a specific post
	 */
	function get_page_builder_content() {

		// Check to see if the page is actually made with zion builder
		if ( ! ZNB()->utility->isPageBuilderEnabled() ) {
			wp_send_json_error( array(
				'message' => 'The pagebuilder is not enabled for current page',
			));
		}

		ZNB()->frontend->registerDefaultLayoutArea();
		ob_start();
		ZNB()->frontend->renderContentByArea();

		$content = ob_get_clean();
		wp_send_json_success( array(
			'content' => $content,
		));
	}

	/**
	 * Load the js file that will add the PB content to Yoast
	 *
	 * @param sctring $hook The current page hook
	 *
	 * @since 1.0.0
	 */
	function load_scripts( $hook ) {
		if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
			global $post;
			$postId = isset( $post ) && ! empty( $post->ID ) ? $post->ID : false;
			wp_enqueue_script( 'znb-yoast-integration-js', ZNB()->assetsUrl( 'js/admin') . 'yoast-integration.js', 'jquery', ZNB()->version, true );
			wp_localize_script( 'znb-yoast-integration-js', 'ZnPbYoastConfig', array(
				'nonce'   => wp_create_nonce( 'znpb_yoast_nonce'),
				'post_id' => $postId,
			) );
		}
	}

	function wpseo_znpb_data_footer() {
		$screen = get_current_screen();
		if ( $screen->base && ( 'post' == $screen->base ) ) {
			$metaValue = get_post_meta( get_the_ID(), 'zn_page_builder_els', 1 );
			echo '<div id="wpseo_zn_page_builder_els__wrapper" style="display:none !important; width:0 !important; height:0 !important;">' . $this->get_pb_content_data( $metaValue ) . '</div>';
		}
	}

	/**
	 * Returns the pagebuilder content data as a string
	 *
	 * @access public
	 *
	 * @var $content string
	 *
	 * @param mixed $content
	 *
	 * @return string
	 */
	public function get_pb_content_data( $content ) {
		$flat    = '';
		$skipped = array(
			'object',
			'uid',
			'width',
		);
		if ( empty( $content ) || ! is_array( $content ) ) {
			return $flat;
		}
		if ( is_array( $content ) ) {
			foreach ( $content as $key => $value ) {
				if ( in_array( $key, $skipped, true ) ) {
					continue;
				}
				if ( is_array( $value ) ) {
					$flat .= $this->get_pb_content_data( $value );
				} else {
					$flat .= ' ' . $value;
				}
			}
		} else {
			return ' ' . $content;
		}
		return $flat;
	}
}
