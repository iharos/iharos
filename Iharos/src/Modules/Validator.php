<?php
// /Iharos/src/Modules/Validator.php

namespace Iharos\Modules;
use Iharos\Modules\Validator;

class Validator extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\Validator\Validator';
	}
}