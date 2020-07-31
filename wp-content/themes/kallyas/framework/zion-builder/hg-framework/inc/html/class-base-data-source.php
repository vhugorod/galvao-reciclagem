<?php

class ZnHgFw_BaseDataSource{

	private $_dataContent = array();
	public $dataSourceType = '';
	/**
	 * Will set the data Source Content
	 */
	function setDataSource(){
		die( 'The setDataSource method needs to be overriden from the child class.' );
	}


	/**
	 * Will return an array for the specified source
	 * @return array the key=>value array that will populate an option select
	 */
	function getSource( $reload = false ){
		if( empty( $this->_dataContent ) || $reload ){
			$this->_dataContent = $this->setDataSource();
		}

		return $this->_dataContent;
	}


}
