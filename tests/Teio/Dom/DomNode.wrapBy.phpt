<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

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
	$dom->walk(function ($node) {
		if ($node->isHtml()) {
			if ($node->getName() === 'a11') {
				$node->wrapBy(Html::el('div')->class('wrapper'));
				$node->wrapBy(Html::el('div')->class('wrapper2'));
			}
		}
	});

	Assert::same("	<a>
		<a1>
			<div class=\"wrapper2\"><div class=\"wrapper\"><a11></a11></div></div>
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
	</b>", $dom->toHtml());
});
