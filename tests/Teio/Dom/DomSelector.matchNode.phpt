<?php

use Nette\Utils\Html;
use Teio\Dom\DomNode;
use Teio\Dom\DomSelector;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$dom = Html::el();
$dom->addHtml(Html::el('a'));
$dom->addHtml(
	Html::el('table')
		->addHtml(
			Html::el('tr')->class('row')
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


test(function () use ($dom) {
	$root = DomNode::root($dom);

	// .row :first-child
	$selector = new DomSelector;
	$selector->addGroup()
		->addPart()
			->requireClass('row')
		->addPart()
			->requireFirstPosition();

	$children = $root->getChildren();
	Assert::false($selector->matchNode($children[0])); // a
	Assert::false($selector->matchNode($children[1])); // table

	$rows = $children[1]->getChildren();
	Assert::false($selector->matchNode($rows[0]));

	$columns = $rows[0]->getChildren();
	Assert::true($selector->matchNode($columns[0])); // table tr.row td:first-child
	Assert::false($selector->matchNode($columns[1])); // table tr.row td

	$columnContent = $columns[2]->getChildren();
	Assert::false($selector->matchNode($columnContent[0])); // table tr.now td ::text
	Assert::true($selector->matchNode($columnContent[1])); // table tr.row td em:first-child
	Assert::false($selector->matchNode($columnContent[2])); // table tr.now td ::text
});


test(function () use ($dom) {
	$root = DomNode::root($dom);

	// .row :first-child
	$selector = new DomSelector;
	$selector->addGroup()
		->addPart()
			->requireTag('table');

	$children = $root->getChildren();
	Assert::false($selector->matchNode($children[0])); // a
	Assert::true($selector->matchNode($children[1])); // table
});
