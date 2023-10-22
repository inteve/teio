<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\Dom\Dom;
	use Teio\Dom\Node;
	use Teio\IModule;


	class HtmlFilterModule implements IModule
	{
		/** @var array<string, string[]|bool> */
		private $enabledTags;


		public function __construct(array $enabledTags)
		{
			$this->enabledTags = $enabledTags;
		}


		public function process(Dom $dom)
		{
			$dom->walk(function (Node $node) {
				if ($node->isElement()) {
					$tagName = strtolower($node->getName());

					if (!isset($this->enabledTags[$tagName]) || $this->enabledTags[$tagName] === FALSE) {
						$node->setName('');
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
