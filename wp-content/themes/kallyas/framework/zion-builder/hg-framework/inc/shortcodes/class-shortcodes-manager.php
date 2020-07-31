<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZnHgFw_ShortcodesManager
 */
class ZnHgFw_ShortcodesManager{

	/**
	 * Will hold a reference to all shortcodes
	 *
	 * @var array
	 */
	private $registeredShortcodes = array();

	/**
	 * Flag to see if the scripts were already loaded or not
	 *
	 * @var bool
	 */
	private $scriptsLoaded = false;

	/**
	 * ZnHgFw_ShortcodesManager constructor
	 */
	function __construct(){

		/**
		 * Load the base class for shortcodes
		 */
		require( dirname(__FILE__) . '/HG_Shortcode.php' );

		// Add shortcode button after media button
		if( true === apply_filters('hgfw_show_shortcodes_button', true) ){
			add_action( 'media_buttons', array( $this, 'addMediaButton' ), 999 );
		}

		do_action( 'znhgfw_shortcodes_init', $this );

		// Register all shortcodes into WordPress
		$this->registerShortcodesToWordPress();
	}

	/**
	 * Will register a given shortcode class
	 *
	 * @param object $shortcodeInstance The shortcode instance
	 * @return void
	 */
	public function registerShortcode( $shortcodeInstance ){
		if ( ! ($shortcodeInstance instanceof HG_Shortcode ) ) {
			return new WP_Error( __('The shortcode must derive from the HG_Shortcode class', 'zn_framework') );
		}

		$this->registeredShortcodes[ $shortcodeInstance->getTag() ] = $shortcodeInstance;

	}

	/**
	 * Will unregister a shortcode
	 *
	 * @param $shortcodeId
	 * @return bool Whatever the shortcode was removed or not
	 */
	public function unregisterShortcode( $shortcodeId ){
		if( ! empty( $this->registeredShortcodes[$shortcodeId] ) ){
			unset( $this->registeredShortcodes[$shortcodeId] );
			return true;
		}

		return false;
	}


	/**
	 * Enqueue all required scripts
	 */
	public function enqueueScripts(){

		if( $this->scriptsLoaded ){
			return;
		}

		$this->scriptsLoaded = true;

		// Load the css files
		wp_enqueue_style( 'znhgtfw-shortcode-mngr-css', ZNHGFW()->getFwUrl('assets/dist/css/shortcodes.css'), array(), ZNHGFW()->getVersion() );
		wp_enqueue_style( 'znhg-options-machine', ZNHGFW()->getFwUrl('assets/dist/css/options.css'), array('wp-color-picker'), ZNHGFW()->getVersion() );

		// Load the main shortcodes Scripts
		wp_register_script( 'znhg-options-machine', ZNHGFW()->getFwUrl('assets/dist/js/admin/options/options.min.js'), array( 'backbone', 'wp-color-picker', 'wp-color-picker-alpha', 'jquery-ui-slider' ), ZNHGFW()->getVersion(), true );
		wp_register_script( 'znhgtfw-shortcode-mngr-js', ZNHGFW()->getFwUrl('assets/dist/js/admin/shortcodes/shortcodes.min.js'), array( 'backbone', 'jquery-ui-accordion', 'znhg-options-machine' ), ZNHGFW()->getVersion(), true );

		// Finally enqueue the script
		wp_enqueue_script( 'znhgtfw-shortcode-mngr-js' );
		wp_localize_script( 'znhgtfw-shortcode-mngr-js', 'ZnHgShManager', array(
			'sections' => $this->getShortcodeSections(),
			'shortcodes' => $this->getShortcodesInfo(),
		));

	}

	/**
	 * Register all shortcodes to WordPress
	 */
	private function registerShortcodesToWordPress(){
		foreach ($this->registeredShortcodes as $shortcodeInstance ){
			add_shortcode( $shortcodeInstance->getTag(), array( $shortcodeInstance, '_render' ) );
		}
	}


	/**
	 * Will return the shortcodes sections
	 * This can be modified trough filter
	 *
	 * @return array
	 */
	private function getShortcodeSections(){
		return apply_filters( 'hg_shortcode_sections', array(
			__( 'Layout', 'zn_framework' ),
			__( 'Content', 'zn_framework' ),
			__( 'Marketing', 'zn_framework' ),
		));
	}


	/**
	 * Retrieve the information associated with the registered class instances
	 *
	 * @return array
	 */
	private function getShortcodesInfo(){
		$shortcodeInfo = array();
		foreach ($this->registeredShortcodes as $shortcodeInstance){
			$shortcodeInfo[] = $shortcodeInstance->getInfo();
		}

		return $shortcodeInfo;
	}

	/**
	 * Will add the shortcode button after insert media button
	 */
	public function addMediaButton(){
		$this->enqueueScripts();
		echo '<span id="znhgtfw-shortcode-modal-open" title="Add shortcode" class="button"></span>';
	}
}

/**
 * Helper function to access this class easily
 *
 * @return object
 */
function ZnHgFwShortcodesManager(){
	return ZNHGFW()->getComponent('shortcode_manager');
}

return new ZnHgFw_ShortcodesManager();