<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types;

use DJTommek\GlympseApi\DateImmutableUtils;
use DJTommek\GlympseApi\Utils;

/**
 * Invite
 *
 * @version 2021-06-04
 * @author Tomas Palider (DJTommek) https://tomas.palider.cz/
 * @see https://developer.glympse.com/docs/core/api/reference/users/self/create_ticket/post#ticket-information
 */
class Invite extends Type
{
	public static function createFromVariable(\stdClass $variables): self {
		$class = new Invite();
		foreach ($variables as $key => $value) {
			$propertyName = Utils::camelize($key);
			if (in_array($propertyName, ['created', 'lastRefresh', 'lastView'])) {
				$value = DateImmutableUtils::fromTimestampMs($value);
			}
			$class->{$propertyName} = $value;
		}
		return $class;
	}


	/** @var string Describes the response object type (invite). */
	public $type = 'invite';

	/** @var string The invite ID, typically the page associated with the Glympse URL. */
	public $id = null;

	/** @var ?string Invite type reflects how the invite was created; see below for possible valueseither ticket_invite or request_invite.*/
	public $inviteType = null;

	/** @var ?string URL for the Glympse ticket associated with the invite.*/
	public $url = null;

	/** @var ?\DateTimeImmutable The invite creation time */
	public $created = null;

	/** @var ?string Describes how the invite was delivered; typically client, server, or a related value. */
	public $send = null;

	/** @var ?string Describes the invite type, typically email, sms, or another supported invite type value. */
	public $recipientType = null;

	/** @var ?string Address used to contact the recipient; value varies according to recipient.type. */
	public $recipientAddress = null;

	/** @var ?string Local nickname assigned by the user when creating the invite. */
	public $recipientName = null;

	/** @var ?string State of the invite; varies according to invite_type. */
	public $status = null;

	/** @var ?string Number of recipient views associated with the Glympse ticket since being created. */
	public $viewers = null;

	/** @var ?string Number of recipients currently viewing the ticket. */
	public $viewing = null;

	/** @var ?\DateTimeImmutable Time of the last data refresh */
	public $lastRefresh = null;

	/** @var ?int Frequency of updates for the current device, in milliseconds. */
	public $postRate = null;

	/** @var ?bool Flag indicating whether update frequency for device. */
	public $postRateIsHigh = null;


	/*******************************************************************************/
	// These items are available by requesting list of tickets with invites=true
	/*******************************************************************************/
	/** @var ?string @TODO missing in documentation (example 'en') */
	public $locale = null;

	/** @var ?string @TODO missing in documentation ('ABCD-EFGH-A1B2C') */
	public $owner = null;

	/** @var ?string @TODO missing in documentation (example '224563934') */
	public $ticketId = null;

	/** @var ?string @TODO missing in documentation (example 'none') */
	public $visible = null;

	/** @var ?bool @TODO missing in documentation (example ' https://glympse.com/0MSE-F137', note space at the beginning) */
	public $text = null;

	/** @var ?\stdClass @TODO missing in documentation (example {type: 'link'}) */
	public $recipient = null;

	/** @var ?\DateTimeImmutable @TODO missing in documentation (example 1622816894940) */
	public $lastView = null;

	/** @var ?\stdClass @TODO missing in documentation (example is empty stdClass: {}) */
	public $theme = null;
}
