<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$parser = new HtmlParser(new Teio\Dom\XmlRules);
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
	$dom->walk(function ($node) {
		if ($node->isHtml()) {
			if ($node->getName() === 'a2') {
				$node->remove();
			}
		}
	});

	Assert::same("	<a>
		<a1>
			<a11></a11>
		</a1>
\t\t
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
	</b>", $dom->toHtml());
});
