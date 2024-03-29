<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\Dom\Dom;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function createDom(): Html
{
	$dom = Html::el();
	$dom->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--first"'))
			->addHtml(Html::el('div class="level-3 level-3--second"'))
			->addText('lorem ipsum')
			->addHtml(Html::el('div class="level-3 level-3--third"'))
		)
	);
	return $dom;
}


test(function () {
	$dom = new Dom(createDom());
	$dom->find('.level-3.level-3--second', function (Teio\Dom\Node $node) {
		$node->moveUp();
	});

	$expected = Html::el();
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--first"'))
		)
		->addHtml(Html::el('div class="level-3 level-3--second"'))
		->addHtml(Html::el('div class="level-2"')
			->addText('lorem ipsum')
			->addHtml(Html::el('div class="level-3 level-3--third"'))
		)
	);
	Assert::same((string) $expected, $dom->toHtml());
});


test(function () {
	$dom = new Dom(createDom());
	$dom->find('.level-3.level-3--first', function (Teio\Dom\Node $node) {
		$node->moveUp();
	});

	$expected = Html::el();
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"'))
		->addHtml(Html::el('div class="level-3 level-3--first"'))
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--second"'))
			->addText('lorem ipsum')
			->addHtml(Html::el('div class="level-3 level-3--third"'))
		)
	);
	Assert::same((string) $expected, $dom->toHtml());
});


test(function () {
	$dom = new Dom(createDom());
	$dom->find('.level-3.level-3--third', function (Teio\Dom\Node $node) {
		$node->moveUp();
	});

	$expected = Html::el();
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--first"'))
			->addHtml(Html::el('div class="level-3 level-3--second"'))
			->addText('lorem ipsum')
		)
		->addHtml(Html::el('div class="level-3 level-3--third"'))
		->addHtml(Html::el('div class="level-2"'))
	);
	Assert::same((string) $expected, $dom->toHtml());
});


test(function () {
	$dom = new Dom(createDom());
	$dom->find('.level-3.level-3--second', function (Teio\Dom\Node $node) {
		$node->moveUp();
		$node->moveUp();
	});

	$expected = Html::el();
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--first"'))
		));
	$expected->addHtml(Html::el('div class="level-3 level-3--second"'));
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"')
			->addText('lorem ipsum')
			->addHtml(Html::el('div class="level-3 level-3--third"'))
		));
	Assert::same((string) $expected, $dom->toHtml());
});
