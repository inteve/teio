<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;


	class Node
	{
		/** @var DomNode|NULL */
		private $domNode;


		public function __construct(DomNode $domNode)
		{
			$this->domNode = $domNode;
		}


		public function remove()
		{
			$this->getDomNode()->remove();
		}


		public function skipChildren($skipChildren = TRUE)
		{
			$this->getDomNode()->skipChildren($skipChildren);
			return $this;
		}


		public function isHtml()
		{
			return $this->getDomNode()->isHtml();
		}


		public function isText()
		{
			return $this->getDomNode()->isText();
		}


		public function isElement()
		{
			return $this->getDomNode()->isElement();
		}


		/**
		 * @return bool
		 */
		public function hasName()
		{
			return $this->getDomNode()->hasName();
		}


		/**
		 * @return string|NULL
		 */
		public function getName()
		{
			return $this->getDomNode()->getName();
		}


		public function setName($name)
		{
			$this->getDomNode()->setName($name);
			return $this;
		}


		public function hasAttribute($attr)
		{
			return $this->getDomNode()->hasAttribute($attr);
		}


		public function getAttribute($attr)
		{
			return $this->getDomNode()->getAttribute($attr);
		}


		public function setAttribute($attr, $value)
		{
			$this->getDomNode()->setAttribute($attr, $value);
			return $this;
		}


		public function removeAttribute($attr)
		{
			$this->getDomNode()->removeAttribute($attr);
			return $this;
		}


		public function getAttributes()
		{
			return $this->getDomNode()->getAttributes();
		}


		public function setAttributes(array $attrs)
		{
			$this->getDomNode()->setAttributes($attrs);
			return $this;
		}


		public function hasClass($class)
		{
			return $this->getDomNode()->hasClass($class);
		}


		public function setHtml($html)
		{
			$this->getDomNode()->setHtml($html);
			return $this;
		}


		public function setText($text)
		{
			$this->getDomNode()->setText($text);
			return $this;
		}


		public function getText()
		{
			return $this->getDomNode()->getText();
		}


		public function addHtml($html)
		{
			$this->getDomNode()->addHtml($html);
			return $this;
		}


		public function addText($text)
		{
			$this->getDomNode()->addText($text);
			return $this;
		}


		public function hasChildren()
		{
			return $this->getDomNode()->hasChildren();
		}


		public function setChildren(array $children)
		{
			$this->getDomNode()->setChildren($children);
			return $this;
		}


		public function removeChildren()
		{
			$this->getDomNode()->removeChildren();
			return $this;
		}


		public function wrapBy(Html $wrapper)
		{
			$this->getDomNode()->wrapBy($wrapper);
			return $this;
		}


		public function replaceByHtml($html)
		{
			$this->getDomNode()->replaceByHtml($html);
			return $this;
		}


		public function moveUp($levels = 1)
		{
			$this->getDomNode()->moveUp($levels);
			return $this;
		}


		public function moveToRoot()
		{
			$this->getDomNode()->moveToRoot();
			return $this;
		}


		/**
		 * @return void
		 * @internal
		 */
		public function detach()
		{
			$this->domNode = NULL;
		}


		/**
		 * @return DomNode
		 */
		private function getDomNode()
		{
			if ($this->domNode === NULL) {
				throw new \Teio\InvalidStateException('Node is detached.');
			}

			return $this->domNode;
		}
	}
