<?php
// /Iharos/src/Kernel/App.php

namespace Iharos\Kernel;

class App
{
	/* Array of module instances
	 * @var $module_instances array
	*/
	public static $module_instances;
	
	public $base_dir;

	
	public function __construct()
	{
		$this->base_dir = realpath(__DIR__ . str_repeat(DIRECTORY_SEPARATOR . '..', 3))
			. DIRECTORY_SEPARATOR;
		
		$this->registerAutoLoader();
		
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
		$instance = static::$module_instances[$name];
		
		if ($instance instanceof Closure) {
			$instance = $instance();
		}
		
		return $instance;
	}


	public function autoLoader($class)
	{
		$class_path_parts = array_filter( explode('\\', $class) );
		array_unshift($class_path_parts, $class_path_parts[0]);
		$class_path_parts[1] = 'src';
		
		$path = $this->base_dir
			. join(DIRECTORY_SEPARATOR, $class_path_parts)
			. '.php';
		
		require_once $path;
	}


	public function registerAutoLoader()
	{
		spl_autoload_register(array($this, 'autoLoader'));
	}


	public function error($msg)
	{
		die($msg);
	}
}
?>