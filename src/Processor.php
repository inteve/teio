<?php

	namespace Teio;


	class Processor
	{
		/** @var Module[] */
		private $modules;

		/** @var IDomRules */
		private $domRules;


		/**
		 * @param Module[] $modules
		 */
		public function __construct(array $modules, IDomRules $domRules = NULL)
		{
			$this->modules = $modules;
			$this->domRules = $domRules !== NULL ? $domRules : new Dom\Html5Rules;
		}


		public function process(string $s): string
		{
			$parser = new HtmlParser($this->domRules);
			$dom = $parser->parse($s);

			foreach ($this->modules as $module) {
				$module->process($dom);
			}

			return $dom->toHtml();
		}
	}
