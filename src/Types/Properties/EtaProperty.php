<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types\Properties;

use DJTommek\GlympseApi\DateImmutableUtils;
use DJTommek\GlympseApi\Utils;

/**
 * @version 2020-10-14
 * @author Tomas Palider (DJTommek) https://tomas.palider.cz/
 * @see https://developer.glympse.com/docs/core/api/reference/objects/data-points#eta-property
 */
class EtaProperty extends Property
{
	public static function createFromVariable(\stdClass $variables): self {
		$class = new self();
		foreach ($variables as $key => $value) {
			$name = Utils::camelize($key);
			if (in_array($name, ['eta'])) {
				$value = new \DateInterval(sprintf('PT%dS', $value / 1000));
			} else if (in_array($name, ['etaTs'])) {
				$value = DateImmutableUtils::fromTimestampMs($value);
			}
			$class->{$name} = $value;
		}
		return $class;
	}

	/** @var ?\DateInterval ETA value */
	public $eta = null;
	/** @var ?\DateTimeImmutable Time when ETA was calculated */
	public $etaTs = null;
}
