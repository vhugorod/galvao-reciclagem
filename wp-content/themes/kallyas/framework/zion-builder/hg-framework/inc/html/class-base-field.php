<?php

class ZnHgFw_BaseFieldType{

	/**
	 * Holds a refference to the option type
	 */
	var $type = '';

	/**
	 * Holds a refference to the HTML manager if passed to constructor
	 * @param object $htmlManager The HTML manager class
	 */
	var $htmlManager;

	/**
	 * Main class constructor
	 * @param object $htmlManager An instance to the HTML manager class
	 */
	function __construct( $htmlManager = null ){
		// Main class constructor
		$this->htmlManager = $htmlManager;
		// Call the option init method
		$this->init();
	}

	/**
	 * Using this method you can add extra functionality that will run on construct
	 * @return void
	 */
	function init(){
		// Will be overriden from child class
	}

	/**
	 * Main option renderer
	 * @param  [type] $optionConfig [description]
	 * @return [type]               [description]
	 */
	function render( $optionConfig ){
		// Will output the option content
	}

	function _render( $optionConfig ){
		return $this->render($optionConfig);
	}


	/**
	 * Option external scripts
	 * Using this method you can enqueue external scripts
	 * @return void
	 */
	function scripts(){
		// Will add additionall css/js scripts
	}


	/**
	 * Returns the option type
	 * @return string The option type
	 */
	function getType(){
		return $this->type;
	}
}
