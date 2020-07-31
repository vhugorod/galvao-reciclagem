<?php

abstract class Znb_Integration
{

	abstract function initialize();


	function __construct()
	{
		$this->initialize();
	}

	static function can_load()
	{
		return false;
	}

}
