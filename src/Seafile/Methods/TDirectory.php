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
trait TDirectory {

	/**
	 * @param string $libId
	 * @param string $path
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function getDirectoryList(string $libId, string $path) {
		$url = sprintf(
			"%s/repos/%s/dir/?p=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path)
		);

		return $this->api($url, Seafile::GET);
	}

	/**
	 * @param string $libId
	 * @param string $path
	 * @param bool   $recursive
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function getDirectoryFoldersList(string $libId, string $path, bool $recursive = FALSE) {
		$url = sprintf(
			"%s/repos/%s/dir/?p=%s&t=d&recursive=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path),
			(string)$recursive
		);

		return $this->api($url, Seafile::GET);
	}

	/**
	 * @param string $libId
	 * @param string $path
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function getDirectoryFilesList(string $libId, string $path) {
		$url = sprintf(
			"%s/repos/%s/dir/?p=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path)
		);

		return $this->api($url, Seafile::GET);
	}

	/**
	 * @param string $libId
	 * @param string $path
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function createDirectory(string $libId, string $path) {
		$url = sprintf(
			"%s/repos/%s/dir/?p=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path)
		);
		$params = [
			'operation' => 'mkdir',
		];

		return $this->api($url, Seafile::POST, $params);
	}

	/**
	 * @param string $libId
	 * @param string $path
	 * @param string $newName
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function renameDirectory(string $libId, string $path, string $newName) {
		$url = sprintf(
			"%s/repos/%s/dir/?p=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path)
		);
		$params = [
			'operation' => 'rename',
			'newname'   => $newName,
		];

		return $this->api($url, Seafile::POST, $params);
	}

	/**
	 * @param string $libId
	 * @param string $path
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function deleteDirectory(string $libId, string $path) {
		$url = sprintf(
			"%s/repos/%s/dir/?p=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path)
		);

		return $this->api($url, Seafile::DELETE);
	}

}