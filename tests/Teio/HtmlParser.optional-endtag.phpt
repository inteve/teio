<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parser = new HtmlParser(new Teio\Dom\Html5Rules);


test(function () use ($parser) {
	Assert::same(
		'<div>1<div>2<div>3</div></div></div>',
		$parser->parse('<div>1<div>2<div>3')->toHtml()
	);
});


test(function () use ($parser) {
	Assert::same(
		'<p>1</p><p>2</p><p>3</p>',
		$parser->parse('<p>1<p>2<p>3')->toHtml()
	);

	Assert::same(
		'<select><option>1</option><option>2</option></select>',
		$parser->parse('<select><option>1<option>2</select>')->toHtml()
	);

	Assert::same(
		'<ul><li>1</li><li>2</li></ul>',
		$parser->parse('<ul><li>1<li>2</ul>')->toHtml()
	);

	Assert::same(
		'<dl><dt>DT1</dt><dd>DD1</dd><dt>DT2</dt><dt>DT3</dt><dd>DD2</dd></dl>',
		$parser->parse('<dl><dt>DT1<dd>DD1<dt>DT2<dt>DT3<dd>DD2</dl>')->toHtml()
	);

	Assert::same(
		'<table>
			<thead>
				<tr><td>Col1</td><td>Col2
				</td></tr><tr><th>Col1</th><th>Col2
				</th></tr><tr><td>Col1</td><th>Col2
				</th></tr><tr><th>Col1</th><td>Col2
			</td></tr></thead><tbody>
				<tr><td>Col1</td><td>Col2
				</td></tr><tr><th>Col1</th><th>Col2
				</th></tr><tr><td>Col1</td><th>Col2
				</th></tr><tr><th>Col1</th><td>Col2
			</td></tr></tbody><tfoot>
				<tr><td>Col1</td><td>Col2
				</td></tr><tr><th>Col1</th><th>Col2
				</th></tr><tr><td>Col1</td><th>Col2
				</th></tr><tr><th>Col1</th><td>Col2
		</td></tr></tfoot></table>',
		$parser->parse('<table>
			<thead>
				<tr><td>Col1<td>Col2
				<tr><th>Col1<th>Col2
				<tr><td>Col1<th>Col2
				<tr><th>Col1<td>Col2
			<tbody>
				<tr><td>Col1<td>Col2
				<tr><th>Col1<th>Col2
				<tr><td>Col1<th>Col2
				<tr><th>Col1<td>Col2
			<tfoot>
				<tr><td>Col1<td>Col2
				<tr><th>Col1<th>Col2
				<tr><td>Col1<th>Col2
				<tr><th>Col1<td>Col2
		</table>')->toHtml()
	);
});
