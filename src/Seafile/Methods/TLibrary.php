<?php


namespace Holabs\Seafile\Methods;

use Holabs\Seafile\ApiException;
use Holabs\Seafile\Seafile;
use stdClass;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
trait TLibrary {


	/**
	 * @param string|null $type
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function getLibraries(string $type = Seafile::LIB_TYPE_ALL) {
		if (!$type) {
			$url = sprintf(
				"%s/repos/",
				Seafile::API_URL
			);
		} else {
			$url = sprintf(
				"%s/repos/?type=%s",
				Seafile::API_URL,
				urlencode($type)
			);
		}
		return $this->api($url, Seafile::GET);
	}

	/**
	 * @param string $libId
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function getLibraryInfo(string $libId) {
		$url = sprintf(
			"%s/repos/%s",
			Seafile::API_URL,
			$libId
		);
		return $this->api($url, Seafile::GET);
	}


	/**
	 * @param string      $name
	 * @param string      $desc
	 * @param string|NULL $password
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function createLibrary(string $name, string $desc, string $password = NULL) {
		$params = [
			'name' => $name,
			'desc' => $desc,
		];

		if ($password !== NULL) {
			$params['passwd'] = $password;
		}

		$url = sprintf(
			"%s/repos/",
			Seafile::API_URL
		);

		return $this->api($url, Seafile::POST, $params);
	}


	/**
	 * @param string $libId
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function deleteLibrary(string $libId) {
		$url = sprintf(
			"%s/repos/%s",
			Seafile::API_URL,
			$libId
		);
		return $this->api($url, Seafile::DELETE);
	}

	/**
	 * @param string $libId
	 * @param string $newName
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function renameLibrary(string $libId, string $newName) {
		$url = sprintf(
			"%s/repos/%s/?op=rename",
			Seafile::API_URL,
			$libId
		);

		return $this->api($url, Seafile::POST, ['repo_name' => $newName]);
	}

	/**
	 * @param string $libId
	 * @param string $password
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function decryptLibrary(string $libId, string $password) {
		$url = sprintf(
			"%s/repos/%s",
			Seafile::API_URL,
			$libId
		);
		return $this->api($url, Seafile::POST, ['password' => $password]);
	}

	/**
	 * @param string $libId
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function publishLibrary(string $libId) {
		$url = sprintf(
			"%s/repos/%s/public",
			Seafile::API_URL,
			$libId
		);
		return $this->api($url, Seafile::POST);
	}

	/**
	 * @param string $libId
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function unpublishLibrary(string $libId) {
		$url = sprintf(
			"%s/repos/%s/public",
			Seafile::API_URL,
			$libId
		);
		return $this->api($url, Seafile::DELETE);
	}

}