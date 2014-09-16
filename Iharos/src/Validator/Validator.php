<?php
// /Iharos/src/Validator/Validator.php

namespace Iharos\Validator;

class Validator {
	public function filterUrl($url)
	{
		return filter_var($url, FILTER_SANITIZE_URL);
	}
}