<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types;

abstract class Type
{
	public function __set($name, $value)
	{
		trigger_error(sprintf(
			'Property "%s::$%s" of type "%s" is not predefined. Please report it on https://github.com/DJTommek/glympse-api',
			static::class,
			$name,
			gettype($value)
		), E_USER_NOTICE);
	}
}
