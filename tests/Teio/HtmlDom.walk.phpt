<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parser = new HtmlParser;
$dom = $parser->parse('
	<a>
		<a1>
			<a11 />
		</a1>
		<a2>
			<a21 />
			<a22 />
			<a23 />
		</a2>
		<a3>
			<a31>
				<a311></a311>
			</a31>
		</a3>
	</a>
	<b>
		<b1>
			<b12></b12>
		</b1>
	</b>
');


test(function () use ($dom) {
	$result = [];
	$dom->walk(function ($node) use (&$result) {
		if ($node->isHtml()) {
			$result[] = $node->getName();
		}
	});

	Assert::same([
		'a',
		'a1',
		'a11',
		'a2',
		'a21',
		'a22',
		'a23',
		'a3',
		'a31',
		'a311',
		'b',
		'b1',
		'b12'
	], $result);

	Assert::same('	<a>
		<a1>
			<a11></a11>
		</a1>
		<a2>
			<a21></a21>
			<a22></a22>
			<a23></a23>
		</a2>
		<a3>
			<a31>
				<a311></a311>
			</a31>
		</a3>
	</a>
	<b>
		<b1>
			<b12></b12>
		</b1>
	</b>', $dom->toHtml());
});
