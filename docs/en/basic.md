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

Using
-----

```php
<?php

$seafile = new \Holabs\Seafile\Seafile('https://cloud.seafile.com');
$seafile->onResponse[] = function($sender, $request, $response, $time, $data){
	
};
$this->seafile->authenticate('user@example.com', '*******');

try {
	$libraries = $this->seafile->getLibraries();
} catch (\Holabs\Seafile\ApiException $e) {
	// ...
}

```