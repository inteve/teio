<?php

use Nette\Utils\Html;
use Teio\Dom\DomNode;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function createDom()
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
	$dom = createDom();
	$root = DomNode::root($dom);

	$level1 = $root->getChildren();
	$level2 = $level1[0]->getChildren();
	$level3 = $level2[0]->getChildren();
	$level3[1]->moveUp();

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
	TeioAssert::equalHtml($expected, $dom);
});


test(function () {
	$dom = createDom();
	$root = DomNode::root($dom);

	$level1 = $root->getChildren();
	$level2 = $level1[0]->getChildren();
	$level3 = $level2[0]->getChildren();
	$level3[0]->moveUp();

	$expected = Html::el();
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-3 level-3--first"'))
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--second"'))
			->addText('lorem ipsum')
			->addHtml(Html::el('div class="level-3 level-3--third"'))
		)
	);
	TeioAssert::equalHtml($expected, $dom);
});


test(function () {
	$dom = createDom();
	$root = DomNode::root($dom);

	$level1 = $root->getChildren();
	$level2 = $level1[0]->getChildren();
	$level3 = $level2[0]->getChildren();
	$level3[2]->moveUp();

	$expected = Html::el();
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--first"'))
			->addHtml(Html::el('div class="level-3 level-3--second"'))
		)
		->addText('lorem ipsum')
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--third"'))
		)
	);
	TeioAssert::equalHtml($expected, $dom);
});


test(function () {
	$dom = createDom();
	$root = DomNode::root($dom);

	$level1 = $root->getChildren();
	$level2 = $level1[0]->getChildren();
	$level3 = $level2[0]->getChildren();
	$level3[3]->moveUp();

	$expected = Html::el();
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--first"'))
			->addHtml(Html::el('div class="level-3 level-3--second"'))
			->addText('lorem ipsum')
		)
		->addHtml(Html::el('div class="level-3 level-3--third"'))
	);
	TeioAssert::equalHtml($expected, $dom);
});


test(function () {
	$dom = createDom();
	$root = DomNode::root($dom);

	$level1 = $root->getChildren();
	$level2 = $level1[0]->getChildren();
	$level3 = $level2[0]->getChildren();
	$level3[1]->moveUp();
	$level3[1]->moveUp();

	$expected = Html::el();
	$expected->addHtml(Html::el('div class="level-1"')
		->addHtml(Html::el('div class="level-2"')
			->addHtml(Html::el('div class="level-3 level-3--first"'))
		));
	$expected->addHtml(Html::el('div class="level-3 level-3--second"'));
	$expected->addHtml(Html::el('div class="level-2"')
		->addText('lorem ipsum')
		->addHtml(Html::el('div class="level-3 level-3--third"'))
	);
	TeioAssert::equalHtml($expected, $dom);
});
