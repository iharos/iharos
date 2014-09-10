<?php
namespace Iharos\Kernel;

class Iharos
{
	// The one and only instance of Iharos
	public static $instance;
	
	/* Array of module aliases and their namespaces (locations)
	 * @var module_registry array
	*/
	public $module_registry;
	
	/* Array of module instances
	 * @var $module_instances array
	*/
	public $module_instances;
	
	public $base_dir;

	
	public function __construct()
	{
		//if (is_object(static::$instance)) {
		//	return static::$instance;
		//} else {
		//	static::$instance = $this;
		//}
		
		$this->base_dir = realpath(
				__DIR__
				. DIRECTORY_SEPARATOR
				. '..'
				. DIRECTORY_SEPARATOR
				. '..'
			)
			. DIRECTORY_SEPARATOR;
		
		$this->registerAutoLoader();
		
		$this->module_registry = array(
			// kernel modules
			'Iharos'		=> 'Iharos\Kernel\Iharos',
			'Label'			=> 'Iharos\Label\Label',
			
			//'OtherVendor' => 'OtherVendor\Package\Class'
		);
		
		$module_instances = array(
			'Iharos\Kernel\Iharos'			=> $this, // prevent redeclaration
		);
	}
	
	public static function getInstance() {  
		if (null === self::$instance) {  
			self::$instance = new self();  
		}  

		return self::$instance;  
	}
	
	/* Resolve the instance of a module identified by its alias.
	 * @var $name string
	 * @return module instance
	*/
	public function manifest($name)
	{
		echo "<br>manifest($name)";
		if (!isset($this->module_registry[$name])) {
			$this->error("Module alias '$name' was not found in registry.");
		}
		
		$module = $this->module_registry[$name];
		$module_name = $module . 'Module';
		
		if (!isset($this->module_instances[$module])) {
			$this->module_instances[$module] = new $module_name();
		} else {
			echo "<br>manifest: module instance '$module' has been found";
		}
		
		return $this->module_instances[$module];
	}

	
	public function autoLoader($class)
	{
		echo "<br><br>app->autoLoader($class)";
		
		$class_path_parts = explode('\\', $class);
		$class_name = array_pop($class_path_parts);
		
		if (isset($this->module_registry[$class_name])) {
			$class = $this->module_registry[$class_name];
		}
		
		$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
		$class = rtrim($class, DIRECTORY_SEPARATOR);
		$class = ltrim($class, DIRECTORY_SEPARATOR);
		
		$path = $this->base_dir . $class . '.php';
		
		echo "<br>autoLoader: " . $path;
		
		if (!isset($path)) {
			echo "<br>NOT FOUND: ". $path;
		}
		
		require_once $path;
	}

	
	public function registerAutoLoader()
	{
		echo "<br>app->registerAutoLoader()";
		spl_autoload_register(array($this, 'autoLoader')); //__NAMESPACE__ . '\\Iharos::autoLoader'
	}
	
	
	public function error($msg)
	{
		die($msg);
	}
}
?>