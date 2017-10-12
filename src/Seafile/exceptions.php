<?php

namespace Holabs\Seafile;

use ErrorException;
use Nette\Application\ApplicationException;
use Nette\InvalidStateException;
use Nette\Security\AuthenticationException as NetteAuthenticationException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class ConfigurationException extends InvalidStateException {

}

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class ApiException extends ApplicationException {

	private $response = NULL;

	/**
	 * ApiException constructor.
	 * @param string                 $message
	 * @param int                    $code
	 * @param null|ResponseInterface $response
	 */
	public function __construct($message = '', $code = 0, ResponseInterface $response = NULL) {
		$this->message = $message;
		$this->code = $code;
		$this->response = $response;
	}


	/**
	 * @return null
	 */
	public function getResponse() {
		return $this->response;
	}



}

class ArgumentException extends ApiException {

}

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class AuthenticationException extends NetteAuthenticationException {

}
