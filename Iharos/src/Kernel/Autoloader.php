<?php
namespace Iharos\Kernel;
// Iharos/src/Kernel/Autoloader.php

class Autoloader {
	protected $base_dir;

	
	public function __construct()
	{
		$this->base_dir = realpath(__DIR__ . str_repeat(DIRECTORY_SEPARATOR . '..', 3))
			. DIRECTORY_SEPARATOR;
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
}

$autoloader = new Autoloader();
$autoloader->registerAutoLoader();