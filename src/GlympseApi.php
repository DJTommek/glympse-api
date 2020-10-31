<?php declare(strict_types=1);

namespace DJTommek\GlympseApi;

use \DJTommek\GlympseApi\Types\AccessToken;
use \DJTommek\GlympseApi\Types\Group;
use \DJTommek\GlympseApi\Types\TicketInvite;

class GlympseApi
{
	const API_URL = 'https://api.glympse.com';

	const API_ENDPOINT_LOGIN = '/v2/account/login/';
	const API_ENDPOINT_INVITES = '/v2/invites/%s';
	const API_ENDPOINT_GROUPS = '/v2/groups/%s';

	private $apiUsername;
	private $apiPassword;
	private $apiKey;

	/** @var ?AccessToken */
	private $accessToken;

	public function __construct($apiUsername, $apiPassword, $apiKey) {
		$this->apiUsername = $apiUsername;
		$this->apiPassword = $apiPassword;
		$this->apiKey = $apiKey;
	}

	/** @throws GlympseApiException|\JsonException */
	public function loadToken(bool $force = false): ?string {
		if ($this->accessToken === null || $force === true) {
			$params = [
				'username' => $this->apiUsername,
				'password' => $this->apiPassword,
				'api_key' => $this->apiKey,
			];
			$content = $this->makeApiRequest(self::API_ENDPOINT_LOGIN, $params);
			$this->accessToken = AccessToken::createFromVariable($content);
		}
		return $this->getToken();
	}

	/** @throws GlympseApiException|\JsonException */
	public function loadGroup(string $tag) {
		$params = [
			'branding' => 'true',
			'oauth_token' => $this->getToken(),
		];
		$content = $this->makeApiRequest(sprintf(self::API_ENDPOINT_GROUPS, $tag), $params);
		return Group::createFromVariable($content);
	}

	/** @throws GlympseApiException|\JsonException */
	public function loadInvite(string $inviteId, ?int $next = null): TicketInvite {
		$params = [
			'next' => $next,
			'uncompressed' => true,
			'oauth_token' => $this->getToken(),
		];
		$content = $this->makeApiRequest(sprintf(self::API_ENDPOINT_INVITES, $inviteId), $params);
		return TicketInvite::createFromVariable($content);
	}

	/** @throws GlympseApiException|\JsonException */
	private function makeApiRequest(string $endpoint, array $params) {
		$url = sprintf('%s%s?%s', self::API_URL, $endpoint, http_build_query($params));
		$response = Utils::fileGetContents($url);
		$content = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
		if ($content->result === 'ok') {
			return $content->response;
		} else {
			throw new GlympseApiException($content->meta->error_detail ?? $content->meta->error);
		}
	}

	public function getToken(): ?string {
		return $this->accessToken->accessToken;
	}
}