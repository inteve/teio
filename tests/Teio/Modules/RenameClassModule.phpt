<?php

declare(strict_types=1);

use Teio\Modules\RenameClassModule;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$module = new RenameClassModule([
		'class-1' => 'foo',
		'bar' => 'foo',
		'foo' => 'foobar',
		'to-remove' => NULL,
	]);
	TeioAssert::checkModuleOutput('RenameClassModule/basic', $module);
});
