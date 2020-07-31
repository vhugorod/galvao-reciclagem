<?php if(! defined('ABSPATH')){ return; }

/**
 * Class Znb_WordPressWidgets_Integration
 */
class Znb_WordPressWidgets_Integration extends Znb_Integration
{
	/**
	 * Check if we can load this integration or not
	 * @return [type] [description]
	 */
	static public function can_load(){
		return version_compare( get_bloginfo( 'version' ), '4.8', '>' );
	}

    /**
     * Initialises the Class if can_lod() returns true
     */
	function initialize()
	{
		add_action('znpb_editor_after_init', array($this, 'on_after_editor_init'));
    }
    
	function on_after_editor_init(){
		add_action( 'znpb_editor_before_load_scripts', array( $this, 'editor_before_load_scripts' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
	}

    /**
	 * @since 1.0.12
	 * @access public
	*/
	public function editor_before_load_scripts() {
		global $wp_scripts;

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Manually load the widget scripts
		$wp_scripts->add( 'media-widgets', admin_url("js/widgets/media-widgets$suffix.js"), array( 'jquery', 'media-models', 'media-views' ) );
		$wp_scripts->add_inline_script( 'media-widgets', 'wp.mediaWidgets.init();', 'after' );

		$wp_scripts->add( 'media-audio-widget', admin_url("js/widgets/media-audio-widget$suffix.js"), array( 'media-widgets', 'media-audiovideo' ) );
		$wp_scripts->add( 'media-image-widget', admin_url("js/widgets/media-image-widget$suffix.js"), array( 'media-widgets' ) );
		$wp_scripts->add( 'media-video-widget', admin_url("js/widgets/media-video-widget$suffix.js"), array( 'media-widgets', 'media-audiovideo' ) );

		// Text widgets
		$wp_scripts->add( 'text-widgets', admin_url("js/widgets/text-widgets$suffix.js"), array( 'jquery', 'editor', 'wp-util' ) );
		$wp_scripts->add_inline_script( 'text-widgets', 'wp.textWidgets.init();', 'after' );

		// Custom HTML widgets
		$wp_scripts->add( 'custom-html-widgets', admin_url("js/widgets/custom-html-widgets.js"), array( 'jquery', 'backbone', 'wp-util', 'jquery-ui-core', 'wp-a11y' ) );


		wp_enqueue_style( 'widgets' );
		wp_enqueue_style( 'media-views' );

		do_action( 'admin_print_scripts-widgets.php' );
	}

	/**
	 * @since 1.0.12
	 * @access public
	*/
	public function wp_footer() {
		do_action( 'admin_footer-widgets.php' );
	}

}