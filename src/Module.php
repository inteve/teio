<?php

	declare(strict_types=1);

	namespace Teio;


	interface Module
	{
		function process(Dom\Dom $dom): void;
	}
