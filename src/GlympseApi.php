<?php declare(strict_types=1);

namespace DJTommek\GlympseApi;

use DJTommek\GlympseApi\Types\AccessToken;
use DJTommek\GlympseApi\Types\Account;
use DJTommek\GlympseApi\Types\Invite;
use DJTommek\GlympseApi\Types\Invites;
use DJTommek\GlympseApi\Types\Ticket;
use DJTommek\GlympseApi\Types\TicketInvite;
use DJTommek\GlympseApi\Types\Tickets;

class GlympseApi
{
	/** @var ?string */
	private $apiUsername;
	/** @var ?string */
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

	public function setAccessToken(AccessToken $token)
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

	public function accountCreate(string $type = null, string $address = null, string $code = null): Account
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
		$response = $this->requestClient->makePostRequest(Endpoint::ACCOUNT_CREATE, $params, $body);
		return Account::createFromVariable($response);
	}

	/**
	 * @param ?string $device Unique device id for this physical device
	 */
	public function accountLogin(string $device = null): AccessToken
	{
		$params = [
			'api_key' => $this->apiKey,
			'id' => $this->apiUsername,
			'password' => $this->apiPassword,
		];
		if (is_null($device) === false) {
			$params['device'] = $device;
		}
		$response = $this->requestClient->makePostRequest(Endpoint::ACCOUNT_LOGIN, $params);
		return AccessToken::createFromVariable($response);
	}

	public function usersSelfCreateTicket(int $duration)
	{
		$params = [
			'duration' => $duration,
		];
		return $this->requestClient->makePostRequest(Endpoint::USERS_SELF_CREATE_TICKET, $params, [], $this->accessToken->accessToken);
	}

	public function ticketsId(string $ticketId, bool $invites = null, bool $properties = null): Ticket
	{
		$params = [];
		if ($invites) {
			$params['invites'] = Utils::stringify($invites);
		}
		if ($properties) {
			$params['properties'] = Utils::stringify($properties);
		}
		$endpoint = sprintf(Endpoint::TICKETS_ID, $ticketId);
		$response = $this->requestClient->makeGetRequest($endpoint, $params, $this->accessToken->accessToken);
		return Ticket::createFromVariable($response);
	}

	/**
	 * @param ?bool $invites Includes the invites sent for each ticket when true; defaults to false.
	 * @param ?bool $properties Includes ticket properties associated with each ticket when true; defaults to false.
	 * @param ?bool $siblings When true, reports sibling tickets associated with each ticket; false by default.
	 * @param ?string $state Limits tickets to those matching the specified state; must be active, archived, or expired.
	 * @param ?\DateTimeInterface $minEndTime Results are limited to those ending after the specified Epoch time.
	 * @param ?int $limit Limits the response to the number specified as the value.
	 */
	public function usersSelfTickets(bool $invites = null, bool $properties = null, bool $siblings = null, string $state = null, \DateTimeInterface $minEndTime = null, int $limit = null): Tickets
	{
		$params = [];
		if (is_null($invites) === false) {
			$params['invites'] = Utils::stringify($invites);
		}
		if (is_null($properties) === false) {
			$params['properties'] = Utils::stringify($properties);
		}
		if (is_null($siblings) === false) {
			$params['siblings'] = Utils::stringify($siblings);
		}
		if (is_null($state) === false) {
			$params['state'] = Utils::stringify($state);
		}
		if (is_null($minEndTime) === false) {
			$params['min_end_time'] = Utils::stringify($minEndTime);
		}
		if (is_null($limit) === false) {
			$params['limit'] = Utils::stringify($limit);
		}
		$response = $this->requestClient->makeGetRequest(Endpoint::USERS_SELF_TICKETS, $params, $this->accessToken->accessToken);
		return Tickets::createFromVariable($response);
	}

	public function ticketsIdUpdate(string $ticketId, int $duration)
	{
		$params = [
			'duration' => $duration,
		];
		$endpoint = sprintf(Endpoint::TICKETS_ID_UPDATE, $ticketId);
		return $this->requestClient->makePostRequest($endpoint, $params, [], $this->accessToken->accessToken);
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
	): Invite
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
		$response = $this->requestClient->makePostRequest($endpoint, $params, [], $this->accessToken->accessToken);
		return Invite::createFromVariable($response);
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
		return $this->requestClient->makePostRequest($endpoint, [], json_encode([$params]), $this->accessToken->accessToken);
	}

	/**
	 * Adds property data to a ticket.
	 * @link https://developer.glympse.com/docs/core/api/reference/tickets/id/append_data/post
	 *
	 * @param string $ticketId
	 * @param \DateTimeInterface $epochTime Time associated with the property change; can influence ticket behavior, depending on the ${property_name}.
	 * @param string $name The specific property you're updating. List of available properties: https://developer.glympse.com/docs/core/api/reference/objects/data-points#properties
	 * @param mixed $value data appropriate for a property with the given name.
	 * @param int|null $partnerId Partner ID, typically optional; contact your Glympse representative to learn more.
	 */
	public function ticketsIdAppendData(
		string $ticketId,
		\DateTimeInterface $epochTime,
		string $name,
		$value,
		?int $partnerId = null
	)
	{
		$params = [
			't' => $epochTime->getTimestamp() * 1000, // in milliseconds
			'n' => $name,
			'v' => Utils::stringify($value),
		];
		if (is_null($partnerId) === false) {
			$params['pid'] = $partnerId;
		}
		$endpoint = sprintf(Endpoint::TICKETS_ID_APPEND_DATA, $ticketId);
		return $this->requestClient->makePostRequest($endpoint, [], json_encode([$params]), $this->accessToken->accessToken);
	}

	/**
	 * Returns a list of invites created by the user.
	 *
	 * @param ?int $since Returns the invites that have been viewed since the specified Epoch time in milliseconds. To see all invites created by a user, set since to zero (0). @TODO change to DateTimeImmutable
	 * @param ?bool $onlyViews When true, invite responses contain just identifying details and view data (default: false).
	 * @param ?bool $expired When true, the response includes expired tickets. When false (default), only active ticket invites are reported.
	 * @param ?bool $siblings When true, reports sibling tickets associated with each ticket (default: false).
	 * @param ?bool $viewers When true, response includes detailed viewer information (default: false).
	 *
	 * @see https://developer.glympse.com/docs/core/api/reference/users/self/invites/get
	 */
	public function usersSelfInvites(int $since = null, bool $onlyViews = null, bool $expired = null, bool $siblings = null, bool $viewers = null): Invites
	{
		$params = [];
		if (is_null($since) === false) {
			$params['since'] = $since;
		}
		if (is_null($onlyViews) === false) {
			$params['only_views'] = Utils::stringify($onlyViews);
		}
		if (is_null($expired) === false) {
			$params['expired'] = Utils::stringify($expired);
		}
		if (is_null($siblings) === false) {
			$params['siblings'] = Utils::stringify($siblings);
		}
		if (is_null($viewers) === false) {
			$params['viewers'] = Utils::stringify($viewers);
		}
		$response = $this->requestClient->makeGetRequest(Endpoint::USERS_SELF_INVITES, $params, $this->accessToken->accessToken);
		return Invites::createFromVariable($response);
	}

	/**
	 * The endpoint to determine type of the item that originally created the invite (ticket, request, etc.) and to retrieve location data and properties for the specified ticket invite.
	 *
	 * @param string $code Invite code
	 *
	 * @see https://developer.glympse.com/docs/core/api/reference/invites/code/get
	 */
	public function invites(string $code): TicketInvite
	{
		$endpoint = sprintf(Endpoint::INVITES_CODE, $code);
		$params = [];
		$response = $this->requestClient->makeGetRequest($endpoint, $params, $this->accessToken->accessToken);
		return TicketInvite::createFromVariable($response);
	}
}
