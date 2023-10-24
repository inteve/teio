<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\HtmlParser;
use Teio\Modules\TopLevelBlockModule;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$module = new TopLevelBlockModule([
		'.block-1',
		'.block-2',
	]);
	TeioAssert::checkModuleOutput('TopLevelBlockModule/basic', $module);
});
