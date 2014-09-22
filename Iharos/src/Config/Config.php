<?php
// /Iharos/src/Config/Config.php

namespace Iharos\Config;

class Config
{
	protected $base_url;
	
	public function __construct()
	{
		$this->set('base_url', '/var/www/iharos');
	}
	
	public function get($key)
	{
		if (!isset($this->$key)) {
			throw new \Exception("Config key '$key' is not found.");
		}
		
		return $this->$key;
	}
	
	public function set($key, $value, $overwrite = true)
	{
		if (isset($this->$key) and !$overwrite) {
			throw new \Exception("Config key '$key' exists already.");
		}
		
		$this->$key = $value;
	}
	
	public function loadFromFile($file_name) {}
	public function loadFromDb($db) {}
}