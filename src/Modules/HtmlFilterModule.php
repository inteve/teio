<?php

	declare(strict_types=1);

	namespace Teio\Modules;

	use Teio\Dom\Dom;
	use Teio\Dom\Node;
	use Teio\Module;


	class HtmlFilterModule implements Module
	{
		/** @var array<string, string[]|bool> */
		private $enabledTags;


		/**
		 * @param array<string, string[]|bool> $enabledTags
		 */
		public function __construct(array $enabledTags)
		{
			$this->enabledTags = $enabledTags;
		}


		public function process(Dom $dom): void
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
