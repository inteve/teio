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
				$node->moveUp(1);

			} elseif ($node->getName() === 'a31') {
				$node->moveToRoot();
			}
		}
	});

	Assert::same("	<a>
		<a1>
			<a11></a11>
		</a1>
		</a><a2>
			<a21></a21>
			<a22></a22>
			<a23></a23>
		</a2><a>
		<a3>
			</a3></a><a31>
				<a311></a311>
			</a31><a><a3>
		</a3>
	</a>
	<b>
		<b1>
			<b12></b12>
		</b1>
	</b>", $dom->toHtml());
});
