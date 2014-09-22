<?php
// /Iharos/src/Facades/App.php

namespace Iharos\Facades;
use Iharos\Facades\Facade;

class App extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\Kernel\App';
	}
}