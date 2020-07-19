<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parser = new HtmlParser;
$dom = $parser->parse('<a href="http://example.com">link &gt;<br><b>to</b> &nbsp; here</a>');

test(function () use ($dom) {
	TeioAssert::sameNodeText([
		'link >',
		" \xc2\xa0 here",
		'to',
	], $dom->findTextNodes());
});
