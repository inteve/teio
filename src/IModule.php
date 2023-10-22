<?php

	namespace Teio;


	interface IModule
	{
		function process(HtmlDom $dom);
	}
