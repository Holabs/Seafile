<?php


namespace Holabs\Seafile;

use Nette\Http\UrlScript;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class Server {

	/** @var Seafile */
	private $seafile;

	/** @var UrlScript */
	private $url;

	/** @var string */
	private $version = NULL;

	/** @var bool */
	private $basic = TRUE;

	/** @var bool */
	private $pro = FALSE;

	/** @var bool */
	private $officePreview = FALSE;

	/** @var bool */
	private $fileSearch = FALSE;

	/** @var string|null */
	private $brand = NULL;

	/** @var string|null */
	private $logoUrl = NULL;

	/**
	 * Server constructor.
	 * @param Seafile   $seafile
	 * @param UrlScript $url
	 */
	public function __construct(Seafile $seafile, UrlScript $url) {
		$this->seafile = $seafile;
		$this->url = $url;
	}

	/**
	 * @return Seafile
	 */
	public function getSeafile(): Seafile {
		return $this->seafile;
	}

	/**
	 * @return UrlScript
	 */
	public function getUrl(): UrlScript {
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getApiUrl(): string {
		return "{$this->getUrl()}{$this->getUrl()->getScriptPath()}/";
	}

	/**
	 * @return string
	 */
	public function getVersion(): string {
		$this->load();

		return $this->version;
	}

	/**
	 * @return null|string
	 */
	public function getBrand(): ?string {
		$this->load();

		return $this->brand;
	}

	/**
	 * @return null|string
	 */
	public function getLogoUrl(): ?string {
		$this->load();

		return $this->logoUrl !== NULL ? $this->getUrl() . $this->logoUrl : NULL;
	}

	/**
	 * @return bool
	 */
	public function hasBrand(): bool {
		return $this->getBrand() !== NULL;
	}

	/**
	 * @return bool
	 */
	public function hasLogo(): bool {
		return $this->getLogoUrl() !== NULL;
	}

	/**
	 * @return bool
	 */
	public function isBasicEnabled(): bool {
		$this->load();

		return $this->basic;
	}

	/**
	 * @return bool
	 */
	public function isPro(): bool {
		$this->load();

		return $this->pro;
	}

	/**
	 * @return bool
	 */
	public function isOfficePreviewEnabled(): bool {
		$this->load();

		return $this->officePreview;
	}

	/**
	 * @return bool
	 */
	public function isFileSearchEnable(): bool {
		$this->load();

		return $this->fileSearch;
	}

	/**
	 * Load server info
	 */
	protected function load() {

		if ($this->version) {
			return;
		}

		$result = $this->seafile->api('/server-info/');

		foreach ($result as $k => $v) {
			switch ($k) {
				case "desktop-custom-brand":
					$this->brand = $v;
					break;
				case "desktop-custom-logo":
					$this->logoUrl = $v;
					break;
				case "version":
					$this->version = $v;
					break;
				case "features":
					foreach ($v as $feature) {
						switch ($feature) {
							case "seafile-basic":
								$this->basic = TRUE;
								break;
							case "seafile-pro":
								$this->pro = TRUE;
								break;
							case "office-preview":
								$this->officePreview = TRUE;
								break;
							case "file-search":
								$this->fileSearch = TRUE;
								break;
						}
					}
			}
		}
	}


}