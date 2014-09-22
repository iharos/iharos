<?php
// /Iharos/src/Modules/Facade.php

namespace Iharos\Facades;
use Iharos\Kernel\App;

class Facade {
	public static function getModuleClassName()
	{
		throw new \Exception('Facade does not implement the getModuleClassName method.');
	}
	
	public static function __callStatic($method, $args)
	{
		$instance = App::resolve( static::getModuleClassName() );

		return call_user_func_array(array($instance, $method), $args);
	}
}
?>