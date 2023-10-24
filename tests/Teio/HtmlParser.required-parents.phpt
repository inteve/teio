<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parser = new HtmlParser(new Teio\Dom\Html5Rules);


test(function () use ($parser) {
	Assert::same(
		'<select><option></option><optgroup><option></option></optgroup></select>',
		$parser->parse('<option></option><optgroup><option></option></optgroup><select><option></option><optgroup><option></option></optgroup></select>')->toHtml()
	);
});
