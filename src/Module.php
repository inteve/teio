<?php

	namespace Teio;


	interface Module
	{
		function process(Dom\Dom $dom): void;
	}
