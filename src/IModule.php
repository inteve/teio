<?php

	namespace Teio;


	interface IModule
	{
		function process(Dom\Dom $dom): void;
	}
