<?php
// /Iharos/src/Facades/Validator.php

namespace Iharos\Facades;
use Iharos\Facades\Validator;

class Validator extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\Validator\Validator';
	}
}