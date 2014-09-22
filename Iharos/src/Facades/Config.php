<?php
// /Iharos/src/Modules/Config.php

namespace Iharos\Facades;
use Iharos\Facades\Facade;

class Config extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\Config\Config';
	}
}