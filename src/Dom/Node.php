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


		public function remove(): void
		{
			$this->getDomNode()->remove();
		}


		/**
		 * @return $this
		 */
		public function skipChildren(bool $skipChildren = TRUE)
		{
			$this->getDomNode()->skipChildren($skipChildren);
			return $this;
		}


		public function isHtml(): bool
		{
			return $this->getDomNode()->isHtml();
		}


		public function isText(): bool
		{
			return $this->getDomNode()->isText();
		}


		public function isElement(): bool
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


		/**
		 * @return $this
		 */
		public function setName(?string $name)
		{
			$this->getDomNode()->setName($name);
			return $this;
		}


		public function hasAttribute(string $attr): bool
		{
			return $this->getDomNode()->hasAttribute($attr);
		}


		/**
		 * @return scalar|bool|NULL|array<scalar|bool|NULL>
		 */
		public function getAttribute(string $attr)
		{
			return $this->getDomNode()->getAttribute($attr);
		}


		/**
		 * @param  scalar|scalar[] $value
		 * @return $this
		 */
		public function setAttribute(string $attr, $value)
		{
			$this->getDomNode()->setAttribute($attr, $value);
			return $this;
		}


		/**
		 * @return $this
		 */
		public function removeAttribute(string $attr)
		{
			$this->getDomNode()->removeAttribute($attr);
			return $this;
		}


		/**
		 * @return array<string, scalar|scalar[]>
		 */
		public function getAttributes(): array
		{
			return $this->getDomNode()->getAttributes();
		}


		/**
		 * @param  array<string, scalar|scalar[]> $attrs
		 * @return $this
		 */
		public function setAttributes(array $attrs)
		{
			$this->getDomNode()->setAttributes($attrs);
			return $this;
		}


		public function hasClass(string $class): bool
		{
			return $this->getDomNode()->hasClass($class);
		}


		/**
		 * @return $this
		 */
		public function setHtml(string $html)
		{
			$this->getDomNode()->setHtml($html);
			return $this;
		}


		/**
		 * @return $this
		 */
		public function setText(string $text)
		{
			$this->getDomNode()->setText($text);
			return $this;
		}


		public function getText(): string
		{
			return $this->getDomNode()->getText();
		}


		/**
		 * @return $this
		 */
		public function addHtml(string $html)
		{
			$this->getDomNode()->addHtml($html);
			return $this;
		}


		/**
		 * @return $this
		 */
		public function addText(string $text)
		{
			$this->getDomNode()->addText($text);
			return $this;
		}


		public function hasChildren(): bool
		{
			return $this->getDomNode()->hasChildren();
		}


		/**
		 * @param  array<Html|string> $children
		 * @return $this
		 */
		public function setChildren(array $children)
		{
			$this->getDomNode()->setChildren($children);
			return $this;
		}


		/**
		 * @return $this
		 */
		public function removeChildren()
		{
			$this->getDomNode()->removeChildren();
			return $this;
		}


		/**
		 * @return $this
		 */
		public function wrapBy(Html $wrapper)
		{
			$this->getDomNode()->wrapBy($wrapper);
			return $this;
		}


		/**
		 * @param  Html|string $html
		 * @return $this
		 */
		public function replaceByHtml($html)
		{
			$this->getDomNode()->replaceByHtml($html);
			return $this;
		}


		/**
		 * @return $this
		 */
		public function moveUp(int $levels = 1)
		{
			$this->getDomNode()->moveUp($levels);
			return $this;
		}


		/**
		 * @return $this
		 */
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
