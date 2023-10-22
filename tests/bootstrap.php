<?php

use Nette\Utils\Html;
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


function test($cb)
{
	$cb();
}


class TeioAssert
{
	public static function equalDom(Html $expected, Teio\HtmlDom $actual)
	{
		self::equalHtml($expected, $actual->getDom());
	}


	public static function equalHtml(Html $expected, Html $actual)
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
					self::equalHtml($expectedChild, $actualChild);

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


	public static function checkModuleOutput($fixture, Teio\IModule $module)
	{
		$sourceFile = __DIR__ . '/Teio/fixtures/' . $fixture . '.source.html';
		$expectedFile = __DIR__ . '/Teio/fixtures/' . $fixture . '.expected.html';

		$parser = new Teio\HtmlParser;
		$dom = $parser->parse(file_get_contents($sourceFile));
		$module->process($dom);
		Assert::matchFile($expectedFile, $dom->toHtml());
	}
}
