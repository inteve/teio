<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\Dom\Dom;
	use Teio\Dom\Node;
	use Teio\IModule;


	class TopLevelBlockModule implements IModule
	{
		/** @var array<string> */
		private $selectors;


		/**
		 * @param string[] $selectors
		 */
		public function __construct(array $selectors)
		{
			$this->selectors = $selectors;
		}


		public function process(Dom $dom): void
		{
			foreach ($this->selectors as $selector) {
				$dom->find($selector, function (Node $node) {
					$node->moveToRoot();
				});
			}
		}
	}
