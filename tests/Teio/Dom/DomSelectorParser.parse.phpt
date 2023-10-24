<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\Dom\DomSelector;
use Teio\Dom\DomSelectorParser;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$parser = new DomSelectorParser;


test(function () use ($parser) {
	$selector = new DomSelector;
	$selector->addGroup()
		->addPart()
			->requireTag('h1');

	Assert::equal($selector, $parser->parse('h1'));
});


test(function () use ($parser) {
	$selector = new DomSelector;
	$selector->addGroup()
		->addPart()
			->requireId('page-wrapper');

	$group = $selector->addGroup()
		->addPart()
			->requireTag('table')
		->addPart()
			->requireClass('link')
			->requireFirstPosition();

	Assert::equal($selector, $parser->parse('#page-wrapper, table .link:first-child'));
});
