<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\Dom\DomNode;
	use Teio\IModule;
	use Teio\HtmlDom;


	class RenameTagModule implements IModule
	{
		/** @var array<string, string> */
		private $replacements;


		public function __construct(array $replacements)
		{
			$this->replacements = $replacements;
		}


		public function process(HtmlDom $dom)
		{
			$dom->walk(function (DomNode $node) {
				if ($node->isElement()) {
					$tagName = strtolower($node->getName());

					if (isset($this->replacements[$tagName])) {
						$node->setName($this->replacements[$tagName]);
					}
				}
			});
		}
	}
