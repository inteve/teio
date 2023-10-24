<?php

	namespace Teio;


	class Processor
	{
		/** @var IModule[] */
		private $modules;

		/** @var IDomRules */
		private $domRules;


		public function __construct(array $modules, IDomRules $domRules = NULL)
		{
			$this->modules = $modules;
			$this->domRules = $domRules !== NULL ? $domRules : new Dom\Html5Rules;
		}


		public function process($s)
		{
			$parser = new HtmlParser($this->domRules);
			$dom = $parser->parse($s);

			foreach ($this->modules as $module) {
				$module->process($dom);
			}

			return $dom->toHtml();
		}
	}
