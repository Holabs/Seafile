<?php


namespace Holabs\Seafile;

use Nette\Utils\ArrayHash;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Psr\Http\Message\ResponseInterface;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class ApiResponse {

	/** @var int */
	private $code;

	/** @var ArrayHash */
	private $headers;

	/** @var ArrayHash|string|NULL */
	private $body;

	/** @var  ResponseInterface */
	private $httpResponse;

	/**
	 * ApiResponse constructor.
	 * @param ResponseInterface $httpResponse
	 */
	public function __construct(ResponseInterface $httpResponse) {
		$this->httpResponse = $httpResponse;
		$this->code = $httpResponse->getStatusCode();
		$this->headers = ArrayHash::from($httpResponse->getHeaders());


		$content = $httpResponse->getBody()->getContents();

		try {
			$content = Json::decode($content);
		} catch (JsonException $e) {
			if (empty($content)) {
				$content = NULL;
			}
		}

		$content = is_array($content) || is_object($content) ? ArrayHash::from($content) : $content;

		$this->body = $content;
	}

	/**
	 * @return int
	 */
	public function getCode(): int {
		return $this->code;
	}

	/**
	 * @return ArrayHash
	 */
	public function getHeaders(): ArrayHash {
		return clone $this->headers;
	}

	/**
	 * @return ArrayHash|NULL|string
	 */
	public function getBody() {
		return is_object($this->body) ? clone $this->body : $this->body;
	}

	/**
	 * @return ResponseInterface
	 */
	public function getHttpResponse(): ResponseInterface {
		return $this->httpResponse;
	}

}