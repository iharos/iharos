<?php
// /Iharos/src/Facades/View.php

namespace Iharos\Facades;
use Iharos\Facades\Facade;

class View extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\View\View';
	}
}