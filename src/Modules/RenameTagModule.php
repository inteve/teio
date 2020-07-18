<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
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
			$dom->walk(function ($child) {
				if ($child instanceof Html) {
					$tagName = strtolower($child->getName());

					if (isset($this->replacements[$tagName])) {
						$child->setName($this->replacements[$tagName]);
					}
				}
			});
		}
	}
