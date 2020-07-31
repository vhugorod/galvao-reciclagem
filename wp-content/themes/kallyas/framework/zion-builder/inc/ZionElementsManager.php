<?php if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionElementsManager
 */
class ZionElementsManager
{
	/**
	 * Holds the list of all registered elements
	 * @var array
	 */
	var $_registered_elements = array();

	/**
	 * Class constructor
	 */
	function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the class' default fucntionality
	 */
	function init()
	{
		// Load the base element class
		include( ZNB()->plugin_dir . 'inc/ZionElement.php' );

		// Register default elements early so others can override
		$this->_registerDefaultElements();

		// Trigger an action so others can add elements
		do_action( 'znb:elements:register_elements', $this );
		do_action( 'znb:elements:init', $this );
	}

	/**
	 * Will load the standard elements
	 * @return void
	 */
	private function _registerDefaultElements()
	{

		$dir = ZNB()->plugin_dir;
		$defaultElements = array();
		// Get all folders inside modules
		$elementsDirs = glob($dir . 'inc/modules/*', GLOB_ONLYDIR);

		// Get all default elements
		foreach ( $elementsDirs as $elementFolder) {
			// print_z( $elementFolder );
			// print_z( basename( $elementFolder ) );
			$defaultElements[basename( $elementFolder )] = trailingslashit( $elementFolder ) . basename( $elementFolder ) . '.php';
		}

		// Allow others to filter the elements
		$defaultElements = apply_filters('znb:default_elements', $defaultElements );

		// Load the dedfault elements
		foreach ( $defaultElements as $elementId => $elementFile ) {
			require( $elementFile );
		}

	}

	/**
	 * Will register an element
	 * @param ZionElement $elementInstance
	 * @return mixed WP_Error if the $elementInstance is not an instance of ZionElement, boolean false if the element cannot load, boolean true if all good
	 */
	public function registerElement( $elementInstance )
	{
		if ( ! ($elementInstance instanceof ZionElement ) ) {
			return new WP_Error( __('The element must derive from the ZionElement class', 'zn_framework') );
		}

		// Check if element requirements are meet
		if ( ! $elementInstance->canLoad() ) {
			return false;
		}

		// Perform an action so other can modify the element instance
		do_action( 'znb:element:registered', $elementInstance );

		$this->_registered_elements[ $elementInstance->id ] = $elementInstance;
		return true;
	}


	/**
	 * Unregisters an element
	 * @param  string $id The element ID that was already registered
	 * @return bool     True if the element was successfully removed or false on failure.
	 */
	public function unregisterElement( $id )
	{
		if ( !isset( $this->_registered_elements[ $id ] ) ) {
			return false;
		}

		unset( $this->_registered_elements[ $id ] );
		return true;
	}

	/**
	 * Retrieve the reference to the instance of the specified $element_id
	 * @param string $element_id
	 * @return mixed object on success, boolean false otherwise
	 */
	function getElement( $element_id )
	{
		if ( !empty( $this->_registered_elements[ $element_id ] ) ) {
			return $this->_registered_elements[ $element_id ];
		}
		return false;
	}


	/**
	 * Returns an array containing all registered elements
	 * @return array All elements that were registered
	 */
	public function getElements()
	{
		return $this->_registered_elements;
	}
}
