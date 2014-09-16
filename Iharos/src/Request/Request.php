<?php
// /Iharos/src/Request/Request.php

namespace Iharos\Request;
use Iharos\Modules\Validator;

class Request {
	public $method;
	public $url;
	
	/* Parts of the parsed URL
	 * @see http://php.net/manual/en/function.parse-url.php
	*/
	public $protocol; // http, https, ftp, ...
	public $host; // domain
	public $port;
	public $user;
	public $pass;
	public $path;
	public $query; // (..?)querypart1=1&querypart2=2
	public $fragment; // #anchor
	
	public $get;
	public $post;
	//public $request;
	public $module;
	public $action;
	public $args;
	// headers
	
	public function parse()
	{
		$this->method = strtolower($_SERVER["REQUEST_METHOD"]);
		
		$this->url = Validator::filterUrl($_SERVER["REQUEST_URI"]);
		
		// set parts of the URL as class properties
		$url = parse_url($this->url);
		
		if (isset($url['scheme'])) 		{ $this->protocol = $url['scheme']; }
		if (isset($url['host'])) 		{ $this->host = $url['host']; }
		if (isset($url['port'])) 		{ $this->port = $url['port']; }
		if (isset($url['user'])) 		{ $this->user = $url['user']; }
		if (isset($url['pass'])) 		{ $this->pass = $url['pass']; }
		if (isset($url['path'])) 		{ $this->path = $url['path']; }
		if (isset($url['fragment']))	{ $this->host = $url['fragment']; }
		
		// extract the query parts
		if (isset($url['query'])) {
			$this->query = $url['query'];
			
			$this->get = array();
			parse_str($this->query, $this->get);
			//$this->get = array_filter($this->get);
			
			if (isset($this->get['q'])) {
				$q = explode('/', $this->get['q']);
				if (isset($q[0])) {
					$this->module = $q[0];
					
					if (isset($q[1])) {
						$this->action = $q[1];
						
						if (isset($q[2])) {
							$this->args = $q[2];
						}
					}
				}
			}
		}
		
		// bind post values
		$this->post = $_POST;
	}

	
	public function get($key, $raw = false)
	{
		if ($raw) {
			return isset($_GET[$key]) ? $_GET[$key] : null;
		} else {
			return isset($this->get[$key]) ? $this->get[$key] : null;
		}
	}

	
	public function post($key, $raw = false)
	{
		if ($raw) {
			return isset($_POST[$key]) ? $_POST[$key] : null;
		} else {
			return isset($this->post[$key]) ? $this->post[$key] : null;
		}
	}

	
	function getInstance()
	{
		return $this;
	}

	
	public function getModule()
	{
		return $this->module;
	}

	
	public function getAction()
	{
		return $this->action;
	}

	
	public function getArgs()
	{
		return $this->args;
	}
	
	
	public function q($num)
	{
		return isset($this->get['q'][$num]) ? $this->get['q'][$num] : null;
	}
}