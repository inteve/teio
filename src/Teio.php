<?php

	namespace Teio;

	use PHPHtmlParser;


	class Teio
	{
		private $modules;


		public function __construct()
		{
			$this->modules = [
				// new Modules\HtmlFilterModule,
				// new Modules\TypographyModule,
				// new Modules\EmoticonModule,
				// new Modules\HtmlTagModificationModule,
				// new Modules\ScriptModule,
			];
		}


		public function addHandler(callable $cb) // ???
		{

		}


		public function process($s)
		{
			$parser = new HtmlParser;
			$html = $parser->parse($s);
			$dom = new HtmlDom($html);

			foreach ($this->modules as $module) {
				$module->process($dom);
			}

			return $dom->toHtml();
		}
	}
