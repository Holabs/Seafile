<?php


namespace Holabs\Seafile\Bridges\Tracy;

use Holabs\Seafile\ApiResponse;
use Holabs\Seafile\Seafile;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tracy\IBarPanel;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class SeafilePanel implements IBarPanel {

	/** @var \stdClass[] */
	private $requests = [];

	/** @var float */
	private $totalTime = 0.0;


	/**
	 * Min. one seafile server is requred
	 * @param Seafile $seafile
	 */
	public function __construct(Seafile $seafile) {
		$this->addSeafile($seafile);
	}


	public function getTab() {
		$requests = $this->requests;
		$count = count($this->requests);
		ob_start();
		require __DIR__ . '/templates/SeafileTab.phtml';

		return ob_get_clean();
	}

	public function getPanel() {

		if (!count($this->requests)) {
			return;
		}

		$requests = $this->requests;
		$count = count($this->requests);
		$totalTime = $this->totalTime;

		ob_start();
		require __DIR__ . '/templates/SeafilePanel.phtml';

		return ob_get_clean();
	}

	/**
	 * @param Seafile $seafile
	 * @return SeafilePanel
	 */
	public function addSeafile(Seafile $seafile): self {

		$seafile->onResponse[] = function ($seafile, $request, $response, $time, $data) {
			$this->addRequest($seafile, $request, $response, $time, $data);
		};

		return $this;
	}


	/**
	 * @param Seafile           $seafile
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param float             $time
	 * @param ApiResponse       $data
	 */
	private function addRequest(Seafile $seafile, RequestInterface $request, ResponseInterface $response, float $time, ApiResponse $data) {
		$r = new \stdClass();
		$this->requests[] = $r;
		$this->totalTime += $time;

		$r->seafile = $seafile;
		$r->request = clone $request;
		$r->response = clone $response;
		$r->time = $time;
		$r->data = $data->getBody();
	}

}