Holabs/Seafile
===============

This is light-weight package for communication wit Seafile server.
No objects no fluent or ActiveRow access.

Installation
------------

**Requirements:**
 - php 7.1+
 - [nette/utils](https://github.com/nette/utils)
 - [guzzlehttp/guzzle](https://github.com/guzzlehttp/guzzle)
 
**Optional:**
 - [tracy/tracy](https://github.com/tracy/tracy) (Debugger panel for show your communications and better debugging)
 
```sh
composer require holabs/seafile
composer require tracy/tracy	# optional
```

Configuration
-------------
```yaml
extensions:
	holabs.seafile: Holabs\Seafile\Bridges\Nette\SeafileExtension
	
holabs.seafile:
	url: 'https://cloud.seafile.com'
	
tracy:
	bar:
		- Holabs\Seafile\Bridges\Tracy\SeafilePanel
```

Using
-----

```php
<?php

namespace App\Presenters;

use Holabs\Seafile\Seafile;
use Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter {

	/** @var Seafile @inject */
	public $seafile;

	public function actionDefault() {
		parent::startup();

		$this->seafile->authenticate('user@example.com', '*******');
		$libraries = $this->seafile->getLibraries();
		
		// ...
	}

}
```