<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Nette\Utils\Strings;
	use Teio\IDomRules;


	class DomBuilder
	{
		/** @var IDomRules */
		private $domRules;

		/** @var Html */
		private $dom;

		/** @var Html */
		private $currentElement;

		/** @var Html[] */
		private $stack = [];

		/** @var string[] */
		private $stackPath = [];


		public function __construct(IDomRules $domRules)
		{
			$this->domRules = $domRules;
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
			if (!$this->isTextAllowed($htmlText)) {
				return;
			}

			$this->currentElement->addText(html_entity_decode($htmlText, ENT_QUOTES, 'UTF-8'));
		}


		public function addCommentNode($comment)
		{
			if (!$this->isCommentAllowed($comment)) {
				return;
			}

			$this->currentElement->addHtml($comment);
		}


		public function addEmptyNode($tag, $attrs)
		{
			if ($this->domRules->canBeEmpty($tag)) {
				$this->addVoidNode($tag, $attrs);

			} else {
				$this->addParentNode($tag, $attrs);
			}
		}


		public function startNode($tag, $attrs)
		{
			if ($this->domRules->canBeParent($tag)) {
				$this->addParentNode($tag, $attrs);

			} else {
				$this->addVoidNode($tag, $attrs);
			}
		}


		public function endNode($tag)
		{
			if ($this->currentElement->getName() === strtolower($tag)) {
				$this->closeLastNode();
			}
		}


		private function addParentNode($tag, $attrs)
		{
			if (!$this->isElementAllowed($tag)) {
				return;
			}

			$el = $this->createElement($tag, $attrs);
			$this->currentElement->addHtml($el);
			$this->stack[] = $el;
			$this->stackPath[] = $el->getName();
			$this->currentElement = $el;
		}


		private function addVoidNode($tag, $attrs)
		{
			if (!$this->isElementAllowed($tag)) {
				return;
			}

			$this->currentElement->addHtml($this->createElement($tag, $attrs));
		}


		private function closeLastNode()
		{
			if (empty($this->stack)) {
				throw new \Teio\InvalidStateException('There is no node to close.');
			}

			array_pop($this->stack);
			array_pop($this->stackPath);

			if (!empty($this->stack)) {
				$this->currentElement = end($this->stack);

			} else {
				$this->currentElement = $this->dom;
			}
		}


		private function createElement($tag, $attrs)
		{
			return Html::el(strtolower($tag) . ' ' . $attrs);
		}


		private function isElementAllowed($tag)
		{
			$tag = strtolower($tag);

			do {
				$res = $this->domRules->isElementAllowed($tag, $this->stackPath);

				if ($res === IDomRules::CLOSE_PARENT) {
					if (empty($this->stack)) {
						throw new \Teio\InvalidStateException('You cannot close root.');
					}

					$this->closeLastNode();
					continue;

				} elseif ($res === IDomRules::DISALLOW) {
					return FALSE;

				} elseif ($res === IDomRules::ALLOW) {
					return TRUE;
				}

				throw new \Teio\InvalidStateException('Invalid result of IDomRules::isElementAllowed()');
			} while (TRUE);
		}


		private function isCommentAllowed($comment)
		{
			$res = $this->domRules->isCommentAllowed($comment, $this->stackPath);

			if ($res === IDomRules::DISALLOW) {
				return FALSE;

			} elseif ($res === IDomRules::ALLOW) {
				return TRUE;
			}

			throw new \Teio\InvalidStateException('Invalid result of IDomRules::isCommentAllowed()');
		}


		private function isTextAllowed($text)
		{
			$res = $this->domRules->isTextAllowed($text, $this->stackPath);

			if ($res === IDomRules::DISALLOW) {
				return FALSE;

			} elseif ($res === IDomRules::ALLOW) {
				return TRUE;
			}

			throw new \Teio\InvalidStateException('Invalid result of IDomRules::isTextAllowed()');
		}
	}
