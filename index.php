<?php
require "Iharos/src/Kernel/Autoloader.php";
//use Iharos\Modules\App;
use Iharos\Modules\Request;

//Iharos\Modules\App::boot();

//$app = new Iharos\Kernel\App();
//$app::bind('Iharos\Validator\Validator');
//$app::bind('Iharos\Request\Request');

Request::parse();

$r = Request::getInstance();
var_dump($r->args);
var_dump(Request::get('q'));
?>