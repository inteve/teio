<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parser = new HtmlParser(new Teio\Dom\Html5Rules);


test(function () use ($parser) {
	Assert::same(
		// TODO: '<b>1</b><p><b>2</b>3</p>',
		'<b>1</b><p>23</p>',
		$parser->parse('<b>1<p>2</b>3</p>')->toHtml()
	);

	Assert::same(
		// TODO: '<a>a</a><a>bbb</a><a>a</a>',
		// TODO: '<a href="#first">abbba</a>',
		'<a href="#first">abbb</a>a',
		$parser->parse('<a href="#first">a<a href="#second">bbb</a>a</a>')->toHtml()
	);

	Assert::same(
		// TODO: '<b></b><b>bbb</b><table><tr><td>aaa</td></tr></table><b>ccc</b>',
		'<table><tr><td>aaa</td></tr></table>ccc',
		$parser->parse('<table><b><tr><td>aaa</td></tr>bbb</table>ccc')->toHtml()
	);
});
