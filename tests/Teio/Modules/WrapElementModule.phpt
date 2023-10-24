<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\HtmlParser;
use Teio\Modules\WrapElementModule;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$module = new WrapElementModule([
		'table' => 'div class=table-wrapper',
		'pre.code' => 'div class=code-block',
	]);
	TeioAssert::checkModuleOutput('WrapElementModule/basic', $module);
});
