<?php

namespace Jmlallier\PHPHelpers;

class Helpers
{
	public static function value($value)
	{
		return $value instanceof Closure ? $value() : $value;
	}
}
