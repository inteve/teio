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
	Assert::equal([
		Html::el('b')->setText('to'),
	], $dom->find('b'));
});


test(function () use ($dom) {
	Assert::equal([
		Html::el('td')->setText('First'),
		Html::el('em')->setText('Third'),
	], $dom->find('.row :first-child'));
});
