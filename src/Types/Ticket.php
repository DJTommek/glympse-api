<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types;

use DJTommek\GlympseApi\DateImmutableUtils;
use DJTommek\GlympseApi\Types\Properties\TicketProperty;
use DJTommek\GlympseApi\Utils;

/**
 * Ticket
 *
 * @version 2021-06-04
 * @author Tomas Palider (DJTommek) https://tomas.palider.cz/
 * @see https://developer.glympse.com/docs/core/api/reference/users/self/create_ticket/post#ticket-information
 */
class Ticket extends Type
{
	const ACTIVE = 'active';
	const ARCHIVED = 'archived';
	const EXPIRED = 'expired';

	public static function createFromVariable(\stdClass $variables): self
	{
		$class = new Ticket();
		foreach ($variables as $key => $value) {
			$propertyName = Utils::camelize($key);
			// @TODO move properties to $this->property->{propertyName} instead of $this->property{PropertyName}
			if ($propertyName === 'properties') {
				$value = TicketProperty::createFromArray($value);
			} else if (in_array($propertyName, ['startTime', 'endTime'])) {
				$value = DateImmutableUtils::fromTimestampMs($value);
			} else if ($propertyName === 'invites') {
				$value = Invites::createFromVariable($value);
			}
			$class->{$propertyName} = $value;
		}
		return $class;
	}


	/** @var string */
	public $type = 'ticket';
	/** @var string */
	public $id = null;
	/** @var string one of state constants in this class */
	public $state = null;
	/** @var \DateTimeImmutable */
	public $startTime = null;
	/** @var \DateTimeImmutable */
	public $endTime = null;
	/** @var ?string */
	public $owner = null;

	/** @var ?Invites */
	public $invites = null;

	/** @var TicketProperty */
	public $properties = null;

	public function isActive(): bool
	{
		return $this->state === self::ACTIVE;
	}

	public function hasInvites(): bool
	{
		return $this->invites !== null && $this->invites->items !== [];
	}
}
