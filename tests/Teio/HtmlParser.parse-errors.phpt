<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// https://html.spec.whatwg.org/multipage/parsing.html#parse-errors
$parser = new HtmlParser(new Teio\Dom\Html5Rules);


test(function () use ($parser) {
	// mishmash tags
	Assert::same(
		'<strong><em>abc</em></strong>',
		$parser->parse('<strong><em>abc</strong></em>')->toHtml()
	);

	Assert::same(
		'<p>1<b>2<i>34</i>5</b></p>',
		// TODO: '<p>1<b>2<i>3</i></b><i>4</i>5</p>',
		$parser->parse('<p>1<b>2<i>3</b>4</i>5</p>')->toHtml()
	);
});


test(function () use ($parser) {
	// nested-comment
	Assert::same(
		'<!-- <!-- nested --> --&gt;',
		$parser->parse('<!-- <!-- nested --> -->')->toHtml()
	);

	Assert::same(
		'hello <!-- <!-- nested --> --&gt; hello',
		$parser->parse('hello <!-- <!-- nested --> --> hello')->toHtml()
	);

	Assert::same(
		'hello <!-- <!-- nested --> --&gt; <a href=""></a>',
		$parser->parse('hello <!-- <!-- nested --> --> <a href=""></a>')->toHtml()
	);
});


test(function () use ($parser) {
	// non-void-html-element-start-tag-with-trailing-solidus
	Assert::same(
		'<div><span></span><span></span></div>',
		$parser->parse('<div/><span></span><span></span>')->toHtml()
	);
});
