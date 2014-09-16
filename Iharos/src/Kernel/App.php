<?php
// /Iharos/src/Kernel/App.php

namespace Iharos\Kernel;
use Iharos\Kernel\Autoloader;

class App
{
	/* Array of module instances
	 * @var $module_instances array
	*/
	public static $module_instances;
	
	public $base_dir;

	
	public function __construct()
	{
		self::bind($this); // register as 'Iharos\Kernel\App' => $this
	}


	public static function bind($name)
	{
		if (is_object($name)) {
			$instance = $name;
			$name = get_class($name);
		} else {
			$instance = new $name();
		}
		
		static::$module_instances[$name] = $instance;
	}


	public static function resolve($name)
	{
		if (!isset(static::$module_instances[$name])) {
			self::bind($name);
		}
		
		$instance = static::$module_instances[$name];
		
		if ($instance instanceof Closure) {
			$instance = $instance();
		}
		
		return $instance;
	}


	public function error($msg)
	{
		die($msg);
	}
}
?>