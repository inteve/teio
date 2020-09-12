<?php

use Nette\Utils\Html;
use Teio\Dom\Dom;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$dom = Html::el();
	$dom->addHtml('<b>hello</b>');
	$dom->addHtml(Html::el('a')->href('http://example.com')->class('external-link')->setText('link'));
	$dom->addText('<b>hello</b>');

	$result = [];
	$root = new Dom($dom);
	$root->walk(function (Teio\Dom\Node $node) use (&$result) {
		$result[] = [
			'isHtml' => $node->isHtml(),
			'isText' => $node->isText(),
			'isElement' => $node->isElement(),
			'hasChildren' => $node->isHtml() ? $node->hasChildren() : NULL,
			'text' => $node->getText(),
			'attributes' => $node->isHtml() ? $node->getAttributes() : NULL,
			'hasClass' => [
				'external-link' => $node->isHtml() ? $node->hasClass('external-link') : NULL,
				'css-class' => $node->isHtml() ? $node->hasClass('css-class') : NULL,
			],
		];
	});

	Assert::same([
		// HTML string
		[
			'isHtml' => FALSE,
			'isText' => FALSE,
			'isElement' => FALSE,
			'hasChildren' => NULL,
			'text' => 'hello',
			'attributes' => NULL,
			'hasClass' => [
				'external-link' => NULL,
				'css-class' => NULL,
			],
		],
		// HTML instance
		[
			'isHtml' => TRUE,
			'isText' => FALSE,
			'isElement' => TRUE,
			'hasChildren' => TRUE,
			'text' => 'link',
			'attributes' => [
				'href' => 'http://example.com',
				'class' => 'external-link',
			],
			'hasClass' => [
				'external-link' => TRUE,
				'css-class' => FALSE,
			],
		],
		// text node
		[
			'isHtml' => FALSE,
			'isText' => TRUE,
			'isElement' => FALSE,
			'hasChildren' => NULL,
			'text' => 'link',
			'attributes' => NULL,
			'hasClass' => [
				'external-link' => NULL,
				'css-class' => NULL,
			],
		],
		// text node
		[
			'isHtml' => FALSE,
			'isText' => TRUE,
			'isElement' => FALSE,
			'hasChildren' => NULL,
			'text' => '<b>hello</b>',
			'attributes' => NULL,
			'hasClass' => [
				'external-link' => NULL,
				'css-class' => NULL,
			],
		],
	], $result);
});
