<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parser = new HtmlParser;


test(function () use ($parser) {
	$dom = Html::el();
	$dom->addHtml('<!-- ahoj -->');

	Assert::same((string) $dom, $parser->parse('<!-- ahoj -->')->toHtml());
});


test(function () use ($parser) {
	$dom = Html::el();
	$dom->create('a')
		->href('http://example.com')
		->addText('link >')
		->addHtml(Html::el('br'))
		->addHtml(Html::el('b')->setText('to'))
		->addText(" \xc2\xa0 here");

	Assert::same((string) $dom, $parser->parse('<a href="http://example.com">link &gt;<br><b>to</b> &nbsp; here</a>')->toHtml());
});
