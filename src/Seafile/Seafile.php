<?php


namespace Holabs\Seafile;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Holabs\Seafile\Methods\TFile;
use Holabs\Seafile\Methods\TLibrary;
use Nette\Http\UrlScript;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\JsonException;
use Nette\Utils\Strings;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tracy\Debugger;

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 *
 * @method onRequest(Seafile $sender, RequestInterface $request)
 * @method onResponse(Seafile $sender, RequestInterface $request, ResponseInterface $response, float $time, ApiResponse $data)
 */
class Seafile {

	use SmartObject;
	use TLibrary;
	use TFile;

	const GET = 'GET';

	const POST = 'POST';

	const PUT = 'PUT';

	const UPDATE = 'UPDATE';

	const DELETE = 'DELETE';

	const API_URL = 'api2';
	const API_URL_2_1 = 'api/v2.1';

	const LOG_NAME = 'seafile';

	const HTTP_OK = 200;
	const HTTP_CREATED = 201;
	const HTTP_ACCEPTED = 202;
	const HTTP_FORBIDDEN = 403;

	const HTTP_OK_CODES = [
		self::HTTP_OK,
		self::HTTP_CREATED,
		self::HTTP_ACCEPTED,
	];

	const LIB_TYPE_ALL = NULL;
	const LIB_TYPE_MINE = 'mine';
	const LIB_TYPE_SHARED = 'shared';
	const LIB_TYPE_GROUP = 'group';
	const LIB_TYPE_ORG = 'org';

	/** @var \Closure[]|callable[]|array */
	public $onRequest = [];

	/** @var \Closure[]|callable[]|array */
	public $onResponse = [];

	/** @var Client */
	private $apiClient;

	/** @var User|null */
	private $user = NULL;

	/** @var Server|null */
	private $server = NULL;

	/**
	 * Client constructor.
	 * @param string $url
	 */
	public function __construct(string $url) {
		$this->server = new Server($this, new UrlScript($url));
		$this->apiClient = new Client(
			[
				'base_uri' => $this->getServer()->getApiUrl(),
			]
		);

		$this->getApiClient();
	}

	/**
	 * @return User|null
	 */
	public function getUser(): ?User {
		return $this->user;
	}

	/**
	 * @return Server
	 */
	public function getServer(): Server {
		return $this->server;
	}

	/**
	 * @param string      $login
	 * @param string      $password
	 * @param string|NULL $code
	 * @return User|null
	 * @throws ApiException
	 * @throws AuthenticationException
	 * @throws JsonException
	 */
	public function authenticate(string $login, string $password, string $code = NULL) {
		$params = [
			'username' => $login,
			'password' => $password,
		];

		$headers = [];

		if ($code !== NULL) {
			$headers['X-SEAFILE-OTP'] = (string)$code;
		}

		$result = $this->api(self::API_URL . '/auth-token/', self::POST, $params, $headers);

		if ($result->getCode() !== self::HTTP_OK) {
			$this->createApiException($result);
		}

		if (!$result->getBody()->offsetExists('token')) {
			throw new AuthenticationException('Seafile authentication failed');
		}

		$token = $result->getBody()->offsetGet('token');

		return $this->login($token);
	}

	/**
	 * @param string $token
	 * @return User|null
	 * @throws JsonException
	 */
	public function login(string $token) {

		$result = $this->api(self::API_URL . '/account/info/', self::GET, [], ['Authorization' => "Token {$token}"]);



		$body = $result->getBody();

		$this->user = new User(
			$token,
			$body['email'],
			$body['usage'],
			$body['total'],
			$body['login_id'],
			$body['name'],
			$body['institution'],
			$body['department'],
			$body['contact_email']
		);

		return $this->getUser();
	}

	/**
	 * @return ApiResponse
	 * @throws JsonException
	 */
	public function getClientLoginToken() {
		return $this->api(self::API_URL . '/client-login/', self::POST);
	}

	/**
	 * @param string $function
	 * @param string $method
	 * @param array  $params
	 * @param array  $headers
	 * @return ApiResponse
	 * @throws ApiException
	 * @throws JsonException
	 */
	public function api(
		string $function,
		string $method = self::GET,
		array $params = [],
		array $headers = []
	): ApiResponse {

		$function = Strings::startsWith($function, '/') ? Strings::after($function, '/') : $function;

		if ($this->getUser()) {
			$headers['Authorization'] = "Token {$this->getUser()->getApiKey()}";
		}

		$tmp = [];
		foreach ($params as $k => $v) {
			$tmp[] = [
				'name'     => $k,
				'contents' => $v,
			];
		}

		$multipart = new MultipartStream($tmp);
		$request = new Request($method, $function, $headers, $multipart);


		$this->onRequest($this, $request);
		Debugger::timer('seafile');
		try {
			$response = $this->getApiClient()->send($request);
			$time = Debugger::timer('seafile');
			$api = new ApiResponse($response);
		} catch (ClientException $e) {

			if($e->hasResponse()) {
				$time = Debugger::timer('seafile');
				$response = $e->getResponse();
				$api = new ApiResponse($e->getResponse());
				$this->onResponse($this, $request, $response, $time, $api);
				$this->createApiException($api);
			}

			throw new ApiException('', $e->getCode());
		}

		$this->onResponse($this, $request, $response, $time, $api);

		return $api;
	}

	/**
	 * @return Client
	 */
	protected function getApiClient(): Client {
		return $this->apiClient;
	}

	/**
	 * @param ApiResponse $response
	 * @throws ApiException
	 */
	protected function createApiException(ApiResponse $response) {
		$body = $response->getBody();
		$code = $response->getCode();

		if ($body instanceof ArrayHash) {
			if ($body->offsetExists('non_field_errors')) {
				$errs = $body->offsetGet('non_field_errors');
				throw new ApiException($errs->offsetExists(0) ? $errs->offsetGet(0) : '', $code);
			}

			if ($body->offsetExists('detail')) {
				throw new ApiException($body->offsetGet('detail'), $code);
			}

			$itr = $body->getIterator();
			$msg = $body->count() ? sprintf("%s: %s", $itr->key(), $itr->current()) : '';

			throw new ArgumentException($msg, $code);
		}

		if (is_string($body)) {
			throw new ApiException($body, $code);
		}

		throw new ApiException('', $code);
	}
}