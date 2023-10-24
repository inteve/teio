<?php

declare(strict_types=1);

use Nette\Utils\Html;
use Teio\HtmlParser;
use Teio\Modules\HtmlFilterModule;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$module = new HtmlFilterModule([
		'a' => ['href'], // only with href
		'b' => [], // without attrs
		'c' => TRUE, // all attrs
		'd' => FALSE, // disabled element
	]);
	TeioAssert::checkModuleOutput('HtmlFilterModule/basic', $module);
});
