<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parser = new HtmlParser(new Teio\Dom\Html5Rules);
$dom = $parser->parse('<a href="http://example.com">link &gt;<br><b>to</b> &nbsp; here</a>');

test(function () use ($dom) {
	TeioAssert::sameNodeText([
		'link >',
		'to',
		" \xc2\xa0 here",
	], $dom);
});
