# Inteve\Teio

[![Build Status](https://github.com/inteve/teio/workflows/Build/badge.svg)](https://github.com/inteve/teio/actions)
[![Downloads this Month](https://img.shields.io/packagist/dm/inteve/teio.svg)](https://packagist.org/packages/inteve/teio)
[![Latest Stable Version](https://poser.pugx.org/inteve/teio/v/stable)](https://github.com/inteve/teio/releases)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/inteve/teio/blob/master/license.md)

Powerful HTML processor.

<a href="https://www.janpecha.cz/donate/"><img src="https://buymecoffee.intm.org/img/donate-banner.v1.svg" alt="Donate" height="100"></a>


## Installation

[Download a latest package](https://github.com/inteve/teio/releases) or use [Composer](http://getcomposer.org/):

```
composer require teio/teio
```

Inteve\Teio requires PHP 7.4.0 or newer.


## Usage # OR Tips, Writing tests, ...

``` php
<?php
	$git = new Cz\Git\Git;
	$filename = __DIR__ . '/my-file.txt';
	file_put_contents($filename, "Lorem ipsum\ndolor\nsit amet");

	if($git->isChanges())
	{
		$git->add($filename)
			->commit('Added a file.');
	}
```

------------------------------

License: [New BSD License](license.md)
<br>Author: Jan Pecha, https://www.janpecha.cz/
