<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\IModule;
	use Teio\HtmlDom;


	class WrapElementModule implements IModule
	{
		/** @var array<string, string> */
		private $wraps;


		public function __construct(array $wraps)
		{
			$this->wraps = $wraps;
		}


		public function process(HtmlDom $dom)
		{
			foreach ($this->wraps as $selector => $wrapper) {
				$nodes = $dom->find($selector);

				foreach ($nodes as $node) {
					$node->wrapBy(Html::el($wrapper));
				}
			}
		}
	}
