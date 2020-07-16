<?php

use Nette\Utils\Html;
use Teio\Dom\DomPath;
use Teio\Dom\DomPathPart;
use Teio\Dom\DomSelector;
use Teio\Dom\DomSelectorParser;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$parser = new DomSelectorParser;


test(function () use ($parser) {
	// .row :first-child
	$selector = new DomSelector;
	$selector->addGroup()
		->addPart()
			->requireClass('row')
		->addPart()
			->requireFirstPosition();

	Assert::false($selector->matchPath(new DomPath([
		new DomPathPart(Html::el('a'), 0, TRUE),
	])));

	Assert::true($selector->matchPath(new DomPath([
		new DomPathPart(Html::el('table'), 0, TRUE),
		new DomPathPart(Html::el('tr')->class('row'), 0, TRUE),
		new DomPathPart(Html::el('td'), 0, FALSE),
	])));

	Assert::false($selector->matchPath(new DomPath([
		new DomPathPart(Html::el('table'), 0, TRUE),
		new DomPathPart(Html::el('tr')->class('row'), 0, TRUE),
		new DomPathPart(Html::el('td'), 1, FALSE),
	])));

	Assert::true($selector->matchPath(new DomPath([
		new DomPathPart(Html::el('table'), 0, TRUE),
		new DomPathPart(Html::el('tr')->class('row'), 0, TRUE),
		new DomPathPart(Html::el('td'), 2, TRUE),
		new DomPathPart(Html::el('em'), 0, TRUE),
	])));
});
