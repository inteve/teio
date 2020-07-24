<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\Dom\Dom;
	use Teio\Dom\DomNode;
	use Teio\IModule;


	class WrapElementModule implements IModule
	{
		/** @var array<string, string> */
		private $wraps;


		public function __construct(array $wraps)
		{
			$this->wraps = $wraps;
		}


		public function process(Dom $dom)
		{
			foreach ($this->wraps as $selector => $wrapper) {
				$nodes = $dom->find($selector, function (DomNode $node) use ($wrapper) {
					$node->wrapBy(Html::el($wrapper));
				});
			}
		}
	}
