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
	$result = [];
	$dom->find('b', function ($node) use (&$result) {
		$result[] = [$node->getName(), $node->getText()];
	});

	Assert::same([
		['b', 'to'],
	], $result);
});


test(function () use ($dom) {
	$result = [];
	$dom->find('.row :first-child', function ($node) use (&$result) {
		$result[] = [$node->getName(), $node->getText()];
	});
	Assert::same([
		['td', 'First'],
		['em', 'Third'],
	], $result);
});
