<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types;

use DJTommek\GlympseApi\Types\Properties\TicketProperty;
use DJTommek\GlympseApi\Utils;

/**
 * List of tickets
 *
 * @version 2021-06-04
 * @author Tomas Palider (DJTommek) https://tomas.palider.cz/
 * @see https://developer.glympse.com/docs/core/api/reference/users/self/tickets/get
 */
class Tickets extends Type
{
	public static function createFromVariable(\stdClass $variables): self {
		$class = new Tickets();
		$class->type = $variables->type;
		if (isset($variables->items) === false) {
			$variables->items = [];
		}
		foreach ($variables->items as $itemRaw) {
			$class->items[] = Ticket::createFromVariable($itemRaw);
		}
		return $class;
	}

	/** @var string */
	public $type = 'tickets';

	/** @var Ticket[] */
	public $items = [];
}
