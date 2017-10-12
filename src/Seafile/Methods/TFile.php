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
trait TFile {

	/**
	 * @param string $libId
	 * @param string $path
	 * @param bool   $reuse
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function getFileDownloadLink(string $libId, string $path, bool $reuse = FALSE) {
		$url = sprintf(
			"%s/repos/%s/file/?p=%s&reuse=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path),
			(string) $reuse
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
	public function getFileDetail(string $libId, string $path) {
		$url = sprintf(
			"%s/repos/%s/file/detail/?p=%s",
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
	public function createFile(string $libId, string $path) {
		$url = sprintf(
			"%s/repos/%s/file/?p=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path)
		);
		$params = [
			'operation' => 'create',
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
	public function renameFile(string $libId, string $path, string $newName) {
		$url = sprintf(
			"%s/repos/%s/file/?p=%s",
			Seafile::API_URL,
			$libId,
			urlencode($path)
		);
		$params = [
			'operation' => 'rename',
			'newname' => $newName,
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
	public function lockFile(string $libId, string $path) {
		$url = sprintf(
			"%s/repos/%s/file/",
			Seafile::API_URL,
			$libId
		);
		$params = [
			'operation' => 'lock',
			'p' => urlencode($path),
		];
		return $this->api($url, Seafile::PUT, $params);
	}

	/**
	 * @param string $libId
	 * @param string $path
	 * @return stdClass
	 * @throws ApiException
	 * @throws \Nette\Utils\JsonException
	 */
	public function unlockFile(string $libId, string $path) {
		$url = sprintf(
			"%s/repos/%s/file/",
			Seafile::API_URL,
			$libId
		);
		$params = [
			'operation' => 'unlock',
			'p' => urlencode($path),
		];
		return $this->api($url, Seafile::PUT, $params);
	}

}