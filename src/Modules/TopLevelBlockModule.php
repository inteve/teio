<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\Dom\Dom;
	use Teio\IModule;


	class TopLevelBlockModule implements IModule
	{
		/** @var array<string> */
		private $blocks;


		public function __construct(array $blocks)
		{
			$this->blocks = $blocks;
		}


		public function process(Dom $dom)
		{
			foreach ($this->blocks as $selector) {
				$nodes = $dom->find($selector);

				foreach ($nodes as $node) {
					while (!$node->getParent()->isRoot()) {
						$node->moveUp();
					}
				}
			}
		}
	}
