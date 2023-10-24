<?php

declare(strict_types=1);

return function (JP\CodeChecker\CheckerConfig $config) {
	$config->setPhpVersion(new JP\CodeChecker\Version('7.4.0'));
	$config->addPath('.');
	JP\CodeChecker\Sets\CzProjectMinimum::configure($config);
};
