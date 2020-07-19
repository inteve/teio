<?php

use Nette\Utils\Html;
use Teio\Dom\DomNode;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$dom = Html::el();
	$dom->addHtml('<b>hello</b>');
	$dom->addHtml(Html::el('a')->href('http://example.com')->class('external-link')->setText('link'));
	$dom->addText('<b>hello</b>');

	$root = DomNode::root($dom);
	Assert::true($root->isRoot());

	$children = $root->getChildren();

	// HTML string
	Assert::false($children[0]->isHtml());
	Assert::false($children[0]->isText());
	Assert::false($children[0]->isRoot());
	Assert::false($children[0]->isElement());
	Assert::false($children[0]->hasPosition());
	Assert::same('hello', $children[0]->getText());

	// HTML instance
	Assert::true($children[1]->isHtml());
	Assert::false($children[1]->isText());
	Assert::false($children[1]->isRoot());
	Assert::true($children[1]->isElement());
	Assert::true($children[1]->hasPosition());
	Assert::true($children[1]->isFirst());
	Assert::true($children[1]->isLast());
	Assert::true($children[1]->hasChildren());
	Assert::same(1, count($children[1]->getChildren()));
	Assert::same('a', $children[1]->getName());
	Assert::same([
		'href' => 'http://example.com',
		'class' => 'external-link',
	], $children[1]->getAttributes());
	Assert::true($children[1]->hasClass('external-link'));
	Assert::false($children[1]->hasClass('css-class'));

	// text node
	Assert::false($children[2]->isHtml());
	Assert::true($children[2]->isText());
	Assert::false($children[2]->isRoot());
	Assert::false($children[2]->isElement());
	Assert::false($children[2]->hasPosition());
	Assert::same('<b>hello</b>', $children[2]->getText());

	// parents
	$content = $children[1]->getChildren();
	$text = $content[0];
	$parents = $text->getParents();
	Assert::same(2, count($parents)); // root + <a>

	Assert::true($parents[0]->isRoot());
	Assert::true($parents[1]->isElement());
	Assert::same('a', $parents[1]->getName());
});
