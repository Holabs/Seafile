<?php


namespace Holabs\Seafile\Bridges\Nette;

use Holabs\Seafile\Seafile;
use Nette\DI\Extensions\ExtensionsExtension;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/seafile
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class SeafileExtension extends ExtensionsExtension {

	public $defaults = [
		'url' => 'https://seacloud.cc/',
	];

	public function loadConfiguration() {

		$this->validateConfig($this->defaults);
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('service'))
			->setFactory(Seafile::class, [$config['url']]);
	}

}