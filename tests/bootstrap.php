<?php

use Nette\Utils\Html;
use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


function test(callable $cb): void
{
	$cb();
}


class TeioAssert
{
	/**
	 * @param  string[] $expected
	 */
	public static function sameNodeText(array $expected, Teio\Dom\Dom $dom): void
	{
		$actual = [];

		$dom->findTextNodes(function (Teio\Dom\Node $node) use (&$actual) {
			$actual[] = $node->getText();
		});

		Assert::same($expected, $actual);
	}


	public static function checkModuleOutput(string $fixture, Teio\Module $module): void
	{
		$sourceFile = __DIR__ . '/Teio/fixtures/' . $fixture . '.source.html';
		$expectedFile = __DIR__ . '/Teio/fixtures/' . $fixture . '.expected.html';

		$parser = new Teio\HtmlParser(new Teio\Dom\XmlRules);
		$dom = $parser->parse((string) file_get_contents($sourceFile));
		$module->process($dom);
		Assert::matchFile($expectedFile, $dom->toHtml());
	}
}
