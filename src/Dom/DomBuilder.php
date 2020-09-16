<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Nette\Utils\Strings;


	class DomBuilder
	{
		/** @var Html */
		private $dom;

		/** @var Html */
		private $currentElement;

		/** @var Html[] */
		private $stack = [];


		public function __construct()
		{
			$this->dom = Html::el();
			$this->currentElement = $this->dom;
		}


		/**
		 * @return Dom
		 */
		public function toDom()
		{
			return new Dom($this->dom);
		}


		public function addTextNode($htmlText)
		{
			$this->currentElement->addText(html_entity_decode($htmlText, ENT_QUOTES, 'UTF-8'));
		}


		public function addCommentNode($comment)
		{
			$this->currentElement->addHtml($comment);
		}


		public function addEmptyNode($tag, $attrs)
		{
			$this->currentElement->addHtml($this->createElement($tag, $attrs));
		}


		public function startNode($tag, $attrs)
		{
			$el = Html::el($tag . ' ' . $attrs);
			$this->currentElement->addHtml($el);
			$this->stack[] = $el;
			$this->currentElement = $el;
		}


		public function endNode($tag)
		{
			if ($this->currentElement->getName() === $tag) {
				array_pop($this->stack);
				$this->currentElement = end($this->stack);

				if (!($this->currentElement instanceof Html)) {
					$this->currentElement = $this->dom;
				}
			}
		}


		private function createElement($tag, $attrs)
		{
			return Html::el($tag . ' ' . $attrs);
		}
	}
