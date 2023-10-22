<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Teio\IModule;
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
			$dom->walk(function ($child) {
				if ($child instanceof Html) {
					$tagName = strtolower($child->getName());

					if (!isset($this->enabledTags[$tagName]) || $this->enabledTags[$tagName] === FALSE) {
						$child->setName(NULL);
						$child->attrs = [];

					} elseif (is_array($this->enabledTags[$tagName])) { // attrs whitelist
						foreach ($child->attrs as $attr => $value) {
							if (!in_array($attr, $this->enabledTags[$tagName], TRUE)) {
								$child->removeAttribute($attr);
							}
						}
					}
				}
			});
		}
	}
