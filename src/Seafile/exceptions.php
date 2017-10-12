<?php

namespace Holabs\Seafile;

use Nette\InvalidStateException;
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
class ApiException extends InvalidStateException {

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

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class ArgumentException extends ApiException {

}

/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class AuthenticationException extends InvalidStateException {

}
