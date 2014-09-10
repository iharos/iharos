<?php
namespace Iharos\Kernel;
//use Iharos\Kernel\Iharos;

class Facade {
	public static function __callStatic($method, $args)
	{
		echo "<br>__callStatic, module ID: '" . static::getModuleId() . "'";
		$app = Iharos::getInstance();

		$class = $app->manifest( static::getModuleId() );

		return call_user_func_array(array($class, $method), $args);
	}
}
?>