<?php

declare(strict_types=1);

use Teio\Modules\AutoLinkModule;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$module = new AutoLinkModule;
	TeioAssert::checkModuleOutput('AutoLinkModule/basic', $module);
});
