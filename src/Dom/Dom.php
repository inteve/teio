<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;


	class Dom
	{
		/** @var Html */
		private $dom;

		/** @var DomSelectorParser */
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

			$this->walk(function (DomNode $node) use (&$result, $selector) {
				if ($selector->matchNode($node)) {
					$result[] = $node;
				}
			});

			return $result;
		}


		/**
		 * @return DomNode[]
		 */
		public function findTextNodes()
		{
			$result = [];

			$this->walk(function (DomNode $node) use (&$result) {
				if ($node->isText()) {
					$result[] = $node;
				}
			});

			return $result;
		}


		public function walk(callable $cb)
		{
			$stack = [];
			$stack[] = DomNode::root($this->dom);

			while (!empty($stack)) {
				$node = array_shift($stack);

				foreach ($node->getChildren() as $child) {
					$cb($child);
				}

				foreach ($node->getChildren() as $child) { // refresh positions after updates
					if ($child->isHtml() && $child->hasChildren()) {
						$stack[] = $child;
					}
				}
			}
		}


		private function getSelectorParser()
		{
			if ($this->selectorParser === NULL) {
				$this->selectorParser = new DomSelectorParser;
			}

			return $this->selectorParser;
		}
	}
