<?php declare(strict_types=1);

namespace DJTommek\GlympseApi;

class Endpoint
{
	const URL = 'https://api.glympse.com';

	const ACCOUNT_CREATE = '/account/create';
	const ACCOUNT_CONFIRM = '/account/confirm';
	const ACCOUNT_LOGIN = '/account/login';

	const CARDS_TYPES = '/v2/cards/types'; // GET
	const CARDS = '/v2/cards';  // GET, POST
	const CARDS_ID = '/v2/cards/%s'; // GET, PUT
	const CARDS_ID_ACTIVITY = '/v2/cards';
	const CARDS_ID_TICKET = '/v2/cards';
	const CARDS_ID_REQUEST = '/v2/cards';
	const CARDS_ID_REQUEST_CODE = '/v2/cards';
	const CARDS_ID_MEMBERS_MID = '/v2/cards';
	const CARDS_ID_STATE_ACKNOWLEDGE = '/v2/cards';
	const CARDS_ID_INVITES = '/v2/cards';
	const CARDS_ID_INVITES_IID = '/v2/cards';
	const CARDS_REQUESTS = '/v2/cards';
	const CARDS_IVITES_CODE = '/v2/cards';
	const CARDS_INVITES_CODE_ACCEPT = '/v2/cards';

	const GROUPS_ID = '/v2/groups/%s';
	const GROUPS_ID_EVENTS = '/v2/groups/%s/events';

	const INVITES_CODE = '/v2/invites/%s'; // GET
	const INVITES_CODE_DELETE = '/v2/invites/%s/delete'; // POST
	const INVITES_CODE_PROPERTIES = '/v2/invites/%s/properties'; // GET
	const INVITES_CODE_UPDATE = '/v2/invites/%s/update/'; // POST

	const MAPS_GEOCODE = '/v2/maps/geocode';
	const MAPS_REVERSE_GEOCODE = '/v2/maps/reverse_geocode';
	const MAPS_ROUTE = '/v2/maps/route';

	const PLACES_AUTOCOMPLETE = '/v2/places/autocomplete';
	const PLACES_SEARCH = '/v2/places/search';
	const PLACES_ID = '/v2/places/%s';

	const TICKETS_APPEND_DATA = '/v2/tickets/append_data'; // POST
	const TICKETS_APPEND_LOCATION = '/v2/tickets/append_location'; // POST
	const TICKETS_ID = '/v2/tickets/%s'; // GET
	const TICKETS_ID_APPEND_DATA = '/v2/tickets/%s/append_data'; // POST
	const TICKETS_ID_APPEND_LOCATION = '/v2/tickets/%s/append_location'; // POST
	const TICKETS_ID_CREATE_INVITE = '/v2/tickets/%s/create_invite'; // POST
	const TICKETS_ID_DELETE = '/v2/tickets/%s/delete'; // POST
	const TICKETS_ID_EXPIRE = '/v2/tickets/%s/expire'; // POST
	const TICKETS_SET_VISIBILITY = '/v2/tickets/%s/set_visibility'; //
	const TICKETS_ID_UPDATE = '/v2/tickets/%s/update'; // POST

	const USERS_SELF_CREATE_TICKET = '/v2/users/self/create_ticket'; // POST
	const USERS_SELF_INVITES = '/v2/users/self/invites'; // GET
	const USERS_SELF_LINKED_ACCOUNTS = '/v2/users/self/linked_accounts'; // GET
	const USERS_SELF_LINKED_ACCOUNTS_TYPE_CONFIRM = '/v2/users/self/linked_accounts/%s/confirm'; // POST
	const USERS_SELF_LINKED_ACCOUNTS_TYPE_LINK = '/v2/users/self/linked_accounts/%s/link'; // POST
	const USERS_SELF_ = '/v2/users/self/register_device'; // POST
	const USERS_SELF_TICKETS = '/v2/users/self/tickets'; // POST
	const USERS_SELF_POIS = '/v2/users/self/pois'; // GET, POST
	const USERS_SELF_POIS_ID = '/v2/users/self/pois/%s'; // GET, PUT, DELETE
}
