<?php

	namespace Teio;


	class Processor
	{
		/** @var IModule[] */
		private $modules;


		public function __construct(array $modules = [])
		{
			$this->modules = $modules;
		}


		public function process($s)
		{
			$parser = new HtmlParser;
			$html = $parser->parse($s);
			$dom = new Dom\Dom($html);

			foreach ($this->modules as $module) {
				$module->process($dom);
			}

			return $dom->toHtml();
		}
	}
