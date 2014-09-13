<?php
// /Iharos/src/Label/Label.php

namespace Iharos\Label;
use Iharos\Kernel\Facade;

class Label extends Facade {
	public static function getModuleClassName()
	{
		return 'Iharos\Label\LabelModule';
	}
}
?>