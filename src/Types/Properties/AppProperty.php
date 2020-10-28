<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types\Properties;

use DJTommek\GlympseApi\Utils;

/**
 * @version 2020-10-14
 * @author Tomas Palider (DJTommek) https://tomas.palider.cz/
 * @see https://developer.glympse.com/docs/core/api/reference/objects/data-points#app-property
 */
class AppProperty extends Property
{
	public static function createFromVariable(\stdClass $variables): self {
		$class = new static();
		foreach ($variables as $key => $value) {
			$propertyName = Utils::camelize($key);
			$class->{$propertyName} = $value;
		}
		return $class;
	}

	/** @var ?string Human-readable string (potentially localized) */
	public $name = null;
	/** @var ?string Application icon URL */
	public $icon = null;
}
