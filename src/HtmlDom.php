<?php

	namespace Teio;

	use Nette\Utils\Html;


	class HtmlDom
	{
		/** @var Html */
		private $dom;

		/** @var Dom\DomSelectorParser */
		private $selectorParser;


		public function __construct(Html $dom)
		{
			$this->dom = $dom;
		}


		/**
		 * @return Html
		 */
		public function getDom()
		{
			return $this->dom;
		}


		/**
		 * @return string
		 */
		public function toHtml()
		{
			return (string) $this->dom;
		}



		/**
		 * @param  string  CSS selector
		 * @return Html[]
		 */
		public function find($selector)
		{
			$result = [];
			$selectorParser = $this->getSelectorParser();
			$selector = $selectorParser->parse($selector);

			$this->walk(function ($child, Dom\DomPath $path = NULL) use (&$result, $selector) {
				if (!($child instanceof Html)) {
					return;
				}

				if ($child->getName() === NULL) {
					return;
				}

				if ($selector->matchPath($path)) {
					$result[] = $child;
				}
			});

			return $result;
		}


		/**
		 * @return string[]
		 */
		public function findTextNodes()
		{
			$result = [];

			$this->walk(function (&$child) use (&$result) {
				if (is_string($child) && strpos($child, '<') === FALSE) {
					$result[] = self::toText($child);
				}
			});

			return $result;
		}


		public function walk(callable $cb)
		{
			$stack = [];
			$stack[] = [$this->dom, Dom\DomPath::root()];

			while (!empty($stack)) {
				$item = array_shift($stack);
				$element = $item[0];
				$path = $item[1];

				$children = $element->getChildren();
				$childrenToStack = [];
				$index = 0;
				$count = 0;

				foreach ($children as $child) {
					if ($child instanceof Html) {
						$count++;
					}
				}

				foreach ($children as $child) {
					$childPath = NULL;

					if ($child instanceof Html) {
						$childPath = Dom\DomPath::append($path, $child, $index, ($index + 1) === $count);
						$index++;
					}

					$cb($child, $childPath);

					if ($child instanceof Html) {
						$childrenToStack[] = $child;
					}
				}

				foreach ($childrenToStack as $index => $childToStack) {
					if (!$childToStack->count()) {
						continue;
					}

					$childPath = $path;

					if ($childToStack->getName() !== NULL) {
						$childPath = Dom\DomPath::append($path, $childToStack, $index, ($index + 1) === count($childrenToStack));
					}

					$stack[] = [$childToStack, $childPath];
				}
			}
		}


		private function getSelectorParser()
		{
			if ($this->selectorParser === NULL) {
				$this->selectorParser = new Dom\DomSelectorParser;
			}

			return $this->selectorParser;
		}


		/**
		 * @param  string
		 * @return string
		 */
		public static function toText($html)
		{
			return html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
		}
	}
