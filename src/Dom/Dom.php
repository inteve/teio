<?php

	declare(strict_types=1);

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
		 * @param  string $selector  CSS selector
		 * @return void
		 */
		public function find($selector, callable $cb)
		{
			$selectorParser = $this->getSelectorParser();
			$selector = $selectorParser->parse($selector);

			$this->walkDom(function (DomNode $domNode) use ($cb, $selector) {
				if ($selector->matchNode($domNode)) {
					$node = new Node($domNode);
					$cb($node);
					$node->detach();
				}
			});
		}


		public function findTextNodes(callable $cb): void
		{
			$this->walkDom(function (DomNode $domNode) use ($cb) {
				if ($domNode->isText()) {
					$node = new Node($domNode);
					$cb($node);
					$node->detach();
				}
			});
		}


		public function walk(callable $cb): void
		{
			$this->walkDom(function (DomNode $domNode) use ($cb) {
				$node = new Node($domNode);
				$cb($node);
				$node->detach();
			});
		}


		private function walkDom(callable $cb): void
		{
			$rebuilder = new DomRebuilder($this->dom, function (DomNode $domNode) use ($cb) {
				$cb($domNode);
			});
			$rebuilder->rebuild();
		}


		private function getSelectorParser(): DomSelectorParser
		{
			if ($this->selectorParser === NULL) {
				$this->selectorParser = new DomSelectorParser;
			}

			return $this->selectorParser;
		}
	}
