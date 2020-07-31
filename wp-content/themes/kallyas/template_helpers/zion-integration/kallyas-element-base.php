<?php if(! defined('ABSPATH')){ return; }
/**
* This class will be extended by all pagebuilder elements
*
* @category   Pagebuilder
* @package    ZnFramework
* @author     Balasa Sorin Stefan ( Zauan )
* @copyright  Copyright (c) Balasa Sorin Stefan
* @link       http://themeforest.net/user/zauan
*/

class ZnElements extends ZionElement
{

	function __construct( $args = array() ){
		parent::__construct($args);
	}

	/**
	 * Check if we can load this element
	 * @return [type] [description]
	 */
	function can_load(){
		return true;
	}

}
