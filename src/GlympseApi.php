<?php declare(strict_types=1);

namespace DJTommek\GlympseApi;

use DJTommek\GlympseApi\Types\AccessToken;

class GlympseApi
{
	private $apiUsername;
	private $apiPassword;
	/** @var string */
	private $apiKey;

	/** @var ?AccessToken */
	private $accessToken;

	private $requestClient;

	/**
	 * @param string $apiKey This is your developer key. You need to register with Glympse and get a key before you can use the Glympse API
	 */
	public function __construct(string $apiKey)
	{
		$this->apiKey = $apiKey;
		$this->requestClient = new RequestClient();
	}

	public function setAccessToken(string $token)
	{
		$this->accessToken = $token;
	}

	/**
	 * @param string $username Unique ID associated with evert Glympse user account
	 */
	public function setUsername(string $username)
	{
		$this->apiUsername = $username;
	}

	/**
	 * @param string $password Refresh token previously issued to the user upon account creation
	 */
	public function setPassword(string $password)
	{
		$this->apiPassword = $password;
	}

	public function accountCreate(string $type = null, string $address = null, string $code = null)
	{
		$params = [
			'api_key' => $this->apiKey,
		];
		$body = [];
		if ($type && $address && $code) {
			$params['type'] = $type;
			$body['type'] = $type;
			$body['address'] = $address;
			$body['code'] = $code;
		}
		return $this->requestClient->makePostRequest(Endpoint::ACCOUNT_CREATE, $params, $body);
	}

	/**
	 * @param ?string $device Unique device id for this physical device
	 */
	public function accountLogin(string $device = null)
	{
		$params = [
			'api_key' => $this->apiKey,
			'id' => $this->apiUsername,
			'password' => $this->apiPassword,
		];
		if (is_null($device) === false) {
			$params['device'] = $device;
		}
		return $this->requestClient->makePostRequest(Endpoint::ACCOUNT_LOGIN, $params);
	}

	public function usersSelfCreateTicket(int $duration)
	{
		$params = [
			'duration' => $duration,
		];
		return $this->requestClient->makePostRequest(Endpoint::USERS_SELF_CREATE_TICKET, $params, [], $this->accessToken);
	}

	public function ticketsId(string $ticketId, bool $invites = null, bool $properties = null)
	{
		$params = [];
		if ($invites) {
			$params['invites'] = $invites;
		}
		if ($properties) {
			$params['properties'] = $properties;
		}
		$endpoint = sprintf(Endpoint::TICKETS_ID_UPDATE, $ticketId);
		return $this->requestClient->makeGetRequest($endpoint, $params, $this->accessToken);
	}

	/**
	 * @param ?bool $invites Includes the invites sent for each ticket when true; defaults to false.
	 * @param ?bool $properties Includes ticket properties associated with each ticket when true; defaults to false.
	 * @param ?bool $siblings When true, reports sibling tickets associated with each ticket; false by default.
	 * @param ?string $state Limits tickets to those matching the specified state; must be active, archived, or expired.
	 * @param ?\DateTimeInterface $minEndTime Results are limited to those ending after the specified Epoch time.
	 * @param ?int $limit Limits the response to the number specified as the value.
	 */
	public function usersSelfTickets(bool $invites = null, bool $properties = null, bool $siblings = null, string $state = null, \DateTimeInterface $minEndTime = null, int $limit = null)
	{
		$params = [];
		if (is_null($invites) === false) {
			$params['invites'] = $invites;
		}
		if (is_null($properties) === false) {
			$params['properties'] = $properties;
		}
		if (is_null($siblings) === false) {
			$params['siblings'] = $siblings;
		}
		if (is_null($state) === false) {
			$params['state'] = $state;
		}
		if (is_null($minEndTime) === false) {
			$params['min_end_time'] = $minEndTime;
		}
		if (is_null($limit) === false) {
			$params['limit'] = $limit;
		}
		return $this->requestClient->makeGetRequest(Endpoint::USERS_SELF_TICKETS, $params, $this->accessToken);
	}

	public function ticketsIdUpdate(string $ticketId, int $duration)
	{
		$params = [
			'duration' => $duration,
		];
		$endpoint = sprintf(Endpoint::TICKETS_ID_UPDATE, $ticketId);
		return $this->requestClient->makePostRequest($endpoint, $params, [], $this->accessToken);
	}

	public function ticketsIdCreateInvite(
		string $ticketId,
		string $type,
		string $subtype = null,
		string $address = null,
		string $visible = null,
		string $name = null,
		string $text = null,
		string $send = null,
		string $requestId = null,
		string $data = null
	)
	{
		$params = [
			'type' => $type
		];
		if (is_null($subtype) === false) {
			$params['subtype'] = $subtype;
		}
		if (is_null($address) === false) {
			$params['address'] = $address;
		}
		if (is_null($visible) === false) {
			$params['visible'] = $visible;
		}
		if (is_null($name) === false) {
			$params['name'] = $name;
		}
		if (is_null($text) === false) {
			$params['text'] = $text;
		}
		if (is_null($send) === false) {
			$params['send'] = $send;
		}
		if (is_null($requestId) === false) {
			$params['request_id'] = $requestId;
		}
		if (is_null($data) === false) {
			$params['data'] = $data;
		}
		$endpoint = sprintf(Endpoint::TICKETS_ID_CREATE_INVITE, $ticketId);
		return $this->requestClient->makePostRequest($endpoint, $params, [], $this->accessToken);
	}

	public function ticketsIdAppendLocation(
		string $ticketId,
		\DateTimeInterface $epochTime,
		float $langtitude,
		float $longtitude,
		float $speed = null,
		int $heading = null,
		int $elevation = null,
		int $horizontalAccuracy = null,
		int $verticalAccuracy = null
	)
	{
		$params = [
			$epochTime->getTimestamp() * 1000, // in milliseconds
			intval($langtitude * 10e5),
			intval($longtitude * 10e5),
			$speed,
			$heading,
			$elevation,
			$horizontalAccuracy,
			$verticalAccuracy
		];
		$endpoint = sprintf(Endpoint::TICKETS_ID_APPEND_LOCATION, $ticketId);
		return $this->requestClient->makePostRequest($endpoint, [], json_encode([$params]), $this->accessToken);
	}
}
