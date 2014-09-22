<?php
// /Iharos/src/Facades/Request.php

namespace Iharos\Facades;
use Iharos\Facades\Facade;

class Request extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\Request\Request';
	}
}