<?php

use Nette\Utils\Html;
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


function test($cb)
{
	$cb();
}


function assertHtml(Html $expected, Html $actual)
{
	Assert::same($expected->getName(), $actual->getName());
	Assert::equal($expected->getAttributes(), $actual->getAttributes());

	$expectedChildren = $expected->getChildren();
	$actualChildren = $actual->getChildren();
	Assert::same(count($expectedChildren), count($actualChildren), 'Wrong count of children');

	foreach ($expectedChildren as $k => $expectedChild) {
		$actualChild = NULL;

		if (isset($actualChildren[$k])) {
			$actualChild = $actualChildren[$k];
		}

		if ($expectedChild instanceof Html) {
			if ($actualChild instanceof Html) {
				assertHtml($expectedChild, $actualChild);

			} else {
				Assert::fail('Children mishmash, expected ' . Html::class . ', ' . (is_object($actualChild) ? get_class($actualChild) : gettype($actualChild)) . ' given.', $actualChild, $expectedChild);
			}

		} else {
			if ($actualChild instanceof Html) {
				Assert::fail('Children mishmash', $actualChild, $expectedChild);

			} else {
				Assert::same($expectedChild, $actualChild);
			}
		}
	}
}
