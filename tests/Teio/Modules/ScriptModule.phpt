<?php

use Nette\Utils\Html;
use Teio\HtmlParser;
use Teio\Modules\ScriptModule;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


test(function () {
	$module = new ScriptModule([
		'handler1' => function ($name, array $args) {
			return Html::el('b')->setText($name);
		},
		'handler2' => function ($name, array $args) {
			return Html::el('em')->setText($name . ' with ' . implode('; ', $args));
		},
		'handler3' => function ($name, array $args) {
			return Html::el('div')->class('handler-3')->setText($name);
		},
	]);
	TeioAssert::checkModuleOutput('ScriptModule/basic', $module);
});
