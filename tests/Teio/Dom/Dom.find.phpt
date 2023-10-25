<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\Dom\Dom;
use Teio\Dom\DomSelector;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function createDom(): Html
{
	$dom = Html::el();
	$dom->addHtml(Html::el('a'));
	$dom->addHtml(
		Html::el('table')
			->addHtml(
				Html::el('tr')->class('row')->data('js', 'data')
					->addHtml(Html::el('td'))
					->addHtml(Html::el('td'))
					->addHtml(
						Html::el('td')
							->addText('begin')
							->addHtml(Html::el('em'))
							->addText('end')
					)
			)
	);

	return $dom;
}


test(function () {
	$dom = new Dom(createDom());

	$dom->find('.row :first-child', function (Teio\Dom\Node $node) {
		$node->setAttribute('matched', TRUE);
	});

	$expectedDom = createDom();
	$children = $expectedDom->getChildren();
	$rows = $children[1]->getChildren();
	$columns = $rows[0]->getChildren();
	$columns[0]->matched(TRUE);

	$columnContent = $columns[2]->getChildren();
	$columnContent[1]->matched(TRUE);

	Assert::same((string) $expectedDom, $dom->toHtml());
});


test(function () {
	$dom = new Dom(createDom());

	$selector = new DomSelector;
	$selector->addGroup()
		->addPart()
			->requireTag('table');

	$dom->find('table', function (Teio\Dom\Node $node) {
		$node->setAttribute('matched', TRUE);
	});

	$expectedDom = createDom();
	$children = $expectedDom->getChildren();
	$children[1]->matched(TRUE);

	Assert::same((string) $expectedDom, $dom->toHtml());
});


test(function () {
	$dom = new Dom(createDom());

	$selector = new DomSelector;
	$selector->addGroup()
		->addPart()
			->requireTag('table');

	$dom->find('[data-js]', function (Teio\Dom\Node $node) {
		$node->setAttribute('matched', TRUE);
	});

	$expectedDom = createDom();
	$domChildren = $expectedDom->getChildren();
	$table = $domChildren[1];
	$rows = $table->getChildren();
	$rows[0]->matched(TRUE);

	Assert::same((string) $expectedDom, $dom->toHtml());
});
