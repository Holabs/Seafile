<?php


namespace Holabs\Seafile;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class User {

	/** @var string */
	private $apiKey;

	/** @var string|null */
	private $loginId;

	/** @var string */
	private $email;

	/** @var string|null */
	private $name;

	/** @var string|null */
	private $institution;

	/** @var string|null */
	private $department;

	/** @var string|null */
	private $contactMail;

	/** @var int */
	private $total;

	/** @var int */
	private $usage;



	/**
	 * User constructor.
	 * @param string      $apiKey
	 * @param string      $email
	 * @param int         $usage
	 * @param int         $total
	 * @param string|null $loginId
	 * @param string|null $name
	 * @param string|null $institution
	 * @param string|null $department
	 * @param string|null $contactMail
	 */
	public function __construct(
		string $apiKey,
		string $email,
		int $usage = 0,
		int $total = 0,
		string $loginId = NULL,
		string $name = NULL,
		string $institution = NULL,
		string $department = NULL,
		string $contactMail = NULL
	) {
		$this->apiKey = $apiKey;
		$this->email = $email;
		$this->total = $total;
		$this->usage = $usage;
		$this->loginId = empty($loginId) ? NULL : $loginId;
		$this->name = empty($name) ? NULL : $name;
		$this->institution = empty($institution) ? NULL : $institution;
		$this->department = empty($department) ? NULL : $department;
		$this->contactMail = empty($contactMail) ? NULL : $contactMail;
	}

	/**
	 * @return string
	 */
	public function getApiKey(): string {
		return $this->apiKey;
	}

	/**
	 * @return null|string
	 */
	public function getLoginId(): ?string {
		return $this->loginId;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * @return null|string
	 */
	public function getName(): ?string {
		return $this->name;
	}

	/**
	 * @return null|string
	 */
	public function getInstitution(): ?string {
		return $this->institution;
	}

	/**
	 * @return null|string
	 */
	public function getDepartment(): ?string {
		return $this->department;
	}

	/**
	 * @return null|string
	 */
	public function getContactMail(): ?string {
		return $this->contactMail;
	}

	/**
	 * @return int
	 */
	public function getTotal(): int {
		return $this->total;
	}

	/**
	 * @return int
	 */
	public function getUsage(): int {
		return $this->usage;
	}


}