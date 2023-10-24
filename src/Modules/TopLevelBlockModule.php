<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\Dom\Dom;
	use Teio\Dom\Node;
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
				$dom->find($selector, function (Node $node) {
					$node->moveToRoot();
				});
			}
		}
	}
