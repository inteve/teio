<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\Dom\Dom;
	use Teio\Dom\Node;
	use Teio\Module;


	class RenameTagModule implements Module
	{
		/** @var array<string, string> */
		private $replacements;


		/**
		 * @param array<string, string> $replacements
		 */
		public function __construct(array $replacements)
		{
			$this->replacements = $replacements;
		}


		public function process(Dom $dom): void
		{
			$dom->walk(function (Node $node) {
				if ($node->isElement()) {
					$tagName = strtolower($node->getName());

					if (isset($this->replacements[$tagName])) {
						$node->setName($this->replacements[$tagName]);
					}
				}
			});
		}
	}
