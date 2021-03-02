<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types;

use DJTommek\GlympseApi\DateImmutableUtils;
use DJTommek\GlympseApi\Utils;

/**
 * @version 2021-03-03
 * @author Tomas Palider (DJTommek) https://tomas.palider.cz/
 * @see https://developer.glympse.com/docs/core/api/reference/account/create
 */
class Account extends Type
{
	/** @var string */
	public $type = 'account';

	/** @var string Unique identifier of a user */
	public $id;

	/** @var ?\DateTimeImmutable */
	public $lastModified;

	/** @var ?\DateTimeImmutable */
	public $createdTime;

	/** @var string Permanent refresh token that is exchanged for short-lived access tokens via POST account/login call. */
	public $password;

	/** @var ?string */
	public $apiKeyId;

	public static function createFromVariable(\stdClass $variables): self {
		$class = new self();
		foreach ($variables as $key => $value) {
			$propertyName = Utils::camelize($key);
			if (in_array($propertyName, ['createdTime', 'lastModified'])) {
				$value = DateImmutableUtils::fromTimestampMs($value);
			}
			$class->{$propertyName} = $value;
		}
		return $class;
	}
}
