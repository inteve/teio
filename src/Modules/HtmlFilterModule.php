<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\IModule;
	use Teio\Dom\DomNode;
	use Teio\HtmlDom;


	class HtmlFilterModule implements IModule
	{
		/** @var array<string, string[]|bool> */
		private $enabledTags;


		public function __construct(array $enabledTags)
		{
			$this->enabledTags = $enabledTags;
		}


		public function process(HtmlDom $dom)
		{
			$dom->walk(function (DomNode $node) {
				if ($node->isElement()) {
					$tagName = strtolower($node->getName());

					if (!isset($this->enabledTags[$tagName]) || $this->enabledTags[$tagName] === FALSE) {
						$node->setName(NULL);
						$node->setAttributes([]);

					} elseif (is_array($this->enabledTags[$tagName])) { // attrs whitelist
						foreach ($node->getAttributes() as $attr => $value) {
							if (!in_array($attr, $this->enabledTags[$tagName], TRUE)) {
								$node->removeAttribute($attr);
							}
						}
					}
				}
			});
		}
	}
