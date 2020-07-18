
# Inteve\Teio

[![Build Status](https://travis-ci.org/inteve/teio.svg?branch=master)](https://travis-ci.org/inteve/teio)

Powerful HTML processor.

<a href="https://www.patreon.com/bePatron?u=9680759"><img src="https://c5.patreon.com/external/logo/become_a_patron_button.png" alt="Become a Patron!" height="35"></a>
<a href="https://www.paypal.me/janpecha/5eur"><img src="https://buymecoffee.intm.org/img/button-paypal-white.png" alt="Buy me a coffee" height="35"></a>


## Installation

[Download a latest package](https://github.com/inteve/teio/releases) or use [Composer](http://getcomposer.org/):

```
composer require teio/teio
```

Inteve\Teio requires PHP 5.6.0 or later and ....


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