<?php declare(strict_types=1);

namespace DJTommek\GlympseApi\Types;

use DJTommek\GlympseApi\DateImmutableUtils;
use DJTommek\GlympseApi\Utils;

/**
 * Generates an access_token for all other API endpoints. An authenticated access token is created by passing a valid user ID and password (refresh token).
 *
 * @version 2020-10-14
 * @author Tomas Palider (DJTommek) https://tomas.palider.cz/
 * @see https://developer.glympse.com/docs/core/api/reference/account/login
 */
class AccessToken extends Type
{
	public static function createFromVariable(\stdClass $variables): self {
		$class = new AccessToken();
		foreach ($variables as $key => $value) {
			$propertyName = Utils::camelize($key);
			if ($key === 'expires_in') {
				$value = new \DateInterval(sprintf('PT%dS', $value));
			} else if ($key === 'account_created') {
				$value = DateImmutableUtils::fromTimestampMs($value);
			}
			$class->{$propertyName} = $value;
		}
		return $class;
	}

	public $type = 'access_token';
	/** @var ?string API access token */
	public $tokenType = null;
	/** @var ?int */
	public $accessToken = null;
	/** @var ?\DateInterval Token duration */
	public $expiresIn = null;
	/** @var ?string */
	public $config = null;
	/** @var ?\DateTimeImmutable */
	public $accountCreated = null;
}
