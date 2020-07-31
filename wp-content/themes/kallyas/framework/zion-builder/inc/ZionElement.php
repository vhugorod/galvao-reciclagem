<?php

/**
 * This class will be extended by all PageBuilder elements
 *
 * @category   PageBuilder
 * @package    Zion Page Builder plugin
 * @author     Balasa Sorin Stefan ( Zauan )
 * @copyright  Copyright (c) Balasa Sorin Stefan
 * @link       http://themeforest.net/user/zauan
 */
class ZionElement
{
	/**
	 * Holds the element name
	 * @var string
	 */
	var $id;

	/**
	 * Holds the element name
	 * @var string
	 */
	var $name;

	/**
	 * Holds the element description configuration
	 * @var string|array
	 */
	var $description;

	/**
	 * Holds the element category
	 * @var string|array
	 */
	var $category;

	/**
	 * Holds the element drop level
	 * @var integer
	 */
	var $level = 3;

	/**
	 * If set to true, the element will have width controls
	 * @var string
	 */
	var $flexible = false;

	/**
	 * Is the element a legacy one ?
	 * A legacy element won't appear in the page builder element list, however,
	 * however, it will work if it was already used in a page
	 * @var boolean
	 */
	var $legacy = false;

	/**
	 * Holds the element keywords
	 * Keywords are used when searching for an element
	 * @var string|array
	 */
	var $keywords = array();

	/**
	 * Will contain all the element data ( 'width' , 'content' , 'options' , 'uid' )
	 */
	var $data = array();

	/**
	 * Whatever the element contains multiple elements inside it or just one child
	 * @var boolean
	 */
	var $has_multiple = false;

	/**
	 * Holds the system path to the element's directory
	 * @var string
	 */
	protected $path = '';
	/**
	 * Holds the http path to the element's directory
	 * @var string
	 */
	protected $url = '';

	/**
	 * Class constructor
	 * @param array $args The lsit of argments to instantiate an element with
	 */
	function __construct( $args = array() )
	{
		// Setup url and paths
		$rc = new ReflectionClass( get_class( $this ) );
		$this->path = dirname( $rc->getFileName() );
		$this->url = ZNB()->utility->getFileUrl( $this->path );

		$this->icon = ( is_file( $this->path . '/icon.png' ) ) ? $this->url . '/icon.png' : ZNB()->plugin_url . '/assets/img/default_icon.png';

		// Normalize keywords
		if( isset( $args['keywords'] ) && ! is_array( $args['keywords'] ) ){
			$args['keywords'] = array($args['keywords']);
		}

		// Set element config data
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		// // Arguments that needs to be passed by child class
		$this->class = $this->id;
	}

	function setData( $args = array() )
	{
		$this->data = $args;
	}

	function set( $key, $value )
	{
		$this->data[ $key ] = $value;
	}

	function getElementName()
	{
		return $this->name;
	}

	/**
	 * Check if we can load this element
	 * @return bool
	 */
	function canLoad()
	{
		return true;
	}

	/**
	 * Check if we can render this element or not
	 * @return bool
	 */
	function _can_render()
	{
		// Always render on editor
		if( ! ZNB()->utility->isActiveEditor() ){
			$display = $this->opt( 'znpb_hide_visitors', 'all' );
			if ( $display === 'loggedin' && !is_user_logged_in() ) {
				return false;
			}
			elseif ( $display === 'visitor' && is_user_logged_in() ) {
				return false;
			}
		}

		return $this->canRender();
	}

	/**
	 * Retrieve the system path to the specified file that must be located under the element's directory
	 * @param string $partialFilePath The partial path to the file. Relative to the element's directory
	 * @return string
	 */
	public function getPath( $partialFilePath = '' ){
		return wp_normalize_path( trailingslashit( $this->path ) . $partialFilePath );
	}

	/**
	 * Retrieve the http path to the specified file that must be located under the element's directory. DO NOT add the forward slash for $partialFilePath or the path will be broken!
	 * @param string $partialFilePath The partial path to the file. Relative to the element's directory. No forward slash or the path will be broken!
	 * @return string
	 */
	public function getUrl( $partialFilePath = '' ){
		return trailingslashit( $this->url ) . $partialFilePath;
	}

	/**
	 * Will check if the element has a render condition
	 *
	 * @return bool
	 */
	function canRender()
	{
		return true;
	}


	/**
	 * Will render the element
	 */
	function elementRender()
	{
		if ( $this->_can_render() ) {
			$this->element();
		}
	}

	/**
	 * Description
	 *
	 * @param string|bool $key
	 * @param string|bool $default
	 * @return mixed
	 */
	function opt( $key = false, $default = false )
	{
		return ( ( isset( $this->data[ 'options' ][ $key ] ) && '' != $this->data[ 'options' ][ $key ] ) ? $this->data[ 'options' ][ $key ] : $default);
	}

	/**
	 * Retrieve the element's options
	 * @return array
	 */
	function options()
	{
		return array();
	}

	/**
	 * Render the element's html markup. Exits the script if the method is accessed directly
	 */
	function element()
	{
		die( 'Houston we have a problem' );
	}

	/**
	 * Will render the inline javascript
	 */
	function js()
	{
	}

	/**
	 * Will render the inline css
	 */
	function css()
	{
	}

	/**
	 * Will enqueue the specified scripts
	 */
	function scripts()
	{
	}

}
