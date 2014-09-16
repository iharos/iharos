<?php
// /Iharos/src/Modules/Request.php

namespace Iharos\Modules;
use Iharos\Modules\Facade;

class Request extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\Request\Request';
	}
}