<?php
// /Iharos/src/Modules/App.php

namespace Iharos\Modules;
use Iharos\Modules\Facade;

class App extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\Kernel\App';
	}
}