<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types;

use DJTommek\GlympseApi\DateImmutableUtils;
use DJTommek\GlympseApi\Utils;

/**
 * List of tickets
 *
 * @version 2021-06-04
 * @author Tomas Palider (DJTommek) https://tomas.palider.cz/
 * @see https://developer.glympse.com/docs/core/api/reference/users/self/invites/get
 */
class Invites extends Type
{
	public static function createFromVariable(\stdClass $variables): self
	{
		$class = new Invites();
		foreach ($variables as $key => $value) {
			$propertyName = Utils::camelize($key);
			if ($propertyName === 'lastRefresh') {
				$value = DateImmutableUtils::fromTimestampMs($value);
			} else if ($propertyName === 'invites') {
				$value = Invites::createFromVariable($value);
			}
			$class->{$propertyName} = $value;
		}
		if (isset($variables->items) === false) { // if no item is available, API is not returning this field.
			$variables->items = [];
		}

		foreach ($variables->items as $itemRaw) {
			$class->items[] = Invite::createFromVariable($itemRaw);
		}
		return $class;
	}

	/** @var string */
	public $type = 'invites';

	/** @var Invite[] */
	public $items = [];

	/** @var \DateTimeImmutable */
	public $lastRefresh = null;

	/** @var ?int */
	public $postRate = null;

	/** @var ?bool */
	public $postRateIsHigh = null;

	// @TODO add support for meta
}
