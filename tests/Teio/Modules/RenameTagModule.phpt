<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Teio\Modules\RenameTagModule;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$module = new RenameTagModule([
		'h1' => 'h2',
		'h2' => 'h3',
		'h3' => 'h4',
		'h4' => 'h5',
		'h5' => 'h6',
	]);
	TeioAssert::checkModuleOutput('RenameTagModule/basic', $module);
});
