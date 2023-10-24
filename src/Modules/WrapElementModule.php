<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\Dom\Dom;
	use Teio\Dom\Node;
	use Teio\Module;


	class WrapElementModule implements Module
	{
		/** @var array<string, string> */
		private $wraps;


		/**
		 * @param array<string, string> $wraps
		 */
		public function __construct(array $wraps)
		{
			$this->wraps = $wraps;
		}


		public function process(Dom $dom): void
		{
			foreach ($this->wraps as $selector => $wrapper) {
				$dom->find($selector, function (Node $node) use ($wrapper) {
					$node->wrapBy(Html::el($wrapper));
				});
			}
		}
	}
