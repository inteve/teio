<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parser = new HtmlParser;
$dom = $parser->parse('
	<a href="http://example.com">link &gt;<br><b>to</b> &nbsp; here</a>

	<table>
		<tr class="row">
			<td>First</td>
			<td id="first-column">Second</td>
			<td><em>Third</em></td>
		</tr>
	</table>
');


test(function () use ($dom) {
	$nodes = [];
	$dom->find('b', function ($node) use (&$nodes) {
		$nodes[] = $node;
	});
	Assert::same(1, count($nodes));

	Assert::same('b', $nodes[0]->getName());
	Assert::same('to', $nodes[0]->getText());
});


test(function () use ($dom) {
	$nodes = [];
	$dom->find('.row :first-child', function ($node) use (&$nodes) {
		$nodes[] = $node;
	});
	Assert::same(2, count($nodes));

	Assert::same('td', $nodes[0]->getName());
	Assert::same('First', $nodes[0]->getText());

	Assert::same('em', $nodes[1]->getName());
	Assert::same('Third', $nodes[1]->getText());
});
